<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

require_once( dirname(__FILE__).'/helper.php' );
JText::script('BETTING_PROFILE_COMPLETE');
JText::script('BETTING_PROFILE_COMPLETE_SUCCESS');
JText::script('BETTING_PROFILE_COMPLETE_WARNING');
JText::script('BETTING_MAKE_DEPOSIT');
JText::script('BETTING_UNLOCK_CLUB');
JText::script('BETTING_VIEW_DETAILS');
JText::script('BETTING_EXTRA_BONUS');
JText::script('BETTING_MORE_BENEFITS');
JText::script('BETTING_MAKE_DEPOSIT');
JText::script('BETTING_UNLOCK_CLUB');
JText::script('BETTING_UNLOCK_CLUB_2');
JText::script('BETTING_EARN_REMAIN');
JText::script('BETTING_ENOUGH_LOYALTY');
JText::script('BETTING_EARN_UPGRADE');
JText::script('BETTING_EARN_MAINTANANCE');
JText::script('BETTING_UNLOCK_CLUB_3');
JText::script('BETTING_BAR_CLUB');
JText::script('BETTING_PLAT_CLUB');
JText::script('BETTING_PLAT_CLUB_2');

require( JModuleHelper::getLayoutPath( 'mod_playerpostloginheader' ) );

JHtml::script(JUri::base()."templates/shaper_helixultimate/js/jquery.validate.min.js?v=".Constants::JS_VER);
JHtml::script(JUri::base()."templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js?v=".Constants::JS_VER);
?>
