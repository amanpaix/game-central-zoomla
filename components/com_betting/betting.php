<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

$controller = JControllerLegacy::getInstance('Betting');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();
