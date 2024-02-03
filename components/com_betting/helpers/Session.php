<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Session {

    const USER_ID = 398;

    public static function sessionInitiate($response) {
        if (isset($response->mapping)) {
            $response->playerLoginInfo->mapping = $response->mapping;
        }
        if (isset($response->ipRegion)) {
            $response->playerLoginInfo->ipRegion = $response->ipRegion;
        }
        Utilities::setPlayerLoginResponse($response->playerLoginInfo);
        Utilities::setPlayerToken($response->playerToken);
        Utilities::setPlayerId($response->playerLoginInfo->playerId);
        Utilities::setRamPlayerInfoResponse($response->ramPlayerInfo);

        $session = JFactory::getSession();
        $instance = JUser::getInstance();
        $instance->set('id', self::USER_ID);
        $instance->set('guest', 0);
        $session->set('user', $instance);
        $session->set('imgUploadDomain', $response->domainName);
        if (isset($response->mixPanenlToken)) {
            $session->set('mixpanelToken', $response->mixPanenlToken);
        }
        if (isset($response->rummyDeepLink) && $response->rummyDeepLink != '') {
            $session->set('deepLink', $response->rummyDeepLink);
        }
        $session->set('popUpShownOn', array());
        $session->set('popUpShownId', array());
        $depositReferSourceData = [];
        if (isset($response->firstDepositReferSource))
            $depositReferSourceData['firstDepositReferSource'] = $response->firstDepositReferSource;
        if (isset($response->firstDepositReferSourceId))
            $depositReferSourceData['firstDepositReferSourceId'] = $response->firstDepositReferSourceId;
        if (isset($response->firstDepositSubSourceId))
            $depositReferSourceData['firstDepositSubSourceId'] = $response->firstDepositSubSourceId;
        if (isset($response->firstDepositCampTrackId))
            $depositReferSourceData['firstDepositCampTrackId'] = $response->firstDepositCampTrackId;
        if (count($depositReferSourceData) !== 0)
            $session->set('depositReferSourceData', $depositReferSourceData);
        if (Constants::TEMP_AUTH_ENABLED)
            $session->set('temporaryAuthentication', true);
        return;
    }

    public static function sessionRemove() {
        $instance = JUser::getInstance();
        $instance->set('guest', 1);
        $instance->set('id', 0);
        $session = JFactory::getSession();
        $session_variables = array(
            'user', 'playerLoginResponse', 'playerToken', 'playerId', 'cashier_initiate', 'select_amount', 'before_payment', 'depositRequest',
            'url', 'type', 'afterPaymentRedirect', 'afterPaymentMessage', 'promoCode', 'depositAmount', 'tierName', 'pokertournametFeedlist',
            'tournamentPrize', '', 'imgUploadDomain', 'popUpShownOn', 'popUpShownId', 'temporaryAuthentication', 'REG_WIDGET_COUNT', 'LOGIN_WIDGET_COUNT',
            'FP_WIDGET_COUNT', 'fromPage', 'logout_playerInfo', 'fromLogOut', 'cashier_initiate', 'passwordChanged', 'verificationPending', 'verificationPendingUserName',
            'fireLoginEvent', 'passwordReset', 'promoCode', 'before_payment', 'url', 'depositRequest', 'type', 'afterPaymentRedirect', 'afterPaymentMessage', 'forgot_emailid',
            'refer_a_friend', 'refer_a_friend_gmail', 'refer_a_friend_yahoo', 'refer_a_friend_outlook', 'fireRegistrationEvent', 'after_registration', 'withdrawalAmount', 'withdrawalDate',
            'withdrawalTime', 'payTypeName', 'subTypeName', 'reEncString1', 'imgUploadDomain', 'mixpanelToken', 'activation-link-expired', 'account_activated', 'forgot-password-link-expired',
            'verificationCodeResetPassword', 'loyaltyTierName', 'loyaltyTotalPoints', 'loyaltyBarPercentage', 'bonusBarPercentage', 'bonusBarReceived', 'bonusBarRedeemed', 'loyalPlayerDetail	',
            'popUpShownOn', 'FP_WIDGET_COUNT', 'LOGIN_WIDGET_COUNT', 'REG_WIDGET_COUNT', 'loyaltyCurrentTierEarning', 'isDepositProcessable', 'isDepositProcessableMsg', 'dontShowDefaultError',
            'loyaltyWithdrawalLimitExhausted', 'loyaltyWithdrawalLimitExceeded', 'loyaltyCurrentTierMaintanancePoints', 'showBackButton', 'isDepositProcessableThird', 'ramPlayerDataResponse'
        );
        foreach ($session_variables as $var) {
            if ($session->has($var)) {
                $session->clear($var);
            }
        }
        return;
    }

    public static function sessionValidate() {
        $session = JFactory::getSession();
        if ($session->has('playerLoginResponse') && $session->has('playerToken') /* && $session->has('playerId') */ && $session->has('user') && $session->get('user')->id == self::USER_ID) {
            return true;
        }
        $instance = JUser::getInstance();
        $instance->set('guest', 1);
        $instance->set('id', 0);
        return false;
    }

    public static function setSessionVariable($name = '', $value = '') {
        $session = JFactory::getSession();
        $session->set($name, $value);
        return;
    }

    public static function getSessionVariable($name) {
        $session = JFactory::getSession();
        if ($session->has($name)) {
            return $session->get($name);
        }
        return false;
    }

    public static function unsetSessionVariable($name) {
        $session = JFactory::getSession();
        if ($session->has($name)) {
            return $session->clear($name);
        }
        return;
    }

}

?>
