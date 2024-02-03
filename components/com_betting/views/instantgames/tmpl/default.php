<?php
defined('_JEXEC') or die('Restricted access');
Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "templates/shaper_helix3/js/lottery/main.js");
Html::addCss(JUri::base() . "templates/shaper_helix3/css/lottery/games.css");
if (isset($this->instantgamesnew)) {
//    Html::addCss(JUri::base() . "templates/shaper_helix3/css/owl.carousel.min.css");
//    Html::addCss(JUri::base() . "templates/shaper_helix3/css/owl.theme.default.min.css");
    Html::addCss(JUri::base() . "templates/shaper_helix3/css/iwg.css");
//    Html::addJs("https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js");
//    Html::addJs(JUri::base() . "templates/shaper_helix3/js/owl.carousel.min.js");
//    Html::addJs(JUri::base() . "templates/shaper_helix3/js/scripts-ige.js");
    Html::addJs("https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js");
    Html::addJs(JUri::base() . "templates/shaper_helix3/js/custom/instantGames.js");
    echo $this->loadTemplate('instantgamesnew');
}
?>