<?php
defined('_JEXEC') or die('Restricted Access');

$uaResp = $this->uaResp;
$evloutionDomain = Configuration::EVOLUTION_HOST;

$lunchURL = $evloutionDomain . $uaResp->entry;
//if( $this->openStyle !== "lobby" ){
//    $sessId = explode("JSESSIONID=", $uaResp->entry)[1];
//    $lunchURL = $evloutionDomain . "/frontend/evo/r1/#provider=evolution&ua_launch_id=" . $sessId . "&category=all_games";
//}


if( isset($uaResp->entry) ){
    ?>
        <div id="casinoGamesContent" >
            <iframe id="casinoGamesFrame" scrolling="no" allowfullscreen="" webkitallowfullscreen="" allow="xr-spatial-tracking"
                    src="<?php echo $lunchURL; ?>"
                    evo-root-iframe="true" ></iframe>
        </div>
    <script src="https://studio.evolutiongaming.com/mobile/js/iframe.js"></script>
    <script>
        EvolutionGaming.init({
            iframeId: "casinoGamesFrame"
        });
    </script>

    <?php

}
else
{
  Redirection::to(Redirection::LOGIN);
}

?>


<script type="application/javascript">

    window.addEventListener("message", function(message) {
        console.log("Evolution Messages: " + JSON.stringify(message.data));
    });

    $(document).ready(function () {
        setInterval(function () {
            updatePlayerBalance(true,"nottoshow");
        },5000);
    });
</script>
