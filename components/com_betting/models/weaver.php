<?php
defined('_JEXEC') or die('Restricted access');
require_once JPATH_BETTING_COMPONENT . '/helpers/Constants.php';

class BettingModelBetting extends JModelList {

    protected $message;

    public function getBettingMessage($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('message')))
                ->from($db->quoteName('#__BETTING_messages'))
                ->where($db->quoteName('published') . " = 1")
                ->where($db->quoteName('id') . " = " . $db->quote($id));
        $db->setQuery($query);
        $result = $db->loadResult();
        if (!$result) {
            return false;
        }
        if (preg_match_all('/{(.*?)}/', $result, $output)) {
            $result = $this->replaceMatches($result, $output[0]);
        }
        return $result;
    }

    public function replaceMatches($result, $output) {
        $session = JFactory::getSession();
        $playerDetail = $session->get('playerLoginResponse');
        $keywords = array(
            Constants::PLAYER_NAME_KEY => ucwords($playerDetail->firstName . ' ' . $playerDetail->lastName),
            Constants::PLAYER_ID_KEY => $playerDetail->playerId,
            Constants::PLAYER_USERNAME_KEY => $playerDetail->userName,
            Constants::PLAYER_MOBILE_KEY => $playerDetail->mobileNo,
            Constants::PLAYER_EMAIL_KEY => $playerDetail->emailId,
            Constants::PLAYER_WITHDRAWBAL_KEY => $playerDetail->walletBean->withdrawableBal,
            Constants::PLAYER_BONUSBAL_KEY => $playerDetail->walletBean->bonusBalance,
            Constants::PLAYER_TOATALBAL_KEY => $playerDetail->walletBean->totalBalance,
            Constants::PLAYER_CASHBAL_KEY => $playerDetail->walletBean->cashBalance
        );
        foreach ($output as $key => $value) {
            if (array_key_exists($value, $keywords)) {
                $result = str_replace($value, $keywords[$value], $result);
            }
        }
        return $result;
    }

    protected function getListQuery() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__BETTING_config'));
        $query->where('type = ' . $db->quote('enableGames'));
        return $query;
    }

}
