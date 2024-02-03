<?php
defined('_JEXEC') or die('Restricted access');
$allGameCategory = array();
foreach ($this->recset as $item) {
    if (!(in_array($item->gameCategory, $allGameCategory) )) {
        array_push($allGameCategory, $item->gameCategory);
    }
}
$deviceType = Configuration::getDeviceType();
$appTypeAndClientType = Configuration::getAppAndClientType($deviceType);
$playerLoginResponse = Utilities::getPlayerLoginResponse();
$cashBalance = number_format((float)$playerLoginResponse->walletBean->cashBalance,2,'.','');
?>
<style>
    iframe{
        overflow:hidden;
    }
</style>

<section class="bannerTop">
    <div class="bannerWrap"><img src="/images/banners/banners.jpg"></div>
</section>
<!-- banner code end-->
<!-- Game Play Start -->
<?php if($deviceType == 'PC'){ ?>
    <div class="bodyWrapper gameDetailsWrap" style="display: none;" id="gamePlayDiv">
        <section class="gameDetailsBgWrap">
            <div class="gameDetailsBgInner" >
                <div class="gamePlayArea">
                    <div class="gamePlayArea-top clearfix">
                        <div class="fl"></div>
                        <div class="fr"></div>
                    </div>
                    <iframe scrolling="no" name="playScreen" id="playScreen" src="about:blank" width="450" height="680" style="background: black;"></iframe>
                </div>
            </div>
            <span class="quickLink homeLink">
            <a href="javascript:void(0);" onclick="toggleFullScreen();">Home</a>
        </span>
        </section>
        <main class="gameDetailsInnerWrap">
            <div class="gameDesc">
                <h1></h1>
                <p></p>
            </div>
        </main>
    </div>
<?php } ?>
<div>   
    <form id="moodleform" target="playScreen" method="GET" action="#" >
        <input type="hidden" name="root" value=""/>
        <input type="hidden" name="gameNum" value=""/>
        <input type="hidden" name="gameMode" value="<?php echo $this->gameMode; ?>"/>
        <input type="hidden" name="domainName" value=""/>
        <input type="hidden" name="merchantKey" value=""/>
        <input type="hidden" name="secureKey" value=""/>
        <input type="hidden" name="currencyCode" value=""/>
        <input type="hidden" name="lang" value=""/>
        <input type="hidden" name="gameType" value="scratch"/>
        <input type="hidden" name="playerId" value=""/>
        <input type="hidden" name="merchantSessionId" value=""/>
        <input type="hidden" name="clientType" value=""/>
        <input type="hidden" name="deviceType" value=""/>
        <input type="hidden" name="appType" value=""/>
        <input type="hidden" name="userAgentIge" value=""/>
        <input type="hidden" name="balance" value=""/>
        <input type="hidden" name="ticketPrice" value=""/>
        <input type="hidden" name="launchIc" value=""/>
        <input type="hidden" name="prizeSchemeIge" value=""/>
        <input type="hidden" name="loaderImage" value=""/>
        <input type="hidden" name="commCharge" value="0"/>
    </form>
</div>
<!-- Game Play End -->
<!-- header start here-->
<?php /* ?>
<header class="gameNav">
    <ul>
        <li class="active  allGame" data-filter="*">
            <a>
                <span>All</span>
            </a>
        </li>
        <?php
        foreach ($allGameCategory as $value) {
            ?>
            <li class="instantGame" id="<?php echo $value ?>" data-filter=".<?php echo $value ?>">
                <a>
                    <span>
                        <?php
                        if ($value == "-New") {
                            $value = "New";
                            echo $value;
                        } else if ($value == "-popular") {
                            $value = "popular";
                            echo $value;
                        } else {
                            echo $value;
                        }
                        ?>
                    </span>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</header>
<?php */ ?>
<!-- header end here-->
<section class="gameListingWrap">
    <div class="gameListing grid">
        <?php
        $gameVar = [];
        foreach ($this->recset as $item) {
            $gameCategory = $item->gameCategory;
            $count = 0;
            $i++;
            $extraParams = json_decode($item->extraParams);
            ?>
            <div class="cell grid-item <?php echo $item->gameCategory ?>" game="Instant_<?php echo $item->gameNumber ?>" >
                <div class="cellInnerWrap">
                    <figure>
                        <img onclick='playGame("<?php echo preg_replace('/\s+/', '-', $item->gameName); ?>", "<?php echo Utilities::getFormattedAmount($item->gamePrice); ?>", "<?php echo $gameCategory . "_" . $count; ?>", this);' src="<?php echo $item->gameImageLocations ?>" alt="<?php echo $item->gameName ?>">
                        <figcaption>
                            <div onclick='playGame("<?php echo preg_replace('/\s+/', '-', $item->gameName); ?>", "<?php echo Utilities::getFormattedAmount($item->gamePrice); ?>", "<?php echo $gameCategory . "_" . $count; ?>", this);' class="gameName"><?php echo $item->gameName; ?></div>
                            
                            <div class="gameDesc"> <?php if($extraParams->gameWinUpto != 0):?><div class="winPrizeWrap">Win up to <span class="currencyWrap"><?php echo Constants::MYCURRENCYSYMBOL; ?><?php echo number_format($extraParams->gameWinUpto?$extraParams->gameWinUpto:0,2)?></span></div><?php endif;?><?php echo $item->gameDescription; ?></div>
                            
                            <button class="btn btnStyle2" onclick='playGame("<?php echo preg_replace('/\s+/', '-', $item->gameName); ?>", "<?php echo Utilities::getFormattedAmount($item->gamePrice); ?>", "<?php echo $gameCategory . "_" . $count; ?>", this);' >Play Now</button>
                            <?php $gameVar[preg_replace('/\s+/', '-', $item->gameName)] = $item; ?>
                            <?php /* ?>
                            <button class="buttonStyle1" onclick='playGame("<?php echo addslashes(json_encode($item)); ?>", "<?php echo Utilities::getFormattedAmount($item->gamePrice); ?>", "<?php echo $gameCategory . "_" . $count; ?>", this);'>Play Now</button>
                            <div class="gamePrice"><span>R</span> <?php echo $item->gamePrice ?></div>
                            <?php */ ?>
                        </figcaption>
                    </figure>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</section>
<script type="text/javascript">

    function gameValChanger(gameOnClickVal) {
        $('.gameListingWrap div[game="'+ gameOnClickVal +'"]  button').click();
    }

    $(document).ready(function()
    {
        gameValChanger(window.location.hash.substr(1));
    });


    var clientType = "<?php echo $appTypeAndClientType['CLIENTTYPE']; ?>";
    var appType = "<?php echo $appTypeAndClientType['APPTYPE']; ?>";
    var userAgentIge = encodeURI("<?php echo Configuration::getDevice(); ?>");
    var deviceType = "<?php echo $deviceType; ?>";
    var playerId = '<?php echo Utilities::getPlayerId(); ?>';
    var merchantSessionId = '<?php echo Utilities::getPlayerToken(); ?>';
    
    var serviceData = JSON.parse('<?php echo json_encode($this->serviceData); ?>');
    var currencyCode='<?php echo Constants::DEFAULT_CURRENCY_CODE ?>';
    var instantPlayerBalance = <?php echo $cashBalance; ?>;
    var newpath = JSON.parse('<?php echo json_encode(ServerUrl::IGE_PATH); ?>');
    var rootPath = JSON.parse('<?php echo json_encode(Configuration::IGE_PATH); ?>');
    
    var gameVar = JSON.parse('<?php echo addslashes(json_encode($gameVar)); ?>');
</script>