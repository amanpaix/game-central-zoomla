<?php

defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
$mainHeading = $params->get('mainHeading');
$contentType = $params->get('contentType');
$mainClass = $params->get('mainClass');
$contentList = $params->get('contentList');
require JModuleHelper::getLayoutPath('mod_customcontent');