<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
require_once JPATH_BETTING_COMPONENT . '/helpers/RedisHandler.php';
//require_once JPATH_BETTING_COMPONENT . '/helpers/Configuration.php';

$newClass = '';
$sliderSize = $params->get('sliderSize');
if ($sliderSize == 1) {
    $newClass = 'smallSlider';
} elseif ($sliderSize == 2) {
    $newClass = 'mediumSlider';
} else {
    $newClass = 'bigSlider';
}
$sliderType = $params->get('sliderType');
$slidesToshow = $params->get('slidesToshow');
if ($sliderType == 3) {
    $slidesToshow = $params->get('slidesToshowcenter');
}
$infinite = $params->get('sliderLoop');
$autoplay = $params->get('autoplayEnable');
if($autoplay == 1){
    $autoplaySpeed = $params->get('autoplaySpeed');
}else{
    $autoplaySpeed = 0;
}
$slidesToscroll = $params->get('slidesToscroll');
$imageList = $params->get('imageList');
//exit(json_encode($imageList));
$direction = $params->get('sliderDirection');
$displayArrows = $params->get('enableArrows');

$gameInfo = Utilities::getGameInfo();
//if(($module->id == 93 && Configuration::getDeviceType() == 'MOBILE_WEB') || ($module->id == 205 && (Configuration::getDeviceType() == 'PC' || Configuration::getDeviceType() == 'TAB'))){
//    
//}else{
    require JModuleHelper::getLayoutPath('mod_customslider');
//}
