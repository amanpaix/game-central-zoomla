<?php
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/slick.css', 'text/css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/slick-theme.css', 'text/css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_customslider/assets/css/customSlider.css', 'text/css');
$doc->addScript(JUri::base() . 'modules/mod_customslider/assets/js/slick.min.js', 'text/javascript');

//$js = '
//	jQuery(document).on(\'subform-row-add\', function(event, row){
//		jQuery(row).find(\'select\').chosen();
//	})
//';
//$doc->addScriptDeclaration($js);

if (Configuration::getDeviceType() != "PC" && $module->id == 93) {

} else {
    ?>
    <div class="customslider-<?php echo $module->id . ' ' . $newClass . ' ' . $params->get('sliderCustomClass'); ?>">
        <?php
        foreach ($imageList as $key => $value) {
            $explodeItem = array();
            $customclick = "";

            if ((isset($value->gameCode) && array_key_exists($value->gameCode, $gameInfo)) || !$value->dynamicContent  || $value->type != 'images') {

                if ($value->dynamicContent) {
                    if (!isset($gameInfo[$value->gameCode])) {
                        $thisGameInfo = array();
                    } else {
                        $thisGameInfo = $gameInfo[$value->gameCode];
                    }
                    //exit(json_encode($thisGameInfo));
                }
                $gameClass = '';
                if (isset($value->gameCode)) {
                    $gameClass = strtolower($value->gameCode);
                    if ($gameClass == 'sportstake') {
                        $gameClass .= 13;
                    }
                    if (strtoupper($gameClass) == 'SS08') {
                        $gameClass = 'sportstake8';
                    }
                    if ($gameClass == 'lottoplus') {
                        $gameClass .= 1;
                    }
                }
                ?>
                <div class="subImage <?php echo ($value->dynamicContent) ? 'dynamic' : 'static'; ?> <?php echo $value->subImagediv; ?> <?php echo 'game-' . $value->gameCode; ?> <?php echo $gameClass; ?> <?php echo ($value->enableCorner) ? $value->cornerClass : ''; ?>">
                    <div class="slideWrap">
                        <?php
                        if($value->type == "images"){
                            if (isset($value->anchorMenuImg) && $value->anchorMenuImg != '') {
                                $mainImgUrl = JRoute::_("index.php?Itemid={$value->anchorMenuImg}");
                                $explodeItem = explode('/', $mainImgUrl);

                                if ($explodeItem[1] == 'play-now') {
                                    $customclick = "customclick=\"play_now_" . $explodeItem[2] . "\"";
                                }
                            } else {
                                $mainImgUrl = 'javascript:void(0);';
                                $customclick = '';
                            }
                            ?>
                            <a href="<?php echo $mainImgUrl; ?>" class="imgAnchor" <?php echo $customclick; ?>>
                                <img <?php echo ($sliderType != 4) ? 'src' : 'data-lazy'; ?>=
                                "<?php echo JUri::base() . $value->imageItem; ?>" alt="<?php echo $value->imageTitle; ?>">
                            </a>
                            <div class="contentWrap <?php echo (!$value->dynamicContent || !($value->showTimer) || !isset($thisGameInfo['next_draw_date']) || $thisGameInfo['next_draw_date'] == '') ? 'noTimer' : ''; ?>">
                                <?php
                                if (isset($value->imageItem2)) {
                                    if ($sliderType != 2) {
                                        ?>
                                        <!--                    <img --><?php //echo ($sliderType != 4) ? 'src' : 'data-lazy';  ?><!--="--><?php //echo JUri::base() . $value->imageItem2;  ?><!--" alt="">-->
                                        <?php
                                    }
                                }
                                if ($value->dynamicContent) {
                                    ?>
                                    <div class="text">
                                        <div class='imgTitle <?php echo isset($thisGameInfo['jackpot_title']) ? preg_replace('/\s+/', '', strtolower($thisGameInfo['jackpot_title'])) : '' ?>' data-text="<?php echo isset($thisGameInfo['jackpot_title']) ? $thisGameInfo['jackpot_title'] : ''; ?>">
                                            <?php
                                            if ($value->gameCodimgTitlee != "RAPIDO") {
                                                if ($module->id != 93 && $value->gameCode != 'DAILYLOTTO') {
                                                    if($value->gameCode != 'RAFFLE')
                                                        echo isset($thisGameInfo['jackpot_title']) ? ( $thisGameInfo['guaranteed_jackpot'] != "R0" ? '' : '') . $thisGameInfo['jackpot_title'] : '';
                                                    else{
                                                        echo isset($thisGameInfo['jackpot_title']) ?  $thisGameInfo['jackpot_title'] : '';
                                                    }
                                                } elseif($value->gameCode != 'DAILYLOTTO'){
                                                    echo isset($thisGameInfo['jackpot_title']) ? 'NEXT ' . $thisGameInfo['jackpot_title'] . ' JACKPOT' : '';
                                                }
                                            } else {
                                                //                                    echo '*Win up to';
                                                echo isset($thisGameInfo['jackpot_title']) ? '' . $thisGameInfo['jackpot_title'] : '';
                                            }
                                            ?>
                                        </div>
                                        <div class='imgDesc loader'
                                             data-text="<?php echo isset($thisGameInfo['jackpot_amount']) ? $thisGameInfo['jackpot_amount'] : ''; ?>">
                                            <?php
                                            if ($module->id != 93 && $value->gameCode != 'DAILYLOTTO' && $value->gameCode != 'RAFFLE') {
                                                echo isset($thisGameInfo['jackpot_amount']) ? $thisGameInfo['jackpot_amount'] . ( $thisGameInfo['guaranteed_jackpot'] != "R0" ? '' : '') : '';
                                            } else {
                                                echo isset($thisGameInfo['jackpot_amount']) ? $thisGameInfo['jackpot_amount'] : '';
                                            }
                                            ?>
                                        </div>
                                        <?php if($value->gameCode == 'POWERBALL') { ?>
                                        <div class="sliderDay">Draw on <span></span></div>
                                        <?php }else if($value->gameCode == 'TWELVEBYTWENTYFOUR'){ ?>
                                        <div class="sliderDay">3 DRAWS DAILY</div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                                if ($value->enableCorner) {
                                    ?>
                                    <span class="cornerBanner"><?php echo $value->cornerClass; ?></span>
                                    <?php
                                }
                                if ($value->dynamicContent && $value->showButton) {
                                    if (isset($value->buttonLabel) && $value->buttonLabel != '') {
                                        $gamename = $value->gameCode;
                                        if ($gamename == 'SPORTSTAKE') {
                                            $gamename = 'sportstake13';
                                        }
                                        if (strtoupper($gamename) == 'SS08') {
                                            $gamename = 'sportstake8';
                                        }
                                        ?>
                                        <a href="javascript:void(0);" class="button1"
                                           title="play_now_<?php echo strtolower($gamename) ?>">
                                            <button class='menu-btn'><?php echo $value->buttonLabel; ?></button>
                                        </a>
                                        <?php
                                    }
                                    if (isset($thisGameInfo['next_draw_date']) && $thisGameInfo['next_draw_date'] != '') {
                                        $nextDraw = $thisGameInfo['next_draw_date'];
                                        ?>
                                        <div class="nextDrawwrap">
                                            <span class="title">Next Draw:</span>
                                            <?php if ($value->gameCode == "RAPIDO") {
                                                ?>
                                                <span class="title closingDate">Every 5 Min</span>
                                                <span class="date"></span>
                                            <?php } else if (date('d M, Y') != date("d M, Y", strtotime($nextDraw))) {
                                                ?>
                                                <span class="title closingDate">Closing Date:</span>
                                                <span class="date"><?php echo date("d M, Y", strtotime($nextDraw . ' ' . Constants::DRAW_TIME)); ?></span>
                                            <?php } else { ?>
                                                <span class="title closingDate">Closing Time:</span>
                                                <span class="time"><?php echo Constants::DRAW_TIME; ?></span>
                                            <?php } ?>
                                        </div>

                                        <?php
                                    }
                                    if (isset($thisGameInfo['draw_date']) && $thisGameInfo['draw_date'] != '') {
                                        $drawDate = $thisGameInfo['draw_date'];
                                        ?>
                                        <div class="lastDrawwrap">
                                            <span class="title">Last Draw:</span>
                                            <span class="date"><?php echo ($value->gameCode == 'RAFFLE') ? date("d M Y", strtotime('2020/01/10')) : date("d M Y", strtotime($drawDate)); ?></span>
                                            <span class="time"></span>
                                        </div>
                                        <?php
                                    }
                                    if (isset($value->buttonLabel2) && $value->buttonLabel2 != '') {

                                        ?>
                                        <a href="<?php echo (isset($value->anchorMenu2) && $value->anchorMenu2 != '') ? JRoute::_("index.php?Itemid={$value->anchorMenu2}") : 'javascript:void(0);'; ?>"
                                           class="button2">
                                            <button class='menu-btn'><?php echo $value->buttonLabel2; ?></button>
                                        </a>
                                        <?php
                                    }
                                }
                                if ($value->dynamicContent && $value->showTimer && isset($thisGameInfo['next_draw_date']) && $thisGameInfo['next_draw_date'] != '') {
                                    //  $dtm = Configuration::getTimeDifference($thisGameInfo['next_draw_date']);
                                    ?>
                                    <div class='gameTimer loader'>
                                        <span class="title">Remaining Time </span>
                                        <span class='daytime'><?php echo $dtm['days']; ?></span>
                                        <span class='hrtime'><?php echo $dtm['hrs']; ?></span>
                                        <span class='mintime'><?php echo $dtm['min']; ?></span>
                                        <span class='sectime'><?php echo $dtm['sec']; ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                                <script>
                                    // $(document).ready(function () {
                                    var tmpData = [];
                                    if ('<?php echo $value->gameCode ?>' == 'RAFFLE') {
                                        tmpData['date'] = '<?php echo date("Y-m-d H:i:s", strtotime($thisGameInfo["next_draw_date"])) ?>';
                                        tmpData['draw_date'] = '<?php echo date("Y-m-d H:i:s", strtotime($thisGameInfo['draw_date'])); ?>';
                                    } else if ('<?php echo $value->gameCode ?>' == 'SPORTSTAKE' || '<?php echo $value->gameCode ?>' == 'SS08') {
                                        tmpData['date'] = '<?php echo date("Y-m-d H:i:s", strtotime($thisGameInfo["next_draw_date"])) ?>';
                                        tmpData['draw_date'] = '<?php echo date("Y-m-d H:i:s", strtotime($thisGameInfo['draw_date'])); ?>';
                                    } else
                                        tmpData['date'] = '<?php echo date("Y-m-d H:i:s", strtotime($thisGameInfo["next_draw_date"])) ?>';

                                    tmpData['class'] = '.customslider-<?php echo $module->id . '.' . $newClass . ' .' . 'game-' . $value->gameCode ?>';
                                    tmpData['game'] = '<?php echo $value->gameCode; ?>';
                                    tmpData['unitCostJson'] = '<?php echo json_encode(json_decode($thisGameInfo["content"])->unitCostJson); ?>';
                                    tmpData['jackpotAmount'] = '<?php echo json_decode($thisGameInfo["content"])->jackpotAmount; ?>';
                                    if( tmpData['game'] ){
                                        timerArray.push(tmpData)
                                    }
                                    //getServerTime('<?php echo $thisGameInfo['next_draw_date'] ?>','.customslider-<?php echo $module->id . '.' . $newClass . ' .' . $value->subImagediv ?>');
                                    // });
                                </script>
                            </div>
                            <?php
                        }
                        elseif($value->type == 'html'){
                            $doc->addStyleDeclaration($value->cssData);
                            echo $value->data;
                        }elseif($value->type == 'videos'){
                            ?>
                            <iframe class="embed-player slide-media" width="100%" src="<?php echo $value->videolink; ?>?enablejsapi=1&controls=0&fs=0&iv_load_policy=3&rel=0&showinfo=0&loop=1&playlist=tdwbYGe8pv8&start=102" frameborder="0" allowfullscreen></iframe>
                        <?php } ?>
                    </div>
                    <!-- akshay-->
                </div>
                <?php
            }
        }
        ?>
    </div>
    <script type="text/javascript">
        if(jQuery('.customslider-<?php echo $module->id; ?>').find('.embed-player').length != 0){
            jQuery('.customslider-<?php echo $module->id; ?>').on("init", function(slick){
                slick = $(slick.currentTarget);
                setTimeout(function(){
                    playPauseVideo(slick,"play");
                }, 1000);
                resizePlayer(jQuery('.customslider-<?php echo $module->id; ?>').find('.embed-player'), 16/9);
            });
            jQuery('.customslider-<?php echo $module->id; ?>').on("beforeChange", function(event, slick) {
                slick = $(slick.$slider);
                playPauseVideo(slick,"pause");
            });
            jQuery('.customslider-<?php echo $module->id; ?>').on("afterChange", function(event, slick) {
                slick = $(slick.$slider);
                playPauseVideo(slick,"play");
            });
        }
    </script>
    <?php
    if ($sliderType == 1) {
        ?>
        <script type="text/javascript">
            jQuery('.customslider-<?php echo $module->id; ?>').slick({
                vertical: <?php echo $direction ? 'false' : 'true'; ?>,
                arrows: <?php echo $displayArrows ? 'true' : 'false'; ?>,
                autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                autoplaySpeed: <?php echo $autoplaySpeed; ?>,
                infinite: <?php echo $infinite ? 'true' : 'false'; ?>
            });
        </script>
        <?php
    } elseif ($sliderType == 2) {
        ?>
        <script type="text/javascript">
            jQuery('.customslider-<?php echo $module->id; ?>').slick({
                dots: false,
                infinite: <?php echo $infinite ? 'true' : 'false'; ?>,
                vertical: <?php echo $direction ? 'false' : 'true'; ?>,
                arrows: <?php echo $displayArrows ? 'true' : 'false'; ?>,
                speed: 300,
                slidesToShow: <?php echo $slidesToshow; ?>,
                slidesToScroll: <?php echo $slidesToscroll; ?>,
                autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                autoplaySpeed: <?php echo $autoplaySpeed; ?>,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: <?php echo $slidesToshow; ?>,
                            slidesToScroll: <?php echo $slidesToscroll; ?>,
                            infinite: <?php echo $infinite ? 'true' : 'false'; ?>,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            infinite: <?php echo $infinite ? 'true' : 'false'; ?>,
                            slidesToShow: ((2 > <?php echo $slidesToshow; ?>) ? <?php echo $slidesToshow; ?> : 2),
                            slidesToScroll: ((2 > <?php echo $slidesToscroll; ?>) ? <?php echo $slidesToscroll; ?> : 2)
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            infinite: <?php echo $infinite ? 'true' : 'false'; ?>,
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
                vertical: <?php echo $direction ? 'false' : 'true'; ?>,
                arrows: <?php echo $displayArrows ? 'true' : 'false'; ?>,
                slidesToShow: <?php echo $slidesToshow; ?>,
                autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                autoplaySpeed: <?php echo $autoplaySpeed; ?>,
                infinite: <?php echo $infinite ? 'true' : 'false'; ?>,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: <?php echo $slidesToshow; ?>,
                            infinite: <?php echo $infinite ? 'true' : 'false'; ?>
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 1,
                            infinite: <?php echo $infinite ? 'true' : 'false'; ?>
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
                vertical: <?php echo $direction ? 'false' : 'true'; ?>,
                arrows: <?php echo $displayArrows ? 'true' : 'false'; ?>,
                slidesToShow: <?php echo $slidesToshow; ?>,
                slidesToScroll: <?php echo $slidesToscroll; ?>,
                autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                autoplaySpeed: <?php echo $autoplaySpeed; ?>,
                infinite: <?php echo $infinite ? 'true' : 'false'; ?>
            });
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            jQuery('.customslider-<?php echo $module->id; ?>').slick({
                slidesToShow: <?php echo $slidesToshow; ?>,
                slidesToScroll: <?php echo $slidesToscroll; ?>,
                vertical: <?php echo $direction ? 'false' : 'true'; ?>,
                arrows: <?php echo $displayArrows ? 'true' : 'false'; ?>,
                autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                autoplaySpeed: <?php echo $autoplaySpeed; ?>,
                infinite: <?php echo $infinite ? 'true' : 'false'; ?>
            });
        </script>
        <?php
    }
}
?>
<script type="text/javascript">
    var SERVER_TIME = true;
    var GET_SERVER_TIME = "/index.php/component/Betting/?task=Betting.getServerTime";
</script>
