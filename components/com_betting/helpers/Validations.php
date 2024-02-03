<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Validations {
    // Response End validation
    private static $errorCode;
    private static $respMsg;
    // Joomla End validation
    public static $error = false;
    public static $errorMsg = "";
    public static $isAjax = false;
    public static $popUpCreated = false;

    public static function getErrorCode() {
        return self::$errorCode;
    }

    public static function setErrorCode($errorCode) {
        self::$errorCode = $errorCode;
    }

    public static function getRespMsg() {
        return self::$respMsg;
    }

    public static function setRespMsg($respMsg) {
        self::$respMsg = $respMsg;
    }

    public static function validateRequestResponseData($for, $data, $isRequest = true, $isAjax = false) {
        $url = '';
        if (!$isRequest) {
            if (!isset($data->errorCode) && !isset($data->responseCode)) {
                Session::sessionRemove();
                if (!$isAjax)
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::INVALID_ERROR_CODE);
                else
                    Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Errors::INVALID_ERROR_CODE);
            } else {
                if( isset($data->errorCode) )
                    self::setErrorCode($data->errorCode);
                else if( isset($data->responseCode) )
                    self::setErrorCode($data->responseCode);
            }


            if (isset($data->respMsg))
                self::setRespMsg($data->respMsg);
            else if( isset($data->responseMessage) )
                self::setRespMsg($data->responseMessage);
        }
        switch ($for) {
            case ServerUrl::PLAYER_LOGIN:
                $url = Redirection::LOGIN;
                if ($isRequest) {
                    self::validateLoginRequest($data);
                } else {
                    if (self::getErrorCode() == 0)
                        self::validateLoginResponse($data);
                }
                break;
            case ServerUrl::PLAYER_REGISTRATION:
            case ServerUrl::PLAYER_REGISTRATION_NEW:
                $url = Redirection::REGISTRATION;
                if ($isRequest ) {
                    self::validateRegistrationRequest($data);
                } else {
                    if (self::getErrorCode() == 0)
                        self::validateLoginResponse($data);
                }
                break;
            default :
                break;
        }
        if (self::$error == true) {
            if (!$isAjax)
                Redirection::to($url, Errors::TYPE_ERROR, self::$errorMsg);
            else
                Redirection::ajaxExit($url, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, self::$errorMsg);
        }
        if (!$isRequest) {
            if (self::getErrorCode() == Errors::PLAYER_NOT_LOGGED_IN) {
                Session::sessionRemove();
                if (!$isAjax) {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Validations::getRespMsg());
                } else {
                    Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Validations::getRespMsg());
                }
            }
        }
        return;
    }

    public static function updateErrorVars($err, $errMsg) {
        self::$error = $err;
        self::$errorMsg = $errMsg;
    }
    // Validate Login Request
    public static function validateLoginRequest($data) {
        if (!self::validateUserName((isset($data['userName'])) ? $data['userName'] : '', Errors::INVALID_REQUEST))
            return false;
        if (!self::validatePassword((isset($data['password'])) ? $data['password'] : '', Errors::INVALID_REQUEST))
            return false;
        self::updateErrorVars(false, "");
        return true;
    }
    // Validate Login Response
    public static function validateLoginResponse($response) {
        if (!self::validatePlayerToken((isset($response->playerToken) ? $response->playerToken : ''), Errors::INVALID_PLAYERTOKEN))
            return false;
        if (!self::validatePlayerId((isset($response->playerLoginInfo->playerId) ? $response->playerLoginInfo->playerId : ''), Errors::INVALID_PLAYERID))
            return false;
//        if (!self::validateEmail((isset($response->playerLoginInfo->emailId) ? $response->playerLoginInfo->emailId : ''), Errors::INVALID_LOGIN_RESPONSE . "[EMAIL-ID]"))
//            return false;
//        if (!self::validateMobile((isset($response->playerLoginInfo->mobileNo) ? $response->playerLoginInfo->mobileNo : ''), Errors::INVALID_LOGIN_RESPONSE . "[MOBILE-NO]"))
//            return false;
        if ((!isset($response->playerLoginInfo->emailVerified)) || ($response->playerLoginInfo->emailVerified != 'Y' && $response->playerLoginInfo->emailVerified != 'N')) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[EMAIL-VERIFIED]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->phoneVerified)) || ($response->playerLoginInfo->phoneVerified != 'Y' && $response->playerLoginInfo->phoneVerified != 'N')) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[PHONE-VERIFIED]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->ageVerified)) || ($response->playerLoginInfo->ageVerified != 'PENDING' && $response->playerLoginInfo->ageVerified != 'VERIFIED')) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[AGE-VERIFIED]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->addressVerified)) || ($response->playerLoginInfo->addressVerified != 'PENDING' && $response->playerLoginInfo->addressVerified != 'VERIFIED')) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[ADDRESS-VERIFIED]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->playerStatus)) || ($response->playerLoginInfo->playerStatus != 'MINI' && $response->playerLoginInfo->playerStatus != 'FULL')) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[PLAYER-STATUS]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->firstDepositDate))) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[FIRST-DEPOSIT-DATE]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->walletBean))) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[WALLET]");
            return false;
        } else {
            if ((!isset($response->playerLoginInfo->walletBean->withdrawableBal))) {
                self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[WITHDRAWAL-BAL]");
                return false;
            }
            if ((!isset($response->playerLoginInfo->walletBean->totalBalance))) {
                self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[TOTAL-BAL]");
                return false;
            }
            if ((!isset($response->playerLoginInfo->walletBean->bonusBalance))) {
                self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[BONUS-BAL]");
                return false;
            }
            if ((!isset($response->playerLoginInfo->walletBean->cashBalance))) {
                self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[CASH-BAL]");
                return false;
            }
            if ((!isset($response->playerLoginInfo->walletBean->practiceBalance))) {
                self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[PRACTISE-BAL]");
                return false;
            }
        }
        if ((!isset($response->playerLoginInfo->avatarPath))) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[AVATAR]");
            return false;
        }
        if ((!isset($response->playerLoginInfo->userName))) {
            self::updateErrorVars(true, Errors::INVALID_LOGIN_RESPONSE . "[USER-NAME]");
            return false;
        }
        self::updateErrorVars(false, "");
        return true;
    }
    // Validate Registration Request
    public static function validateRegistrationRequest($data) {
        if (!self::validateUserName((isset($data['userName'])) ? $data['userName'] : '', Errors::INVALID_REQUEST))
            return false;
        if (!self::validatePassword((isset($data['password'])) ? $data['password'] : '', Errors::INVALID_REQUEST))
            return false;
//        if (!self::validateEmail((isset($data['emailId']) ? $data['emailId'] : ''), Errors::INVALID_REQUEST))
//            return false;
        if (!self::validateMobile((isset($data['mobileNo']) ? $data['mobileNo'] : ''), Errors::INVALID_REQUEST))
            return false;
        self::updateErrorVars(false, "");
        return true;
    }
    /*** Specific functions */
    // Username validation
    public static function validateUserName($userName, $err) {
        if (is_null($userName) || empty($userName) || strlen($userName) < 3 || strlen($userName) > 100) {
            self::updateErrorVars(true, $err);
            return false;
        }
        return true;
    }
    // Password validation
    public static function validatePassword($password, $err) {
        if (is_null($password) || empty($password) || strlen($password) < 3 || strlen($password) > 100) {
            self::updateErrorVars(true, $err);
            return false;
        }
        return true;
    }
    // Email validation
    public static function validateEmail($email, $err) {
        if (is_null($email) || empty($email) || strlen($email) < 3 || strlen($email) > 100) {
            self::updateErrorVars(true, $err);
            return false;
        }
    }
    // Mobile validation
    public static function validateMobile($mobile, $err) {
        if (is_null($mobile) || empty($mobile) || (strlen($mobile) < Constants::MOBILE_MIN_LENGTH) || (strlen($mobile) > Constants::MOBILE_MAX_LENGTH)) {
            self::updateErrorVars(true, $err);
            return false;
        }
    }
    // Player Id validation
    public static function validatePlayerId($playerId, $err) {
        if (is_null($playerId) || empty($playerId)) {
            self::updateErrorVars(true, $err);
            return false;
        }
        return true;
    }
    // Player Token validation
    public static function validatePlayerToken($playerToken, $err) {
        if (is_null($playerToken) || empty($playerToken)) {
            self::updateErrorVars(true, $err);
            return false;
        }
        return true;
    }
    
    public static function validateDate($date) {
        if (empty($date))
            return false;
        if (strlen($date) != 10)
            return false;
        $dateArr = explode("/", $date);
        if ((strlen($dateArr[0]) != 2) || (strlen($dateArr[1]) != 2) || (strlen($dateArr[2]) != 4))
            return false;
        if (!checkdate($dateArr[1], $dateArr[0], $dateArr[2]))
            return false;
        return true;
    }

    public static function compareDate($fromDate, $toDate) {
        $fromDateArr = explode("/", $fromDate);
        $toDateArr = explode("/", $toDate);
        $fromTime = mktime(0, 0, 0, $fromDateArr[1], $fromDateArr[0], $fromDateArr[2]);
        $toTime = mktime(0, 0, 0, $toDateArr[1], $toDateArr[0], $toDateArr[2]);
        $diff = $toTime - $fromTime;
        if ($diff < 0)
            return false;
        return true;
    }

}
?>
