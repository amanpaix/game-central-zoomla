<?php
$lang = explode("-", JFactory::getLanguage()->getTag())[0];
$playerToken = Utilities::getPlayerToken();
$playerId = Utilities::getPlayerID();
$playerInfo = Utilities::getPlayerLoginResponse();
$url = JUri::base() . 'bingo/';
$url = 'http://13.234.199.186:4201/';
?>
<script type="text/javascript">
    var _ic = _ic || [];
    _ic.push(['server', '<?php echo $url; ?>']);
    _ic.push(['gametype', 'dge']);
    _ic.push(['player_id', '<?php echo $playerInfo->playerId; ?>']);
    _ic.push(['player_name', '<?php echo $playerInfo->userName; ?>']);
    _ic.push(['session_id', '<?php echo $playerToken; ?>']);
    _ic.push(['balance', '<?php echo (float) $playerInfo->walletBean->totalBalance; ?>']);
    _ic.push(['language', '<?php echo $lang; ?>']);
    _ic.push(['currency', '<?php echo $this->CurrData["curCode"]; ?>']);
    _ic.push(['alias', '<?php echo Configuration::DOMAIN_NAME; ?>']);
    _ic.push(['iframe_div_id', 'lottogames_div_iframe']);
    _ic.push(['iframe_origin', '<?php echo Configuration::GAMES_DOMAIN; ?>']);
    (function () {
        document.write('<' + 'script type="text/javascript" src="<?php echo $url; ?>assets/js/lottogames.js"><' + '/script>');
    })();
    function postClientLogin() {
        $('#home_login').modal('show');
    }
</script>

<script type="text/javascript">LottoGames.frame(_ic);</script>
<div id="fakeDarkBg"></div>
<!--<div id="lottogames_div_iframe"></div>-->
<script type = "text/javascript">
    function listener(event) {
        var url = event.data;

        if (window.opener != null) {
            if (url == "updatePlayerBalance") {
                window.opener.updatePlayerBalance('both');
            }
        } else if (url == "updatePlayerBalance")
        {
            parent.updatePlayerBalance('both');
        }

    }

    if (window.addEventListener) {
        window.addEventListener("message", listener, false);
    } else {
        window.attachEvent("onmessage", listener);
    }

</script>
