<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

require_once( dirname(__FILE__).'/helper.php' );
require_once JPATH_BETTING_COMPONENT.'/helpers/Constants.php';
$css = $params->get('css');
if(count($css) > 0) {
    foreach ($css as $a) {
        if($a == "")
            continue;
        JHtml::stylesheet(JUri::base()."templates/landing_page/css/".$a."?v=".Constants::CSS_VER);
    }
}

JHtml::_('jquery.framework');
//JHtml::_('bootstrap.framework');

JHtml::script(JUri::base()."templates/shaper_helixultimate/js/jquery.validate.min.js?v=".Constants::JS_VER);
JHtml::script(JUri::base()."templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js?v=".Constants::JS_VER);
JHtml::script(JUri::base()."templates/shaper_helixultimate/js/MD5.min.js?v=".Constants::JS_VER);
//JHtml::script(JUri::base()."templates/shaper_helixultimate/js/core/common.js?v=".Constants::JS_VER);
//JHtml::stylesheet(JUri::base()."templates/shaper_helix3/css/common.css?v=".Constants::CSS_VER);

JHtml::script(JUri::base()."templates/shaper_helixultimate/js/core/forgotpassword.js?v=".Constants::JS_VER);

$js = $params->get('js');
if(count($js) > 0) {
    foreach ($js as $a) {
        if($a == "")
            continue;
        JHtml::script(JUri::base()."templates/landing_page/js/".$a."?v=".Constants::JS_VER);
    }
}
JText::script('BETTING_PLEASE_ENTER_YOUR_USERNAME_MOBILE');
JText::script('BETTING_USERNAME_MOBILE_SHOULD_BE_IN_RANGE');
JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED');
JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');
JText::script('BETTING_PASSWORD_SHOULD_BE_IN_RANGE');
JText::script('BETTING_PLAESE_ENTER_OTP');
JText::script('BETTING_OTP_SHOULD_BE_IN_RANGE');
JText::script('BETTING_PLEASE_ENTER_YOUR_CONFIRM_PASSWORD');
JText::script('BETTING_INVALID_CONFIRM_PASSWORD_FORMAT');
JText::script('BETTING_CONFIRM_PASSWORD_SHOULD_BE_IN_RANGE');
JText::script('BETTING_CONFIRM_PASSWORD_NOT_EQUAL');
JText::script('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED');
JText::script('BETTING_PASSWORD_RESET_SUCCESSFULLY');
JText::script('BETTING_OTP_CODE_HAS_BEEN_EXPIRED');
JText::script('BETTING_OTP_CODE_IS_NOT_VALID');
JText::script('BETTING_INVALID_PASSWORD');
JText::script('BETTING_INVALID_REQUEST');
JText::script('BETTING_INVALID_ALIAS_NAME');
JText::script('BETTING_PLEASE_PROVIDE_VALID');
JText::script('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER');
JText::script('BETTING_IS_NOT_PROVIDED');
JText::script('SOME_ERROR_DURING_VALIDATION_CHECK');
JText::script('BETTING_OPERATION_NOT_SUPPORTED');
JText::script('BETTING_INVALID_DOMAIN');
JText::script('BETTING_HIBERNATE_EXCEPTION');
JText::script('BETTING_SOME_INTERNAL_ERROR');
JText::script('BETTING_PLEASE_PROVIDE_VALID_MOBILE');
JText::script('BETTING_PLAYER_STATUS_IS_INACTIVE');
JText::script('BETTING_PLAYER_INFO_NOT_FOUND');
JText::script('BETTING_RSA_ID_NOT_FOUND');
JText::script('BETTING_PLEASE_PROVIDE_VALID_USERNAME_MOBILE');
JText::script('BETTING_INVALID_VERIFICATION_CODE');
JText::script('BETTING_PLAYER_ALREADY_EXIST');
require( JModuleHelper::getLayoutPath( 'mod_widgetforgotpass' ) );
?>
