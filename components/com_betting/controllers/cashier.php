<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerCashier extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function playerDetailUpdate() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $firstName = $request->getString('fname', '');
            $lastName = $request->getString('lname', '');
            $gender = $request->getString('gender', '');
            $addressLine1 = $request->getString('address', '');
            $city = $request->getString('city', '');
            $stateCode = $request->getString('state', '');
            $dob = $request->getString('dob', '');
            $pinCode = $request->getString('pincode', '');
            $requestData = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "gender" => $gender,
                "addressLine1" => $addressLine1,
                "city" => $city,
                "stateCode" => $stateCode,
                "dob" => $dob,
                "pinCode" => $pinCode,
            );
            $redirectError = Redirection::CASHIER_INITIATE;
            $redirectSuccess = Redirection::CASHIER_SELECT_AMOUNT;
            Session::setSessionVariable('cashier_initiate', true);
            $playerInfo = Utilities::getPlayerLoginResponse();
            $playerStatus = false;
            if ($playerInfo->emailVerified == "Y" && $playerInfo->phoneVerified == "Y") {
                $playerStatus = "FULL";
            }
            Utilities::updatePlayerProfile($requestData, $redirectError, $playerStatus);
            if ((strtoupper($playerInfo->playerStatus) == "FULL" || strtoupper($playerInfo->playerStatus) == "Active") && $playerInfo->emailVerified == "Y" && $playerInfo->phoneVerified == "Y") {
                Redirection::to($redirectSuccess);
            }
            Redirection::to($redirectError);
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function paymentInitiate() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $amount = $request->getInt('amount', 0);
            Utilities::isDepositProcessable(false, $amount);
            if (Validations::getErrorCode() != 0) {
                if (Validations::getErrorCode() == Errors::DEPOSIT_AMOUNT_EXCEEDED || Validations::getErrorCode() == Errors::DEPOSIT_COUNT_EXCEEDED || Validations::getErrorCode() == Errors::DEPOSIT_COUNT_THIRD) {
                    Session::setSessionVariable('cashier_initiate', true);
                    Session::setSessionVariable('isDepositProcessable', true);
                    if (Validations::getErrorCode() == Errors::DEPOSIT_COUNT_THIRD)
                        Session::setSessionVariable('isDepositProcessableThird', true);
                    Session::setSessionVariable('isDepositProcessableMsg', Validations::getRespMsg());
                    Redirection::to(Redirection::CASHIER_SELECT_AMOUNT);
                }
            }
            $promoCode = $request->getString('hidden_promoCode', '');
            if (trim($promoCode) != '') {
                Utilities::validatePromoCode(array(
                    "depositAmount" => $amount,
                    "promoCode" => $promoCode
                ));
                if (Validations::getErrorCode() != 0) {
                    Session::setSessionVariable('cashier_initiate', true);
                    if (Validations::getErrorCode() == 310)
                        $bonusCodeErrorMsg = "Bonus code is not applicable for this request.";
                    else if (Validations::getErrorCode() == 311)
                        $bonusCodeErrorMsg = "Invalid Bonus Code.";
                    else
                        $bonusCodeErrorMsg = Validations::getRespMsg();
                    Redirection::to(Redirection::CASHIER_SELECT_AMOUNT, Errors::TYPE_ERROR, $bonusCodeErrorMsg);
                }
                Session::setSessionVariable('promoCode', $promoCode);
            }
            $payTypeCode = $request->getString('payTypeCode', '');
            $payTypeId = $request->getInt('payTypeId', 0);
            if (array_search($payTypeCode, Constants::ONLINE_DEPOSIT_MODES) !== false) {
                $depositRequest = array(
                    "requestBean.paymentTypeId" => $payTypeId,
                    "requestBean.paymentTypeCode" => $payTypeCode,
                    "requestBean.currencyId" => Configuration::getCurrencyDetails()['id'],
                    "requestBean.amount" => $amount,
                    "requestBean.device" => Configuration::getDeviceType(),
                    "playerSessionId" => Utilities::getPlayerToken(),
                    "responseType" => "WEB",
                    "respSuccess" => urlencode(Redirection::AFTER_PAY_CALLBACK_SUCCESS),
                    "respError" => urlencode(Redirection::AFTER_PAY_CALLBACK_FAILED)
                );
                if (trim($promoCode) != '') {
                    $depositRequest['requestBean.promoCode'] = $promoCode;
                }
                if (Utilities::getPlayerLoginResponse()->firstDepositDate == "") {
                    $depositRequest['firstDepositResponse'] = urlencode(Redirection::FIRST_DEPOSIT_CALLBACK);
                    if (Session::getSessionVariable('depositReferSourceData') !== false) {
                        $depositReferSourceData = Session::getSessionVariable('depositReferSourceData');
                        if (count($depositReferSourceData) !== 0 && $depositReferSourceData['firstDepositReferSource'] !== "") {
                            $depositRequest['firstDepositReferSource'] = $depositReferSourceData['firstDepositReferSource'];
                            $depositRequest['firstDepositReferSourceId'] = $depositReferSourceData['firstDepositReferSourceId'];
                            $depositRequest['firstDepositSubSourceId'] = $depositReferSourceData['firstDepositSubSourceId'];
                            $depositRequest['firstDepositCampTrackId'] = $depositReferSourceData['firstDepositCampTrackId'];
                        }
                    }
                }
                if ($payTypeCode == Constants::CREDIT_CARD_DEPOSIT || $payTypeCode == Constants::DEBIT_CARD_DEPOSIT || $payTypeCode == Constants::NET_BANKING_DEPOSIT || $payTypeCode == Constants::PREPAID_WALLET_DEPOSIT || $payTypeCode == Constants::PAYTM_WALLET_DEPOSIT || $payTypeCode == Constants::MOBILE_WALLET_DEPOSIT) {
                    $depositRequest['requestBean.subTypeId'] = $request->getInt('subTypeId', 0);
                }
                Session::setSessionVariable('before_payment', true);
                Session::setSessionVariable('type', 'ONLINEDEPOSIT');
                Session::setSessionVariable('url', ServerUrl::CASHIER_SUBMIT_URL . ServerUrl::ONLINE_DEPOSIT_REQUEST);
                Session::setSessionVariable('depositRequest', $depositRequest);
                Redirection::to(Redirection::CASHIER_BEFORE_PAYMENT);
            } else if (array_search($payTypeCode, Constants::OFFLINE_DEPOSIT_MODES) !== false) {
                $depositRequest = array(
                    "currencyId" => Configuration::getCurrencyDetails()['id'],
                    "amount" => $amount,
                    "paymentTypeId" => $payTypeId,
                    "paymentTypeCode" => $payTypeCode,
                );
                if ($payTypeCode == Constants::CASH_CARD_DEPOSIT) {
                    $depositRequest['subTypeId'] = $request->getInt('subTypeId', 0);
                    $depositRequest['olaPin'] = $request->getString('pinNo', '');
                    $depositRequest['serialNumber'] = $request->getString('serialNo', '');
                    $depositRequest['olaType'] = "PIN";
                } elseif ($payTypeCode == Constants::CHEQUE_TRANS_DEPOSIT) {
                    $depositRequest['chequeNo'] = $request->getString('chequeNo', '');
                    $depositRequest['plrBankName'] = $request->getString('bankName', '');
                    $depositRequest['referenceDate'] = $request->getString('chequeDate', '');
                } else if ($payTypeCode == Constants::WIRE_TRANS_DEPOSIT) {
                    $depositRequest['refBankTxnNo'] = $request->getString('referenceNo', '');
                    $depositRequest['providerId'] = $request->getString('bankName', '');
                    $depositRequest['referenceDate'] = $request->getString('date', '');
                } else if ($payTypeCode == Constants::CASH_PAYMENT_DEPOSIT) {
                    $depositRequest['requestId'] = $request->getString('requestId', '');
                }
                if (trim($promoCode) != '') {
                    $depositRequest['promoCode'] = $promoCode;
                }
                date_default_timezone_set(Constants::DEFAULT_TIMEZONE);
                $date = $depositRequest['referenceDate'];
                $date = str_replace('/', '-', $date);
                $date = date('Y-m-d', strtotime($date));
                $date = $date . " " . date("H:i:s");
                $depositRequest['referenceDate'] = $date;
                $response = ServerCommunication::sendCall(ServerUrl::OFFLINE_DEPOSIT_REQUEST, array(
                            "depositRequest" => $depositRequest
                ));
                if (Validations::getErrorCode() != 0) {
                    if ($payTypeCode == Constants::CASH_CARD_DEPOSIT && Validations::getErrorCode() == Errors::TRANSACTION_FAILED_KPCARD) {
                        Session::setSessionVariable('type', 'OFFLINEDEPOSIT');
                        Redirection::to(Redirection::CASHIER_AFTER_PAYMENT_FAILED);
                    }
                    Session::setSessionVariable('cashier_initiate', true);
                    Redirection::to(Redirection::CASHIER_SELECT_AMOUNT, Errors::TYPE_ERROR, Validations::getRespMsg());
                }
                Session::setSessionVariable('type', 'OFFLINEDEPOSIT');
                Session::setSessionVariable('offlineRequestId', $response->txnId);
                Session::setSessionVariable('afterPaymentRedirect', true);
                Session::setSessionVariable('afterPaymentMessage', Validations::getRespMsg());
                Session::setSessionVariable('url', Redirection::MYACC_ACC);
                if ($payTypeCode == Constants::CASH_CARD_DEPOSIT && Utilities::getPlayerLoginResponse()->firstDepositDate == "") {
                    Utilities::updatePlayerLoginResponse(array(
                        "firstDepositDate" => date("Y-m-d H:i:s")
                    ));
                    Redirection::to(Redirection::FIRST_DEPOSIT_CALLBACK);
                }
                Redirection::to(Redirection::CASHIER_AFTER_PAYMENT_SUCCESS);
            }
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }
    
    
    function getDepositDetails() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $offset = $request->getString('offset', '');
            $limit = $request->getString('limit', '');
            $isCashierUrl = $request->getString('isCashierUrl', '');
            if($isCashierUrl){
            $fromDate = $request->getString('fromDate', '');
            $toDate = $request->getString('toDate', ''); 
            $txnType = $request->getString('txnType', '');          
            }
            if ($offset < 0) {
                Redirection::ajaxSendDataToView(true, 1, JText::_('BETTING_INVALID_DATA_RECEIVED'));
            }
            if ($isCashierUrl) {
                $response = ServerCommunication::sendCall(ServerUrl::CASHIER_DEPOSIT_STATUS, array(
                            "fromDate" => $fromDate,
                            "toDate" => $toDate,
                            "txnType" => $txnType,
                            "offset" => $offset,
                            "limit" => $limit,
                                ), Validations::$isAjax,true, array('merchantCode' => Configuration::DOMAIN_NAME,'playerId' => Utilities::getPlayerId(),'playerToken' => Utilities::getPlayerToken()));
            }else{
            $response = ServerCommunication::sendCall(ServerUrl::DEPOSIT_STATUS, array(
                        "offset" => $offset,
                        "limit" => $limit,
                            ), true);
            }            
            Redirection::ajaxSendDataToView($response);
            if (!isset($response->response)) {
                Redirection::ajaxSendDataToView(true, 1, JText::_("INVALID_RESPONSE_RECEIVED"));
                return [];
            }
            Redirection::ajaxSendDataToView($response);
            //return $response->withdrawalList;
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function AddNewAccount() {
     if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $accNum = $request->getString('accNum', '');
            $paymentTypeCode = $request->getString('paymentTypeCode', '');
            $accHolderName = $request->getString('accHolderName', '');
            $subTypeId = $request->getString('subTypeId' , '');
            $isNewRedeemAcc = $request->getString('isNewRedeemAcc' , '');
            $payTypeId = $request->getString('paymentTypeId' , '');
            $currencyCode = $request->getString('currencyCode' , '');
            $isAjax = $request->getString('isAjax', '');
            $isOtp = $request->getString('isOtp' , '');
            $forAttr = $request->getString('forattr', 'add_cash_button');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            if($isOtp)
              $verifyOtp = $request->getString('verifyOtp' , '');
            if (empty($accNum) || empty($accHolderName)) {
                Redirection::ajaxSendDataToView(true, 1, JText::_("ACCOUNT_NAME_AND_ACCOUNT_HOLDER_NAME_CANNOT_BE_EMPTY"));
            }
            $requestData = array(
                        "accNum" => $accNum,
                        "paymentTypeId" => $payTypeId,
                        "paymentTypeCode" => $paymentTypeCode,
                        "accHolderName" => $accHolderName,
                        "subTypeId" => $subTypeId,
                        "isNewPaymentAcc" => $isNewRedeemAcc,
                        "currencyCode" => $currencyCode
                            );
            $requestData['accType'] =  'SAVING';
            if($isOtp){
            $requestData['verifyOtp'] =  $verifyOtp;

            }
            $response = ServerCommunication::sendCall(ServerUrl::ADD_CASHIER_REDEEM_ACCOUNT, $requestData, Validations::$isAjax,true, array('merchantCode' => Configuration::DOMAIN_NAME,'playerId' => Utilities::getPlayerId(),'playerToken' => Utilities::getPlayerToken()));

            $response->forattr = $forAttr;

            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }         
    }
    
    function verfyOtpCode() {
      if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $accNum = $request->getString('accNum', '');
            $paymentTypeCode = $request->getString('paymentTypeCode', '');
            $accHolderName = $request->getString('accHolderName', '');
            $subTypeId = $request->getString('subTypeId' , '');
            $isNewRedeemAcc = $request->getString('isNewRedeemAcc' , '');
            $verifyOtp = $request->getString('verifyOtp' , '');
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            if (empty($verifyOtp)) {
                Redirection::ajaxSendDataToView(true, 1, JText::_("OTP_FIELD_CANNOT_BE_EMPTY"));
            }
            $response = ServerCommunication::sendCall(ServerUrl::ADD_REDEEM_ACCOUNT, array(
                        "accNum" => $accNum,
                        "paymentTypeCode" => $paymentTypeCode,
                        "accHolderName" => $accHolderName,
                        "subTypeId" => $subTypeId,
                        "isNewRedeemAcc" => $isNewRedeemAcc,
                        "verifyOtp" => $verifyOtp
                            ), Validations::$isAjax);
              Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }    
    }
    
    function fetchRedeemAccount() {
    if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $paymentTypeCode = $request->getString('paymentTypeCode','');
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $response = ServerCommunication::sendCall(ServerUrl::CASHIER_REDEEM_ACCOUNT, array(
                        "paymentTypeCode" => $paymentTypeCode,
                            ), Validations::$isAjax, true, array('merchantCode' => 'infiniti','playerId' => Utilities::getPlayerId(),'playerToken' => Utilities::getPlayerToken()));
            
              Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }        
    }
    
    function depositRequest() {  
    if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;  
            $paymentTypeId = $request->getInt('paymentTypeId', 0);   
            $subTypeId = $request->getInt('subTypeId', 0);
            $amount = $request->getInt('amount', 0);
            $paymentTypeCode = $request->getString('paymentTypeCode', 0);
            $redeemAccId = $request->getInt('redeemAccId', 0);
            $referenceDate = date("Y-m-d") . ' ' . date("h:i:s");
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $depositRequest = array(
                    "currencyId" => Configuration::getCurrencyDetails()['id'],
                    "paymentTypeId" => $paymentTypeId,
                    "paymentTypeCode" => $paymentTypeCode,
                    "subTypeId" => $subTypeId,
                    "amount" => $amount,                   
                    "redeemAccId" => $redeemAccId,
                    "referenceDate" => $referenceDate
                );
            $requestArray = array(
                "depositRequest" => $depositRequest
            );
            $response = ServerCommunication::sendCall(ServerUrl::DEPOSIT_REQUEST, $requestArray, Validations::$isAjax);
              Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }         
    }
    
    function requestWithdrawalDetails(){
    if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;  
            $paymentTypeId = $request->getInt('paymentTypeId', 0);   
            $paymentTypeCode = $request->getString('paymentTypeCode', '');   
            $subTypeId = $request->getInt('subTypeId', 0);
            $amount = $request->getString('amount', 0);
            $redeemAccId = $request->getInt('redeemAccId', 0);
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $requestArray = array(
                    "txnType" => 'WITHDRAWAL',
                    "paymentTypeId" => $paymentTypeId,
                    "paymentTypeCode" => $paymentTypeCode,
                    "currencyCode" => Configuration::getCurrencyDetails()['id'],
                    "subTypeId" => $subTypeId,
                    "amount" => $amount,
                );
//            $requestArray = array(
//              "requestBean" => $withdrawalRequest
//               );
            Utilities::getPlayerBalance(true, true);
            $playerLoginResponse = Utilities::getPlayerLoginResponse();
            $withdrawableBalance = $playerLoginResponse->walletBean->withdrawableBal;
            $cashBalance = $playerLoginResponse->walletBean->cashBalance;
            if ((float) $cashBalance < (float) $withdrawableBalance) {
                $withdrawableBalance = $cashBalance;
            }
            $response = ServerCommunication::sendCall(ServerUrl::CASHIER_WITHDRAWAL_REQUEST, $requestArray, Validations::$isAjax);
            if (Validations::getErrorCode() != 0) {
                Redirection::ajaxSendDataToView($response);
            }
            $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
            $current_cashBalance = floatval($walletBean->cashBalance);
            $current_withdrawBal = floatval($walletBean->withdrawableBal);
            $to_update_cashBalance = floatval($response->amount);
            $new_cashBalance = floatval($current_cashBalance - $to_update_cashBalance);
            $new_withdrawBal = floatval($current_withdrawBal - $to_update_cashBalance);
            $walletBean->cashBalance =  $new_cashBalance;
            $walletBean->withdrawableBal = $new_withdrawBal;		
            Utilities::updatePlayerLoginResponse(array(
                "walletBean" => $walletBean
            ));
            $response->cashBalance = $new_cashBalance;
            $response->withdrawableBal = $new_withdrawBal;
            Session::setSessionVariable('withdrawalAmount', $response->amount);
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }         
    }
    
    function requestCashierDeposit() {
        if (Session::sessionValidate()) {
        $request = JFactory::getApplication()->input;
        $depositPaymentTypeCode = $request->getString('payTypeCode', '');
        $amount = $request->get('deposit', '');
        $redeemAccountId = $request->get('paymentAccount', '');
        $paymentTypeId = $request->getInt('paytypeId', 0);
        $playerLoginResponse = Utilities::getPlayerLoginResponse();
        $subTypeId = $request->getInt('subType', 0);
        $currency = $request->getString('currency', '');
        $url = Redirection::AFTER_PAY_CALLBACK_SUCCESS;
        //$url = explode('/', $url);
        //$lang = $lang = explode("-", JFactory::getLanguage()->getTag())[0];
        //$url = $url[0] . '//' . $url[1] . $url[2] . '/' . $lang . '/' . $url[3];
        $depositRequest = array(
                    "paymentTypeId" => $paymentTypeId,
                    "txnType" => 'DEPOSIT',
                    "paymentTypeCode" => $depositPaymentTypeCode,
                    "amount" => $amount,
                    "domainName" => Configuration::DOMAIN_NAME,
                    "currencyCode" => $currency,
                    "subTypeId" => $subTypeId,
                    "deviceType" => Configuration::getDeviceType(),
                    "playerId" => Utilities::getPlayerId(),
                    "playerToken" =>  Utilities::getPlayerToken(),
                    "respSuccess" => $url,
                    "respError" => $url,
                    "merchantCode" => Configuration::DOMAIN_NAME,
                    "userAgent" => Configuration::getDevice()
                );
            if($redeemAccountId != ''){
             $depositRequest['paymentAccId'] = $redeemAccountId;   
            }
            
                Session::setSessionVariable('before_payment', true);
                Session::setSessionVariable('type', 'ONLINEDEPOSIT');
                Session::setSessionVariable('url', ServerUrl::CASHIER_BASE_URL . ServerUrl::CASHIER_DEPOSIT_REQUEST);
                Session::setSessionVariable('depositRequest', $depositRequest);
                Redirection::to(Redirection::CASHIER_BEFORE_PAYMENT);
             } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }  
    }

    function getPaymentOptions(){
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $for = $request->getString('for', '');
            $resp = Utilities::paymentOptions($for);
            $resp->for = $for;
            Redirection::ajaxSendDataToView($resp);
        }
        else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }

    }

}
