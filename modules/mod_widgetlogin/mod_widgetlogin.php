<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once( dirname(__FILE__).'/helper.php' );
require_once JPATH_BETTING_COMPONENT.'/helpers/Constants.php';
//echo "<pre>";
//print_r($params);
//die;
$formLegend = $params->get('formlegend');
$menu_id = $params->get('submiturl');
$submiturl = '';
if(empty($menu_id) || is_null($menu_id)) {
    $submiturl = "/";
}else {
    $submiturl = trim(JFactory::getApplication()->getMenu()->getItem($menu_id)->route);
    if(is_null($submiturl) || strlen($submiturl) ==0 ) {
        die('Submit url not found');
    }
    $submiturl = "/".urlencode($submiturl);
}

$enableRegister = $params->get('enableRegister');
if($enableRegister == 1){
    $registerType = $params->get('register_type');
    if ( $registerType  )
    {
        $registerModal = $params->get('registermodal');

    }
    else
    {
        $menu_id = $params->get('registerurl');
        $registerurl = '';
        if(empty($menu_id) || is_null($menu_id)) {
            $registerurl = "/";
        }
        else {
            $registerurl = trim(JFactory::getApplication()->getMenu()->getItem($menu_id)->route);
            if(is_null($registerurl) || strlen($registerurl) ==0 ) {
                die('Register url not found');
            }
        }
    }

}

$forgot_password = $params->get('forgot_password');
if($forgot_password == 1){
    $forgotType = $params->get('forgotpassword_type');
    if ( $forgotType  )
    {
        $forgotModal = $params->get('forgotpasswordmodal');

    }
    else
    {
        $menu_id = $params->get('forgoturl');
        $forgoturl = '';
        if(empty($menu_id) || is_null($menu_id)) {
            $forgoturl = "/";
        }
        else {
            $forgoturl = trim(JFactory::getApplication()->getMenu()->getItem($menu_id)->route);
            if(is_null($forgoturl) || strlen($forgoturl) ==0 ) {
                die('Forgot password url not found');
            }
        }
    }

}

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
JHtml::script(JUri::base()."templates/shaper_helixultimate/js/core/login.js?v=".Constants::JS_VER);
JHtml::script(JUri::base()."templates/shaper_helixultimate/js/core/forgotpassword.js?v=".Constants::JS_VER);
$js = $params->get('js');
//exit(json_encode($js));
if(count($js) > 0) {
    foreach ($js as $a) {
        if($a == "")
            continue;
        JHtml::script(JUri::base()."templates/landing_page/js/".$a."?v=".Constants::JS_VER);
    }
}
  JText::script('LOGIN_ERROR');
  JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');
  JText::script('BETTING_PLEASE_ENTER_EIGHT_TO_SIXTEEN_CHARACTERS');
  JText::script('BETTING_PLEASE_ENTER_USERNAME');
  JText::script('BETTING_USERNAME_HAS_VALID_PATTERN');
  JText::script('BETTING_PASSWORD_SHOULD_BE_IN_RANGE');
  JText::script('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED');
  JText::script('BETTING_EITHER_USERNAME_OR_PASSWORD_IS_INVALID');
  JText::script('BETTING_NO_INTERNET_CONNECTION_MSG');
  JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED');
  JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');
  JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_MINLENGTH');
  JText::script('BETTING_USERNAME_MOBILE_SHOULD_BE_IN_RANGE');
  JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH');
  JText::script('BETTING_JS_CHANGE_PASSWORD_RETYPE_REQUIRED');
  JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');
  JText::script('BETTING_PLEASE_ENTER_MOBILE_NUMBER');
  JText::script('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC');
  JText::script('BETTING_PLEASE_ENTER_VALID');
  JText::script('BETTING_DIGIT_MOBILE_NO');
  JText::script('BETTING_MOBILE_NO_MSG');
  JText::script('BETTING_DIGITS');
  JText::script('BETTING_MOBILE_NO_MSG');
  JText::script('BETTING_JS_CHANGE_PASSWORD_RETYPE_EQUAL');
  JText::script('BETTING_SOME_INTERNAL_ERROR');
  JText::script('SOME_ERROR_DURING_VALIDATION_CHECK');
  JText::script('MOBILE_NUMBER_IS_NOT_PROVIDED');
  JText::script('OLD_PASSWORD_INCORRECT');
  JText::script('BETTING_CURRENT_AND_NEW_PASSWORD_CANT_BE_SAME');
  JText::script('BETTING_NEW_PASSWORD_CANT_BE_FROM_LAST');
  JText::script('BETTING_PLAYER_STATUS_IS_INACTIVE');
  JText::script('BETTING_DEVICE_TYPE_NOT_SUPPLIED');
  JText::script('BETTING_USER_AGENT_TYPE_NOT_SUPPLIED');
  JText::script('BETTING_SOME_INTERNAL_ERROR');
  JText::script('BETTING_APPTYPE_OR_LOGINDEVICE_MISSING');
  JText::script('BETTING_INVALID_ALIAS_NAME');
  JText::script('BETTING_APP_DOES_NOT_SUPPORT_YOUR_LOCATION');
  JText::script('BETTING_OPERATION_NOT_SUPPORTED');
  JText::script('BETTING_INVALID_DOMAIN');
  JText::script('BETTING_HIBERNATE_EXCEPTION');
  JText::script('BETTING_YPUR_VERIFICATION_IS_PENDING');
  JText::script('PLAYER_ALREADY_EXIST');
  JText::script('BETTING_INVALID_REQUEST');
  JText::script('INVALID_EMAIL_FORMAT');
  JText::script('EMAIL_ADDRESS_SHOULD_BE_BETWEEN_THREE_MSG');
  JText::script('FORM_JS_EMAIL_ADDRESS_IS_INVALID');
  JText::script('EMAIL_ID_ALREADY_EXIST');
  JText::script('BETTING_MOBILE_NO_ALREADY_EXIST');
  JText::script('BETTING_PLEASE_PROVIDE_VALID_EMAIL_ID');
  JText::script('BETTING_PLEASE_TRY_AGAIN_LATER');
  JText::script('PLEASE_ENTER_A_VALID_MOBILE_NO');
require( JModuleHelper::getLayoutPath( 'mod_widgetlogin' ) );
?>
