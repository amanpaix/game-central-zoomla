<?php
defined('_JEXEC') or die('Restricted Access');

$announcer = "anne";
$caller = "anne";
$skin = "-";
if( isset($this->announcer) && $this->announcer != "" ){
    $announcer = $this->announcer;
}
if( isset($this->caller) && $this->caller != "" ){
    $caller = $this->caller;
}
if( isset($this->skin) && $this->skin != "" ){
    $skin = $this->skin;
}



$url = Configuration::BINGO_GAME_DOMAIN . Configuration::BINGO_SITEID . "/". $this->roomId ."/".
    Utilities::getPlayerId() ."/". Utilities::getPlayerToken() ."/". Utilities::getPlayerToken() ."/".
    $announcer  ."/". $caller ."/" . $skin;
//$url = "http://65.0.180.139/bingo/gamelaunch/BVA/5/BVA123/2gmcHhHSRrpe23tpjNAMuf3G-Xe5HOagfuw5GNBjLHE/sdgfdsf/anne/anne/blue";
?>


<div class="bingoframe">
    <iframe  allowfullscreen  src="<?php echo $url ?>"  width="100%" height="900px" ></iframe>
</div>

