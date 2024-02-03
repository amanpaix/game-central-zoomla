<?php
defined('_JEXEC') or die('Restricted Access');

$lang = JFactory::getLanguage();
try {
    $lang = Constants::MY_LANGUAGE_MAP[$lang->getTag()];
}
catch ( Exception $e){
    $lang = Constants::MY_LANGUAGE_MAP['en-GB'];
}

$playerToken = Utilities::getPlayerToken() ?: "-";
$isMobile = Configuration::getDeviceType() === "PC" ? 0 : 1;

$gameId = $this->gameId;

?>



<div id="mayfair"></div>
<script type="text/javascript">


    setTimeout(function () {
        $(".livebet_loader").hide();
    }, 8000);

    var clientUrl = 'https://integrations01-webiframe.betgames.tv';
    var script = document.createElement('script');

    script.onload = function () {
        window.BetGames.setup({
            containerId: 'mayfair',
            clientUrl: clientUrl,
            apiUrl: 'https://integrations01.betgames.tv',
            partnerCode: 'mayfair_casino_dev',
            partnerToken: '<?php echo $playerToken ?>',
            language: 'en',
            timezone: '3',
            homeUrl: '<?php echo Configuration::DOMAIN ?>',
            isMobile: '<?php echo $isMobile ?>',
            defaultGame: '<?php echo is_null($this->gameId) ? "" : $this->gameId  ?>'
        });
    };


    script.type = 'text/javascript';
    script.src = clientUrl + '/public/betgames.js' + '?' + Date.now();

    document.head.appendChild(script);
</script>

<script>

    window.addEventListener("message", message => {
        try {
            const parsedData = JSON.parse(message.data);
            // console.log(parsedData);
            if( parsedData.type === "balance_check" ){
                updatePlayerBalance(true,"nottoshow");
            }
        } catch (e) {}
    });

</script>
