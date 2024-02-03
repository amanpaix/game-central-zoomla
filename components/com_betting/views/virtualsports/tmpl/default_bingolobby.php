<?php
defined('_JEXEC') or die('Restricted Access');


jimport('joomla.application.module.helper');
$modules = JModuleHelper::getModules('popup');


echo JHtml::_('content.prepare', '{loadposition bingolobby}');

?>



