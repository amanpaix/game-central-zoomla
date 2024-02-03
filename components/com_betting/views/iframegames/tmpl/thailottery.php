<?php 
    $lang = explode("-",JFactory::getLanguage()->getTag())[0];	
    $playerToken = Utilities::getPlayerToken();
    $playerInfo = Utilities::getPlayerLoginResponse();
    $url = Configuration::GAMES_DOMAIN;
$currencyInfo = Utilities::getCurrencyInfo();
$currency = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
?>
<script type="text/javascript">
    var _ic = _ic || [];
    _ic.push(['server', '<?php echo $url; ?>']);
    _ic.push(['gametype', 'dge']);
    _ic.push(['game_name', 'Thai Lottery']);
    _ic.push(['player_id', '<?php echo $playerInfo->playerId; ?>']);
    _ic.push(['player_name', '<?php echo $playerInfo->userName; ?>']);
    _ic.push(['session_id', '<?php echo $playerToken; ?>']);
    _ic.push(['balance', '<?php echo (float) $playerInfo->walletBean->cashBalance;
; ?>']);
    _ic.push(['language', '<?php echo $lang; ?>']);
    _ic.push(['currency', '<?php echo $playerInfo->walletBean->currencyDisplayCode; ?>']);
    _ic.push(['alias', '<?php echo Configuration::DOMAIN_NAME; ?>']);
    _ic.push(['iframe_div_id', 'lottogames_div_iframe']);
    _ic.push(['iframe_origin', '<?php echo Configuration::GAMES_DOMAIN; ?>']);
    (function () {
        document.write('<' + 'script type="text/javascript" src="<?php echo $url; ?>assets/js/lottogames.js"><' + '/script>');
    })();
</script>
<script type="text/javascript">LottoGames.frame(_ic);</script>
<div id="fakeDarkBg"></div>
