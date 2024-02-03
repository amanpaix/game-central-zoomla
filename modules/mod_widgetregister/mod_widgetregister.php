<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once( dirname(__FILE__) . '/helper.php' );
require_once JPATH_BETTING_COMPONENT . '/helpers/Constants.php';

$menu_id = $params->get('submiturl');
$otp_enable = $params->get('OtpEnable', '0');
$capach_enable = $params->get('capchaEnable', '0');

$submiturl = '';
if (empty($menu_id) || is_null($menu_id)) {
    $submiturl = Redirection::AFTER_REGISTRATION;
} else {
    $submiturl = trim(JFactory::getApplication()->getMenu()->getItem($menu_id)->route);
    if (is_null($submiturl) || strlen($submiturl) == 0) {
        die('Submit url not found');
    }
    $submiturl = "/" . urlencode($submiturl);
}
$css = $params->get('css');
if (count($css) > 0) {
    foreach ($css as $a) {
        if ($a == "")
            continue;
        JHtml::stylesheet(JUri::base() . "templates/landing_page/css/" . $a . "?v=" . Constants::CSS_VER);
    }
}
JHtml::_('jquery.framework');
//JHtml::_('bootstrap.framework');
JHtml::script(JUri::base() . "templates/shaper_helixultimate/js/jquery.validate.min.js?v=" . Constants::JS_VER);
JHtml::script(JUri::base() . "templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js?v=" . Constants::JS_VER);
JHtml::script(JUri::base() . "templates/shaper_helixultimate/js/MD5.min.js?v=" . Constants::JS_VER);
//JHtml::script(JUri::base() . "templates/shaper_helix3/js/custom/common.js?v=" . Constants::JS_VER);
//JHtml::stylesheet(JUri::base()."templates/shaper_helix3/css/common.css?v=".Constants::CSS_VER);
JHtml::script(JUri::base() . "templates/shaper_helixultimate/js/core/registration.js?v=" . Constants::JS_VER);
if (trim($params->get('AutoEmail')) == "1") {
    JHtml::stylesheet(JUri::base() . "templates/shaper_helixultimate/css/mailtip.css?v=" . Constants::CSS_VER);
    JHtml::script(JUri::base() . "templates/shaper_helixultimate/js/jquery.mailtip.js?v=" . Constants::JS_VER);
}
$js = $params->get('js');
if (count($js) > 0) {
    foreach ($js as $a) {
        if ($a == "")
            continue;
        JHtml::script(JUri::base() . "templates/landing_page/js/" . $a . "?v=" . Constants::JS_VER);
    }
}
JText::script('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED');
JText::script('BETTING_ONLY_ALPHANUMERIC_CHRACTER_ACCEPTED');
JText::script('BETTING_USERNAME_LENGTH_SHOULD_BE_EIGHT_TO_SIXTEEN');
JText::script('LOGIN_ERROR');
JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');
JText::script('BETTING_PASWORD_LENGTH_SHOULD_BE_EIGHT_TO_SIXTEEN');
JText::script('BETTING_PLEASE_RE_ENTER_YOUR_PASSWORD');
JText::script('BETTING_INVALID_CONFIRM_PASSWORD_FORMAT');
JText::script('BETTING_CONFIRM_PASSWORD_MUST_BE_OF_EIGHT_TO_SIXTEEN');
JText::script('BETTING_CONFIRM_PASSWORD_NOT_EQUAL_TO_PASSWORD_FIELD');
JText::script('BETTING_PLEASE_ENTER_MOBILE_NUMBER');
JText::script('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC');
JText::script('BETTING_MOBILE_NO_MSG');
JText::script('BETTING_DIGITS');
JText::script('BETTING_PLEASE_VERIFY_YOU_ARE_HUMAN');
JText::script('BETTING_USERNAME_ALREADY_EXIST');
JText::script('BETTING_EMAILID_ALREADY_EXIST');
JText::script('BETTING_MOBILE_NO_ALREADY_EXIST');
JText::script('BETTING_PLASE_ENTER_YOUR_MOBILE_NO');
JText::script('BETTING_PLEASE_ENTER_VALID');
JText::script('BETTING_DIGIT_MOBILE_NO');
JText::script('PLEASE_ENTER_USERNAME');
JText::script('BETTING_PLEASE_PROVIDE_VALID_MOBILE');
JText::script('OTP_SENT_TO_YOUR_PHONE_SUCCESSFULLY');
JText::script('BETTING_OTP_CODE_HAS_BEEN_EXPIRED');
JText::script('BETTING_OTP_CODE_IS_NOT_VALID');
JText::script('BETTING_INVALID_REQUEST');
JText::script('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER');
require( JModuleHelper::getLayoutPath('mod_widgetregister') );
?>
