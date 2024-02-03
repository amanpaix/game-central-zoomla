<?php

defined('_JEXEC') or die('Restricted access');

class BettingModelApi extends JModelList
{

    public function getUpcomingFixtures($gamecode)
    {
        $table = (strtolower($gamecode) == '1x2') ? '#__sportstake_config' : '#__sportstake8_config';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName($table))
            ->where($db->quoteName('status') . " = " . $db->quote('upcoming'))
            ->where($db->quoteName('published') . " = 1")
            ->order($db->quoteName('fixture') . ' ASC')
            ->order($db->quoteName('position') . ' ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $response = array();
        if ($result && !empty($result)) {
            foreach ($result as $key => $value) {
                if (!array_key_exists($value->fixture, $response)) {
                    $response[$value->fixture] = array();
                    $response[$value->fixture]['fixture'] = $value->fixture;
                    $response[$value->fixture]['opening_date'] = $value->opening_date;
                    $response[$value->fixture]['closing_date'] = $value->closing_date;
                    $response[$value->fixture]['draw_date'] = $value->draw_date;
                    $response[$value->fixture]['list_type'] = $value->list_type;
                    $response[$value->fixture]['total_sales'] = $value->total_sales;
                    $response[$value->fixture]['total_prize_pool'] = $value->total_prize_pool;
                    $response[$value->fixture]['next_estimated_jackpot'] = $value->next_estimated_jackpot;
                    $response[$value->fixture]['div1'] = $value->div1;
                    $response[$value->fixture]['div1Payout'] = $value->div1Payout;
                    $response[$value->fixture]['div2'] = $value->div2;
                    $response[$value->fixture]['div2Payout'] = $value->div2Payout;
                    $response[$value->fixture]['div3'] = $value->div3;
                    $response[$value->fixture]['div3Payout'] = $value->div3Payout;
                    $response[$value->fixture]['matches'] = array();
                }
                $tempResponse = array(
                    'position' => $value->position,
                    'home_team' => $value->home_team,
                    'home_score' => $value->home_score,
                    'away_team' => $value->away_team,
                    'away_score' => $value->away_score,
                    'date' => $value->date,
                    'time' => $value->time,
                    'status' => $value->status,
                );
                if ($gamecode == 'ss08') {
                    $tempResponse['half'] = $value->half;
                }
                $response[$value->fixture]['matches'][$value->position] = $tempResponse;
            }
        }

        return $response;
    }

    public function getGameList($playerId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")
            ->from($db->quoteName('#__game_master'))
            ->where($db->quoteName('published') . " = " . $db->quote(1))
            ->order('ordering');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        
        $query = $db->getQuery(true);
        $query->select("*")
            ->from($db->quoteName('#__game_visible'))
            ->where($db->quoteName('published') . " = " . $db->quote(1))
            ->where($db->quoteName('id') . " = " . $db->quote(1));
        $db->setQuery($query);
        $result1 = $db->loadObjectList();
        if(count($result1) > 0){
            $result1[0]->user_ids = explode(', ',$result1[0]->user_ids);
            $result1[0]->game_ids = explode(', ',$result1[0]->game_ids);
        }
        
        $tempResult = [];
        foreach($result as $key => $value){
//            if(count($result1) > 0 && in_array($result[$key]->gameNumber, $result1[0]->game_ids) && !in_array($playerId, $result1[0]->user_ids)){
            if(count($result1) > 0 && in_array($result[$key]->gameNumber, $result1[0]->game_ids)){
                continue;
            }
            $result[$key]->gameNumber = (int)$result[$key]->gameNumber;
            $result[$key]->extraParams = json_decode( $result[$key]->extraParams);
            $result[$key]->prizeSchemeIge = json_decode($result[$key]->prizeSchemeIge);
            array_push($tempResult, $result[$key]);
        }
        return $tempResult;
    }

    public function getRetailerList($data)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $typeQuery = "";
        if ($data['type'] == 0) {
//            $typeQuery = $db->quoteName('type') . " = '0' OR " . $db->quoteName('type') . " = '1'";
            $typeQuery = $db->quoteName('type') . " = " . $db->quote(0);
        } else {
            //echo $db->quoteName('type').' = '.$db->quote($ty);
            $typeQuery = $db->quoteName('type') . ' = ' . $db->quote($data['type']);
        }
        if ($data['lat'] != '' && $data['lng'] != '') {
            $query->select('*,( 3959 * acos( cos( radians(' . $data['lat'] . ') ) * cos( radians(latitude) ) * cos( radians(longitude)- radians(' . $data['lng'] . ' ) ) + sin( radians(' . $data['lat'] . ') ) * sin( radians( latitude ) ) ) ) AS distance')
                ->from($db->quoteName('#__retailer_list'))
                ->having('distance < 25');
        } else {
            $query->select('*')
                ->from($db->quoteName('#__retailer_list'));
        }
        $query->where($typeQuery)
            ->order('TRIM(' . $db->quoteName('name') . ") ASC");
        $db->setQuery($query, $data['offset'], $data['limit']);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $response = array();
        if ($result && !empty($result)) {
            foreach ($result as $key => $value) {
                $data = array();
                $data['id'] = $value->id;
                $data['store_id'] = $value->store_id;
                $data['name'] = $value->name;
                $data['telephone'] = $value->telephone;
                $data['address'] = $value->address;
                $data['address2'] = $value->address2;
                $data['address3'] = $value->address3;
                $data['city'] = $value->city;
                $data['province'] = $value->province;
                $data['latitude'] = $value->latitude;
                $data['longitude'] = $value->longitude;
                $data['type'] = $value->type;
                array_push($response, $data);
            }
        }
        return $response;
    }

    public function getGamesInfo()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__games_info'))
