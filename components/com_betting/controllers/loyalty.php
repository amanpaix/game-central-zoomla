<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerLoyalty extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function getLoyalPlayerDetail() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            Redirection::ajaxSendDataToView(Utilities::getLoyalPlayerDetail("MEDIUM", Validations::$isAjax));
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function getCashLoyalty() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $amount = $request->getInt('amount', 0);
            $response = ServerCommunication::sendCall(ServerUrl::LOYALTY_GET_CASH, array(
                        "amount" => $amount
                            ), Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true)
                    Redirection::ajaxSendDataToView($response);
                if (Validations::getErrorCode() == Errors::LOYALTY_WITHDRAWAL_LIMIT_EXCEEDS || Validations::getErrorCode() == Errors::LOYALTY_WITHDRAWAL_LIMIT_EXHAUSTED) {
                    if (Validations::getErrorCode() == Errors::LOYALTY_WITHDRAWAL_LIMIT_EXCEEDS)
                        Session::setSessionVariable("loyaltyWithdrawalLimitExceeded", true);
                    if (Validations::getErrorCode() == Errors::LOYALTY_WITHDRAWAL_LIMIT_EXHAUSTED)
                        Session::setSessionVariable("loyaltyWithdrawalLimitExhausted", true);
                    Redirection::to(Redirection::LOYALTY_REDEEM);
                }
                Redirection::to(Redirection::LOYALTY_REDEEM, Errors::TYPE_ERROR, Validations::getRespMsg());
            }
            $response->amount = $amount;
            if (Validations::$isAjax == true) {
                Redirection::ajaxSendDataToView($response);
            }
            Session::setSessionVariable("loyaltyRedeemCashPopup", true);
            Session::setSessionVariable("loyaltyRedeemCashPopupAmount", $amount);
            Redirection::to(Redirection::LOYALTY_REDEEM);
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function buyMerchandise() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $productId = $request->getInt('productId', 0);
            $quantity = $request->getString('quantity', '');
            $response = ServerCommunication::sendCall(ServerUrl::LOYALTY_BUY_MERCHANDISE, array(
                        "productId" => $productId,
                        'quantity' => $quantity
                            ), Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                if (Validations::$isAjax == true)
                    Redirection::ajaxSendDataToView($response);
                Redirection::to(Redirection::LOYALTY_REDEEM, Errors::TYPE_ERROR, Validations::getRespMsg());
            }
            $response->productId = $productId;
            $response->quantity = $quantity;
            if (Validations::$isAjax == true) {
                Redirection::ajaxSendDataToView($response);
            }
            Session::setSessionVariable("loyaltyBuyMerchandisePopup", true);
            Redirection::to(Redirection::LOYALTY_REDEEM);
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

}
