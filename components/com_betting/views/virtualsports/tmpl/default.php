<?php
defined('_JEXEC') or die('Restricted access');

Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");

if(isset($this->keroninteractive))
	echo $this->loadTemplate('keroninteractive');

if(isset($this->goldenrace))
	echo $this->loadTemplate('goldenrace');

if(isset($this->livegames))
	echo $this->loadTemplate('livegames');

if(isset($this->evolutiongames))
    echo $this->loadTemplate('evolutiongames');

if(isset($this->betgames_lobby))
    echo $this->loadTemplate('betgames_lobby');

if(isset($this->bingoplay))
    echo $this->loadTemplate('bingoplay');

if(isset($this->bingolobby))
    echo $this->loadTemplate('bingolobby');
