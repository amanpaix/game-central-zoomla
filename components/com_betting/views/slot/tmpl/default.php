<?php
defined('_JEXEC') or die('Restricted access');

Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/lottery/main.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/lottery/jquery-barcode.min.js");

Html::addCss(JUri::base()."templates/shaper_helix3/css/lottery/games.css");
if(isset($this->slot))
	echo $this->loadTemplate('slot');


if(isset($this->page))
	echo $this->loadTemplate('page');

if(isset($this->slotpage))
    echo $this->loadTemplate('slot_page');

if(isset($this->slotnew))
{

    Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate.min.js");
    Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
    Html::addJs(JUri::base()."templates/shaper_helix3/js/lottery/main.js");

    Html::addCss(JUri::base()."templates/shaper_helix3/css/lottery/games.css");

    Html::addCss(JUri::base()."templates/shaper_helix3/css/owl.carousel.min.css");
    Html::addCss(JUri::base()."templates/shaper_helix3/css/owl.theme.default.min.css");
    Html::addCss(JUri::base()."templates/shaper_helix3/css/iwg.css");

    Html::addJs("https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js");
    Html::addJs(JUri::base()."templates/shaper_helix3/js/owl.carousel.min.js");
    Html::addJs(JUri::base()."templates/shaper_helix3/js/scripts-ige.js");
    Html::addJs("https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js");


    echo $this->loadTemplate('slotnew');
}


?>

<div class="modal fade" id="globalModal" tabindex="-1" style="z-index:1000000" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pmsHeader">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="color:white !important;text-shadow:0 0 0 white !important">×</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
