<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerBonus extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function getBonusDetails() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $fromDate = $request->getString('fromDate', '');
            $toDate = $request->getString('toDate', '');
            $offset = $request->getString('offset', '');
            $limit = $request->getString('limit', '');
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            if (!Validations::validateDate($fromDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'Please enter valid from date.');
            }
            if (!Validations::validateDate($toDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'Please enter valid to date.');
            }
            if (!Validations::compareDate($fromDate, $toDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'To date must be greater than or equal to from date.');
            }
            if ($limit != Constants::MAX_ROW_LIMIT) {
                Redirection::ajaxSendDataToView(true, 1, 'Invalid data received.');
            }
            if ($offset < 0) {
                Redirection::ajaxSendDataToView(true, 1, 'Invalid data received.');
            }
            $response = ServerCommunication::sendCall(ServerUrl::BONUS_DETAILS, array(
                        "fromDate" => $fromDate,
                        "toDate" => $toDate,
                        "offset" => $offset,
                        "limit" => $limit
                            ), Validations::$isAjax);
            if (Validations::getErrorCode() == 0) {
                if (!isset($response->bonusList)) {
                    Redirection::ajaxSendDataToView(true, 1, 'Invalid response received.');
                }
            }
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

}
