<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerTransaction extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function getTransactionDetails() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $txnType = $request->getString('txnType', '');
            if($txnType !== Constants::TXNTYPE_TICKET_DETAILS && $txnType !== "LAST10" && $txnType !== "LAST20" ){
            if (array_key_exists($txnType, Constants::$txnTypes_TransactionDetails['EN']) === false) {
                Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_TRANSACTION_TYPE_RECEIVED'));
            }
            }
            $fromDate = $request->getString('fromDate', '');
            $toDate = $request->getString('toDate', '');
            $offset = $request->getString('offset', '');
            $limit = $request->getString('limit', '');
            $callCat = $request->getString('callCat', '');

            $reqArr = array();

            if( $txnType === "LAST10" || $txnType === "LAST20"  ){
                $reqArr = array(
                    "txnType" => "ALL",
                    "offset" => $offset,
                    "limit" => $txnType === "LAST20" ? "20" : "10"
                );
            }
            else
            {
                if (!Validations::validateDate($fromDate)) {
                    Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_PLEASE_ENTER_FROM_DATE'));
                }
                if (!Validations::validateDate($toDate)) {
                    Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_PLEASE_ENTER_TO_DATE'));
                }
                if (!Validations::compareDate($fromDate, $toDate)) {
                    Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_TO_DATE_MUST_BE_GREATER_MSG'));
                }
                if ($limit != Constants::MAX_ROW_LIMIT) {
                    Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_DATA_RECEIVED'));
                }

                $reqArr = array(
                    "txnType" => $txnType,
                    "fromDate" => $fromDate,
                    "toDate" => $toDate,
                    "offset" => $offset,
                    "limit" => $limit
                );
            }


            if ($offset < 0) {
                Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_DATA_RECEIVED'));
            }
            if($txnType == Constants::TXNTYPE_TICKET_DETAILS){                
                 $response = ServerCommunication::sendCall(ServerUrl::TICKET_DETAILS, $reqArr, true);
            }else{
            $response = ServerCommunication::sendCall(ServerUrl::TRANSACTION_DETAILS, $reqArr, true);
            }

            if( $txnType === "LAST10" || $txnType === "LAST20"  ){
                $response->callType = "AUTO";
            }
            else{
                $response->callType = "GENERIC";
            }
            $response->callCat = $callCat;

            if (Validations::getErrorCode() == 0) {
                if ($txnType == Constants::TXNTYPE_TICKET_DETAILS) {
                    if (!isset($response->ticketList)) {
                        Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_RESPONSE_RECEIVED'));
                    }
                    if (count($response->ticketList) == 0) {
                        Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_NO_TICKET_FOUND_MSG'));
                    }
                } else {
                    if (!isset($response->txnList)) {
                        Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_RESPONSE_RECEIVED'));
                    }
                    if (count($response->txnList) == 0) {
                        Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_NO_TRANSACTION_DETAILS_MSG'));
                    }
                }
                if ($offset == 0 && $txnType != Constants::TXNTYPE_TICKET_DETAILS) {
                    $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
                    $cashBalance = number_format((float)$response->txnList[0]->balance,2);
                    $withdrawableBal = number_format((float)$walletBean->withdrawableBal,2);
//                    if (strpos($cashBalance, ".") !== false) {
//                        $cashBalance = substr($cashBalance, 0, strpos($cashBalance, "."));
//                    }
                    if (strpos($withdrawableBal, ".") !== false) {
                        $withdrawableBal = substr($withdrawableBal, 0, strpos($withdrawableBal, "."));
                    }
                    $walletBean->cashBalance = $cashBalance;
                    $walletBean->withdrawableBalance = $withdrawableBal;
                    Utilities::updatePlayerLoginResponse(array(
                        "walletBean" => $walletBean
                    ));
                    $response->cashBalance = $cashBalance;
                    $response->withdrawableBalance = $withdrawableBal;
                }
            }
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

}