//                ->where($db->quoteName('game_code') . ' NOT IN('.$db->quote('RAFFLE').', '.$db->quote('RAPIDO').')')
            // ->where($db->quoteName('game_code') . ' = ' . $db->quote('RAFFLE'))
            ->where($db->quoteName('active') . ' = ' . $db->quote(1))
            ->order($db->quoteName('id') . ' ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $response = array();
        if ($result && !empty($result)) {

            foreach ($result as $key => $value) {
                if (!array_key_exists($value->fixture, $response)) {
                    $response[$value->game_code] = array();
                    $response[$value->game_code]['game_code'] = $value->game_code;
                    $response[$value->game_code]['datetime'] = $value->datetime;
                    $response[$value->game_code]['estimated_jackpot'] = $value->estimated_jackpot;
                    $response[$value->game_code]['guaranteed_jackpot'] = $value->guaranteed_jackpot;
                    $response[$value->game_code]['jackpot_title'] = $value->jackpot_title;
                    $response[$value->game_code]['jackpot_amount'] = $value->jackpot_amount;
                    $response[$value->game_code]['draw_date'] = $value->draw_date;
                    //$response[$value->game_code]['extra'] = new stdClass();
                    $response[$value->game_code]['extra'] = json_decode($value->content);
                    if(strtoupper($value->game_code) != "RAFFLE"){
                       $response[$value->game_code]['next_draw_date'] = $value->next_draw_date;
                    }
                    $response[$value->game_code]['active'] = $value->active;
                }
            }
        }
        return $response;
    }

    public function getFooterBanner()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('params')
            ->from($db->quoteName('#__modules'))
            ->where($db->quoteName('module') . " = " . $db->quote('mod_customapp'))
            ->where($db->quoteName('published') . " = 1")
            ->order($db->quoteName('id') . ' ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();        
        $response = array(
            "HOME" => array()
        );
        if ($result && !empty($result)) {
            foreach ($result as $key => $value) {
                $temp = json_decode($value->params);     
                foreach ($temp as $key1 => $value1) { 
                    if(array_key_exists($key1, $response)){
                        $temp2 = json_decode($value1);
                        for($i = 0; $i < count($temp2->gameCode); $i++){
                            $response[$key1][] = array(
                                "gameCode" => $temp2->gameCode[$i],
                                "title" => $temp2->title[$i],
                                "imageItem" => Configuration::CONTENT_SERVER_DOMAIN . '/' . $temp2->imageItem[$i],
                            );
                        }
                    }
                }
            }
        }
        return $response;
    }

    public function getActivePopup()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__modules'))
            ->where($db->quoteName('module') . " = " . $db->quote('mod_popup'))
            ->where($db->quoteName('published') . " = 1");
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $response = array("images" => array(), "videos" => array(), "text" => array());
        if ($result && !empty($result)) {
            foreach ($result as $key => $value) {
                $temp = json_decode($value->params);
                $response["contentType"] = $temp->contentType;
                if ($temp->platform == 0) {
                    foreach ($temp->imageList as $key1 => $value1) {
                        if ($value1->type == 'text' && ($temp->contentType == 'text' || $temp->contentType == 'any')) {
                            array_push($response[$value1->type], array("title" => $value1->title, "description" => $value1->description));
                        } elseif ($value1->type == 'images' && ($temp->contentType == 'images' || $temp->contentType == 'any')) {
                            array_push($response[$value1->type], Configuration::CONTENT_SERVER_DOMAIN . '/' . $value1->imageItem);
                        }if ($value1->type == 'videos' && ($temp->contentType == 'videos' || $temp->contentType == 'any')) {
                            array_push($response[$value1->type], $value1->videolink);
                        }
                    }
                }
            }
        }
        return array_filter($response);
    }

    public function getCasinoGamesList($lobby_cat,$limit,$offset)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__casino_game_master'))
            ->where($db->quoteName('lobby_cat') ." like '%" . $lobby_cat . "%'")
            ->where($db->quoteName('status') . " = 'ACTIVE'")
            ->order($db->quoteName('id') . ' ASC');
        $query->setLimit((int)$limit, (int) $offset);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

}
