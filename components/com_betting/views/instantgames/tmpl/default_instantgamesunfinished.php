<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    $('.left-header').html('Instant Win Games');
</script>
<div class="row">
    <div class='col-xs-12 col-sm-12 col-md-12 pmsHeader'>
        <h3>
            <span>Unfinished Instant Win Games</span>
        </h3>
    </div>
</div>
<div class="row whiteBackground">
    <div class='col-xs-12 col-sm-12 col-md-12' id="gameCatNav" style="line-height:20px;font-size:16px;">
    </div>
</div>
<div class='row whiteBackground'>
    <div class="col-md-12">
        <div class='instantGroupWrap'>
            <ul>
                <?php
                foreach ($this->recset as $games) {
                    ?>
                    <li id='<?php echo $games->transactionId; ?>'>
                        <a data-toggle="modal" data-target="#globalModal" onclick="showImage('<?php echo $games->gameMaster->gameId; ?>', '<?php echo $games->date; ?>', '<?php echo $games->ticketNbr; ?>', '<?php echo addslashes($games->gameMaster->gameName); ?>', '<?php echo $games->gameMaster->gameImageName; ?>', '<?php echo addslashes($games->gameMaster->gameDesc); ?>', '<?php echo Utilities::getFormattedAmount($games->gameMaster->gamePrice); ?>', '<?php echo $games->transactionId; ?>', '<?php echo $games->gameMaster->gameNum; ?>', '<?php echo $games->gameMaster->swfHeight; ?>', '<?php echo $games->gameMaster->swfWidth; ?>');">
                            <div class='gameDiv'>
                                <div class="gameDivInner">
                                    <div class='imgWrap' >
                                        <img src="<?php echo $games->gameMaster->gameImageName; ?>" alt="<?php echo $games->gameMaster->gameName; ?>" />
                                    </div>
                                    <div class='row'>
                                        <div class="div col-xs-8">
                                            <?php echo $games->gameMaster->gameName; ?>
                                        </div>
                                        <div class="col-xs-4"><span class='currencySymbol'><!--<i class="fa fa-fw">&#xf155;</i>--><?php echo $this->CurrData['decSymbol']; ?></span><?php echo Utilities::getFormattedAmount($games->gameMaster->gamePrice); ?></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<script type='text/javascript'>
<?php
$deviceType = Configuration::getDeviceType();
$appTypeAndClientType = Configuration::getAppAndClientType($deviceType);
?>
    var clientType = "<?php echo $appTypeAndClientType['CLIENTTYPE']; ?>";
    var appType = "<?php echo $appTypeAndClientType['APPTYPE']; ?>";
    var userAgentIge = "<?php echo Configuration::getDevice(); ?>";
    var deviceType = "<?php echo $deviceType; ?>";
    function listener(event) {
        pmslog(event.data);
        if (event.data == 'updateUnfinishedList') {
            location.reload();
        }
        if (event.data == 'updateBal') {
            startAjax("/component/poker/?task=account.getPlayerBalance", '', getBalance, "nottoshow");
//			updatebalance('<?php //echo $this->CurrData['decSymbol']; ?>//');
        }
        if (event.data == 'updateParent') {
            location.reload();
        }
    }
    if (window.addEventListener) {
        window.addEventListener("message", listener, false)
    } else {
        window.attachEvent("onmessage", listener)
    }
    $(window).load(function () {
        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        if (playerId.trim().length != 0) {
            startAjax("/component/poker/?task=account.getPlayerBalance", '', getBalance, "nottoshow");
//			updatebalance('<?php //echo $this->CurrData['decSymbol']; ?>//');
        }
    });
    function playForCash(gameId, date, ticketNbr, gameNumber, height, width){
        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        var merchantSessionId = '<?php echo Utilities::getPlayerToken(); ?>';
        var igePath = '<?php echo $this->igepath ?>';
        var domainName = '<?php echo $this->serviceData['domainName']; ?>';
        var merchantKey = '<?php echo $this->serviceData['merchantKey']; ?>';
        var secureKey = '<?php echo $this->serviceData['secureCode']; ?>';
        var currencyCode = '<?php echo $this->CurrData['decSymbol']; ?>';
        var lang = '<?php echo $this->serviceData['lang']; ?>';
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB/i.test(navigator.userAgent)) {
            igePath += "<?php echo ServerUrl::IGE_GAMEPLAY_UNFINISHED_MOBILE; ?>";
            //TODO check for game play source
        } else {
            igePath += "<?php echo ServerUrl::IGE_GAMEPLAY_UNFINISHED; ?>";
        }
        windowOpenMethod(igePath + '?gameId=' + gameId + '&date=' + date + '&ticketNbr=' + ticketNbr + '&gameType=flash&gameMode=UNFINISH&gameNumber=' + gameNumber + '&domainName=' + domainName + '&merchantKey=' + merchantKey + '&secureKey=' + secureKey + '&playerId=' + playerId + '&merchantSessionId=' + merchantSessionId + '&currencyCode=' + currencyCode + '&lang=' + lang + "&clientType=" + clientType + "&deviceType=" + deviceType + "&appType=" + appType + "&userAgentIge=" + userAgentIge, 'gamewindow' + gameNumber + 'UNFINISH', width, height, '');
    }
    function showImage(gameId, date, ticketNbr, gameName, imagePath, howToPlay, Price, clkGameId, gameNumber, height, width){
        $("#globalModal .modal-title").html("");
        $("#globalModal .modal-body").html("");
        $("#globalModal .modal-footer").html("");
        if (/BlackBerry|BB/i.test(navigator.userAgent)) {
            $("#globalModal .modal-title").html("Instant Win Games");
            $("#globalModal .modal-body").html("<p>Coming soon... To enjoy these games, please visit our website on PC</p>");
            $("#globalModal .modal-footer").html();
        } else if ((/ Mac OS/i.test(navigator.userAgent) == true) && (/ Mobile/i.test(navigator.userAgent) == false)) {
            $("#globalModal .modal-title").html("Instant Win Games");
            $("#globalModal .modal-body").html("<p>This Feature Is Not Supported On This Platform. For More Information, Please Contact Your Support Team. </p>");
            $("#globalModal .modal-footer").html();
        } else {
            $("#globalModal .modal-title").html(gameName);
            var innerHtmlStr = "<div class='row'><div class='col-xs-4'><img src='" + imagePath + "' alt=''  style='display:block; margin: auto;width:100%;' / ></div><div class='col-xs-8' style='padding-bottom: 20px; font-size: 15px; padding-top:8px;'>" + howToPlay + "</div></div>";
            innerHtmlStr = innerHtmlStr + "<div class='row'><div class='col-sm-8 col-sm-push-4 modelbtnBox'> <a href='#' data-dismiss='modal' onclick='playForCash(\"" + gameId + "\",\"" + date + "\",\"" + ticketNbr + "\",\"" + gameNumber + "\",\"" + height + "\",\"" + width + "\");'><div class='btnOuterWrap'><div class='priceBox'><span class='currencySymbol'><?php echo $this->CurrData['decSymbol']; ?></span>" + Price + "</div> <div class='btnTitle'>Play To <span>Finish!!</span></div></div></a></div></div>";
            $("#globalModal .modal-body").html(innerHtmlStr);
            $("#globalModal .modal-footer").html();
        }
    }
    $(document).ready(function (e) {
        var maxHeight = 0;
        $('.instantGroupWrap .gameDiv').each(function () {
            if ($(this).height() > maxHeight)
                maxHeight = $(this).height();
        });
        pmslog("max" + maxHeight);
        $('.instantGroupWrap .gameDiv .gameDivInner').css('height', maxHeight);
        adjustSideBar();
    });
</script>

<div class="gamePlaySlider swFullBG">
    <?php echo JHtml::_('content.prepare', '{loadposition gameplay-slider}'); ?>
</div>