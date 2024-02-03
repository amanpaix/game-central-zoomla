<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Redirection {

    const BASE = "";
    const DOMAIN = "http://43.205.133.88/" . self::BASE;
    const ICE_DOMAIN = "http://43.205.133.88/" . self::BASE;
    const LOGIN = self::BASE . "/";
    const LOGIN_PAGE = self::BASE . "/login";
    const LOGOUT = self::BASE . "/logout";
    const REGISTRATION = self::BASE . "/register";
    const AFTER_REGISTRATION = self::BASE . "/after-registration";
    const FORGOT_PASSWORD = self::BASE . "/forgot-password";
    const ACCOUNT_ACTIVATED = self::BASE . "/account-activated";
    const CONTACT_US = self::BASE . "/contact-us";
    const SUCCESSFUL_REGISTRATION = self::BASE . "/";
    const CONTACT_US_MOBILE = self::BASE . "/mobile-pages/mobile-contact-us";
    const PLAY_RUMMY = self::BASE . "/rummy";
    const PLAY_HTML_RUMMY = self::BASE . "/play-html-rummy";
    const CASHIER_INITIATE = self::BASE . "/cashier-initiate";
    const CASHIER_PLAYER_DETAIL = self::BASE . "/player-detail";
    const CASHIER_SELECT_AMOUNT = self::BASE . "/select-amount";
    const CASHIER_PAYMENT_OPTIONS = self::BASE . "/payment-options";
    const CASHIER_BEFORE_PAYMENT = self::BASE . "/before-payment";
    const CASHIER_AFTER_PAYMENT_SUCCESS = self::BASE . "deposit-response";
    const CASHIER_AFTER_PAYMENT_FAILED = self::BASE . "deposit-response";
    const CASHIER_FIRST_DEPOSIT = self::BASE . "/first-deposit";
    const MYACC_ACC = self::BASE . "/my-profile";
    const MYACC_PROFILE = self::BASE . "/my-profile";
    const MYACC_CHANGE_PASSWORD = self::MYACC_PROFILE . "#changePassword";
    const MYACC_UPDATE_PROFILE = self::MYACC_PROFILE . "#profile";
    const MYACC_CHANGE_PASSWORD_RAW = self::BASE . "/change-password";
    const MYACC_BONUS_DETAILS = self::BASE . "/transaction-details";
    const MYACC_TRANSACTION_DETAILS = self::BASE . "/transaction-details";
    const MYACC_WITHDRAWAL_DETAILS = self::BASE . "/withdrawal-details";
    const MYACC_INBOX = self::BASE . "/inbox";
    const REFER_A_FRIEND = self::BASE . "/refer-a-friend";
    const REFER_A_FRIEND_TRACK_BONUS = self::REFER_A_FRIEND . "#track-status";
    const MY_WALLET_DEPOSIT =  "/my-wallet#deposit";
    const MYACC_EDIT_AVATAR = self::MYACC_PROFILE . "#editAvatar";
    const MYACC_EDIT_AVATAR_RAW = self::BASE . "/edit-avatar";
    const MYACC_LOYALTY = self::BASE . "/loyalty";
    const WITHDRAWAL_REQUEST = self::BASE . "/withdrawal-details";
    const WITHDRAWAL_PROCESS = self::BASE . "/withdrawal";
    const WITHDRAWAL_SUCCESS = self::BASE . "/withdrawal-success";
    const WITHDRAWAL_WALLET = self::BASE . "/my-wallet";
    const CASHIER_HELP = self::BASE . "/cashier-help";
    const URL_TERMS = self::BASE . "/terms";
    const LOGIN_NEW = self::BASE . "/login";
    const REGISTER_NEW = self::BASE . "/registration";
    const FORGOT_PASSWORD_NEW = self::BASE . "/forgot-password";
    const FORGOT_PASSWORD_NEW_SUCCESS = self::BASE . "/forgot-password-success";
    const RESET_PASSWORD = self::BASE . "/reset-password-change";
    const PASSWORD_RESET = self::BASE . "/password-reset";
    const VERIFICATION_PENDING = self::BASE . "/verification-pending";
    const PASSWORD_CHANGED = self::BASE . "/";
    const AFTER_PAY_CALLBACK_SUCCESS = self::DOMAIN . self::CASHIER_AFTER_PAYMENT_SUCCESS;
    const AFTER_PAY_CALLBACK_FAILED = self::DOMAIN . self::CASHIER_AFTER_PAYMENT_FAILED;
    const FIRST_DEPOSIT_CALLBACK = self::DOMAIN . self::CASHIER_FIRST_DEPOSIT;
    const EXPIRED_ACTIVATION_LINK = self::BASE . "/activation-link-expired";
    const EXPIRED_FORGOT_PASSWORD_LINK = self::BASE . "/forgot-password-link-expired";
    const BROWSER_NOT_SUPPORTED = self::BASE . "/browser-not-supported";
    const RESPONSIBLE_GAMING = self::BASE . "/responsible-gaming";
    const LOYALTY = self::BASE . "/loyalty";
    const LOYALTY_REDEEM = self::BASE . "/redeem-loyalty-points";
    const LOYALTY_DETAILS = self::BASE . "/loyalty-details";
    const INVITE_FRIEND_THANK_YOU = self::BASE . "/invite-friend-thank-you";
    const REFER_A_FRIEND_INVITE_LIST = self::BASE . "/refer-a-friend-invite-list";
    const REFER_FRIEND_ERROR_PAGE = self::BASE . "/refer-friend-error";
    const GOOGLE_CALLBACK = self::DOMAIN . "/gmail-callback";
    const OUTLOOK_CALLBACK = self::DOMAIN . "/outlook-callback";
    const FACEBOOK_CALLBACK = self::DOMAIN . "/facebook-callback";
    const YAHOO_CALLBACK = self::DOMAIN . "/yhocallback";
    const PROMOTIONS = self::BASE . "/promotions";
    const CAMPAIGN_EXPIRED_LINK = self::BASE . "/promotion-link-expired";
    const GSP_REGISTRATION_LANDING_PAGE = self::BASE . "/1000-bonus";
    const KERON_INTERACTIVE = self::BASE . "/virtual-sports/keron-interactive";
    const GOLDEN_RACE = self::BASE . "/virtual-sport";
    const INSTANT_WIN = self::BASE . "/instant-win-games/instant-win";
    const INSTANT_WIN_UNFINISHED = self::BASE . "/instant-win-games/unfinished-games";
    const ERROR_PAGE = self::BASE . "/error-page";
    const SLOT = self::BASE . "/slot";

    public static function to($url, $type = false, $msg = false) {
        if ($msg !== false)
            JFactory::getApplication()->enqueueMessage(JText::_($msg), $type);
        JFactory::getApplication()->redirect(JRoute::_($url, false));
        return;
    }

    public static function ajaxExit($url, $flag = false, $type = false, $msg = false) {
        if ($msg !== false)
            JFactory::getApplication()->enqueueMessage(JText::_($msg), $type);

        exit(json_encode(array(
            "flag" => $flag,
            "path" => $url
        )));
    }

    public static function ajaxSendDataToView($response, $jmErrCode = '', $jmErrMessage = '') {
        if ($jmErrCode != '') {
            $errArr = array();
            $errArr['errorCode'] = $jmErrCode;
            $errArr['respMsg'] = $jmErrMessage;
            exit(json_encode($errArr));
        }
        exit(json_encode($response));
    }

    public static function getBase() {
        $lang = JFactory::getLanguage();
        $locales = $lang->get('tag');
        ;
        if ($locales == "fr-FR") {
            return BASE . $langpath = "/fr";
        } else if ($locales == "en-GB") {
            return BASE . $langpath = "";
        } else if ($locales == "es-ES") {
            return BASE . $langpath = "/es";
        } else
            $langpath = "";
    }

}
