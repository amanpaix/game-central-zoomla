<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once 'Detection.php';

class Configuration {

    const DOMAIN_NAME = "trinity";
    const TEST_DOMAIN_NAME = "trinity";
    const BINGO_SITEID = "BVA";

    const BINGO_DOMAIN = "https://bingo.lukkywin.com";
    const BINGO_GAME_DOMAIN = self::BINGO_DOMAIN . "/bingo/gamelaunch/";
    const BINGO_PRE_BUY = self::BINGO_DOMAIN . "/preorder/";
    const CONTENT_SERVER_DOMAIN = "http://65.2.32.249";
    const AVATAR_DOMAIN = "https://api.lukkywin.com/";

    const DOMAIN = "http://65.2.32.249";
    const GAMES_DOMAIN = "http://65.2.32.249";
    const SPORTS_BETTING_IFRAME = "http://65.2.32.249";
    const WEB_SOCKET_DOMAIN = "wss://65.2.32.249/websocket";


    const EVOLUTION_HOST = "https://mayfairke.uat1.evo-test.com";
    const EVOLUTION_INSTANCE_ID = "mayfairke0000001";
    const EVOLUTION_TOKEN = "test123";

    const IGE_PATH = array(
        "IGE" => "http://ige.sabanzuri.com/InstantGameEngine/",
        "IGE-GAMELIST" => "gamePlay/api/getGameList.action?domainName=". self::DOMAIN_NAME ."&merchantKey=4&secureKey=12345678&isDeviceCheck=false&deviceId=c88296cd1d48eb26&lang=english&deviceType=PC&clientType=html5&currencyCode=".Constants::DEFAULT_CURRENCY_CODE
    );

    const MERCHANT_CODE = "trinity";
    const MERCHANT_PWD = "ph2Nj5knd4IjWBVLc4mhmYHo1hQDEdS3FlIC2KskHpeHFhsqxD";
    const BOLD_CHAT_ACCOUNT_ID = "738474531418939854";
    const BOLD_CHAT_INVITATION_ID = "731482418849032442";
    const BOLD_CHAT_WEBSITE_DEF_ID = "1791682981619001421";
    const BOLD_CHAT_FLOAT_CHAT_ID = "731482418999369717";
    const BOLD_CHAT_CB_ID = "731482418505888867";
    const REDIS_SERVER = "127.0.0.1";
    const REDIS_PORT = 6379;
    const REDIS_GLOBAL_KEY = "portal_";
    const REDIS_KEY_LIFETIME = 3600;
    const DEVICE_PC = "PC";
    const DEVICE_MOBILE = "MOBILE_WEB";
    const DEVICE_TABLET = "TAB";
    const DEVICE_DOWNLOADABLE_CLIENT = "DOWNLOADABLE_CLIENT";
    const LOGIN_DEVICE_PC_BROWSER = "PC_BROWSER";
    const LOGIN_DEVICE_PC_DOWNLOADABLE_CLIENT = "PC_DOWNLOADABLE_CLIENT";
    const LOGIN_DEVICE_ANDROID_BROWSER = "ANDROID_BROWSER";
    const LOGIN_DEVICE_ANDROID_APP = "ANDROID_APP";
    const LOGIN_DEVICE_IOS_BROWSER = "IOS_BROWSER";
    const LOGIN_DEVICE_IOS_APP = "IOS_APP";
    const LOGIN_DEVICE_WINDOWS_BROWSER = "WINDOWS_BROWSER";
    const LOGIN_DEVICE_WINDOWS_APP = "WINDOWS_APP";
    const OS_WINDOWS = "Windows";
    const OS_MAC = "Mac";
    const OS_LINUX = "Linux";
    const OS_IOS = "iOS";
    const OS_ANDROID = "Android";
    const OS_BLACKBERRY = "Blackberry";
    const CLIENT_FULL_WEB = "Full-Web";
    const CLIENT_DOWNLOADABLE = "Downloadable";
    const CLIENT_MOBILE_WEB = "Mobile-Web";
    const CLIENT_IOS_APP = "IOS-APP";
    const CLIENT_ANDROID_FULL = "Android-Full";
    const CLIENT_ANDROID_LITE = "Android-Lite";
    const CLIENT_WINDOWS_APP = "Windows App";
    const CLIENT_BLACKBERRY_APP = "BlackBerry App";
    const GOOGLE_CALLBACK = self::DOMAIN . "/gmail-callback";
    const YAHOO_CALLBACK = self::DOMAIN . "/yho-callback";
    const OUTLOOK_CALLBACK = self::DOMAIN . "/outlook-callback";
    const FACEBOOK_CALLBACK = self::DOMAIN . "/facebook-callback";
    const MINIMUM_BALANCE = 50;

