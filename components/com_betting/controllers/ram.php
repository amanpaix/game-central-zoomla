<?php

defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerRam extends BettingController
{

    public function display($cachable = false, $urlparams = false)
    {
    }

    public function registrationOTP()
    {
        if (!Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $userName = $request->getString('userName', '');
            $mobileNo = $request->getString('mobile', 0);
            $password = $request->get('reg_password', '', 'RAW');
            $registrationType = $request->getString('registrationType', '');
            $countrycode = $request->getString('countrycode', 0);
            //$mobileNo = $countrycode . $mobileNo;
            $currency = Configuration::getCurrencyDetails();
            $currencyId = $request->getString('currency', $currency['id']);
            $currencyCode = $request->getString('currencyCode', Constants::DEFAULT_CURRENCY_CODE);
            $refercode = $request->getString('refercode', '');
            $countrycode = $request->getString('countrycode', 0);
            //$submitUrl = $request->getString('submiturl', null);
            $rtkcid = $request->getString('rtkcid', '');
            $cmpid = $request->getString('cmpid', '');

            $requestArr = array(
                "userName" => $userName,
                "mobileNo" => $mobileNo,
                "password" => $password,
                "requestIp" => Configuration::getClientIP(),
                "registrationType" => $registrationType,
                "currencyId" => $currencyId,
                "currencyCode" => $currencyCode,
            );
            $requestArr['referCode'] = $refercode;
            if ($requestArr['registrationType'] == "FULL") {
//                $requestArr['firstName'] = $request->getString('fname', '');
                $requestArr['firstName'] = $request->getString('firstName', '');
                $requestArr['lastName'] = $request->getString('lname', '');
                $requestArr['countryCode'] = Constants::COUNTRY_CODE;
            }
            if ($rtkcid != "") {
                $requestArr['trackId'] = $rtkcid;
            }
            if ($cmpid != "") {
                $requestArr['campId'] = $cmpid;
            }
            Session::setSessionVariable('playerPreRegistrationData', $requestArr);
            $regOtpRequestArr = array("mobileNo" => $mobileNo);
            $response = ServerCommunication::sendCallRam(ServerUrl::RAM_PRELOGIN_SEND_OTP, $regOtpRequestArr, Validations::$isAjax, true, false, 'GET');
            Session::setSessionVariable("register_otp", $response->respMsg);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true)
                    Redirection::ajaxSendDataToView($response);
                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            } else {
                $response->respMsg = true;
                //unset($response->data->mobVerificationCode);
                Redirection::ajaxSendDataToView($response);
            }
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function verifyOtpRegistration()
    {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            $verificationCode = $request->getString('otp_confirm', '');
            $requestArr = array(
                "userName" => Session::getSessionVariable('playerPreRegistrationData')['userName'],
                "password" => Session::getSessionVariable('playerPreRegistrationData')['password'],
                "mobileNo" => Session::getSessionVariable('playerPreRegistrationData')['mobileNo'],
                "requestIp" => Configuration::getClientIP(),
                "registrationType" => Session::getSessionVariable('playerPreRegistrationData')['registrationType'],
                "currencyId" => Session::getSessionVariable('playerPreRegistrationData')['currencyId'],
                "currencyCode" => Session::getSessionVariable('playerPreRegistrationData')['currencyCode'],
                "otp" => $verificationCode
            );
            if (array_key_exists("referCode", Session::getSessionVariable('playerPreRegistrationData'))) {
                $requestArr["referCode"] = Session::getSessionVariable('playerPreRegistrationData')["referCode"];
            }
            if (array_key_exists("trackId", Session::getSessionVariable('playerPreRegistrationData'))) {
                $requestArr["trackId"] = Session::getSessionVariable('playerPreRegistrationData')["trackId"];
            }
            if (array_key_exists("campId", Session::getSessionVariable('playerPreRegistrationData'))) {
                $requestArr["campId"] = Session::getSessionVariable('playerPreRegistrationData')["campId"];
            }

            if ($requestArr['registrationType'] == "FULL") {
                $requestArr['firstName'] = Session::getSessionVariable('playerPreRegistrationData')['firstName'];
                $requestArr['lastName'] = Session::getSessionVariable('playerPreRegistrationData')['lname'];
                $requestArr['countryCode'] = Session::getSessionVariable('playerPreRegistrationData')['countryCode'];
            }

            $response = ServerCommunication::sendCallRam(ServerUrl::RAM_REGISTRATION_WITH_OTP, $requestArr, Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true) {
                    Redirection::ajaxSendDataToView($response);
                }
                Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
            }
            Session::sessionInitiate($response);
            Session::unsetSessionVariable("playerPreRegistrationData");
            //if($requestArr["referCode"] != "")
            $redirectTo = Redirection::SUCCESSFUL_REGISTRATION;
            //else
            // $redirectTo = Redirection::LOGIN;
            if (Validations::$isAjax) {
                $response->path = $redirectTo;
                Redirection::ajaxSendDataToView($response);
            }
        } else {
            if (Validations::$isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            }

            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function playerLogin()
    {
        Session::sessionRemove();
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $userName = $request->getString('userName_email', '');
        $password = $request->get('password', '', 'RAW');
        $mobile = $request->get('mobile', '');
        $countrycode = $request->get('country', '');
        $loginToken = $request->getString('loginToken', '');
        $submitUrl = $request->getString('submiturl', null);
        $encPwd = $request->get('encPwd', '', 'RAW');
        $requestData = array(
            "ussd" => "false",
            "userName" => $userName,
            "password" => trim($password),
            "loginToken" => $loginToken,
            "requestIp" => Configuration::getClientIP(),
        );
        if ($mobile != '' && $countrycode != '') {
            $mobileNo = $countrycode . $mobile;
            $requestData['userName'] = $mobileNo;
        }
        if ($userName != "") {
            $requestData['userName'] = $userName;
        }
        if (Session::getSessionVariable('reEncString') !== false) {
            $requestData['trackingCipher'] = Session::getSessionVariable('reEncString');
        } else {
            $requestData['trackingCipher'] = "";
        }
        $response = ServerCommunication::sendCallRam(ServerUrl::RAM_LOGIN, $requestData, Validations::$isAjax);
        if (Validations::getErrorCode() != 0) {
            if (Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if ($response->playerLoginInfo->autoPassword == 'Y' && (!isset($response->playerLoginInfo->emailId)) && (!isset($response->playerLoginInfo->mobileNo))) {
            $tempResponse = array('errorCode' => 0, 'autoPassword' => 'N');
            $session = JFactory::getSession();
            $session->set('tempPlayerSession', $response);
            Redirection::ajaxSendDataToView($tempResponse);
        }
        Session::sessionInitiate($response);
        Session::setSessionVariable('fireLoginEvent', true);
        Session::unsetSessionVariable("reEncString");
        Session::setSessionVariable("encPwd", $encPwd);
        $redirectTo = "";
        if ($submitUrl == null) {
            $redirectTo = JUri::base();
        } else {
            $redirectTo = urldecode($submitUrl);
        }
        if (Session::getSessionVariable('LOTTERY_LOGINREDIRECT') === true) {
            Session::unsetSessionVariable('LOTTERY_LOGINREDIRECT');
            $redirectTo = Session::getSessionVariable('LOTTERY_pageToGo');
        }
        $response->cashierLink = Redirection::CASHIER_INITIATE;
        $response->rummyLink = Redirection::PLAY_RUMMY;
        if (Validations::$isAjax == true) {
            $response->path = $redirectTo;
            Redirection::ajaxSendDataToView($response);
        }
        if ($loginToken == Constants::IGE_LOGIN_TOKEN) {
            if (Session::getSessionVariable('igedevice') != false) {
                Redirection::to($redirectTo . "&forceLogin=YES&playerId=" . Utilities::getPlayerId() . "&merchantSessionId=" . Utilities::getPlayerToken());
            } else {
                Redirection::to($redirectTo . "&forceLogin=YES&playerId=" . Utilities::getPlayerId() . "&merchantSessionId=" . Utilities::getPlayerToken());
            }
        } else
            Redirection::to($redirectTo);
    }

    function sendVerificationCode()
    {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $verificationField = $request->getString('verificationField', '');
            $isOtpVerification = $request->getString('isOtpVerification', '');
            $emailId = $request->getString('emailId', '');
            $requestArr = array(
                "isOtpVerification" => $isOtpVerification,
                "emailId" => $emailId
            );
            if ($verificationField == "MOBILE") {
                $mobileNo = $request->getString('mobileNo', '');
                $requestArr = array(
                    "mobileNo" => $mobileNo,
                    "otpType" => "MOBILE_UPDATE",
                    "resend" => "NO"
                );
            }
            $response = ServerCommunication::sendCallRam(ServerUrl::RAM_SEND_EMAIL_VERICATION_CODE, $requestArr);
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }


    function verifyPlayer()
    {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $verificationField = $request->getString('verificationField', '');
            $verificationCode = $request->getString('verificationCode', '');
            $emailId = $request->getString('emailId', '');
            $requestArr = array(
                "emailId" => $emailId,
                "otp" =>  $verificationCode,
                "merchantPlayerId" =>  Utilities::getPlayerId()
            );
            $response = ServerCommunication::sendCallRam(ServerUrl::RAM_VERIFY_EMAIL_OTP, $requestArr);
//            if ($response->errorCode == 0) {
//                $response->verificationField = $verificationField;
//            }

            $response->verificationField = $verificationField;
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function updatePlayerProfile()
    {
        //JSession::checkToken() or exit( "{ 'errorCode': 1, 'respMsg': 'Invalid File Extension' }" );
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $firstName = $request->getString('fname', '');
            $lastName = $request->getString('lname', '');
            //$mobileNo = $request->getString('mobile', 0);
            $gender = $request->getString('gender', '');

            $date = $request->getString('dob', '');
            if( $date !== '' )
            $dob = date("Y-m-d", strtotime($date));


            $address = $request->getString('address', '');
            $stateCode = $request->getString('state', '');
            $city = $request->getString('city', '');
            $pinCode = $request->getString('pincode', '');
            $email = $request->getString('email', '');
            $from = $request->getString('from', '');
            $countryCode = $request->getString('country', '');
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;

            $requestData = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "gender" => $gender,
                "dob" => $dob,
                "addressLine1" => $address,
                "stateCode" => $stateCode,
                "city" => $city,
                "pinCode" => $pinCode,
                "emailId" => $email,
                "countryCode" => Constants::COUNTRY_CODE,
                "merchantPlayerId" => Utilities::getPlayerId()
            );

            $redirectTo = Redirection::MYACC_PROFILE;
            $ramplayerInfo = Utilities::getRamPlayerInfoResponse();
            $playerStatus = false;
            if ($ramplayerInfo->emailVerified == "Y" && $ramplayerInfo->phoneVerified == "Y") {
                $playerStatus = "FULL";
            }
            $response =  Utilities::updatePlayerProfile($requestData, $redirectTo, $playerStatus, $isAjax);
            if (Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
                Redirection::to($redirectTo, Errors::TYPE_SUCCESS, 'Player Profile Updated Successfully');
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function resendRegOtp()
    {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            //$regOtpRequestArr = array("mobileNo" => Session::getSessionVariable('playerPreRegistrationData')['mobileNo']);
            $request->getString('mobile', 0);
            $regOtpRequestArr = array("mobileNo" => $request->getString('mobile', 0));
            $response = ServerCommunication::sendCallRam(ServerUrl::RAM_PRELOGIN_SEND_OTP, $regOtpRequestArr, Validations::$isAjax, true, false, 'GET');
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true) {
                    Redirection::ajaxSendDataToView($response);
                }

                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            } else {
                //unset($response->data->mobVerificationCode);
            }
            Redirection::ajaxSendDataToView($response);
        } else {
            if (Validations::$isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            }

            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }
}
