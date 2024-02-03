<?php

defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
$newClass = '';
$sliderSize = $params->get('sliderSize');
if($sliderSize == 1){
    $newClass = 'smallSlider';
}elseif($sliderSize == 2){
    $newClass = 'mediumSlider';
}else{
    $newClass = 'bigSlider';
}
$sliderType = $params->get('sliderType');
$slidesToshow = $params->get('slidesToshow');
if($sliderType == 3){
    $slidesToshow = $params->get('slidesToshowcenter');
}
$slidesToscroll = $params->get('slidesToscroll');
$autoplaySpeed = $params->get('autoplaySpeed');
$imageList = $params->get('imageList');
require JModuleHelper::getLayoutPath('mod_customslider');