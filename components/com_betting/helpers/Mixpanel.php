<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT . '/helpers/Includes.php';

class Mixpanel {
    static $MIXPANEL_ID = "";
    const MIXPANEL_DOMAIN = Configuration::DOMAIN;
    public function __construct() {
        self::setMixPanelToken();
    }
    public static function setMixPanelToken() {
        self::$MIXPANEL_ID = Session::getSessionVariable('mixpanelToken');
    }
    public static function getMixPanelToken() {
        return self::$MIXPANEL_ID;
    }
    public static function fireLoginEvent() {
        Session::unsetSessionVariable('fireLoginEvent');
        $playerLoginInfo = Utilities::getPlayerLoginResponse();
        return;
        $document = JFactory::getDocument();
        $document->addScriptDeclaration("
            jQuery(document).ready(function($) {
                mixpanelLogin('" . self::MIXPANEL_DOMAIN . "',
                    '" . (isset($playerLoginInfo->state) ? $playerLoginInfo->state : '') . "',
                    '" . (isset($playerLoginInfo->referSource) ? $playerLoginInfo->referSource : '') . "',
                    '" . (isset($playerLoginInfo->campaignName) ? $playerLoginInfo->campaignName : '') . "',
                    '" . (isset($playerLoginInfo->playerStatus) ? $playerLoginInfo->playerStatus : '') . "',
                    '" . (isset($playerLoginInfo->lastLoginIP) ? $playerLoginInfo->lastLoginIP : '') . "',
                    '" . (isset($playerLoginInfo->registrationIp) ? $playerLoginInfo->registrationIp : '') . "',
                    '" . (isset($playerLoginInfo->userName) ? $playerLoginInfo->userName : '') . "',
                    '" . (isset($playerLoginInfo->playerId) ? $playerLoginInfo->playerId : '') . "',
                    '" . (isset($playerLoginInfo->registrationDate) ? $playerLoginInfo->registrationDate : '') . "',
                    '" . (isset($playerLoginInfo->regDevice) ? $playerLoginInfo->regDevice : '') . "',
                    '" . (isset($playerLoginInfo->emailId) ? $playerLoginInfo->emailId : '') . "',
                    '" . (isset($playerLoginInfo->emailVerified) ? $playerLoginInfo->emailVerified : '') . "',
                    '" . (isset($playerLoginInfo->mobileNo) ? $playerLoginInfo->mobileNo : '') . "',
                    '" . (isset($playerLoginInfo->phoneVerified) ? $playerLoginInfo->phoneVerified : '') . "',
                    '" . (isset($playerLoginInfo->lastLoginDate) ? $playerLoginInfo->lastLoginDate : '') . "',
                    '" . (isset($playerLoginInfo->firstDepositDate) ? $playerLoginInfo->firstDepositDate : '') . "',
                    '" . (isset($playerLoginInfo->firstName) ? $playerLoginInfo->firstName : '') . "',
                    '" . (isset($playerLoginInfo->lastName) ? $playerLoginInfo->lastName : '') . "',
                    '" . (isset($playerLoginInfo->gender) ? $playerLoginInfo->gender : '') . "',
                    '" . (isset($playerLoginInfo->dob) ? $playerLoginInfo->dob : '') . "',
                    '" . (isset($playerLoginInfo->city) ? $playerLoginInfo->city : '') . "',
                    '" . (isset($playerLoginInfo->pinCode) ? $playerLoginInfo->pinCode : '') . "',
                    '" . (isset($playerLoginInfo->walletBean->cashBalance) ? $playerLoginInfo->walletBean->cashBalance : '') . "',
                    '" . (isset($playerLoginInfo->walletBean->practiceBalance) ? $playerLoginInfo->walletBean->practiceBalance : '') . "',
                    MIXPANEL_DEVICE_TYPE,
                    'Website',
                    'Web',
                    '" . (isset($playerLoginInfo->affiliateId) ? $playerLoginInfo->affiliateId : '') . "',
                    '" . Configuration::getOS() . "');
            });
        ");
    }

    public static function fireRegistrationEvent() {
        Session::unsetSessionVariable('fireRegistrationEvent');
//        $playerLoginInfo = Utilities::getPlayerLoginResponse();
//        $document = JFactory::getDocument();
//        $document->addScript('https://my.rtmark.net/p.js?f=sync&lr=1&partner=2353884cc643a3b2e22fe9d1b92adc3628607ceb2ee8633766a7fbc441006e5d', 'text/javascript', true);
//        $document->addCustomTag('<noscript><img src="https://my.rtmark.net/img.gif?f=sync&lr=1&partner=2353884cc643a3b2e22fe9d1b92adc3628607ceb2ee8633766a7fbc441006e5d" width="1" height="1" /></noscript>');
//        $document->addScript('https://my.rtmark.net/p.js?f=sync&lr=1&partner=ee76ad13e593c8273c69f9924036d3147450179fdc2d09417ecda591dffccce1', 'text/javascript', true);
//        $document->addCustomTag('<noscript><img src="https://my.rtmark.net/img.gif?f=sync&lr=1&partner=ee76ad13e593c8273c69f9924036d3147450179fdc2d09417ecda591dffccce1" width="1" height="1" /></noscript>');
//        $document->addScript('https://my.rtmark.net/p.js?f=sync&lr=1&partner=ad890a897ef18663d8f5a97c94f08f722b31b1b1533e8770dd164f21b0d78aec', 'text/javascript', true);
//        $document->addCustomTag('<noscript><img src="https://my.rtmark.net/img.gif?f=sync&lr=1&partner=ad890a897ef18663d8f5a97c94f08f722b31b1b1533e8770dd164f21b0d78aec" width="1" height="1" /></noscript>');
//        $document->addScriptDeclaration("
//            jQuery(document).ready(function($) {
//                esk('track', 'Conversion');
//            });
//        ");
    }

}
