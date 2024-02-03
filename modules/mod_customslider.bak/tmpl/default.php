<?php
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/slick.css?v='.Constants::CSS_VER, 'text/css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/slick-theme.css?v='.Constants::CSS_VER, 'text/css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/customSlider.css?v='.Constants::CSS_VER, 'text/css');
$doc->addScript(JUri::base() . 'modules/mod_customslider/assets/js/slick.min.js?v='.Constants::JS_VER, 'text/javascript');
//$js = '
//	jQuery(document).on(\'subform-row-add\', function(event, row){
//		jQuery(row).find(\'select\').chosen();
//	})
//';
//$doc->addScriptDeclaration($js);
?>
<div class="customslider-<?php echo $module->id . ' ' . $newClass; ?>">
    <?php
    foreach ($imageList as $key => $value) {
        ?>
        <div class="subImage <?php echo ($value->enableCorner) ? $value->cornerClass : ''; ?> <?php echo $value->subImagediv; ?>">
            <div class="slideWrap">
                <img <?php echo ($sliderType != 4) ? 'src' : 'data-lazy'; ?>="<?php echo JUri::base() . $value->imageItem; ?>" alt="<?php echo $value->imageTitle; ?>">
                <div class='imgTitle'><?php echo $value->imageTitle; ?></div>
                <div class='imgDesc'><?php echo $value->imageDesc; ?></div>
                <?php
                if ($value->enableCorner) {
                    ?>
                    <span class="cornerBanner"><?php echo $value->cornerClass; ?></span>
                    <?php
                }
                if ($value->showButton) {
                    if(isset($value->buttonLabel) && $value->buttonLabel != ''){
                    ?>
                    <a href="<?php echo JRoute::_("index.php?Itemid={$value->anchorMenu}"); ?>" class="button1">
                        <button class='menu-btn'><?php echo $value->buttonLabel; ?></button>
                    </a>
                    <?php
                    }if(isset($value->buttonLabel2) && $value->buttonLabel2 != ''){
                    ?>
                    <a href="<?php echo JRoute::_("index.php?Itemid={$value->anchorMenu2}"); ?>" class="button2">
                        <button class='menu-btn'><?php echo $value->buttonLabel2; ?></button>
                    </a>
                    <?php
                    }                    
                }
                if ($value->showTimer) {
                    ?>
                    <div class='gameTimer'>
                        <span class='hrtime'></span>
                        <span class='mintime'></span>
                        <span class='sectime'></span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php
if ($sliderType == 1) {
    ?>
    <script type="text/javascript">
        jQuery('.customslider-<?php echo $module->id; ?>').slick({
            autoplay: true
        });
    </script>
    <?php
} elseif ($sliderType == 2) {
    ?>
    <script type="text/javascript">
        jQuery('.customslider-<?php echo $module->id; ?>').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: <?php echo $slidesToshow; ?>,
            slidesToScroll: <?php echo $slidesToscroll; ?>,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: <?php echo $slidesToshow; ?>,
                        slidesToScroll: <?php echo $slidesToscroll; ?>,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: ((2 > <?php echo $slidesToshow; ?>) ? <?php echo $slidesToshow; ?> : 2),
                        slidesToScroll: ((2 > <?php echo $slidesToscroll; ?>) ? <?php echo $slidesToscroll; ?> : 2)
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    </script>
    <?php
} elseif ($sliderType == 3) {
    ?>
    <script type="text/javascript">
        jQuery('.customslider-<?php echo $module->id; ?>').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: <?php echo $slidesToshow; ?>,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: <?php echo $slidesToshow; ?>
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
    </script>
    <?php
} elseif ($sliderType == 4) {
    ?>
    <script type="text/javascript">
        jQuery('.customslider-<?php echo $module->id; ?>').slick({
            lazyLoad: 'ondemand',
            slidesToShow: <?php echo $slidesToshow; ?>,
            slidesToScroll: <?php echo $slidesToscroll; ?>
        });
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">
        jQuery('.customslider-<?php echo $module->id; ?>').slick({
            slidesToShow: <?php echo $slidesToshow; ?>,
            slidesToScroll: <?php echo $slidesToscroll; ?>,
            autoplay: true,
            autoplaySpeed: <?php echo $autoplaySpeed; ?>,
        });
    </script>
    <?php
}
?>

<script type="text/javascript">
    var SERVER_TIME = true;
    var GET_SERVER_TIME = "/index.php/component/Betting/?task=Betting.getServerTime";
</script>
