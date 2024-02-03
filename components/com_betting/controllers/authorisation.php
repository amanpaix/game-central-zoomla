<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerAuthorisation extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function playerLogin() {        
        Session::sessionRemove();
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $userName = $request->getString('userName_email', '');
        $password = $request->get('password', '', 'RAW');
        $loginToken = $request->getString('loginToken', '');
        $submitUrl = $request->getString('submiturl', null);
        $encPwd = $request->get('encPwd', '', 'RAW');
        $requestData = array(
            "userName" => $userName,
            "password" => $password,
            "loginToken" => $loginToken,
            "requestIp" => Configuration::getClientIP()
        );
//        if (Session::getSessionVariable('reEncString') !== false) {
//            $requestData['trackingCipher'] = Session::getSessionVariable('reEncString');
//        } else {
//            $requestData['trackingCipher'] = "";
//        }
        $response = ServerCommunication::sendCall(ServerUrl::PLAYER_LOGIN, $requestData, Validations::$isAjax);
        if (Validations::getErrorCode() != 0) {
//            Session::setSessionVariable('verificationPending', true);
//            Session::setSessionVariable('verificationPendingUserName', $userName);
//            if (Validations::getErrorCode() == Errors::VERIFICATION_PENDING) {
//                if (Validations::$isAjax) {
//                    Redirection::ajaxExit(Redirection::VERIFICATION_PENDING, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Validations::getRespMsg());
//                } else {
//                    Redirection::to(Redirection::VERIFICATION_PENDING, Errors::TYPE_ERROR, Validations::getRespMsg());
//                }
//            }
//            if ($loginToken == Constants::IGE_LOGIN_TOKEN) {
//                Redirection::to(Configuration::DOMAIN . '/component/Betting/?task=authorisation.loginWindow&error=' . Validations::getRespMsg());
//            }
            if (Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
       if($response->playerLoginInfo->autoPassword == 'Y' && (!isset($response->playerLoginInfo->emailId)) && (!isset($response->playerLoginInfo->mobileNo))){
           $tempResponse = array('errorCode'=> 0, 'autoPassword'=> 'N');
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

    function resetPassword() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $newPassword = $request->getString('newPassword', '');
        $confirmPassword = $request->getString('retypePassword', '');
        if ($newPassword == "" || empty($newPassword) || is_null($newPassword) || $confirmPassword == "" || empty($confirmPassword) || is_null($confirmPassword)) {
            Redirection::ajaxSendDataToView(true, 1, 'Invalid request.');
        }
        $verificationCode = Session::getSessionVariable('verificationCodeResetPassword');
        if (!$verificationCode) {
            $response = "";
            $response->errorCode = 0;
            $response->path = Redirection::EXPIRED_FORGOT_PASSWORD_LINK;
            Session::setSessionVariable('forgot-password-link-expired', true);
            Redirection::ajaxSendDataToView($response);
        }
        $response = ServerCommunication::sendCall(ServerUrl::RESET_PASSWORD, array(
                    "verificationCode" => $verificationCode,
                    "newPassword" => $newPassword,
                    "confirmPassword" => $confirmPassword
                        ), Validations::$isAjax);
        if (Validations::getErrorCode() != 0) {
            Redirection::ajaxSendDataToView(true, 1, Validations::getRespMsg());
        }
        $response->path = Redirection::PASSWORD_RESET;
        Session::unsetSessionVariable('verificationCodeResetPassword');
        Session::setSessionVariable('passwordReset', true);
        Redirection::ajaxSendDataToView($response);
    }

    function getToken() {
        $request = JFactory::getApplication()->input;
        $title = $request->getString('title', '');
        $token = Utilities::getPlayerToken();
        $response = array(
            'title' => $title
        );
//        if(Configuration::getOS() == Configuration::OS_MAC && Configuration::getDeviceType() == Configuration::DEVICE_PC) {
//            $response['downloadClient'] = true;
//        }
//        else {
        $response['downloadClient'] = false;
//        }
        $response['openPlayerAliasModal'] = false;
        if ($token === false) {
            $response['errorCode'] = 101;
            Redirection::ajaxSendDataToView($response);
        }
        $token = $token . "-" . Utilities::getPlayerId();
        $response['errorCode'] = 0;
        $response['token'] = $token;
        $response['cashierLink'] = Redirection::CASHIER_INITIATE;
        if ($title == "play_new_rummy")
            $response['rummyLink'] = Redirection::PLAY_HTML_RUMMY;
        else
            $response['rummyLink'] = Redirection::PLAY_RUMMY;
        if (strtoupper(substr(Utilities::getPlayerLoginResponse()->userName, 0, 4)) != strtoupper("stpl")) {
            Utilities::updatePlayerLoginResponse(array(
                "pokerNickName" => "ABC"
            ));
        }
        if ((( Utilities::getPlayerLoginResponse()->pokerNickName == "" || strlen(trim(Utilities::getPlayerLoginResponse()->pokerNickName)) == 0))) {
            $autoCreateAlias = Utilities::createPlayerAlias(Utilities::getPlayerLoginResponse()->userName, "CreateAlias", Utilities::getPlayerLoginResponse()->userName);
            if ($autoCreateAlias->errorCode != 0) {
                $response['openPlayerAliasModal'] = true;
                if (!Session::getSessionVariable('aliasPageEnable'))
                    Session::setSessionVariable('aliasPageEnable', true);
            }
        }
        Redirection::ajaxSendDataToView($response);
    }
    
    public function getRetailLogin(){
    $request = JFactory::getApplication()->input;
    $isAjax = $request->getString('isAjax', '');
    Validations::$isAjax = ($isAjax == 'true') ? true : false;
    $session = JFactory::getSession();
    $tempPlayerSession = $session->get('tempPlayerSession');
     $password = $request->getString('password', '');
     $requestType = $request->getString('requestType', '');
     $phone = $request->getString('mobileNo', '');
     $email = $request->getString('emailId', '');
     //$submitUrl = $request->getString('submiturl', null);
     //$submitUrl = '/';
     if (empty($email)) {
            $response = ServerCommunication::sendCall(ServerUrl::RETAILER_LOGIN, array(
                        "password" => $password,
                        "requestType" => $requestType,
                        "mobileNo" => $phone,
                        "playerToken" => $tempPlayerSession->playerToken,
                        "playerId" => $tempPlayerSession->playerLoginInfo->playerId
                            ), Validations::$isAjax);
        } else {

            $response = ServerCommunication::sendCall(ServerUrl::RETAILER_LOGIN, array(
                        "password" => $password,
                        "requestType" => $requestType,
                        "mobileNo" => $phone,
                        "emailId" => $email,
                        "playerToken" => $tempPlayerSession->playerToken,
                        "playerId" => $tempPlayerSession->playerLoginInfo->playerId
                            ), Validations::$isAjax);
        }
        if (Validations::getErrorCode() != 0) {
            Redirection::ajaxSendDataToView($response);
       }
      Session::unsetSessionVariable('tempPlayerSession');
      Session::sessionInitiate($response);
      $redirectTo = urldecode($submitUrl);
       if( Validations::$isAjax == true){
             $response->path = $redirectTo;
            Redirection::ajaxSendDataToView($response);
       }
      
    }
    public function getOs() {}

    function loginWindow() {
        $error = "";
        if (array_key_exists("error", $_GET)) {
            $error = $_GET['error'];
        }
        $callBackURL = JRequest::getVar("callBackURL");
        $igedevice = JRequest::getVar("igedevice");
        if (!is_null($igedevice))
            Session::setSessionVariable('igedevice', $igedevice);
        $callBackURL = urlencode("http://ala-new.winBetting.com/InstantGameEngineOLD/" . $callBackURL);
        if (!isset($_SESSION['callBackURL']))
            Session::setSessionVariable('callBackURL', $callBackURL);
        $varLoginPage = '<!DOCTYPE html>
						<head>
						  <meta name="msapplication-tap-highlight" content="no" />
						  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=no"/>
						  <meta name="apple-mobile-web-app-capable" content="yes"/>
						  <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
						  <title>Login Form</title>
						  	<link rel="stylesheet" href="/templates/shaper_helix3/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="/templates/shaper_helix3/css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="/templates/shaper_helix3/css/legacy.css" type="text/css" />
	<link rel="stylesheet" href="/templates/shaper_helix3/css/template.css" type="text/css" />
	<link rel="stylesheet" href="/templates/shaper_helix3/css/presets/preset1.css" type="text/css" class="preset" />
	<link rel="stylesheet" href="/templates/shaper_helix3/css/custom/igeLogin.css" type="text/css" />
	<script src="/media/jui/js/jquery.min.js" type="text/javascript"></script>
	<script src="/media/jui/js/jquery-noconflict.js" type="text/javascript"></script>
	<script src="/media/jui/js/jquery-migrate.min.js" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="/media/system/js/core.js" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/jquery.sticky.js" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/main.js" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/custom/email_mobile_verify.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/MD5.min.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/custom/forgotpassword.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/custom/login.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/custom/registration.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/jquery.validate.min.js?v=21.0" type="text/javascript"></script>
	<script src="/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js?v=21.0" type="text/javascript"></script>
	<script type="text/javascript" src="/templates/shaper_helix3/js/custom/common.js?v=21.0"></script>
	<script>
	var myDeviceType = "PC";
	var loginToken = "IGELOGIN";
</script>
						</head>
						<body>
							<div class="container afIWGgameOuterWrap">
								<div class="pmsHeader">
								    <h3>Login</h3>
								  </div>
								  <div class="contDiv">
									  <form method="post" action="http://Betting-demo-new.lottoBetting.com/component/Betting/?task=authorisation.playerLogin" id="login-form-ige" submit-type="" validation-style="" tooltip-mode="bootstrap">
										<p><label>Username:</label><input type="text" name="userName_email" id="userName_email" value="" autocomplete="off"></p>
										<p><label>Password:</label><input type="password" name="password" id="password" value="" autocomplete="off"></p>
										<p style="color:red">' . $error . '</p>
										<input type="hidden" name="callBackURL"  id="callBackURL" value="' . $callBackURL . '">
									    <input type="hidden" name="isAjax" id="isAjax" value="false" />
									    <input type="hidden" name="loginToken" id="loginToken" value="IGELOGIN" />
									    <input type="hidden" name="encPwd" id="encPwd" value="" />
									    <input type="hidden" name="submiturl" id="submiturl" value="' . $callBackURL . '" />
										<p class="submit">
										<input type="submit"  name="commit" value="Login" class="pms_button">
										</p>
									  </form>
								</div>
							</div>
							<script>            </script>
						</body>
						</html>';
        exit($varLoginPage);
    }

}