    public static function setCurrencyDetails($currencyCode) {
        $data = Constants::$currencyMap[$currencyCode];
        if (!$data) {
            $data = Constants::$currencyMap[Constants::DEFAULT_CURRENCY_CODE];
        }
        Session::setSessionVariable("CurrencyDetails", $data);
    }

    public static function getCurrencyDetails() {
        return Session::getSessionVariable("CurrencyDetails");
    }

    public static function getClientIP() {
        /* $ipaddress = '';
          if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
          else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
          else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
          else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
          else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
          else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
          else
          $ipaddress = 'UNKNOWN';
          return $ipaddress; */
        $ipaddress = '';
        if (getenv('HTTP_X_REAL_IP'))
            $ipaddress = getenv('HTTP_X_REAL_IP');
        else if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getDevice() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function getDeviceType() {
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? self::DEVICE_MOBILE : self::DEVICE_PC);
        return $deviceType;
    }

    public static function getOS() {
        return Mobile_Detect::getOS($_SERVER['HTTP_USER_AGENT']);
    }

    public static function getOSVer() {
        return Mobile_Detect::getOSVer($_SERVER['HTTP_USER_AGENT']);
    }

    public static function getClientType($deviceType, $os) {
        $client_type = '';
        switch ($deviceType) {
            case self::DEVICE_PC:
                switch ($os) {
                    case self::OS_WINDOWS;
                        $client_type = self::CLIENT_FULL_WEB;
                        break;
                    case self::OS_MAC;
                        $client_type = self::CLIENT_DOWNLOADABLE;
                        break;
                    case self::OS_LINUX;
                        $client_type = self::CLIENT_MOBILE_WEB;
                        break;
                }
                break;
            case self::DEVICE_MOBILE:
                switch ($os) {
                    case self::OS_IOS;
                        $client_type = self::CLIENT_IOS_APP;
                        break;
                    case self::OS_ANDROID;
                        $client_type = self::CLIENT_ANDROID_FULL;
                        break;
                    case self::OS_LINUX;
                        $client_type = self::CLIENT_BLACKBERRY_APP;
                        break;
                    case self::OS_WINDOWS;
                        $client_type = self::CLIENT_ANDROID_LITE;
                        break;
                    case self::OS_BLACKBERRY;
                        $client_type = self::CLIENT_WINDOWS_APP;
                        break;
                }
                break;
            case self::DEVICE_TABLET:
                switch ($os) {
                    case self::OS_IOS;
                        $client_type = self::CLIENT_IOS_APP;
                        break;
                    case self::OS_ANDROID;
                        $client_type = self::CLIENT_ANDROID_FULL;
                        break;
                    case self::OS_LINUX;
                        $client_type = self::CLIENT_BLACKBERRY_APP;
                        break;
                    case self::OS_WINDOWS;
                        $client_type = self::CLIENT_ANDROID_LITE;
                        break;
                    case self::OS_BLACKBERRY;
                        $client_type = self::CLIENT_WINDOWS_APP;
                        break;
                }
                break;
        }
        return $client_type;
    }

    public static function getLoginDevice() {
        $os = self::getOS();
        $deviceType = self::getDeviceType();
        if ($deviceType == self::DEVICE_PC)
            return self::LOGIN_DEVICE_PC_BROWSER;
        else if ($deviceType == self::DEVICE_TABLET || $deviceType == self::DEVICE_MOBILE) {
            if ($os == self::OS_ANDROID)
                return self::LOGIN_DEVICE_ANDROID_BROWSER;
            else if ($os == self::OS_IOS)
                return self::LOGIN_DEVICE_IOS_BROWSER;
            else if ($os == self::OS_WINDOWS)
                return self::LOGIN_DEVICE_WINDOWS_BROWSER;
            else
                return self::LOGIN_DEVICE_PC_BROWSER;
        } else
            return self::LOGIN_DEVICE_PC_DOWNLOADABLE_CLIENT;
    }
    /** Lottery*/
    public static function getAppAndClientType($deviceType) {
        if ($deviceType == self::DEVICE_PC) {
            return array("APPTYPE" => "WEB", "CLIENTTYPE" => "FLASH");
        } else {
            $os = self::getOS();
            if (strtolower($os) == strtolower(self::OS_IOS)) {
                return array("APPTYPE" => "IOS_WEB", "CLIENTTYPE" => "IMAGE_GENERATION");
            } elseif (strtolower($os) == strtolower(self::OS_ANDROID)) {
                return array("APPTYPE" => "ANDROID_WEB", "CLIENTTYPE" => "IMAGE_GENERATION");
            } else {
                return array("APPTYPE" => "OTHER_WEB", "CLIENTTYPE" => "IMAGE_GENERATION");
            }
        }
    }

}
