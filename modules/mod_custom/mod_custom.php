<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if ($params->def('prepare_content', 1))
{
    JPluginHelper::importPlugin('content');
    $module->content = JHtml::_('content.prepare', $module->content, '', 'mod_custom.content');
}


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_custom', $params->get('layout', 'default'));
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';

$custom_js =  $params->get('custom_js');
if(!is_null($custom_js)) {
    foreach ($custom_js as $js) {
        Html::addJs(JUri::base().substr($js, 3));
    }
}

$custom_css =  $params->get('custom_css');
if(!is_null($custom_css)) {
    foreach ($custom_css as $css) {
        Html::addCss(JUri::base().substr($css, 3));
    }
}
