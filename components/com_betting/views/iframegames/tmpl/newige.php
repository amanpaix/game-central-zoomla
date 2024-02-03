<?php 
$app = JFactory::getApplication();
$input = $app->input;
$vendor = $input->getString('vendor', '');
$game = $input->getInt('game', 0);
$playType = $input->getString('playType', '');
$return_url = JUri::current();
$lang = explode("-", JFactory::getLanguage()->getTag())[0];
$playerToken = Utilities::getPlayerToken();
$playerInfo = Utilities::getPlayerLoginResponse();
$url = Configuration::GAMES_DOMAIN;
$currencyInfo = Utilities::getCurrencyInfo();
$currency = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
?>
<script type="text/javascript">
    var hash = location.hash;
    if (hash.length > 11)
        hash = hash.split(",");
    var _ic = _ic || [];
    _ic.push(['server', '<?php echo $url; ?>']);
    _ic.push(['gametype', 'ige']);
    if( hash.length == 3 ){
        _ic.push(['vendor', hash[0].replace("#","")]);
        _ic.push(['game_id', hash[1]]);
        _ic.push(['play_type', hash[2]]);
    }
    else
    {
        _ic.push(['vendor', '<?php echo $vendor; ?>']);
        _ic.push(['game_id', <?php echo $game; ?>]);
        _ic.push(['play_type', '<?php echo $playType; ?>']);
    }

    _ic.push(['player_id', '<?php echo $playerInfo->playerId; ?>']);
    _ic.push(['player_name', '<?php echo $playerInfo->userName; ?>']);
    _ic.push(['session_id', '<?php echo $playerToken; ?>']);
    _ic.push(['balance', '<?php echo (float) $playerInfo->walletBean->cashBalance; ?>']);
    _ic.push(['language', '<?php echo $lang; ?>']);
    _ic.push(['currency', '<?php echo $currency ?>']);
    _ic.push(['currencyDisplay', '<?php echo $dispCurrency ?>']);
    _ic.push(['alias', '<?php echo Configuration::DOMAIN_NAME; ?>']);
    _ic.push(['isMobileApp', '0']);
    _ic.push(['return_url', '<?php echo $return_url; ?>']);
    _ic.push(['iframe_div_id', 'lottogames_div_iframe']);
    _ic.push(['iframe_origin', '<?php echo Configuration::GAMES_DOMAIN; ?>']);
    (function () {
        document.write('<' + 'script type="text/javascript" src="<?php echo $url; ?>assets/js/lottogames.js"><' + '/script>');
    })();
</script>
<script type="text/javascript">LottoGames.frame(_ic);</script>
<div id="fakeDarkBg"></div>
