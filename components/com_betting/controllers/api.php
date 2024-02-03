<?php

defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerApi extends BettingController
{

    public function getModel($name = 'Api', $prefix = 'BettingModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function getPhpInput()
    {
        $request_body = file_get_contents('php://input');
        return json_decode($request_body);
    }

    public function validateRequest($request, $optional = array())
    {
        $this->request = $request;
        $this->startTime = date("Y-m-d H:i:s");
        $response = array(
            'errorCode' => 0,
            'message' => '',
        );
        if ($request['deviceType'] == '') {
            $response['errorCode'] = 101;
            $response['message'] = 'Device type is mandatory.';
        } elseif (strtolower($request['deviceType']) != 'mobile') {
            $response['errorCode'] = 102;
            $response['message'] = 'Device type is invalid.';
//        }elseif($request['os'] == ''){
            //            $response['errorCode'] = 103;
            //            $response['message'] = 'Os is mandatory.';
            //        }elseif(strtolower($request['os']) != 'android'){
            //            $response['errorCode'] = 104;
            //            $response['message'] = 'Os is invalid.';
        }
        if (!empty($optional) && $response['errorCode'] == 0) {
            if ($request['gameCode'] == '' && isset($optional['gameCode'])) {
                $response['errorCode'] = 105;
                $response['message'] = 'Game Code is mandatory.';
            } elseif (!in_array(strtolower($request['gameCode']), $optional['gameCode']) && isset($optional['gameCode'])) {
                $response['errorCode'] = 106;
                $response['message'] = 'Game Code is invalid.';
            }
            if (isset($optional['lat']) || isset($optional['lng'])) {
                if (!((isset($optional['lat']) && $request['lat'] != '') && (isset($optional['lng']) && $request['lng'] != ''))) {
                    $response['errorCode'] = 107;
                    $response['message'] = 'Enter latitude and longitude both.';
                }
            }
        }

        return $response;
    }

//    public function validateMobileRequest($request, $optional = array()) {
    //        $response = array(
    //            'errorCode' => 0,
    //            'message' => ''
    //        );
    //        if ($request['deviceType'] == '') {
    //            $response['errorCode'] = 101;
    //            $response['message'] = 'Device type is mandatory.';
    //        } elseif (strtolower($request['deviceType']) != 'mobile') {
    //            $response['errorCode'] = 102;
    //            $response['message'] = 'Device type is invalid.';
    //        }
    //
    //        if (!empty($optional) && $response['errorCode'] == 0) {
    //            if ($request['gameCode'] == '' && isset($optional['gameCode'])) {
    //                $response['errorCode'] = 105;
    //                $response['message'] = 'Game Code is mandatory.';
    //            } elseif (strtolower($request['gameCode']) != strtolower($optional['gameCode']) && isset($optional['gameCode'])) {
    //                $response['errorCode'] = 106;
    //                $response['message'] = 'Game Code is invalid.';
    //            }
    //        }
    //
    //        return $response;
    //    }

    public function validateResponse($response)
    {
        $logging = false;

        if ($logging) {
            Utilities::BettingAccessLog($_SERVER['REQUEST_URI'], $this->startTime, date("Y-m-d H:i:s"), $_SERVER['REQUEST_URI'], json_encode($this->request), json_encode($response));
        }

        if (!empty($response['data']) || !empty($response['games'])) {
            $response['message'] = 'Request successfully served.';
        } else {
            $response['errorCode'] = 201;
            $response['message'] = 'No data available.';
        }
        return $response;
    }

    public function jsonOutput($response)
    {
        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        header('Content-Type: application/json');
        if ($response['errorCode'] != 0) {
            header("HTTP/1.1 406");
        }
        exit(json_encode($response));
//        exit(json_encode(json_decode(json_encode($response), false)));
    }

    public function getgamelist()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
        $request['playerId'] = (int) $jinput->playerId;
        //    $jinput = JFactory::getApplication()->input;
        //    $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        //    $request['playerId'] = $jinput->get('playerId', '', 'STR');

        if(!isset($request['playerId'])){
            $request['playerId'] = 0;
        }
        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }

        $model = $this->getModel();
        $response['games'] = $model->getGameList($request['playerId']);
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getServerTime()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
//        $jinput = JFactory::getApplication()->input;
        //        $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $response['data'] = new DateTime();
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getUpcomingFixtures()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
        $request['gameCode'] = (string) $jinput->gameCode;
        //    $jinput = JFactory::getApplication()->input;
        //    $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        //    $request['gameCode'] = $jinput->get('gameCode', '', 'STR');
        $optional['gameCode'] = array('1x2', 'ss08');
        $response = $this->validateRequest($request, $optional);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }

        $model = $this->getModel();
        $response['data'] = $model->getUpcomingFixtures(strtoupper($request['gameCode']));
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getRetailerList()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
        $request['lat'] = (float) $jinput->lat;
        $request['lng'] = (float) $jinput->lng;
        $request['type'] = (int) (isset($jinput->type) ? $jinput->type : 0);
        $request['offset'] = (int) (isset($jinput->offset) ? $jinput->offset : 0);
        $request['limit'] = (int) (isset($jinput->limit) ? $jinput->limit : 10000);
