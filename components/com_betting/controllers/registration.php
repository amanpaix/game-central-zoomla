<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerRegistration extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function playerRegistration() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            $otpresponse = Session::getSessionVariable('register_otp');
            $request = JFactory::getApplication()->input;
            $registrationType = $request->getString('registrationType', '');
            $userName = $request->getString('userName', '');
            $password = $request->get('reg_password', '', 'RAW');
            $mobileNo = $request->getString('mobile', 0);
            //$emailId = $request->get('email', '', 'RAW');
            $stateCode = $request->getString('state', '');
            $submitUrl = $request->getString('submiturl', null);
            $refercode = $request->getString('refercode', '');
            $is3FieldReg = $request->getString('is3FieldReg', '');
            $currency = Configuration::getCurrencyDetails();

            $currencyId = $request->getString('currency', $currency['id']);
            $otpcode = $request->getString('otpcode');
            $otpEnable = $request->getString('otpenable', 0);
            $requestArr = array(
                "userName" => $userName,
                "password" => $password,
                "mobileNo" => $mobileNo,
//                "emailId" => $emailId,
                "requestIp" => Configuration::getClientIP(),
                "registrationType" => $registrationType,
                "currencyId" => $currencyId
            );
            $isBonusCheck = false;
            if ($is3FieldReg) {
                $requestArr['userName'] = $emailId;
            }
            if ($refercode != "") {
                if( substr( strtoupper($refercode), 0, 2 ) === "P-" ){
                    $requestArr['referCode'] = "";
                    $isBonusCheck = true;
                }
                else{
                    $requestArr['referCode'] = $refercode;
                    $isBonusCheck = false;
                }

            }
            if ($stateCode != "") {
                $requestArr['stateCode'] = $stateCode;
            }
            if ($registrationType == "FULL") {
                $requestArr['firstName'] = $request->getString('fname', '');
                $requestArr['lastName'] = $request->getString('lname', '');
//                $requestArr['gender'] = $request->getString('gender', '');
//                $requestArr['addressLine1'] = $request->getString('address', '');
//                $requestArr['stateCode'] = $request->getString('state', '');
//                $requestArr['city'] = $request->getString('city', '');
                $requestArr['countryCode'] = Constants::COUNTRY_CODE;
                //$requestArr['dob'] = $request->getString('dob', '');
//                $requestArr['pinCode'] = $request->getString('pincode', '');
            }
            if (Session::getSessionVariable('reEncString') !== false) {
                $requestArr['trackingCipher'] = Session::getSessionVariable('reEncString');
            }
            if (Session::getSessionVariable('idVCommString') !== false) {
                $requestArr['idVComm'] = Session::getSessionVariable('idVCommString');
            }
            if ($otpEnable == 1) {
                if ($otpcode == $otpresponse) {
                    $requestArr["isOtpVerified"] = true;
                } else {
                    exit("false");
                }
            }
            $requestArr['countryCode'] = Constants::COUNTRY_CODE;
            $response = ServerCommunication::sendCall(ServerUrl::PLAYER_REGISTRATION_NEW, $requestArr, Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true)
                    Redirection::ajaxSendDataToView($response);
                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            }

            if( $isBonusCheck && Constants::BONUS_MODULE ){
                if( strtoupper($refercode) == "P-BIGGY237" ){
                    $resp = Utilities::validatePlayerBonus($refercode);
                    // If Success
                    if( $resp['code'] == 0 ){

                        $req  = array();
                        $req['userNameList'] = array($response->playerLoginInfo->userName);
                        $req['bonusAmt'] = $resp['data']['amount'];
                        $req['bonusId'] = 12;

                        $responseBonus = ServerCommunication::sendCall(ServerUrl::FREE_CASH_BONUS, $req, Validations::$isAjax);

                        if (Validations::getErrorCode() == 0) {
                            Utilities::changeBonusStatus($refercode, $response->playerLoginInfo->userName,$resp['data']['id']);
                            Session::setSessionVariable('showBonusPopup', $req['bonusAmt']);
                        }
                    }
                    else if( $resp['code'] == 101 )
                    {
                        Session::setSessionVariable('showFailBonusPopup', true);
                    }
                }
            }

            Session::sessionInitiate($response);
            Session::setSessionVariable('fireRegistrationEvent', true);
            Session::unsetSessionVariable("reEncString");
            Session::unsetSessionVariable("idVCommString");
            Session::unsetSessionVariable("register_otp");
            $redirectTo = "";
            if ($submitUrl == null) {
                $redirectTo = Redirection::AFTER_REGISTRATION;
            } else {
                $redirectTo = urldecode($submitUrl);
            }
            Session::setSessionVariable('after_registration', true);
            if (Validations::$isAjax) {
                $response->path = $redirectTo;
                Redirection::ajaxSendDataToView($response);
            }
            Redirection::to($redirectTo);
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function checkAvailability() {
        $request = JFactory::getApplication()->input;
        $availabilityFor = $request->getString('availabilityFor', '');
        if ($availabilityFor != "USERNAME" && $availabilityFor != "MOBILENO" && $availabilityFor != "EMAILID") {}
        $requestArr = array();
        if ($availabilityFor == "USERNAME") {
            $requestArr['userName'] = $request->getString('userName', '');
        } else if ($availabilityFor == "MOBILENO") {
            $requestArr['mobileNo'] = $request->getString('mobileNo', '');
        } else if ($availabilityFor == "emailId") {
            $requestArr['emailId'] = $request->getString('emailId', '');
        }
        $response = ServerCommunication::sendCall(ServerUrl::CHECK_AVAILABILITY, $requestArr);
        exit($response);
    }

    function inviteFriend() {}

    function registrationOTP() {
        if (!Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $userName = $request->getString('userName', '');
            $mobileNo = $request->getInt('mobile', 0);
            $emailId = $request->get('email', '', 'RAW');
            $password = $request->get('reg_password', '', 'RAW');
            $registrationType = $request->getString('registrationType', '');
            //$countrycode = $request->getString('countrycode', 0);
            //$mobileNo = $countrycode . $mobileNo;
            $currency = Configuration::getCurrencyDetails();
            $currencyId = $request->getString('currency', $currency['id']);
            $refercode = $request->getString('refercode', '');
            //$countrycode = $request->getString('countrycode', 0);
            //$submitUrl = $request->getString('submiturl', null);
            $requestArr = array(
                "userName" => $userName,
                "emailId" => $emailId,
                "mobileNo" => $mobileNo,
                "password" => $password,
                "requestIp" => Configuration::getClientIP(),
                "registrationType" => $registrationType,
                "currencyId" => $currencyId,
            );
            $requestArr['referCode'] = $refercode;
            if( $requestArr['registrationType'] == "FULL" ){
                $requestArr['firstName'] = $request->getString('fname', '');
                $requestArr['lastName'] = $request->getString('lname', '');
                $requestArr['countryCode'] = Constants::COUNTRY_CODE;
            }

            Session::setSessionVariable('playerPreRegistrationData', $requestArr);
            $response = ServerCommunication::sendCall(ServerUrl::REGISTRATION_OTP, $requestArr, Validations::$isAjax);
            Session::setSessionVariable("register_otp", $response->respMsg);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true)
                    Redirection::ajaxSendDataToView($response);
                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            }
            else {
                $response->respMsg = true;
            }
            exit(json_encode($response));
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function partialRegistration() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $userName = $request->getString('userName', '');
            $mobileNo = $request->getInt('mobile', 0);
            $emailId = $request->get('email', '', 'RAW');
            $requestArr = array(
                "userName" => $userName,
                "emailId" => $emailId,
            );
            if ($mobileNo != 0) {
                $requestArr['mobileNo'] = $mobileNo;
            }
            $requestArr['domainName'] = Configuration::DOMAIN_NAME;
            $requestArr['deviceType'] = Configuration::getDeviceType();
            $response = ServerCommunication::sendCall(ServerUrl::PLAYER_PARTIAL_REGISTRATION, $requestArr, Validations::$isAjax);
            exit(json_encode($response));
        } else {
            
        }
    }

    public function verifyOtp()
    {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            $verificationCode = $request->getString('otp_confirm', '');
            $requestArr = array(
                "verificationCode" => $verificationCode,
                "verificationType" => 'OTP',
                "mobileNo" => Session::getSessionVariable('playerPreRegistrationData')['mobileNo'],
                "loginType" => 'PRE',
            );

            $response = ServerCommunication::sendCall(ServerUrl::VERIFY_REGISTRATION_OTP, $requestArr, Validations::$isAjax);
//            Session::setSessionVariable("register_otp", $response->respMsg);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true) {
                    Redirection::ajaxSendDataToView($response);
                }

                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            } else {
                $response->respMsg = true;
            }
            Session::setSessionVariable('fireRegistrationEvent', true);
            //exit(json_encode($response));
            $this->afterOtpRegistration();
        } else {
            if (Validations::$isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            }

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
                "otp" => $verificationCode
            );
            if (array_key_exists("referCode", Session::getSessionVariable('playerPreRegistrationData'))) {
                $requestArr["referCode"] = Session::getSessionVariable('playerPreRegistrationData')["referCode"];
            }

            if( $requestArr['registrationType'] == "FULL" ){
                $requestArr['firstName'] = Session::getSessionVariable('playerPreRegistrationData')['fname'];
                $requestArr['lastName'] = Session::getSessionVariable('playerPreRegistrationData')['lname'];
                $requestArr['countryCode'] = Session::getSessionVariable('playerPreRegistrationData')['countryCode'];
            }

            $response = ServerCommunication::sendCall(ServerUrl::PLAYER_REGISTRATION_NEW, $requestArr, Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true) {
                    Redirection::ajaxSendDataToView($response);
                }
                Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
            }

            if( Session::getSessionVariable('playerPreRegistrationData')['isBonusCheck'] && Constants::BONUS_MODULE ){
                if( strtoupper(Session::getSessionVariable('playerPreRegistrationData')['referCodeTmp']) == "P-BIGGY237" ){
                    $resp = Utilities::validatePlayerBonus(Session::getSessionVariable('playerPreRegistrationData')['referCodeTmp']);
                    // If Success
                    if( $resp['code'] == 0 ){

                        $req  = array();
                        $req['userNameList'] = array($response->playerLoginInfo->userName);
                        $req['bonusAmt'] = $resp['data']['amount'];
                        $req['bonusId'] = 3;

                        $responseBonus = ServerCommunication::sendCall(ServerUrl::FREE_CASH_BONUS, $req, Validations::$isAjax);

                        if (Validations::getErrorCode() == 0) {
                            Utilities::changeBonusStatus(Session::getSessionVariable('playerPreRegistrationData')['referCodeTmp'], $response->playerLoginInfo->userName,$resp['data']['id']);
                            Session::setSessionVariable('showBonusPopup', $req['bonusAmt']);
                        }
                    }
                    else if( $resp['code'] == 101 )
                    {
                        Session::setSessionVariable('showFailBonusPopup', true);
                    }
                }
            }
            Session::sessionInitiate($response);
            Session::unsetSessionVariable("playerPreRegistrationData");
            $redirectTo = Redirection::LOGIN;
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

    public function resendRegOtp()
    {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (!Session::sessionValidate()) {
            $regOtpRequestArr = array("mobileNo" => Session::getSessionVariable('playerPreRegistrationData')['mobileNo']);
            $response = ServerCommunication::sendCall(ServerUrl::REGISTRATION_OTP, $regOtpRequestArr, Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true) {
                    Redirection::ajaxSendDataToView($response);
                }

                Redirection::to(Redirection::REGISTRATION, Errors::TYPE_ERROR, Validations::getRespMsg());
            } else {
                $response->respMsg = true;
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
