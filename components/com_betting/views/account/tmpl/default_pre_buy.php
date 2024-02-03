<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

$playerLoginResponse = Utilities::getPlayerLoginResponse();
$CurrData=Configuration::getCurrencyDetails();
$cashBalance = number_format((float)$playerLoginResponse->walletBean->cashBal,2);
$bonusBalance = number_format((float)$playerLoginResponse->walletBean->bonusBal,2);
$withdrawalBalance = number_format((float)$playerLoginResponse->walletBean->withdrawableBal,2);
$currencyInfo = Utilities::getCurrencyInfo();
$currencyCode = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
$currency_code =  $playerLoginResponse->walletBean->currency;

$url = Configuration::BINGO_PRE_BUY . "5/" . Utilities::getPlayerId() ."/". Utilities::getPlayerToken() ."/". Utilities::getPlayerToken();
?>
<div class="entry-header has-post-format">
    <div class="my-acc-title mb-5">
        <h1>Pre Buy Tickets</h1>
        <p class="sub-title">Check details of your pre buy tickets</p>
    </div>
</div>


<iframe id="pre_buy_container" src="<?php echo $url; ?>"  width="100%" height="500px" ></iframe>





<script>

    window.addEventListener("message", message => {
        try {
            const parsedData = JSON.parse(message.data);
            console.log(parsedData);
            $("#pre_buy_container").css("height",parsedData+"px");
        } catch (e) {}
    });

</script>












