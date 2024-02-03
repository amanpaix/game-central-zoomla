<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class ServerCommunication {

    public static function sendCall($url, $data = array(), $isAjax = false, $isJson = true, $extra_headers = false) {
        Validations::validateRequestResponseData($url, $data, true, $isAjax);
        $data['domainName'] = Configuration::DOMAIN_NAME;
        if ($url == ServerUrl::PLAYER_LOGIN ||
                $url == ServerUrl::DEPOSIT_REQUEST ||
                $url == ServerUrl::PLAYER_REGISTRATION ||
                $url == ServerUrl::PLAYER_REGISTRATION_NEW ||
                $url == ServerUrl::WITHDRAWAL_REQUEST ||
                $url == ServerUrl::WITHDRAWAL_REQUEST_MOMO ||
                $url == ServerUrl::CASHIER_WITHDRAWAL_REQUEST ||
                $url == ServerUrl::OFFLINE_DEPOSIT_REQUEST ||
                $url == ServerUrl::PAYMENT_OPTIONS ||
                $url == ServerUrl::PREPARE_CAMPAIGN_TREKKING ||
                $url == ServerUrl::REFER_A_FRIEND) {
            $data['deviceType'] = Configuration::getDeviceType();
            $data['userAgent'] = Configuration::getDevice();
        }
        if ($url == ServerUrl::PLAYER_LOGIN || $url == ServerUrl::PLAYER_REGISTRATION || $url == ServerUrl::PLAYER_REGISTRATION_NEW) {
            $data['loginDevice'] = Configuration::getLoginDevice();
        }
        if ($url != ServerUrl::PLAYER_LOGIN &&
                $url != ServerUrl::PLAYER_REGISTRATION &&
                $url != ServerUrl::PLAYER_REGISTRATION_NEW &&
                $url != ServerUrl::FORGOT_PASSWORD &&
                $url != ServerUrl::CHECK_LOGIN &&  $url != ServerUrl::RETAILER_LOGIN && $url != ServerUrl::POST_LOGIN_DATA &&
                Session::sessionValidate()) {
            if (Utilities::getPlayerToken() !== false)
                $data['playerToken'] = Utilities::getPlayerToken();

            if ($url != ServerUrl::PLAYER_PROFILE && Utilities::getPlayerId() !== false)
                $data['playerId'] = Utilities::getPlayerId();


        }
        $baseUrl = ServerUrl::BASE_URL;
        if($url == ServerUrl::PAYMENT_OPTIONS || 
          $url == ServerUrl::CASHIER_REDEEM_ACCOUNT || 
          $url == ServerUrl::ADD_CASHIER_REDEEM_ACCOUNT ||
          $url == ServerUrl::CASHIER_DEPOSIT_STATUS ||
          $url == ServerUrl::CASHIER_WITHDRAWAL_REQUEST){
          $baseUrl = ServerUrl::CASHIER_BASE_URL; 
        }
        else if( $url == ServerUrl::PLAYER_INBOX ||
        $url == ServerUrl::INBOX_ACTIVITY ){
            $baseUrl = ServerUrl::COMM_ENGINE;
        }
        else if( $url == ServerUrl::TRANSACTION_DETAILS ||
            $url == ServerUrl::UPDATE_PRACTICE_BAL){
            $baseUrl = ServerUrl::TXN_ENGINE;
        }
        if ($isJson) {
            $header = array("Content-type: application/json");
        }
        if ($extra_headers !== false) {
            foreach ($extra_headers as $key => $value) {
                array_push($header, $key . ":" . $value);
            }
        }

        array_push($header, "merchantCode: ".Configuration::MERCHANT_CODE);

        $dontCheck = false;
        if ($url == ServerUrl::POST_LOGIN_DATA || $url == ServerUrl::CHECK_LOGIN)
            $dontCheck = true;
        $startTime = date("Y-m-d H:i:s");
        $curl = curl_init($baseUrl . $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($isJson) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $response = curl_exec($curl);
        $endtimeTime = date("Y-m-d H:i:s");
        // echo "<pre>";
        // echo json_encode($data);
        // echo "<br>";
        // echo $response;

         Utilities::BettingAccessLog($baseUrl . $url."---".date_default_timezone_get(),$startTime,$endtimeTime,json_encode($header),json_encode($data),$response,json_encode($header));
        if ($response === false && !$dontCheck) {
            Session::sessionRemove();

            if ($isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            } else {
                Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            }
        }
        curl_close($curl);
        $response = json_decode($response);
        //$response->errorCode = 0;
        if (!$dontCheck) {
            Validations::validateRequestResponseData($url, $response, false, $isAjax);
        }
        return $response;
    }

    public static function sendCallRam($url, $data = array(), $isAjax = false, $isJson = true, $extra_headers = false, $requestType = 'POST') {
        Validations::validateRequestResponseData($url, $data, true, $isAjax);
        $data['aliasName'] = Configuration::DOMAIN_NAME;
        $data['domainName'] = Configuration::DOMAIN_NAME;

        if ($url == ServerUrl::RAM_LOGIN ||
            $url == ServerUrl::RAM_REGISTRATION ||
            $url == ServerUrl::RAM_REGISTRATION_WITH_OTP
//            $url == ServerUrl::WITHDRAWAL_REQUEST ||
//            $url == ServerUrl::OFFLINE_DEPOSIT_REQUEST ||
//            $url == ServerUrl::PAYMENT_OPTIONS ||
//            $url == ServerUrl::PREPARE_CAMPAIGN_TREKKING ||
//            $url == ServerUrl::REFER_A_FRIEND ||
//            $url == ServerUrl::PLAYER_LOGOUT ||
//            $url == ServerUrl::VOUCHER_DEPOSIT
        ) {
            $data['deviceType'] = Configuration::getDeviceType();
            $data['userAgent'] = Configuration::getDevice();
        }
        if ($url == ServerUrl::RAM_LOGIN || $url == ServerUrl::RAM_REGISTRATION || $url == ServerUrl::RAM_REGISTRATION_WITH_OTP) {
            $data['loginDevice'] = Configuration::getLoginDevice();
        }
        if ($url != ServerUrl::RAM_LOGIN &&
            $url != ServerUrl::RAM_REGISTRATION &&
            $url != ServerUrl::RAM_REGISTRATION_WITH_OTP &&
            $url != ServerUrl::FORGOT_PASSWORD &&
            $url != ServerUrl::POST_LOGIN_DATA &&
            Session::sessionValidate()) {
            if (Utilities::getPlayerToken() !== false) {
                $data['playerToken'] = Utilities::getPlayerToken();
            }
            if (!($url == ServerUrl::PLAYER_PROFILE || $url == ServerUrl::RAM_VERIFY_EMAIL_OTP) && (Utilities::getPlayerId() !== false)) {
                $data['playerId'] = Utilities::getPlayerId();
            }
        }
        if ($isJson) {
                $header = array("Content-type: application/json");
            if (Utilities::getPlayerToken() !== false) {
                array_push($header, "playerToken: " . $data['playerToken']);
            }
            if (Utilities::getPlayerId() !== false) {
                array_push($header, "playerId: " . Utilities::getPlayerId());
            }
        }
        if ($extra_headers !== false) {
            foreach ($extra_headers as $key => $value) {
                array_push($header, $key . ":" . $value);
            }
        }

        array_push($header, "merchantCode: ".Configuration::MERCHANT_CODE);

        $dontCheck = false;
        if ($url == ServerUrl::POST_LOGIN_DATA || $url == ServerUrl::SAVE_SITE_OFFLINE) {
            $dontCheck = true;
        }

        if ($requestType == 'GET') {
            $URL = ServerUrl::RAM_BASE . $url . "?" . http_build_query($data);
        } else {
            $URL = ServerUrl::RAM_BASE . $url;
        }

        $startTime = date("Y-m-d H:i:s");
        $curl = curl_init($URL);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $request = json_encode($data);
        if ($isJson) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        if ($requestType == "PUT") {
            curl_setopt($curl, CURLOPT_PUT, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        else if ($requestType == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        else if ($requestType == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $response = curl_exec($curl);
        $endtimeTime = date("Y-m-d H:i:s");
        Utilities::BettingAccessLog($URL, $startTime, $endtimeTime, $_SERVER['REQUEST_URI'], json_encode($data), $response,json_encode($header));
        if ($response === false && !$dontCheck) {
            Session::sessionRemove();
            if ($isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            } else {
                Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            }
        }
        curl_close($curl);
        $response = json_decode($response);
        if (!$dontCheck) {
            Validations::validateRequestResponseData($url, $response, false, $isAjax);
        }
        //die(json_encode($response));
        return $response;
    }

    public static function serverUploadImage($url, $data, $isAjax = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        if ($response === false) {
            Session::sessionRemove();
            if ($isAjax) {
                Redirection::ajaxSendDataToView(true, 1, Errors::SYTEM_ERROR);
            }
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
        }
        curl_close($ch);
        $response = json_decode($response);
        return $response;
    }

    public static function serverUploadImageNew($url, $data, $isAjax = false)
    {
        $header = array("Content-Type: multipart/form-data;");
        array_push($header, "merchantCode: ".Configuration::MERCHANT_CODE);

        $curl = curl_init(ServerUrl::BASE_URL . $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($curl);
        Utilities::BettingAccessLog(ServerUrl::BASE_URL . $url,"","",$_SERVER['REQUEST_URI'],json_encode($data),$response);

        if ($response === false) {
//            Session::sessionRemove();
            if ($isAjax) {
                Redirection::ajaxSendDataToView(true, 1, Errors::SYTEM_ERROR);
            }
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
        }
        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

    public static function sendCallToGameEngine($url, $data = array(), $isAjax = false, $isJson = true, $extra_headers = false, $ige = false, $isPost = true) {
        $headerArr = array(
            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        );
        if ($isJson) {
            array_push($headerArr, "Content-Type: application/json");
        }
        if ($extra_headers !== false) {
            foreach ($extra_headers as $k => $v) {
                array_push($headerArr, $k . ": " . $v);
            }
        }
        $playerToken = Utilities::getPlayerToken();
        if ($playerToken !== false) {
            array_push($headerArr, "sessionId: " . $playerToken);
        }
        if ($ige) {
            $deviceType = Configuration::getDeviceType();
            $appTypeAndClientType = Configuration::getAppAndClientType($deviceType);
            $url .= "&clientType=" . $appTypeAndClientType['CLIENTTYPE'] . "&deviceType=" . Configuration::getDeviceType() . "&appType=" . $appTypeAndClientType['APPTYPE'] . "&userAgentIge=" . urlencode(Configuration::getDevice());
        }
        array_push($headerArr, "merchantCode: ".Configuration::MERCHANT_CODE);
        //Validations::$isAjax = isAjax;
        $startTime = date("Y-m-d H:i:s");
        if ($isPost) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 110);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $response = curl_exec($ch);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 110);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $response = curl_exec($ch);
        }
        $endtimeTime = date("Y-m-d H:i:s");
        Utilities::BettingAccessLog($url,$startTime,$endtimeTime,$_SERVER['REQUEST_URI'],json_encode($data),$response);
        if (curl_errno($ch) !== 0 || $response === false) {
            Session::sessionRemove();
            if ($isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN_PAGE, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            } else {
                Redirection::to(Redirection::LOGIN_PAGE, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            }
        }
        curl_close($ch);
        if (!$ige) {
            //$response = Validations::validateResponseFromEngine(json_decode($response, true), true, $isAjax);
        } else {
            //$response = Validations::validateResponseFromEngineIGE(json_decode($response, true), true, $isAjax);
        }
        return json_decode($response);
    }

    public static function serverUploadImageRam($url, $data, $isAjax = false) {
        $data['domainName'] =  Configuration::DOMAIN_NAME;
        $header = [];
        array_push($header, "Content-Type: multipart/form-data");
        if (Utilities::getPlayerToken() !== false) {
            array_push($header, "playerToken: " . Utilities::getPlayerToken());
        }
        if (Utilities::getPlayerId() !== false) {
            array_push($header, "playerId: " . Utilities::getPlayerId());
        }
        array_push($header, "merchantCode: ".Configuration::MERCHANT_CODE);

        $URL = ServerUrl::RAM_BASE . $url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_CONNECTTIMEOUT => 30
        ));
        $response = curl_exec($curl);
        Utilities::BettingAccessLog($URL,"","",$_SERVER['REQUEST_URI'] ,json_encode($data),$response,json_encode($header));
        if ($response === false) {
            Session::sessionRemove();
            if ($isAjax) {
                Redirection::ajaxSendDataToView(true, 1, Errors::SYTEM_ERROR);
            }
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
        }
        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

    public static function sendCallTo3Party($url, $data = array(), $isAjax = false, $extra_headers = false, $isPost = true, $isJson = true)
    {
        $headerArr = array(
            "Authorization: Basic ".base64_encode(Configuration::EVOLUTION_INSTANCE_ID . ":" . Configuration::EVOLUTION_TOKEN)
        );

        if ($isJson) {
            $headerArr[] = "Content-Type: application/json";
        }

        if ($extra_headers !== false) {
            foreach ($extra_headers as $k => $v) {
                $headerArr[] = $k . ": " . $v;
            }
        }

        $startTime = date("Y-m-d H:i:s");
        if ($isPost) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 110);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $response = curl_exec($ch);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 110);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $response = curl_exec($ch);
        }

        $endtimeTime = date("Y-m-d H:i:s");

        Utilities::BettingAccessLog($url,$startTime,$endtimeTime,$_SERVER['REQUEST_URI'],json_encode($data),$response,json_encode($headerArr));

        if (curl_errno($ch) !== 0 || $response === false) {
            if ($isAjax) {
                Redirection::ajaxExit(Redirection::LOGIN_PAGE, Constants::AJAX_FLAG_RELOAD, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            } else {
                Redirection::to(Redirection::LOGIN_PAGE, Errors::TYPE_ERROR, Errors::SYTEM_ERROR);
            }
        }
        curl_close($ch);
        return json_decode($response);
    }

}