//        $jinput = JFactory::getApplication()->input;
        //        $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        //        $request['lat'] = $jinput->get('lat', '', 'FLOAT');
        //        $request['lng'] = $jinput->get('lng', '', 'FLOAT');
        //        $request['type'] = $jinput->get('type', 0, 'INT');
        //        $request['offset'] = $jinput->get('offset', 0, 'INT');
        //        $request['limit'] = $jinput->get('limit', 10000, 'INT');
        if ($request['lat'] != '' || $request['lng'] != '') {
            $optional['lat'] = '';
            $optional['lng'] = '';
        }
        $response = $this->validateRequest($request, $optional);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $model = $this->getModel();
        $response['data'] = $model->getRetailerList($request);
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getGamesInfo()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
        //    $jinput = JFactory::getApplication()->input;
        //    $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $model = $this->getModel();
        $response['data']['games'] = $model->getGamesInfo();
        Utilities::set_time_zone(Constants::DEFAULT_TIMEZONE);
        $response['data']['currentTime'] = new DateTime();
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getBanners()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
//        $jinput = JFactory::getApplication()->input;
        //        $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $model = $this->getModel();
        $response['data'] = $model->getFooterBanner();
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getAllSplashScreen()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
//        $jinput = JFactory::getApplication()->input;
        //        $request['deviceType'] = $jinput->get('deviceType', '', 'STR');
        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $response['data'] = array('images' => array("1024" => array(), "2048" => array()));
        $allfiles = glob('images/splash-screen/1024/*');
        if (isset($allfiles) && count($allfiles)) {
            foreach ($allfiles as $files) {
                array_push($response['data']['images']["1024"], Configuration::CONTENT_SERVER_DOMAIN . '/' . $files);
            }
        }
        $allvideos = glob('images/splash-screen/2048/*');
        if (isset($allvideos) && count($allvideos)) {
            foreach ($allvideos as $video) {
                array_push($response['data']['images']['2048'], Configuration::CONTENT_SERVER_DOMAIN . '/' . $video);
            }
        }
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }

    public function getActivePopup()
    {
        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;
//         $jinput = JFactory::getApplication()->input;
        //         $request['deviceType'] = $jinput->get('deviceType', '', 'STR');

        $response = $this->validateRequest($request);
        if ($response['errorCode'] != 0) {
            $this->jsonOutput($response);
        }
        $model = $this->getModel();
        $response['data'] = $model->getActivePopup();
        $response = $this->validateResponse($response);
        $this->jsonOutput($response);
    }


    public function getCasinoGameList()
    {

        $jinput = $this->getPhpInput();
        $request['deviceType'] = (string) $jinput->deviceType;

        if( $request['deviceType'] === 'Mobile' ){
            $lobby_cat = (string) $jinput->lobby_cat;
            $limit = (string) $jinput->limit;
            $offset = (string) $jinput->offset;
        }
        else
        {
            $request = JFactory::getApplication()->input;
            $lobby_cat = $request->getString('lobby_cat', 'all');
            $limit = $request->getString('limit',Constants::GAME_LIST_LIMIT);
            $offset = $request->getString('offset','0');
        }


        $model = $this->getModel();

        if( $lobby_cat == "all" ){
            $lobby_cat = "";
        }
        $resp = array(
            "type" => $lobby_cat == "" ? "all" : $lobby_cat,
            "data" => $model->getCasinoGamesList($lobby_cat,$limit,$offset)
        );
        $this->jsonOutput($resp);
    }



    public function getCasinoGamesCategory()
    {
        $request = JFactory::getApplication()->input;


        $model = $this->getModel();

        $resp = array(
            "data" => Utilities::getCasinoGamesCategory()
        );
        $this->jsonOutput($resp);
    }
    public function setCasinoGameList()
    {

        $url = Configuration::EVOLUTION_HOST . "/api/lobby/v1/" . Configuration::EVOLUTION_INSTANCE_ID . "/state?gameVertical=live,rng,slots";
        $response = ServerCommunication::sendCallTo3Party($url,array(),false,false,false,true);

        if( isset($response->tables) ){
            if( count((array)$response->tables) > 0 ){

                $db = JFactory::getDbo();

                $query = $db->getQuery(true);
                $query->delete($db->quoteName('#__casino_game_master'));
                $db->setQuery($query);
                $db->query();

                $gameData = new stdClass();



                foreach ( $response->tables as $game ){

                    $gameData->tableId = $game->tableId;
                    $gameData->name = $game->name;
                    $gameData->gameType = $game->gameType;
                    $gameData->gameProvider = $game->gameProvider;
                    $gameData->gameVertical = $game->gameVertical;
                    $gameData->language = $game->language;
                    $gameData->status = 'ACTIVE';
                    $gameData->gameTypeUnified = $game->gameTypeUnified;
                    $gameData->videoSnapshot = json_encode($game->videoSnapshot);
                    $imgSrc = str_replace(" ", "_", strtolower($game->name)) . ".jpg";
                    $imgSrc = str_replace(":", "", strtolower($game->name));
                    $gameData->img_src = "images/".strtolower($game->gameProvider)."/".$imgSrc;

                    if( $game->gameVertical === 'slots' ){
                        $gameData->lobby_cat = "Slots";
                        $gameData->priority = 5;
                    }
                    else
                    {
                        switch ($game->gameType){
                            case 'gonzotreasurehunt':
                            case 'MoneyWheel':
                                $gameData->lobby_cat = "New";
                                $gameData->priority = 2;
                                break;

                            case 'DealNoDeal':
                            case 'SidebetCity':
                            case 'crazytime':
                            case 'Monopoly':
                            case 'cashorcrash':
                            case 'rng-dragontiger':
                            case 'rng-dealnodeal':
                            case 'DragonTiger':
                                $gameData->lobby_cat = "Live Dealer";
                                $gameData->priority = 9;
                                break;

                            case 'ScalableBlackjack':
                            case 'UTH':
                            case 'ETH':
                            case 'THB':
                            case 'CSP':
                            case 'Holdem':
                            case 'TRP':
                            case 'DHP':
                            case 'TCP':
                            case 'craps':
                                $gameData->lobby_cat = "Table Games";
                                $gameData->priority = 5;
                                break;

                            case 'AmericanRoulette':
                            case 'instantroulette':
                            case 'RngEuropeanRoulette':
                            case 'Roulette':
                                $gameData->lobby_cat = "Roulette";
                                $gameData->priority = 6;
                                break;

                            case 'rng-megaball':
                                $gameData->lobby_cat = "Exclusive";
                                $gameData->priority = 8;
                                break;

                            case 'andarbahar':
                                $gameData->lobby_cat = "Jackpots";
                                $gameData->priority = 2;
                                break;

                            case 'powerscalableblackjack':
                            case 'RngBlackjack':
                            case 'rng-lightningscalablebj':
                            case 'FreeBetBlackjack':
                            case 'Blackjack':
                                $gameData->lobby_cat = "Blackjack";
                                $gameData->priority = 2;
                                break;

                            case 'rng-baccarat':
                            case 'Baccarat':
                                $gameData->lobby_cat = "Baccarat";
                                $gameData->priority = 3;
                                break;

                            case 'SicBo':
                            case 'LightningDice':
                            case 'bacbo':
                                $gameData->lobby_cat = "Dice Game";
                                $gameData->priority = 5;
                                break;

                            default:
                                $gameData->lobby_cat = "Featured Games";
                                $gameData->priority = 1;

                        }
                    }



                    $db->insertObject('stpl_casino_game_master', $gameData);
                }
                exit("Games Successfully Added");
            }
            else
            {
                exit("No Games Found");
            }
        }
        else
        {
            exit("No Response Found");
        }

    }

    public function evloutionUserAuthentication()
    {

        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $tableId = $request->getString('tableId', '');
            $playMode = $request->getString('playMode', 'demo');

            $requestObj = new stdClass();
            $playerObj = new stdClass();
            $configObj = new stdClass();
            $sessionObj = new stdClass();
            $gameObj = new stdClass();
            $tableObj = new stdClass();
            $channelObj = new stdClass();

            $playerObj->id = Utilities::getPlayerId();
            $playerObj->update = true;
            $playerObj->language = "en-GB";
            $playerObj->country = Constants::COUNTRY_CODE;
            $playerObj->currency = Constants::MYCURRENCYCODE;

            $sessionObj->id = Utilities::getPlayerToken();
            $sessionObj->ip = $_SERVER['REMOTE_ADDR'];

            $playerObj->session = $sessionObj;

            $tableObj->id = $tableId;
            $gameObj->table = $tableObj;

            $configObj->game = $gameObj;
            $configObj->playMode = $playMode;

            $channelObj->wrapped = true;

            $configObj->channel = $channelObj;

            $requestObj->uuid = time();
            $requestObj->player = $playerObj;
            $requestObj->config = $configObj;

            $url = Configuration::EVOLUTION_HOST . "/ua/v1/" . Configuration::EVOLUTION_INSTANCE_ID . "/" . Configuration::EVOLUTION_TOKEN;

            $response = ServerCommunication::sendCallTo3Party($url,$requestObj,true,false,true,true);

            if( isset($response->entry) ){

            }
            else
            {

            }

        }
        else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

}
