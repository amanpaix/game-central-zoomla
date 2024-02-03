<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerBetting extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function fetch() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select($db->quoteName(array('id', 'title', 'alias')))
                ->from($db->quoteName('#__menu'))
                ->where("(menutype<>'rummy-client-urls') and (access=2 or access=1) and type<>'alias' and published=1")
                ->order('id asc');
        $db->setQuery($query);
        $result = $db->loadAssocList();
        if (count($result) === 0) {
            exit(json_encode(array('errorCode' => 106, 'respMsg' => "No menu-items have been created yet.")));
        }
        $main_arr = array();
        $menu_items = array();
        foreach ($result as $row) {
            array_push($menu_items, array('id' => $row['id'], 'title' => $row['title'], 'alias' => $row['alias'], 'availModules' => array()));
        }
        $main_arr['menus'] = $menu_items;
        $is_playerwise = '"is_playerwise":"1"';
        $query = $db->getQuery(true);
        $query
                ->select($db->quoteName(array('id', 'title', 'params', 'position')))
                ->from($db->quoteName('#__modules'))
                ->where("(module='mod_custom' or module='mod_popup') and params like '%" . $is_playerwise . "%' and position<>'' and published=1")
                ->order('id asc');
        $db->setQuery($query);
        $result = $db->loadAssocList();
        if (count($result) === 0) {
            exit(json_encode(array('errorCode' => 103, 'respMsg' => 'Error occurred while fetching modules.')));
        }
        $modules = array();
        $module_ids = array();
        foreach ($result as $row) {
            array_push($modules, array('id' => $row['id'], 'title' => $row['title'], 'position' => $row['position']));
            array_push($module_ids, $row['id']);
        }
        $main_arr['modules'] = $modules;
        $module_ids_str = implode(",", $module_ids);
        $query = $db->getQuery(true);
        $query
                ->select("*")
                ->from($db->quoteName('#__modules_menu'))
                ->where("moduleid in (" . $module_ids_str . ") order by moduleid");
        $db->setQuery($query);
        $result = $db->loadAssocList();
        if (count($result) === 0) {
            exit(json_encode(array('errorCode' => 104, 'respMsg' => 'Error occurred while fetching modules-menu-items mapping.')));
        }
        $modules_enabled_on = array();
        foreach ($result as $row) {
            if (!isset($modules_enabled_on[$row['moduleid']]))
                $modules_enabled_on[$row['moduleid']] = array();
            array_push($modules_enabled_on[$row['moduleid']], $row['menuid']);
        }
        for ($i = 0; $i < count($main_arr['menus']); $i++) {
            foreach ($modules_enabled_on as $k => $v) {
                if (array_search($main_arr['menus'][$i]['id'], $v) !== false)
                    array_push($main_arr['menus'][$i]['availModules'], $k);
            }
            if (count($main_arr['menus'][$i]['availModules']) == 0)
                unset($main_arr['menus'][$i]['availModules']);
        }
        $main_arr['errorCode'] = 0;
        print_r(json_encode($main_arr));
        die;
    }

    function fetchLandingPages() {
        $landing_pages = JFactory::getApplication()->getMenu()->getItems('menutype', 'landingpages', false);
        if (count($landing_pages) == 0) {
            exit(json_encode(array('errorCode' => 201, 'respMsg' => "No landing pages available")));
        }
        $main_arr = array();
        $tmp_arr = array();
        foreach ($landing_pages as $page) {
            array_push($tmp_arr, array(
                'name' => $page->title,
                'publicUrl' => Configuration::DOMAIN . "/" . $page->route
            ));
        }
        $main_arr['pages'] = $tmp_arr;
        $main_arr['errorCode'] = 0;
        print_r(json_encode($main_arr));
        die;
    }

    function fetchEmailTemplates() {
        $request = JFactory::getApplication()->input;
        $emailType = $request->getString('emailType', 'NA');
        if ($emailType != "NA" && strtolower($emailType) != "internal" && strtolower($emailType) != "external" && strtolower($emailType) != "both") {
            exit(json_encode(array('errorCode' => 301, 'respMsg' => "Invalid email type.")));
        }
        if (strtolower($emailType) == "both")
            $emailType = "external";
        $emailType_mod = '';
        if ($emailType == "NA") {
            $emailType_mod = '"email_type":';
        } else {
            $emailType = strtolower($emailType);
            $emailType_mod = '"email_type":"' . $emailType . '"';
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select($db->quoteName(array('id', 'title', 'params', 'position')))
                ->from($db->quoteName('#__modules'))
                ->where("module='mod_Bettingemail' and params like '%" . $emailType_mod . "%' and published=1")
                ->order('id asc');
        $db->setQuery($query);
        $result = $db->loadAssocList();
        if (count($result) == 0) {
            exit(json_encode(array('errorCode' => 302, 'respMsg' => "No email templates available.")));
        }
        $tmpArr = array();
        $module_ids = array();
        if (strtolower($emailType) == 'internal') {
            foreach ($result as $row) {
                $json = json_decode($row['params']);
                array_push($tmpArr, array(
                    'id' => $row['id'],
                    'name' => $row['title'],
                    'from' => $json->from,
                    'subject' => $json->subject,
                    'url' => array(
                        "/fetch-email-data?id=" . $row['id']
                    )
                ));
                array_push($module_ids, $row['id']);
            }
        } else {
            foreach ($result as $row) {
                $json = json_decode($row['params']);
                array_push($tmpArr, array(
                    'id' => $row['id'],
                    'name' => $row['title'],
                    'from' => $json->from,
                    'subject' => $json->subject,
                    'url' => array()
                ));
                array_push($module_ids, $row['id']);
            }
        }
        $mainArr = array();
        $mainArr['emailTemplates'] = $tmpArr;
        if (strtolower($emailType) == 'external') {
            $module_ids_str = implode(",", $module_ids);
            $query = $db->getQuery(true);
            $query
                    ->select("*")
                    ->from($db->quoteName('#__modules_menu'))
                    ->where("moduleid in (" . $module_ids_str . ") order by moduleid");
            $db->setQuery($query);
            $result = $db->loadAssocList();
            if (count($result) === 0) {
                exit(json_encode(array('errorCode' => 303, 'respMsg' => 'The available email-templates are not enabled on any urls.')));
            }
            $modules_enabled_on = array();
            foreach ($result as $row) {
                if (!isset($modules_enabled_on[$row['moduleid']]))
                    $modules_enabled_on[$row['moduleid']] = array();
                array_push($modules_enabled_on[$row['moduleid']], $row['menuid']);
            }
            $email_menus = JFactory::getApplication()->getMenu()->getItems('menutype', 'Bettingemail', false);
            $urls = array();
            foreach ($email_menus as $menu) {
                $urls[$menu->id] = "/" . $menu->route;
            }
            for ($i = 0; $i < count($mainArr['emailTemplates']); $i++) {
                if (array_key_exists($mainArr['emailTemplates'][$i]['id'], $modules_enabled_on) === true) {
                    foreach ($modules_enabled_on[$mainArr['emailTemplates'][$i]['id']] as $menu_id) {
                        array_push($mainArr['emailTemplates'][$i]['url'], $urls[$menu_id]);
                    }
                }
            }
        }
        $mainArr['errorCode'] = 0;
        print_r(json_encode($mainArr));
        die;
    }
    //images
    function fetchUrlsClient() {
        jimport('joomla.filesystem.folder');
        $main_arr = array();
        $tmp_arr = array();
        $images_list = JFolder::files("images/rummy-client-images", $filter = '.', true, true);
        foreach ($images_list as $page) {
            array_push($tmp_arr, array(
                'link' => Configuration::DOMAIN . "/" . $page
            ));
        }
        if (count($tmp_arr) == 0) {
            exit(json_encode(array('errorCode' => 201, 'respMsg' => "No Client-Images available")));
        }
        $main_arr['images'] = $tmp_arr;
        $main_arr['errorCode'] = 0;
        print_r(json_encode($main_arr));
        die;
    }
    //all
    function fetchAll() {
        $all_urls = JFactory::getApplication()->getMenu()->getItems();
        if (count($all_urls) == 0) {
            exit(json_encode(array('errorCode' => 202, 'respMsg' => "No urls available")));
        }
        $main_arr = array();
        $tmp_arr = array();
        foreach ($all_urls as $page) {
            if ((!isset(json_decode($page->params)->aliasoptions)) && $page->link !== "#") {
                if (in_array($page->route, Constants::FETCHALL_SKIP_ROUTE))
                    continue;
                if ($page->menutype == "Bettingemail")
                    continue;
                if ($page->menutype == "hidden-menu" && !($page->route == "promotion" || $page->route == "app-download" || $page->route == "rummy"))
                    continue;
                if ($page->menutype == "mobilepages")
                    continue;
                if ($page->menutype == "cashier" && !($page->route == "cashier-initiate"))
                    continue;
                array_push($tmp_arr, array(
                    'menuname' => ucfirst($page->route),
                    'menutype' => $page->menutype,
                    'publicUrl' => Configuration::DOMAIN . "/" . $page->route
                ));
            }
        }
        $main_arr['pages'] = $tmp_arr;
        $main_arr['errorCode'] = 0;
        print_r(json_encode($main_arr));
        die;
    }

    function addManualEmail() {
        $request = JFactory::getApplication()->input;
        // $title = $request->getString('title', 'NA');
        $subject = $request->getString('subject', 'NA');
        // $from = $request->getString('from', 'NA');
        $from = Constants::MAIL_FROM;
        //$content= urldecode($request->getHtml('content', '', "RAW"));
        $content = preg_replace("/\t/", '', urldecode($_POST['content']));
        $content = preg_replace("/\r\n|\r|\n/", '<br/>', $content);
        $content = preg_replace("/\"/", '\'', $content);
        $style = $request->getString('style', 'NA');
        $title = $subject;
        $response = array();
        if ($title != "" && $subject != "" && $from != "" && $content != "") {
            $db = JFactory::getDbo();
            $data = new stdClass();
            $data->title = $title;
            $data->ordering = "1";
            $data->module = "mod_Bettingemail";
            $data->access = "1";
            $data->showtitle = "0";
            $data->published = 1;
            $data->params = '{"from":"' . $from . '","subject":"' . $subject . '","email_type":"manual","content":"' . $content . '","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"' . $style . '"}';
            $data->language = "*";
            $result = $db->insertObject('kpr_modules', $data);
            if ($result) {
                $module_arr = array();
                $module_arr['id'] = $db->insertid();
                $module_arr['name'] = $title;
                $module_arr['from'] = $from;
                $module_arr['subject'] = $subject;
                $module_arr['url'] = array("/fetch-email-data?id=" . $db->insertid());
                $response['moduleData'] = $module_arr;
                $response['errorCode'] = 0;
            } else {
                $response['errorCode'] = 311;
                $response['errorMsg'] = "Template Not Created.";
            }
        } else {
            if (empty($subject)) {

                $response['errorCode'] = 211;
                $response['errorMsg'] = "Subject Can't ne Empty";
            }
            if (empty($content)) {

                $response['errorCode'] = 212;
                $response['errorMsg'] = "Content Can't ne Empty";
            }
        }
        print_r(json_encode($response));
        die;
    }


    public function updateGameInfo()
    {

        $games = file_get_contents('php://input');
        $games = json_decode(($games), true);

        //file_put_contents("result.txt", json_encode($games));

        $file = 'result.txt';
        $current = file_get_contents($file);
        $current .= json_encode($games)."\n";
        file_put_contents($file, $current);

        $gameCode = $games['gameCode'];
        $games['gameName'] = strtoupper($gameCode);
        $gameData = $games['gameData'];
        unset($games['gameCode']);
        if (isset($gameCode) && $gameCode != "NA" && $gameData != "NA") {
            $feed = $gameData;
            if (empty($feed)) {
                exit(('{"errorCode":500,"message":"Invalid Game Data" }'));
            }
            $gameData = array(
                'currentDrawNumber',
                'currentDrawFreezeDate',
                'jackpotAmount'
            );
            $myGameData = array();
            $myGameData[$gameCode] = new stdClass();
            foreach ($feed as $key => $value) {
                //if (in_array($key, $gameData)) {
                    $myGameData[$gameCode]->$key = (string) $value;
                //}
            }
            $db = JFactory::getDbo();
            try {
                $db->transactionStart();
                $query = $db->getQuery(true);
                $query
                    ->select('*')
                    ->from($db->quoteName('#__games_info'))
                    ->where($db->quoteName('game_code') . " = " . $db->quote($gameCode));
                $db->setQuery($query);
                $result = $db->loadAssocList();
                $count = count($result);
                $estimatedJackpot = Utilities::formatCurrency(number_format((float)$myGameData[$gameCode]->jackpotAmount,2));
                $guaranteedJackpot = Utilities::formatCurrency(number_format((float)$myGameData[$gameCode]->jackpotAmount,2));
                Utilities::set_time_zone(Constants::DEFAULT_TIMEZONE);

                $gameInfo = new stdClass();
                $gameInfo->datetime = date("Y-m-d H:i:s");
                $gameInfo->estimated_jackpot = $estimatedJackpot;
                $gameInfo->draw_date = (string) $myGameData[$gameCode]->currentDrawStopTime;
                $gameInfo->next_draw_date = (string) $myGameData[$gameCode]->currentDrawStopTime;
                $gameInfo->guaranteed_jackpot = $guaranteedJackpot;
                //$gameInfo->jackpot_title = "";
                $gameInfo->jackpot_amount = $estimatedJackpot;
                $gameInfo->content = json_encode($feed);
                if ($guaranteedJackpot > 0) {
                    $gameInfo->jackpot_title = "";
                    $gameInfo->jackpot_amount = $guaranteedJackpot;
                }
                //$paramData = json_decode($result[0]['params']);
                $skipGames = array("RAPIDO", "PICK3", "RAFFLE", "DAILYLOTTO");
                if (in_array($gameCode, $skipGames)) {
                    unset($gameInfo->{'jackpot_title'});
                    unset($gameInfo->{'jackpot_amount'});
                }
                $skipGamesTitle = array("POWERBALL", "TWELVEBYTWENTYFOUR");
                if (in_array($gameCode, $skipGamesTitle)) {
                    unset($gameInfo->{'jackpot_title'});
                }
                if ($count == 0) {
                    $gameInfo->game_code = strtoupper($gameCode);
                    $db->insertObject('#__games_info', $gameInfo);
                } else if ($count == 1) {
                    $gameInfo->id = (int) $result[0]['id'];
                    $db->updateObject('#__games_info', $gameInfo, 'id');
                } else {
                    $db->setQuery(
                        'DELETE FROM `' . $db->quoteName('#__games_info') . '`' .
                        ' WHERE `game_code` = ' . $gameCode
                    );
                    $count = $db->loadResult();
                    $gameInfo->game_code = $gameCode;
                    $db->insertObject('#__games_info', $gameInfo);
                }
                $gameHistory = array();
                $gameHistory['gameName'] = $games['gameName'];
                $gameHistory['date'] = date("Y-m-d H:i:s");
                $gameHistory['status'] = "DMS";
                $gameHistory['drawNumber'] = $games['gameData']['currentDrawNumber'];
                $gameHistory['drawDate'] = $games['gameData']['currentDrawStopTime'];
                $gameHistory["gameData"] = json_encode($games);
                $gameHistory = (object) $gameHistory;
                $db->insertObject('#__xml_feed_history', $gameHistory);
                //$db->updateObject('#__xml_feed', $games, 'gameName');
                $db->transactionCommit();
                self::deleteRedisContent();
                exit(('{"errorCode":0,"message":"success" }'));
            } catch (Exception $e) {
                $db->transactionRollback();
                exit($e);
            }
            self::deleteRedisContent();

        } else {
            exit(('{"errorCode":500,"message":"Invalid Request" }'));
        }

    }

    public function deleteRedisContent($return = false)
    {

        $redis_obj = new RedisHandler();
        $redis_obj->remove(Configuration::REDIS_GLOBAL_KEY . "gameInfo");
        if ($return) {
            return true;
        }
        exit(('{"errorCode":0,"message":"success" }'));

    }

    public function getServerTime()
    {

        Utilities::set_time_zone(Constants::DEFAULT_TIMEZONE);
        $dtm_now = new DateTime();
        $data['dateTime'] = $dtm_now;
        exit(json_encode($data));

    }

}
