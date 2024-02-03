<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerWithdrawal extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function getWithdrawalDetails(  ) {
        if (Session::sessionValidate()) {


            $request = JFactory::getApplication()->input;
            $fromDate = $request->getString('fromDate', '');
            $toDate = $request->getString('toDate', '');
            $offset = $request->getString('offset', '');
            $limit = $request->getString('limit', '');
            $txnTypeList = array("INITIATED","PENDING");
            if (!Validations::validateDate($fromDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'Please enter valid from date.');
            }
            if (!Validations::validateDate($toDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'Please enter valid to date.');
            }
            if (!Validations::compareDate($fromDate, $toDate)) {
                Redirection::ajaxSendDataToView(true, 1, 'To date must be greater than or equal to from date.');
            }
//            if ($limit != Constants::MAX_ROW_LIMIT) {
//                Redirection::ajaxSendDataToView(true, 1, 'Invalid data received.');
//            }
            if ($offset < 0) {
                Redirection::ajaxSendDataToView(true, 1, 'Invalid data received.');
            }
            $response = ServerCommunication::sendCall(ServerUrl::WITHDRAWAL_DETAILS, array(
                        "fromDate" => $fromDate,
                        "toDate" => $toDate,
                        "offset" => $offset,
                        "limit" => $limit,
                        "txnTypeList" => $txnTypeList
                            ), true);
            if (!isset($response->withdrawalList)) {
                Redirection::ajaxSendDataToView(true, 1, 'Invalid response received.');
//                return [];
            }
            Redirection::ajaxSendDataToView($response);
//            return $response->withdrawalList;
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function cancelPendingWithdrawal() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $transactionId = $request->getString('transactionId', '');
            $cancelAmount = $request->getString('cancelAmount', '');
            $response = ServerCommunication::sendCall(ServerUrl::CANCEL_WITHDRAWAL, array(
                        "transactionId" => $transactionId
                            ), true);
            if (Validations::getErrorCode() == 0) {
                $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
                $current_cashBalance = floatval($walletBean->cashBalance);
                $current_withdrawBal  = floatval($walletBean->withdrawableBal);
//                if (strpos($current_cashBalance, ".") !== false) {
//                    $current_cashBalance = substr($current_cashBalance, 0, strpos($current_cashBalance, "."));
//                }
                $to_update_cashBalance = floatval($cancelAmount);
//                if (strpos($to_update_cashBalance, ".") !== false) {
//                    $to_update_cashBalance = substr($to_update_cashBalance, 0, strpos($to_update_cashBalance, "."));
//                }
                $new_cashBalance = floatval($current_cashBalance + $to_update_cashBalance);
		$new_withdrawBal = floatval($current_withdrawBal + $to_update_cashBalance);
                $walletBean->cashBalance = $new_cashBalance;
                $walletBean->withdrawableBal = $new_withdrawBal;
                Utilities::updatePlayerLoginResponse(array(
                    "walletBean" => $walletBean
                ));
                $response->cashBalance =  $new_cashBalance;
		$response->withdrawableBal = $new_withdrawBal;
//                Utilities::getPlayerBalance();
//                $response->cashBalance = floatval(number_format((float) Utilities::getPlayerLoginResponse()->walletBean->cashBalance, 2));
            }
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function uploadWithdrawalDocumentsOld() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $uploadType = $request->getString('uploadType', '');
            $isAjax = $request->getString('isAjax', '');
            if ((strtoupper($uploadType) != "SINGLE") && (strtoupper($uploadType) != "MULTI")) {
                Redirection::ajaxSendDataToView(true, 1, "Unknown upload type received.");
            }
            jimport('joomla.filesystem.file');
            $doc_array = array();
            $doc_array_tmp = array();
            $playerDocumentArr = array();
            if ($uploadType == "SINGLE") {
                $playerDocumentArr['single'] = JRequest::getVar('file', null, 'files');
                $doc_array[$uploadType] = $request->getString('single', '');
                array_push($doc_array_tmp, $request->getString('single', ''));
            } else {
                $playerDocumentArr['id'] = JRequest::getVar('id-proof-file', null, 'files');
                $playerDocumentArr['address'] = JRequest::getVar('address-proof-file', null, 'files');
                $doc_array['id'] = $request->getString('id', '');
                $doc_array['address'] = $request->getString('address', '');
                array_push($doc_array_tmp, $request->getString('id', ''));
                array_push($doc_array_tmp, $request->getString('address', ''));
            }
            $playerInfo = Utilities::getPlayerLoginResponse();
            if (!file_exists(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId))
                mkdir(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId);
            $files = array();
            $session = JFactory::getSession();
            foreach ($playerDocumentArr as $key => $value) {
                $fileName = JFile::makeSafe($playerDocumentArr[$key]['name']);
                $fileName = preg_replace('/\s+/', '', $fileName);
                if (empty($fileName)) {
                    Redirection::ajaxSendDataToView(true, 2, "Invalid file received.");
                }
                $fileTmpName = $playerDocumentArr[$key]['tmp_name'];
                $fileSize = $playerDocumentArr[$key]['size'];
                $fileType = $playerDocumentArr[$key]['type'];
                move_uploaded_file($fileTmpName, Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId . "/" . $fileName);
                array_push($files, $fileName);
                ServerCommunication::serverUploadImage(ServerUrl::IMAGE_UPLOAD, array(
                    "fileContentEncoded" => base64_encode(file_get_contents(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId . "/" . $fileName)),
                    "docType" => "playerDoc",
                    "fileName" => $playerInfo->playerId . "_" . $request->getString($key, '') . "." . pathinfo($fileName, PATHINFO_EXTENSION),
                    "domainName" => Session::getSessionVariable('imgUploadDomain')
                        ), true);
            }
            $response = ServerCommunication::sendCall(ServerUrl::UPLOAD_PLAYER_DOCUMENT, array(
                        "docTypes" => $doc_array_tmp,
                        "fileNames" => $files
                            ), true);
            if (Validations::getErrorCode() == 0) {
                Utilities::updatePlayerLoginResponse(array(
                    "ageVerified" => "UPLOADED",
                    "addressVerified" => "UPLOADED"
                ));
            }
            unlink(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId . "/" . $fileName);
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function uploadWithdrawalDocuments() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $uploadType = $request->getString('uploadType', '');
            $isAjax = $request->getString('isAjax', '');
            if ((strtoupper($uploadType) != "SINGLE") && (strtoupper($uploadType) != "MULTI")) {
                Redirection::ajaxSendDataToView(true, 1, "Unknown upload type received.");
            }
            jimport('joomla.filesystem.file');
            $doc_array = array();
            $doc_array_tmp = array();
            $playerDocumentArr = array();
            $files_array_tmp = array();
            if ($uploadType == "SINGLE") {
                $playerDocumentArr['single'] = JRequest::getVar('file', null, 'files');
                $doc_array[$uploadType] = $request->getString('single', '');
                array_push($doc_array_tmp, $request->getString('single', ''));
            } else {
                $playerDocumentArr['id'] = JRequest::getVar('id-proof-file', null, 'files');
                $playerDocumentArr['address'] = JRequest::getVar('address-proof-file', null, 'files');
                $doc_array['id'] = $request->getString('id', '');
                $doc_array['address'] = $request->getString('address', '');
                array_push($doc_array_tmp, $request->getString('id', ''));
                array_push($doc_array_tmp, $request->getString('address', ''));
            }
            $playerInfo = Utilities::getPlayerLoginResponse();
            if (!file_exists(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId))
                mkdir(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId);
            $files = array();
            $session = JFactory::getSession();
            $myArr = array();
            $cfile = "";
            foreach ($playerDocumentArr as $key => $value) {
                $fileName = JFile::makeSafe($playerDocumentArr[$key]['name']);
                $fileName = preg_replace('/\s+/', '', $fileName);
                if (empty($fileName)) {
                    Redirection::ajaxSendDataToView(true, 2, "Invalid file received.");
                }
                $fileTmpName = $playerDocumentArr[$key]['tmp_name'];
                $fileSize = $playerDocumentArr[$key]['size'];
                $fileType = $playerDocumentArr[$key]['type'];
                move_uploaded_file($fileTmpName, Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId . "/" . $fileName);
                array_push($files, $fileName);
                /* ServerCommunication::serverUploadImage(ServerUrl::IMAGE_UPLOAD, array(
                  "fileContentEncoded" => base64_encode(file_get_contents(Constants::PLAYER_DOCUMENT_PATH.$playerInfo->playerId."/".$fileName)),
                  "docType" => "playerDoc",
                  "fileName" => $playerInfo->playerId."_".$request->getString($key, '').".".pathinfo($fileName, PATHINFO_EXTENSION),
                  "domainName" => Session::getSessionVariable('imgUploadDomain')
                  ), true); */
                $cfile = new CURLFile(Constants::PLAYER_DOCUMENT_PATH . $playerInfo->playerId . "/" . $fileName, $fileType);
                //curl_file_create($playerInfo->playerId."_".$request->getString($key, '').".".pathinfo($fileName, PATHINFO_EXTENSION));
                array_push($files_array_tmp, $cfile);
                //array_push($files_array_tmp, '@' . $cfile->name);
            }
            if ($uploadType == "SINGLE") {
                $data = array("playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "docType1" => $doc_array_tmp[0], "files1" => $files_array_tmp[0]);
            } else {
                $data = array("playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "docType1" => $doc_array_tmp[0], "files1" => $files_array_tmp[0], "docType2" => $doc_array_tmp[1], "files2" => $files_array_tmp[1]);
            }
            //        $data = array("playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "docType" => $request->getString('single', '') ,  "files" => $cfile);
            //         $data = array("playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "docType" => $doc_array_tmp ,  "files" => $files_array_tmp);
            //           $data = array("playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "docType1" => $doc_array_tmp[0] ,  "files1" => $files_array_tmp[0], "docType2" => $doc_array_tmp[1] ,  "files2" => $files_array_tmp[1]);
//exit(json_encode($data));
            $response = ServerCommunication::serverUploadImageNew(ServerUrl::UPLOAD_PLAYER_DOCUMENT_NEW, $data, true);
//exit(json_encode($response));
            if (Validations::getErrorCode() == 0) {
                Utilities::updatePlayerLoginResponse(array(
                    "ageVerified" => "UPLOADED",
                    "addressVerified" => "UPLOADED"
                ));
            }
            foreach ($files_array_tmp as $f) {
                unlink($f->name);
            }
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function withdrawalRequest() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $paymentTypeCode = $request->getString('payTypeCode', ''); 
            $paymentTypeId = $request->getInt('paymentTypeId', 0);
            $payTypeName = "Cash Payment";
            $subTypeName = "OLA";
            if (array_key_exists($paymentTypeCode, Constants::$paymentTypeCodes_Withdrawal) === false) {
                Redirection::to(Redirection::WITHDRAWAL_PROCESS, Errors::TYPE_ERROR, 'Invalid withdrawal type received.');
            }
            $requestBean = array(
                "currencyId" => Configuration::getCurrencyDetails()['id'],
                "paymentTypeId" => $paymentTypeId,
                "paymentTypeCode" => $paymentTypeCode
            );
            if ($paymentTypeCode == Constants::WITHDRAWAL_CASH_TRANS) {
                $requestBean['amount'] = $request->getFloat('cashAmount', 0);
            } elseif ($paymentTypeCode == Constants::WITHDRAWAL_CHEQUE_TRANS) {
                $requestBean['amount'] = $request->getFloat('chequeAmount', 0);
                $isNewRedeemAcc = $request->getString('isNewRedeemAcc', '');
                $requestBean['isNewRedeemAcc'] = $isNewRedeemAcc;
                if ($isNewRedeemAcc == "N") {
                    $requestBean['redeemAccountId'] = $request->getString('redeemAccountId', '');
                }
            } elseif ($paymentTypeCode == Constants::WITHDRAWAL_BANK_TRANS) {
                $requestBean['amount'] = $request->getFloat('bankAmount', 0);
                $isNewRedeemAcc = $request->getString('isNewRedeemAcc', '');
                $requestBean['isNewRedeemAcc'] = $isNewRedeemAcc;
                if ($isNewRedeemAcc == "N") {
                    $requestBean['redeemAccountId'] = $request->getString('selectAccount', '');
                } else {
                    $bankRedAcc = array(
                        "subtypeId" => $request->getString('bankName', ''),
                        "branchCity" => $request->getString('branchCity', ''),
                        "branchLocation" => $request->getString('branchLocation', ''),
                        "branchAddress" => $request->getString('branchAddress', ''),
                        "ifscCode" => $request->getString('ifsc', ''),
                        "micrCode" => $request->getString('micr', ''),
                        "accType" => $request->getString('actype', ''),
                        "accNum" => $request->getString('accNo', ''),
                        "accHolderName" => $request->getString('accHolderName', '')
                    );
                    $requestBean['bankRedAcc'] = $bankRedAcc;
                }
            }
            Utilities::getPlayerBalance(true, true);
            $playerLoginResponse = Utilities::getPlayerLoginResponse();
            $withdrawableBalance = $playerLoginResponse->walletBean->withdrawableBal;
            $cashBalance = $playerLoginResponse->walletBean->cashBalance;
            if ((float) $cashBalance < (float) $withdrawableBalance) {
                $withdrawableBalance = $cashBalance;
            }
            if ($withdrawableBalance < (float) $requestBean['amount']) {
//                Redirection::to(Redirection::WITHDRAWAL_PROCESS, Errors::TYPE_ERROR, Errors::INSUFFICIENT_WITHDRAWABLE_BAL);
                Redirection::ajaxExit(Redirection::WITHDRAWAL_WALLET, Constants::AJAX_FLAG_ALREADY_LOGGED_IN, Errors::TYPE_ERROR, Errors::INSUFFICIENT_WITHDRAWABLE_BAL);
//                Redirection::ajaxSendDataToView($response);
            }
            $response = ServerCommunication::sendCall(ServerUrl::WITHDRAWAL_REQUEST, array(
                        "requestBean" => $requestBean
            ));
            if (Validations::getErrorCode() != 0) {
                //Redirection::to(Redirection::WITHDRAWAL_PROCESS, Errors::TYPE_ERROR, Validations::getRespMsg());
                Redirection::ajaxSendDataToView($response);
            }

//            $response->withdrawalList = self::getWithdrawalDetails(Constants::WITHDRAWAL_START_DATE, date("d/m/Y"), 0, 100 );

            $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
            $current_cashBalance = floatval($walletBean->cashBalance);
            $current_withdrawBal = floatval($walletBean->withdrawableBal);
//            if (strpos($current_cashBalance, ".") !== false) {
//                $current_cashBalance = substr($current_cashBalance, 0, strpos($current_cashBalance, "."));
//            }
            $to_update_cashBalance = floatval($response->amount);
//            if (strpos($to_update_cashBalance, ".") !== false) {
//                $to_update_cashBalance = substr($to_update_cashBalance, 0, strpos($to_update_cashBalance, "."));
//            }
            $new_cashBalance = floatval($current_cashBalance - $to_update_cashBalance);
            $new_withdrawBal = floatval($current_withdrawBal - $to_update_cashBalance);
            $walletBean->cashBalance =  $new_cashBalance;
            $walletBean->withdrawableBal = $new_withdrawBal;		
            Utilities::updatePlayerLoginResponse(array(
                "walletBean" => $walletBean
            ));

            $response->cashBalnce = $new_cashBalance;
            $response->withdrawableBal = $new_withdrawBal;
            Session::setSessionVariable('withdrawalAmount', $response->amount);
            $date_time = $response->txnDate;
            $date_time = explode(" ", $date_time);
            Session::setSessionVariable('withdrawalTxnId', $response->txnId);
            Session::setSessionVariable('withdrawalDate', $date_time[0]);
            Session::setSessionVariable('withdrawalTime', $date_time[1]);
            Session::setSessionVariable('payTypeName', $payTypeName);
            Session::setSessionVariable('subTypeName', $subTypeName);
            Redirection::ajaxSendDataToView($response);
            //Redirection::to(Redirection::WITHDRAWAL_SUCCESS);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }
    
    function initiateOTPRequest(){
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $paymentTypeCode = $request->getString('payTypeCode', ''); 
            $amount = $request->getString('cashAmount', ''); 
            $playerLoginResponse = Utilities::getPlayerLoginResponse();
            $withdrawableBalance = $playerLoginResponse->walletBean->withdrawableBal;
            $cashBalance = $playerLoginResponse->walletBean->cashBalance;
            if ((float) $cashBalance < (float) $withdrawableBalance) {
                $withdrawableBalance = $cashBalance;
            }
            if ($withdrawableBalance < (float) $requestArr['amount']) {
                Redirection::to(Redirection::WITHDRAWAL_PROCESS, Errors::TYPE_ERROR, Errors::INSUFFICIENT_WITHDRAWABLE_BAL);
            }
            
            $response = ServerCommunication::sendCall(ServerUrl::SEND_OTP, array(
                         "mobileNo" => $playerLoginResponse->mobileNo,
                         "type" => $paymentTypeCode,
                         "amount" => $amount
            ));
//            if (Validations::getErrorCode() != 0) {
                //Redirection::to(Redirection::WITHDRAWAL_PROCESS, Errors::TYPE_ERROR, Validations::getRespMsg());

            if( Validations::getErrorCode() == 0 ){
                $response->withdrawalList = self::getWithdrawalDetails(Constants::WITHDRAWAL_START_DATE, date("d/m/Y"), 0, 100 );
            }
                Redirection::ajaxSendDataToView($response);
//            }
            
        }else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
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
            $isCashierurl = $request->getString('isCashierUrl', ''); 
            $currencyCode = $request->getString('CurrencyCode','');
            if($isCashierurl){
//             if($paymentTypeCode == 'DIGITAL_WALLET'){
//             $currencyCode = 'THB';
//             }else{
//              $currencyCode = $playerLoginResponse->walletBean->currency;
//             }
             $playerLoginResponse = Utilities::getPlayerLoginResponse();
            $withdrawalRequest = array(
                    "txnType" => 'WITHDRAWAL',
                    "paymentTypeId" => $paymentTypeId,
                    "paymentTypeCode" => $paymentTypeCode,
                    "currencyCode" =>  $currencyCode,
                    "paymentAccId" =>  $redeemAccId,
                    "subTypeId" => $subTypeId,
                    "amount" => $amount,
                );    
            }else{
            $withdrawalRequest = array(
                    "currencyId" => Configuration::getCurrencyDetails()['id'],
                    "paymentTypeId" => $paymentTypeId,
                    "paymentTypeCode" => $paymentTypeCode,
                    "subTypeId" => $subTypeId,
                    "amount" => $amount,
                    "redeemAccountId" => $redeemAccId,
                );
            $requestArray = array(
              "requestBean" => $withdrawalRequest
               );
            }
            Utilities::getPlayerBalance(true, true);
            $playerLoginResponse = Utilities::getPlayerLoginResponse();
            $withdrawableBalance = $playerLoginResponse->walletBean->withdrawableBal;
            $cashBalance = $playerLoginResponse->walletBean->cashBalance;
            if ((float) $cashBalance < (float) $withdrawableBalance) {
                $withdrawableBalance = $cashBalance;
            }
            if($isCashierurl){
            $response = ServerCommunication::sendCall(ServerUrl::CASHIER_WITHDRAWAL_REQUEST, $withdrawalRequest, Validations::$isAjax,true, array('merchantCode' => Configuration::DOMAIN_NAME,'playerId' => Utilities::getPlayerId(),'playerToken' => Utilities::getPlayerToken()));
            }else{
            $response = ServerCommunication::sendCall(ServerUrl::WITHDRAWAL_REQUEST_MOMO, $requestArray, Validations::$isAjax);
            }
            if (Validations::getErrorCode() != 0) {
                Redirection::ajaxSendDataToView($response);
            }
            $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
            $current_cashBalance = (float)$walletBean->cashBalance;
            $current_withdrawBal = (float)$walletBean->withdrawableBal;
            $to_update_cashBalance = (float)$response->amount;
            $new_cashBalance = $current_cashBalance - $to_update_cashBalance;
            $new_withdrawBal = (float)($current_withdrawBal - $to_update_cashBalance);
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
    
}
