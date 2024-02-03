<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerForgotPassword extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function forgotPassword() {
        $request = JFactory::getApplication()->input;
        $emailId = $request->get('forgot_email', '', 'RAW');
        $forgotLandingPageCall = $request->get('forgotLandingPageCall', '');
        $success_url = $request->get('success-url', '');
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (empty($emailId)) {
            if (Validations::$isAjax) {
                Redirection::ajaxSendDataToView(true, 101, JText::_('BETTING_PLEASE_ENTER_USERNAME'));
            }
            Redirection::to(Redirection::FORGOT_PASSWORD, Errors::TYPE_ERROR, JText::_('BETTING_PLEASE_ENTER_USERNAME'));
        }
//        if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
//            if (Validations::$isAjax) {
//                Redirection::ajaxSendDataToView(true, 101, "Please enter valid email id.");
//            }
//            Redirection::to(Redirection::FORGOT_PASSWORD, Errors::TYPE_ERROR, "Please enter valid email id.");
//        }
        $response = ServerCommunication::sendCall(ServerUrl::FORGOT_PASSWORD, array(
                    "mobileNo" => $emailId
                        ), Validations::$isAjax);
        //$response->emailId = $emailId;
        if (Validations::$isAjax) {
//            if($forgotLandingPageCall) {
//                $_SESSION['forgot_emailid'] = $emailId;
//                $response->path = Redirection::FORGOT_PASSWORD_NEW_SUCCESS;
//            }
            if ($success_url != '') {
                Session::setSessionVariable('forgot_emailid', $emailId);
                $_SESSION['forgot_emailid'] = $emailId;
                $response->path = $success_url;
            }
            Redirection::ajaxSendDataToView($response);
        }
        if (Validations::getErrorCode() != 0) {
            Redirection::to(Redirection::FORGOT_PASSWORD, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if ($success_url != '') {
            Session::setSessionVariable('forgot_emailid', $emailId);
            $_SESSION['forgot_emailid'] = $emailId;
            Redirection::to($success_url, Errors::TYPE_SUCCESS, Validations::getRespMsg());
        }
        Redirection::to(Redirection::FORGOT_PASSWORD, Errors::TYPE_SUCCESS, Validations::getRespMsg());
    }



    function resetPasswordForgot() {

        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $newPassword = $request->getString('newPassword', '');
        $confirmPassword = $request->getString('retypePassword', '');
        $verificationCode = $request->getString('playerotp', '');
        $mobile = $request->getString('forgot_mobile', '');
        if ($newPassword == "" || empty($newPassword) || is_null($newPassword) || $confirmPassword == "" || empty($confirmPassword) || is_null($confirmPassword)) {
            Redirection::ajaxSendDataToView(true, 1, 'Invalid request.');
        }
        if (!$verificationCode) {
            $response = "";
            $response->errorCode = 0;
            $response->path = Redirection::EXPIRED_FORGOT_PASSWORD_LINK;
            Session::setSessionVariable('forgot-password-link-expired', true);
            Redirection::ajaxSendDataToView($response);
        }
        $response = ServerCommunication::sendCall(ServerUrl::RESET_PASSWORD, array(
            "otp" => $verificationCode,
            "newPassword" => $newPassword,
            "confirmPassword" => $confirmPassword,
            "mobileNo" => $mobile
        ), Validations::$isAjax);
        if (Validations::getErrorCode() != 0) {
            Redirection::ajaxSendDataToView($response);
        }
        $response->path = Redirection::LOGIN;
        Session::unsetSessionVariable('verificationCodeResetPassword');
        Session::setSessionVariable('passwordReset', true);
        Redirection::ajaxSendDataToView($response);
    }



}
