<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Html {

    public static function addJs($path) {
        JHtml::_('jquery.framework');
//        JHtml::_('bootstrap.framework');
        if (strpos($path, "login.js") !== false) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('
                var loginToken = "' . Utilities::generateLoginToken() . '";                
            ');
        }
        JHtml::script($path . '?v=' . Constants::JS_VER);
//        echo '<script type="text/javascript" src="'.$path.'?v='.Constants::JS_VER.'"></script>';
    }

    public static function addCss($path) {
        JHtml::stylesheet($path . '?v=' . Constants::CSS_VER);
//        echo '<link rel="stylesheet" href="'.$path.'?v='.Constants::CSS_VER.'" type="text/css"/>';
    }

}

?>