<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Utilities
{

    public static function beforeCashierInitiate()
    {
        //        $playerLoginResponse = self::getPlayerLoginResponse();
        //
        //        if(!isset($playerLoginResponse->playerStatus) || !isset($playerLoginResponse->emailVerified) || !isset($playerLoginResponse->phoneVerified))
        //            return false;
        //
        //        if((strtoupper($playerLoginResponse->playerStatus) == "FULL" || strtoupper($playerLoginResponse->playerStatus) == "ACTIVE") && $playerLoginResponse->emailVerified == "Y" && $playerLoginResponse->phoneVerified == "Y") {
        //            return Redirection::CASHIER_SELECT_AMOUNT;
        //        }
        //        else
        //            return false;
        self::getPlayerProfile();
        $playerLoginResponse = self::getPlayerLoginResponse();
        if (strtoupper($playerLoginResponse->playerStatus) == "MINI") {
            return Redirection::CASHIER_PLAYER_DETAIL;
        } else if (strtoupper($playerLoginResponse->playerStatus) == "FULL" || strtoupper($playerLoginResponse->playerStatus) == "ACTIVE") {
            if ($playerLoginResponse->emailVerified == "Y" && $playerLoginResponse->phoneVerified == "Y") {
                return Redirection::CASHIER_SELECT_AMOUNT;
            } else if ($playerLoginResponse->emailVerified == "N" || $playerLoginResponse->phoneVerified == "N") {
                $request = JFactory::getApplication()->input;
                $rd = $request->getString('rd', null);
                if (!is_null($rd)) {
                    if ($rd == "t") {
                        Session::setSessionVariable('dontShowDefaultError', true);
                        Session::setSessionVariable('showBackButton', true);
                    } else {
                        Session::unsetSessionVariable('dontShowDefaultError');
                        Session::unsetSessionVariable('showBackButton');
                    }
                    return Redirection::CASHIER_PLAYER_DETAIL;
                }
                self::isDepositProcessable(false);
                if (Validations::getErrorCode() == 0) {
                    return Redirection::CASHIER_SELECT_AMOUNT;
                } elseif (Validations::getErrorCode() == Errors::DEPOSIT_AMOUNT_EXCEEDED || Validations::getErrorCode() == Errors::DEPOSIT_COUNT_EXCEEDED) {
                    Session::setSessionVariable('isDepositProcessable', true);
                    Session::setSessionVariable('isDepositProcessableMsg', Validations::getRespMsg());
                    return Redirection::CASHIER_PLAYER_DETAIL;
                }
            }
        }
    }

    public static function getPlayerId()
    {
        $session = JFactory::getSession();
        if ($session->has('playerId')) {
            return $session->get('playerId');
        }
        return false;
    }

    public static function setPlayerId($playerId)
    {
        $session = JFactory::getSession();
        $session->set('playerId', $playerId);
        return;
    }

    public static function getPlayerToken()
    {
        $session = JFactory::getSession();
        if ($session->has('playerToken')) {
            return $session->get('playerToken');
        }
        return false;
    }

    public static function setPlayerToken($playerToken)
    {
        $session = JFactory::getSession();
        $session->set('playerToken', $playerToken);
        return;
    }

    public static function getPlayerLoginResponse()
    {
        $session = JFactory::getSession();
        if ($session->has('playerLoginResponse')) {
            return $session->get('playerLoginResponse');
        }
        return false;
    }

    public static function setPlayerLoginResponse($playerLoginResponse)
    {
        $session = JFactory::getSession();
        $session->set('playerLoginResponse', $playerLoginResponse);
    }

    public static function setRamPlayerInfoResponse($ramPlayerInfoResponse)
    {
        $session = JFactory::getSession();
        $session->set('ramPlayerInfoResponse', $ramPlayerInfoResponse);
    }

    public static function setRamPlayerDataResponse($ramPlayerDataResponse)
    {
        $session = JFactory::getSession();
        $session->set('ramPlayerDataResponse', $ramPlayerDataResponse);
    }

    public static function getRamPlayerDataResponse()
    {
        $session = JFactory::getSession();
        if ($session->has('ramPlayerDataResponse')) {
            return $session->get('ramPlayerDataResponse');
        }
        return false;
    }

    public static function getRamPlayerInfoResponse()
    {
        $session = JFactory::getSession();
        if ($session->has('ramPlayerInfoResponse')) {
            return $session->get('ramPlayerInfoResponse');
        }
        return false;
    }

    public static function updatePlayerLoginResponse($data)
    {
        $playerLoginResponse = self::getPlayerLoginResponse();
        foreach ($data as $k => $v) {
            $playerLoginResponse->$k = $v;
        }
        self::setPlayerLoginResponse($playerLoginResponse);
    }

    public static function updateRamPlayerLoginResponse($data)
    {
        $ramPlayerInfoResponse = self::getRamPlayerInfoResponse();
        foreach ($data as $k => $v) {
            $ramPlayerInfoResponse->$k = $v;
        }
        self::setRamPlayerInfoResponse($ramPlayerInfoResponse);
    }

    public static function getPlayerProfile()
    {
        $requestArray = array("profile" => Constants::PROJECT_NAME);
        $response = ServerCommunication::sendCallRam(ServerUrl::RAM_PLAYER_PROFILE, $requestArray);
        if (Validations::getErrorCode() != 0) {
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        $response->playerInfoBean->profileUpdate = $response->profileUpdate;
        if (isset($response->mapping)) {
            $response->playerInfoBean->mapping = $response->mapping;
        }
        $sessionVar = Session::getSessionVariable("playerLoginResponse");
        $response->playerInfoBean->walletBean->currency = $sessionVar->walletBean->currency;
        $response->playerInfoBean->walletBean->currencyDisplayCode = $sessionVar->walletBean->currencyDisplayCode;
        // self::updatePlayerLoginResponse($response->playerInfoBean);
        self::setRamPlayerDataResponse($response->ramPlayerData);
        self::updateRamPlayerLoginResponse($response->playerInfoBean);
        self::setRamPlayerInfoResponse($response->ramPlayerInfo);
        self::setPlayerId($response->playerInfoBean->playerId);
        if (isset($response->domainName)) {
            Session::setSessionVariable('imgUploadDomain', $response->domainName);
        }
        if (isset($response->mixPanenlToken)) {
            Session::setSessionVariable('mixpanelToken', $response->mixPanenlToken);
        }
        //        if (isset($response->playerInfoBean->stateCode)) {
        //            if (array_key_exists($response->playerInfoBean->stateCode, Constants::STATE_LIST)) {
        //                $stateList = Constants::STATE_LIST;
        //                self::updatePlayerLoginResponse(array(
        //                    "state" => $stateList[$response->playerInfoBean->stateCode]
        //                ));
        //                unset($stateList);
        //            }
        //        }
        return $response;
    }

    public static function getPlayerPracticeBalance($refTxnNo, $isAjax = false)
    {

        $response = ServerCommunication::sendCall(ServerUrl::UPDATE_PRACTICE_BAL, array(
            "playerToken" => Utilities::getPlayerToken(),
            "playerId" => Utilities::getPlayerId(),
            "transactionType" => "PRACTICE"
        ));

        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }

//        foreach ($response->playerLoginInfo->walletBean as $k => $v) {
//            //            if (strtolower($k) == "currency")
//            //                continue;
//            //if (strpos($v, ".") !== false)
//            // $response->wallet->$k = (int) $v;
//            //$response->wallet->$k= round($v, 2);
//        }


        $response->walletBean = new stdClass();
        $response->walletBean->practiceBal = $response->amount;
        self::updatePlayerLoginResponse(array(
            "walletBean" => $response->walletBean
        ));

        $resObj = new stdClass();
        $resObj->errorCode = $response->errorCode;
//        $resObj->refill = false;
        $resObj->wallet = $response->walletBean;
        $resObj->currency = Constants::DEFAULT_CURRENCY_CODE;
        $resObj->currencyDisplayCode = Constants::DEFAULT_CURRENCY_CODE;
        return $resObj;
    }
    public static function getPlayerBalance($refill = false, $isAjax = false)
    {
//        $response = ServerCommunication::sendCall(ServerUrl::GET_BALANCE, array('refill' => $refill), $isAjax);

        $response = ServerCommunication::sendCallRam(ServerUrl::CHECK_LOGIN, array(
            "playerToken" => Utilities::getPlayerToken()
        ));


        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }

        foreach ($response->playerLoginInfo->walletBean as $k => $v) {
            //            if (strtolower($k) == "currency")
            //                continue;
            //if (strpos($v, ".") !== false)
            // $response->wallet->$k = (int) $v;
            //$response->wallet->$k= round($v, 2);
        }
        $response->playerLoginInfo->walletBean->currency = Constants::DEFAULT_CURRENCY_CODE;
        $response->playerLoginInfo->walletBean->currencyDisplayCode = Constants::DEFAULT_CURRENCY_CODE;
        $response->playerLoginInfo->walletBean->currencyDisplayCode = "$";
        self::updatePlayerLoginResponse(array(
            "walletBean" => $response->playerLoginInfo->walletBean
        ));
        $resObj = new stdClass();
        $resObj->errorCode = $response->errorCode;
        $resObj->refill = false;
        $resObj->wallet = $response->playerLoginInfo->walletBean;
        return $resObj;
    }

    public static function playerInbox($offset = 0, $limit = Constants::INBOX_LIMIT)
    {
        $response = ServerCommunication::sendCall(ServerUrl::PLAYER_INBOX, array(
            'limit' => $limit,
            'offset' => $offset,
        ),false,true,array('merchantCode' => Configuration::MERCHANT_CODE,'merchantPwd'=> Configuration::MERCHANT_PWD));
        if (Validations::getErrorCode() != 0) {
            if (Validations::$isAjax) {
                Redirection::ajaxSendDataToView($response);
            }

            Redirection::to(Redirection::MYACC_ACC, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if (Validations::getErrorCode() == 0) {
            if(  isset($response->responseData->unreadMsgCount )){
                self::updatePlayerLoginResponse(array(
                    "unreadMsgCount" => $response->responseData->unreadMsgCount,
                ));
            }
            if ( !isset($response->responseData->data) || count($response->responseData->data) == 0) {
                if (Validations::$isAjax) {
                    Redirection::ajaxSendDataToView(true, 1, Errors::NO_MESSAGES_IN_INBOX);
                }
                return false;
            }
            $ids = array();
            foreach ($response->responseData->data as $msg) {
                if (array_search($msg->content_id, $ids) === false) {
                    array_push($ids, $msg->content_id);
                }
            }
            if (count($ids) == 0) {
                if (Validations::$isAjax) {
                    Redirection::ajaxSendDataToView(true, 1, Errors::NO_MESSAGES_IN_INBOX);
                }

                return false;
            }
            return array('response' => $response->responseData, 'content' => self::getMessageContent("'" . implode("','", $ids) . "'"));
        }
        return $response;
    }

    public static function getMessageContent($ids)
    {
        return false;
        //        $db = JFactory::getDbo();
        //        $query = $db->getQuery(true);
        //        $query
        //            ->select($db->quoteName(array('id', 'title', 'params', 'position')))
        //            ->from($db->quoteName('#__modules'))
        //            ->where("module='mod_Bettingemail' and published=1 and id IN(" . $ids . ")");
        //        $db->setQuery($query);
        //        $result = $db->loadAssocList();
        //        if (count($result) == 0) {
        //            self::updatePlayerLoginResponse(array(
        //                "unreadMsgCount" => 0,
        //            ));
        //            if (Validations::$isAjax) {
        //                Redirection::ajaxSendDataToView(true, 1, Errors::NO_MESSAGES_IN_INBOX);
        //            }
        //
        //            return false;
        //        }
        //        return $result;
    }

    public static function paymentOptions($for)
    {
        $playerInfo = Utilities::getPlayerLoginResponse();
        $currency = $playerInfo->walletBean->currency;
        $response = ServerCommunication::sendCall(ServerUrl::PAYMENT_OPTIONS, array(
            "txnType" => $for,
            "currencyCode" => $currency
        ), false, true, array('merchantCode' => Configuration::DOMAIN_NAME));
        return $response;
    }

    public static function fetchDepositBonus()
    {
        if (Session::sessionValidate()) {
            $amounts = array(
                "amounts" => array(10000, 5000, 1000, 500, 200, 100)
            );
            $response = ServerCommunication::sendCall(ServerUrl::FETCH_DEPOSIT_BONUS, $amounts);
            if (Validations::getErrorCode() != 0)
                Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
            return $response;
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public static function getCommonData()
    {
        return ServerCommunication::sendCall(ServerUrl::GET_COMMON_DATA, array(
            "countryCode" => Constants::COUNTRY_CODE,
            "commonRequestList" => array(
                "CURRENCY_LIST",
                "COUNTRY_LIST",
                "STATE_LIST",
                "AVATAR_LIST"
            )
        ));
    }

    public static function updatePlayerProfile($data, $redirectionUrl, $playerStatus = false, $isAjax='')
    {
//        $response = ServerCommunication::serverUploadImageRam(ServerUrl::RAM_UDATE_PLAYER_PROFILE, $data);
        $response = ServerCommunication::sendCallRam(ServerUrl::RAM_UDATE_PLAYER_PROFILE, $data);
        if ($isAjax) {
            if (Validations::getErrorCode() != 0) {
                Redirection::ajaxSendDataToView($response);
            }
        } else {
            if (Validations::getErrorCode() != 0) {
                $redirectionUrl = Redirection::MYACC_UPDATE_PROFILE;
                Redirection::to($redirectionUrl, Errors::TYPE_ERROR, Validations::getRespMsg());
            }
        }
        if ($playerStatus != false)
            $data['playerStatus'] = $playerStatus;
        $stateArr = Constants::STATE_LIST;
        $data['state'] = $stateArr[$data['stateCode']];
        unset($stateArr);
        self::updatePlayerLoginResponse($data);
        return $response;
    }

    public static function validatePromoCode($data)
    {
        return ServerCommunication::sendCall(ServerUrl::VALIDATE_PROMO_CODE, $data);
    }

    public static function checkLogin($playerToken)
    {
        $response = ServerCommunication::sendCall(ServerUrl::CHECK_LOGIN, array(
            "playerToken" => $playerToken
        ));
        if ($response->errorCode == 0) {
            //Session::sessionInitiate($response);
            return $response;
        }
        return false;
    }

    public static function getAvatarImages($playerId = "", $imageName = "")
    {
        $avatarImagesArr = scandir(Constants::AVATAR_PATH_ABS_COMMON);
        foreach ($avatarImagesArr as $key => $name) {
            if (!is_file(Constants::AVATAR_PATH_ABS_COMMON . $name)) {
                unset($avatarImagesArr[$key]);
            } elseif (exif_imagetype(Constants::AVATAR_PATH_ABS_COMMON . $name) === false) {
                unset($avatarImagesArr[$key]);
            } else {
                $avatarImagesArr[$key] = Constants::AVATAR_PATH_REL_COMMON . $name;
            }
        }
        return $avatarImagesArr;
    }

    public static function getPlayerImage($playerId = "", $imageName = "")
    {
        if (empty($playerId) || empty($imageName) || strtolower($imageName) == strtolower(Constants::AVATAR_DEFAULT_IMG_NAME)) {
            return Constants::AVATAR_PATH_REL_DEFAULT . Constants::AVATAR_DEFAULT_IMG_NAME;
        } elseif (strpos($imageName, (string) $playerId) !== false) {
            return Constants::AVATAR_PATH_REL_PLAYER . $playerId . "_image.jpg";
        } elseif (file_exists(Constants::AVATAR_PATH_ABS_COMMON . $imageName)) {
            return Constants::AVATAR_PATH_REL_COMMON . $imageName;
        }
        return Constants::AVATAR_PATH_REL_DEFAULT . Constants::AVATAR_DEFAULT_IMG_NAME;
    }

    public static function playerLogout($data = array())
    {
        $response = ServerCommunication::sendCall(ServerUrl::PLAYER_LOGOUT, $data);
        Session::sessionRemove();
        return $response;
    }

    public static function emailVerify()
    {
        $request = JFactory::getApplication()->input;
        $data = $request->getString('data', '');
        if ($data == "" || empty($data) || is_null($data)) {
            //exit('Invalid request');
            Redirection::to(Redirection::LOGIN);
        }
        $response = ServerCommunication::sendCall(ServerUrl::EMAIL_VERIFY, array(
            "verificationCode" => $data
        ));
        if (Validations::getErrorCode() != 0) {
            if (Validations::getErrorCode() == Errors::LINK_EXPIRED) {
                Session::setSessionVariable('activation-link-expired', true);
                Redirection::to(Redirection::EXPIRED_ACTIVATION_LINK);
            }
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if (isset($response->tokenPlayerLogout) && $response->tokenPlayerLogout === true) {
            Session::sessionRemove();
        }
        if (Session::sessionValidate()) {
            $tmp = array();
            foreach ($response->playerLoginInfo as $k => $v) {
                $tmp[$k] = $v;
            }
            Utilities::updatePlayerLoginResponse($tmp);
        }
        Session::setSessionVariable('account_activated', true);
        Redirection::to(Redirection::ACCOUNT_ACTIVATED);
    }

    public static function resetPasswordLink()
    {
        $request = JFactory::getApplication()->input;
        $data = $request->getString('data', '');
        if ($data == "" || empty($data) || is_null($data)) {
            //exit('Invalid request');
            Redirection::to(Redirection::LOGIN);
        }
        $response = ServerCommunication::sendCall(ServerUrl::RESET_PASSWORD_LINK, array(
            "verificationCode" => $data
        ));
        if (Validations::getErrorCode() != 0) {
            if (Validations::getErrorCode() == Errors::LINK_EXPIRED) {
                Session::setSessionVariable('forgot-password-link-expired', true);
                Redirection::to(Redirection::EXPIRED_FORGOT_PASSWORD_LINK);
            }
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if ($response->changePswdDisplay == false) {
            Redirection::to(JUri::base());
        }
        Session::setSessionVariable('verificationCodeResetPassword', $data);
    }

    public static function fetchHeaderInfo()
    {
        $response = ServerCommunication::sendCall(ServerUrl::FETCH_HEADER_INFO, array(
            'playerId' => Utilities::getPlayerId()
        ));
        if (Validations::getErrorCode() == 0) {
            $unreadMsgCount = $response->unreadMsgCount;
            $walletBean = Utilities::getPlayerLoginResponse()->walletBean;
            $walletBean->practiceBal = $response->practiceBal;
//            $walletBean->cashBal = $response->cashbal;
            $walletBean->cashBal = $response->practiceBal;
            $walletBean->withdrawableBal = $response->withdrawableBal;
            if (isset($response->data->currentTier))
                Session::setSessionVariable("loyaltyTierName", ucfirst(strtolower($response->data->currentTier)));
            else
                Session::setSessionVariable("loyaltyTierName", "Bronze");
            if (isset($response->data->currentTierEarning))
                Session::setSessionVariable("loyaltyCurrentTierEarning", $response->data->currentTierEarning);
            else
                Session::setSessionVariable("loyaltyCurrentTierEarning", 0);
            if (isset($response->data->currentTierMaintanancePoints))
                Session::setSessionVariable("loyaltyCurrentTierMaintanancePoints", $response->data->currentTierMaintanancePoints);
            else
                Session::setSessionVariable("loyaltyCurrentTierMaintanancePoints", 0);
            $percentage = 0;
            /*  if(isset($response->data->nextTierEntryPt) &&  isset($response->data->currentTierEarning)) {
              if((($response->data->nextTierEntryPt)/100) != 0)
              $percentage = ($response->data->currentTierEarning)/(($response->data->nextTierEntryPt)/100);
              }

              if(!isset($response->data->nextTierEntryPt)) {
              $percentage = 100;
              } */
            if (isset($response->data->nextTierEntryPt) && isset($response->data->currentTierEarning)) {
                if ((($response->data->nextTierEntryPt) / 100) != 0)
                    $percentage = ($response->data->currentTierEarning) / (($response->data->nextTierEntryPt) / 100);
            } else {
                if (isset($response->data->currentTierMaintanancePoints) && isset($response->data->currentTierEarning)) {
                    if ($response->data->currentTierEarning >= $response->data->currentTierMaintanancePoints) {
                        $percentage = 100;
                    } else {
                        $percentage = (($response->data->currentTierEarning) / ($response->data->currentTierMaintanancePoints)) * 100;
                    }
                }
            }
            $percentage = round($percentage, 2);
            Session::setSessionVariable("loyaltyBarPercentage", $percentage);
            if (isset($response->data->bonusBarInfo)) {
                $bonusBarInfo = explode("|", $response->data->bonusBarInfo);
                Session::setSessionVariable("bonusBarPercentage", (isset($bonusBarInfo[0])) ? explode(".", $bonusBarInfo[0])[0] : 0);
                Session::setSessionVariable("bonusBarReceived", (isset($bonusBarInfo[1])) ? explode(".", $bonusBarInfo[1])[0] : 0);
                Session::setSessionVariable("bonusBarRedeemed", (isset($bonusBarInfo[2])) ? explode(".", $bonusBarInfo[2])[0] : 0);
            }
            Utilities::updatePlayerLoginResponse(array(
                "unreadMsgCount" => $unreadMsgCount,
                "walletBean" => $walletBean
            ));
            if (isset($response->ramPlayerInfo)) {
                Utilities::setRamPlayerInfoResponse($response->ramPlayerInfo);
            }
        } else {
            Session::setSessionVariable("loyaltyTierName", "Bronze");
            Session::setSessionVariable("loyaltyCurrentTierEarning", 0);
        }
    }

    public static function encodeDecode($string, $type = "encode")
    {
        $response = ServerCommunication::sendCall(ServerUrl::ENCODE_DECODE_API, array(
            'requestData' => $string,
            'requestType' => $type
        ));
        if (Validations::getErrorCode() == 0) {
            return $response->responseValue;
        }

        return false;
    }

    public static function generateLoginToken()
    {
        list($msec, $sec) = explode(" ", microtime());
        $msec = str_replace("0.", "", $msec);
        $loginToken = str_pad($sec . $msec, 18, "0");
        $loginToken = md5($loginToken);
        return $loginToken;
    }

    public static function getLoyalPlayerDetail($size = "FULL", $isAjax = false)
    {
        $response = ServerCommunication::sendCall(ServerUrl::LOYAL_PLAYER_DETAIL, array(
            'size' => $size
        ), $isAjax);
        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            if (Validations::getErrorCode() == Errors::LOYALTY_PLAYER_NOT_EXISTS) {
                return Errors::LOYALTY_PLAYER_NOT_EXISTS;
            }
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        $percentage = 0;
        /* if(isset($response->playerInfo->nextTier)) {
          if((($response->playerInfo->nextTier->entryPoints)/100) != 0)
          $percentage = ($response->playerInfo->currentTierEarning)/(($response->playerInfo->nextTier->entryPoints)/100);
          }
          else {
          $percentage = 100;
          } */
        if (isset($response->playerInfo->currentTier->maintanancePoints)) {
            Session::setSessionVariable("loyaltyCurrentTierMaintanancePoints", $response->playerInfo->currentTier->maintanancePoints);
        } else {
            Session::setSessionVariable("loyaltyCurrentTierMaintanancePoints", 0);
        }
        if (isset($response->playerInfo->nextTier)) {
            if ((($response->playerInfo->nextTier->entryPoints) / 100) != 0)
                $percentage = ($response->playerInfo->currentTierEarning) / (($response->playerInfo->nextTier->entryPoints) / 100);
        } else {
            $percentage = (($response->playerInfo->currentTierEarning) / ($response->playerInfo->currentTier->maintanancePoints)) * 100;
        }
        $percentage = round($percentage, 2);
        Session::setSessionVariable("loyaltyBarPercentage", $percentage);
        Session::setSessionVariable("loyalPlayerDetail", $response);
        Session::setSessionVariable("loyaltyTierName", ucfirst(strtolower($response->playerInfo->currentTier->displayName)));
        Session::setSessionVariable("loyaltyTotalPoints", ucfirst(strtolower($response->playerInfo->totalPoints)));
        Session::setSessionVariable("loyaltyCurrentTierEarning", ucfirst(strtolower($response->playerInfo->currentTierEarning)));
        //  Session::setSessionVariable("loyaltyCurrentTierMaintanancePoints", ucfirst(strtolower($response->currentTier->maintanancePoints)));
        return $response;
    }

    public static function getLoyaltyRedeemPage($isAjax = false)
    {
        $response = ServerCommunication::sendCall(ServerUrl::LOYALTY_REDEEM_PAGE, array(), $isAjax);
        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        return $response;
    }

    public static function getLoyaltyStatementDetails($packetId, $isAjax = false)
    {
        $response = ServerCommunication::sendCall(ServerUrl::LOYALTY_PACKET_STATEMENT, array(
            "packetId" => $packetId
        ), $isAjax);
        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        return $response;
    }

    public static function parseDate($str)
    {
        $returning_string = "";
        for ($k = 0; $k < strlen($str); $k++) {
            if (is_numeric($str[$k]) === true) {
                $returning_string .= $str[$k];
            }
        }
        return $returning_string;
    }

    public static function manageButtons()
    {
        $deviceType = Configuration::getDeviceType();
        $os = Configuration::getOS();
        $doc = JFactory::getDocument();
        $doc->addStyleDeclaration('[play-now-button="true"], [android-download-button="true"], [ios-download-button="true"] {display:none !important;}');
        if ($deviceType !== Configuration::DEVICE_PC) {
            if ($os == Configuration::OS_ANDROID) {
                $doc->addStyleDeclaration('[android-download-button="true"]{display:inline-block!important;}');
            } elseif ($os == Configuration::OS_IOS) {
                $doc->addStyleDeclaration('[ios-download-button="true"]{display:inline-block!important;}');
            }
        } elseif ($deviceType === Configuration::DEVICE_PC) {
            $doc->addStyleDeclaration('[play-now-button="true"], [android-download-button="true"], [ios-download-button="true"]{display:inline-block!important;}');
        }
        $doc->addScriptDeclaration("
           if(window.opener != null  && window.location.href == '" . JUri::base() . "' && (window.name == 'cashierWindow' || window.name == 'rummyWindow' || window.name == 'rummyClientWindow' || window.name == 'RaferaFriendWindow' )) {
                opener.location.href = '" . Redirection::LOGIN . "';
                window.close();
            }
        ");
        if (Session::sessionValidate()) {
            $doc->addScriptDeclaration("
            jQuery(document).ready(function($) {
               $('.mobile-notify-menu-user-name').html('" . self::getPlayerLoginResponse()->userName . "');
               try
               {
                updateMessageCount('" . self::getPlayerLoginResponse()->unreadMsgCount . "');
               }
               catch( e )
               {
               }
               
            });
        ");
        }
        if (Session::sessionValidate()) {
            if (Session::getSessionVariable('showBonusPopup')) {
                $val = Session::getSessionVariable('showBonusPopup');
                Session::setSessionVariable('showBonusPopup', false);
                $doc->addScriptDeclaration("
                jQuery(document).ready(function($) {
                   jQuery('#bonus_popup #bonusAmt').html('" . $val . "');
                   jQuery('#bonus_popup .bonusCurrency').html('" . Constants::MYCURRENCYSYMBOL . "');              
                   jQuery('#bonus_popup').modal('show');
                });
            ");
            } else if (Session::getSessionVariable('showFailBonusPopup')) {
                Session::setSessionVariable('showFailBonusPopup', false);
                $doc->addScriptDeclaration("
                jQuery(document).ready(function($) {            
                   jQuery('#bonus_popup_fail').modal('show');
                });
            ");
            }
        }
        $deviceMode = Configuration::DEVICE_PC;
        $doc->addScriptDeclaration("
            jQuery(document).ready(function($) {
               console.log('$deviceType');
			   console.log('$deviceMode');
            });
        ");
    }

    public static function sendFacebookResp($post_id)
    {
        $response = ServerCommunication::sendCall("", array(
            'postId' => $post_id,
            "referThrough" => "FACEBOOK"
        ));
        return;
    }

    public static function trackBonus($isAjax = false)
    {
        $response = ServerCommunication::sendCall(ServerUrl::REFER_A_FRIEND_TRACK_BONUS, array(), $isAjax);
        if (Validations::getErrorCode() != 0) {
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        return $response;
    }

    public static function isDepositProcessable($isAjax = false, $amount = 0)
    {
        return ServerCommunication::sendCall(ServerUrl::IS_DEPOSIT_PROCESSABLE, array("depositRequest" => array("amount" => $amount)), $isAjax);
    }

    public static function getReferralLink($referThrough)
    {
        ServerCommunication::sendCall(ServerUrl::INVITE_FRIEND, array(
            "referType" => "socialRefer",
            "inviteMode" => "EMAIL",
            "referThrough" => $referThrough
        ));
        if (Validations::getErrorCode() == 0)
            return Validations::getRespMsg();
        else
            return false;
    }

    public static function getFormatedEarnings($earning)
    {
        $EarningArr = explode(".", $earning);
        if (count($EarningArr) >= 2) {
            $firstInt = $EarningArr[1] . "";
            if ($firstInt[0] >= 5) {
                $firstInt = (int) $firstInt[0];
                return (int) $EarningArr[0] . "." . ++$firstInt;
            } else {
                $firstInt = (int) $firstInt[0];
                return (int) $EarningArr[0] . "." . $firstInt;
            }
        } else {
            return $earning;
        }
    }

    public static function fetchInternalEmailData($modId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName(array('id', 'title', 'params', 'position')))
            ->from($db->quoteName('#__modules'))
            ->where("module='mod_weaveremail' and id=" . $modId . "");
        $db->setQuery($query);
        $result = $db->loadAssocList();
        //exit(json_decode($result[0]['params'])->content);
        if (count($result) == 0) {
            exit(json_encode(array('errorCode' => 302, 'respMsg' => "No internal email data found for module id " . $modId . ".")));
        }
        $css_style = json_decode($result[0]['params'])->css_style;
        $content = json_decode($result[0]['params'])->content;
        echo '<style>' . $css_style . '</style>';
        echo $content;
        exit;
    }

    public static function BettingAccessLog($url, $start, $end, $current_url, $req, $res,$headers=false)
    {
        $db = JFactory::getDbo();
        date_default_timezone_set(Constants::DEFAULT_TIMEZONE);
        $setLog = new stdClass();
        $setLog->url_betting = $url;
        $setLog->intime = $start;
        $setLog->outtime = $end;
        $setLog->url = $current_url;
        $setLog->request = $req;
        $setLog->response = $res;
        $setLog->headers = $headers;
        $db->insertObject('stpl_betting_accesslog', $setLog);
    }

    public static function emailCheck($email)
    {
        if (is_null($email) || empty($email) || strlen($email) < 3 || strlen($email) > 100) {
            return false;
        }
        return true;
    }

    public static function mobileCheck($mobile)
    {
        if (is_null($mobile) || empty($mobile) || strlen($mobile) != 10) {
            return false;
        }
        return true;
    }

    public static function doGSPregistration($userName, $password, $mobileNo, $emailId, $landingPage, $registrationType, $stateCode, $submitUrl, $refercode)
    {
        $requestArr = array(
            "userName" => $userName,
            "password" => $password,
            "mobileNo" => $mobileNo,
            "emailId" => $emailId,
            "requestIp" => Configuration::getClientIP(),
            "registrationType" => $registrationType
        );
        //exit(json_encode($requestArr));
        $isError = false;
        if (!Validations::validateUserName($userName, "Username")) {
            $response = json_decode('{"errorCode":501,"respMsg":"Please Enter your Username"}');
            if (!self::emailCheck($emailId)) {
                $response = json_decode('{"errorCode":612,"respMsg":"Please Enter your Username and email"}');
                //exit(json_encode($mobileNo));
                if (!self::mobileCheck($mobileNo)) {
                    //exit(json_encode($mobileNo));
                    $response = json_decode('{"errorCode":613,"respMsg":"Please Enter your Username and email and Mobile No."}');
                    //exit(json_encode($response));
                }
            } else if (!self::mobileCheck($mobileNo)) {
                $response = json_decode('{"errorCode":611,"respMsg":"Please Enter your Username and Mobile No."}');
            }
            $isError = true;
        }
        if (!self::emailCheck($emailId) && Validations::validateUserName($userName, "whjsf")) {
            $response = json_decode('{"errorCode":502,"respMsg":"Please Enter your Email"}');
            if (!self::mobileCheck($mobileNo)) {
                $response = json_decode('{"errorCode":610,"respMsg":"Please Enter your Email and Mobile No."}');
            }
            $isError = true;
        }
        if (!self::mobileCheck($mobileNo) && self::emailCheck($emailId) && Validations::validateUserName($userName, "email")) {
            $response = json_decode('{"errorCode":503,"respMsg":"Please Enter your Mobile "}');
            $isError = true;
        }
        if ($isError == true) {
            setcookie("GSP_res", json_encode($response));
            if (Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
            Redirection::to($landingPage, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        if ($stateCode != "") {
            $requestArr['stateCode'] = $stateCode;
        }
        if ($refercode != "") {
            $requestArr['referCode'] = $refercode;
        }
        if (Session::getSessionVariable('reEncString') !== false) {
            $requestArr['trackingCipher'] = Session::getSessionVariable('reEncString');
        }
        if (Session::getSessionVariable('idVCommString') !== false) {
            $requestArr['idVComm'] = Session::getSessionVariable('idVCommString');
        }
        $response = ServerCommunication::sendCall(ServerUrl::PLAYER_REGISTRATION, $requestArr, Validations::$isAjax);
        if (Validations::getErrorCode() != 0) {
            setcookie("GSP_uname", $userName);
            setcookie("GSP_pwd", $password);
            setcookie("GSP_mob", $mobileNo);
            setcookie("GSP_mail", $emailId);
            setcookie("GSP_res", json_encode($response));
            //  setcookie("hi",'hi');
            //exit("GSP_uname");
            if (Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
            Redirection::to($landingPage, Errors::TYPE_ERROR, Validations::getRespMsg());
        }
        Session::sessionInitiate($response);
        Session::setSessionVariable('fireRegistrationEvent', true);
        Session::unsetSessionVariable("reEncString?");
        Session::unsetSessionVariable("idVCommString");
        $redirectTo = "";
        if ($submitUrl == null) {
            $redirectTo = Redirection::AFTER_REGISTRATION;
        } else {
            $redirectTo = urldecode($submitUrl);
        }
        Session::setSessionVariable('after_registration', true);
        if (Validations::$isAjax) {
            $response->path = $redirectTo;
            Redirection::ajaxSendDataToView($response);
        }
        Redirection::to($redirectTo);
    }

    public static function goldenraceData()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $id = Utilities::getPlayerId();
        $query
            ->select($db->quoteName(('PlayerId')))
            ->from($db->quoteName('lw_GoldenRace_master'))
            ->where("PlayerId=$id");
        $db->setQuery($query);
        $result = $db->loadAssocList();
        //        exit(json_encode($result));
        if ($result != null || $result != [])
            return true;
        else
            return false;
    }

    public static function parseXmlResponse($url)
    {
        $xmlResp = file_get_contents($url);
        $resp = new SimpleXMLElement($xmlResp);
        return $resp;
    }

    public static function setPlayerIdForGR($id)
    {
        $db = JFactory::getDbo();
        $setId = new stdClass();
        $setId->PlayerId = $id;
        $db->insertObject('lw_GoldenRace_master', $setId);
        return;
    }
    /*** Lottery Utilities */
    public static function getServiceDetails($service)
    {
        return Constants::$serviceDetails[$service];
    }

    public static function getFormattedAmount($amount)
    {
        $tmp = explode(".", $amount);
        $amount = str_replace(",", "", $tmp[0]) . "." . $tmp[1];
        if (count($tmp) == 2) {
            if (strlen($tmp[1]) > 2) {
                if (substr($tmp[1], 0, 2) != "00")
                    return number_format($amount, 2);
            } else if (strlen($tmp[1]) == 2) {
                if ($tmp[1] != "00")
                    return number_format($amount, 2);
            } else if (strlen($tmp[1]) == 1) {
                if ($tmp[1] != "0")
                    return number_format($amount, 2);
            } else {
                return number_format($amount, 0);
            }
        }
        return number_format($amount, 0);
    }

    public static function getCurrencyInfo()
    {
        if (Session::sessionValidate()) {
            $playerInfo = Utilities::getPlayerLoginResponse();
            $currency = Constants::DEFAULT_CURRENCY_CODE;
            $dispCurrency = Constants::$currencyMap[$currency]['entity'];
        } else {
            $currency = Constants::DEFAULT_CURRENCY_CODE;
            $dispCurrency = Constants::$currencyMap[$currency]['entity'];
        }
        return array($currency, "INR");
    }

    public static function formatCurrency($amount, $currency = Constants::DEFAULT_CURRENCY_CODE, $dispCurrency = '')
    {

        $info = self::getCurrencyInfo();
        $currency = $info[0];
        $dispCurrency = $info[1];

        //        exit(json_encode($amount));
        $show_deciaml = Constants::HIDE_ZERO_DECIMAL;
        if ($show_deciaml) {
            $decimal_value = explode('.', $amount);
            if ($decimal_value[1] == 00) {
                $amount = $decimal_value[0];
            }
        }
        if (!empty($amount) || !empty($currency)) {
            switch ($currency) {
                case "CFA":
                    if ((!empty($amount) || $amount >= 0) && !empty($currency)) {
                        $amount = str_replace(",", " ", $amount);
                        $amount = str_replace(".", ",", $amount);
                        return $amount . ' ' . $currency;
                    } else if (empty($amount)) {
                        return $currency;
                    } else if (empty($currency)) {
                        $amount = str_replace(",", " ", $amount);
                        $amount = str_replace(".", ",", $amount);
                        return $amount;
                    }
                    break;
                default:
                    if ((!empty($amount) || $amount >= 0) && !empty($currency)) {
                        return $dispCurrency . ' ' . $amount;
                    } else if (empty($amount)) {
                        return $dispCurrency;
                    } else if (empty($currency)) {
                        return $amount;
                    }
                    break;
            }
        } else {
            return '';
        }
    }

    public static function validatePlayerBonus($referCode)
    {

        $resp = array();
        $db = JFactory::getDbo();
        try {
            $query = $db->getQuery(true);
            $query
                ->select('*')
                ->from($db->quoteName('#__bonus_master'))
                ->where($db->quoteName('bonusCode') . " = " . $db->quote(trim($referCode)))
                ->where($db->quoteName('date') . " = " . $db->quote(date("Y-m-d")));

            $db->setQuery($query);
            $result = $db->loadAssocList();
            $count = count($result);

            if ($count == 0) {
                $resp['code'] = 100;
                $resp['data'] = [];
            } else {
                foreach ($result as $bonus) {
                    if ($bonus['status'] == "OPEN") {
                        $resp['code'] = 0;
                        $resp['data'] = $bonus;
                        break;
                    }
                }
                if (!isset($resp['code'])) {
                    $resp['code'] = 101;
                    $resp['data'] = [];
                }
            }
        } catch (Exception $e) {
            $resp['code'] = 102;
            $resp['data'] = [];
        }

        return $resp;
    }

    public static function changeBonusStatus($referCode, $playerId, $id)
    {
        $db = JFactory::getDbo();
        try {
            $db->transactionStart();
            $object = new stdClass();
            $object->id = $id;
            $object->status = 'CLOSED';
            $object->playerId = $playerId;
            $result = $db->updateObject('#__bonus_master', $object, 'id');
            $db->transactionCommit();
        } catch (Exception $e) {
            $db->transactionRollback();
        }
    }

    public static function getCurrencyList()
    {
        //        $response = ServerCommunication::sendCall(ServerUrl::GET_PLAYER_CURRENCY, array());
        //        if (Validations::getErrorCode() != 0) {
        //               return [];
        //        }
        //        return $response->currencyList;
        $response = Constants::$currencyMap;
        return $response;
    }

    // Outputs a human readable number.

    public static function bd_nice_number($n)
    {


        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        // now filter it;
        if ($n > 1000000000000) {
            return round(($n / 1000000000000), 1) . ' Trillion';
        } else if ($n >= 1000000000) {
            return round(($n / 1000000000), 1) . ' Billion';
        } else if ($n >= 1000000) {
            return round(($n / 1000000), 1) . ' Million';
        } else if ($n >= 1000) {
            return round(($n / 1000), 1) . ' Thousand';
        }

        return number_format($n);
    }

    public static function set_time_zone($timeZone)
    {
        date_default_timezone_set($timeZone);
    }


    public static function getGameInfo()
    {
        if (!isset($GLOBAL['gameInfo'])) {
            $redisHandler = new RedisHandler();
            $gameInfo = $redisHandler->get(Configuration::REDIS_GLOBAL_KEY . "gameInfo");
            if ($gameInfo == false || is_null($gameInfo)) {
                $isConnected = RedisHandler::isConnected();
                $gameInfo = self::getGameInfoForBanner();
                if ($isConnected) {
                    $redisHandler->store(Configuration::REDIS_GLOBAL_KEY . "gameInfo", json_encode($gameInfo));
                }
            }
            $GLOBALS['gameInfo'] = $gameInfo;
        } else {
            $gameInfo = $GLOBALS['gameInfo'];
        }
        return $gameInfo;
    }

    public static function getGameInfoForBanner()
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->quoteName('#__games_info'));
        $db->setQuery($query);
        $gameInfo = $db->loadAssocList();

        $gameInfo = self::formateGameInfoData($gameInfo);

        return $gameInfo;
    }

    public static function formateGameInfoData($gameInfo)
    {

        $gameData = array();
        foreach ($gameInfo as $item) {
            $gameData[$item['game_code']] = $item;
            unset($gameData[$item['game_code']]['game_code']);
        }

        return $gameData;
    }

    public static function getCasinoGamesCategory()
    {

        //SELECT `lobby_cat`,COUNT(`id`) AS LENGTH FROM `stpl_casino_game_master` GROUP BY `lobby_cat` ORDER BY `priority`
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName(array('lobby_cat')))
            ->select('COUNT(' . $db->quoteName('id') . ') AS length')
            ->from($db->quoteName('#__casino_game_master'))
            ->group($db->quoteName('lobby_cat'))
            ->order($db->quoteName('priority'));
        $db->setQuery($query);
        $gameInfo = $db->loadAssocList();

        return $gameInfo;
    }

    public static function getCasinoGamesList($lobby_cat)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__casino_game_master'))
            ->where($db->quoteName('lobby_cat') ." like '%" . $lobby_cat . "%'")
            ->where($db->quoteName('status') . " = 'ACTIVE'");
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public static function formateCasinoGameInfoData($gameInfo)
    {
        $gameData = array();
        $count = 0;
        foreach ($gameInfo as $item) {
            $gameData[$item['lobby_cat']][] = array(
                "tableId" => $item['tableId'],
                "name" => $item['name'],
                "gameType" => $item['gameType'],
                "gameProvider" => $item['gameProvider'],
                "gameTypeUnified" => $item['gameTypeUnified'],
                "language" => $item['language'],
                "img_src" => $item['img_src'],
                "lobby_cat" => $item['lobby_cat'],
                "priority" => $item['priority']
            );
            $count++;
        }


        $priorities = array();
        foreach ($gameData as $item) {
            foreach ($item as $key => $val) {
                $priorities[$val['lobby_cat']] = $val['priority'];
            }
        }
//        exit(json_encode($priorities));
        array_multisort($priorities, SORT_STRING, $gameData);

        return array($gameData,$count);
    }

    public static function getCasinoGameList()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->quoteName('#__casino_game_master'));
        $db->setQuery($query);
        $gameInfo = $db->loadAssocList();

        $gameInfo = self::formateCasinoGameInfoData($gameInfo);

        return array($gameInfo[0],$gameInfo[1]);
    }

    public static function evloutionUserAuthentication($tableId,$playMode,$gameCategory)
    {
        if (Session::sessionValidate()) {

            $requestObj = new stdClass();
            $playerObj = new stdClass();
            $configObj = new stdClass();
            $sessionObj = new stdClass();
            $gameObj = new stdClass();
            $tableObj = new stdClass();
            $channelObj = new stdClass();

            $playerObj->id = Utilities::getPlayerId()."";
            $playerObj->update = true;
            $playerObj->language = "en";
            $playerObj->country = Constants::COUNTRY_CODE;
            $playerObj->currency = Constants::MYCURRENCYCODE;

            $sessionObj->id = Utilities::getPlayerToken();
            $sessionObj->ip = $_SERVER['REMOTE_ADDR'];

            $playerObj->session = $sessionObj;

            $tableObj->id = $tableId;
            $gameObj->table = $tableObj;
            $gameObj->category = $gameCategory;


            $configObj->game = $gameObj;
//            $configObj->playMode = $playMode;

            $channelObj->wrapped = true;

            $configObj->channel = $channelObj;

            $requestObj->uuid = time()."";
            $requestObj->player = $playerObj;
            $requestObj->config = $configObj;

            if( $tableId == null ) {
                if(  ( $gameCategory !== "game_shows" && $gameCategory !== "slots")  ){
                    unset($requestObj->config->game);
                }
            }

            if( $gameCategory === "game_shows" || $gameCategory === "slots" ){
                unset($requestObj->config->game->table);
            }



            $url = Configuration::EVOLUTION_HOST . "/ua/v1/" . Configuration::EVOLUTION_INSTANCE_ID . "/" . Configuration::EVOLUTION_TOKEN;

            $response = ServerCommunication::sendCallTo3Party($url,$requestObj,false,false,true,true);

            return $response;

        }
        else {
            Redirection::to(JUri::base(), Errors::TYPE_ERROR, Validations::getRespMsg());
        }

    }

    public static function getActiveBingoRooms()
    {
        $extraHeaders = array(
            "token" => "1",
            "userId" => "1",
        );

        $response = ServerCommunication::sendCallToGameEngine(
            ServerUrl::BINGO_API . ServerUrl::BINGO_ACTIVE_ROOMS, array(), false, true, $extraHeaders, false,false
        );
        
        return $response;

    }
}
