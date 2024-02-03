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
?>
<section class="pb35" >
    <div class="container">
        <div class="my-profile-title">
            <h1>Balance</h1>
            <p class="sub-title">Champions keep playing until they get their wallet full</p>
        </div>
    </div>
</section>
<section class="pb20">
    <div class="container">
        <div class="balance-row">
            <div class="balance-col amount-balance radius10">
                <div class="balance-price-box">
                    <h1><?php echo Utilities::FormatCurrency($cashBalance,$currencyCode,$dispCurrency)?></h1>
                    <span class="balance-subline-span">Total Balance</span></div>
<!--                <div class="balance-deposit-box"><button class="balance-deposit-button primary-gradient" add_cash_button><img src="images/pages/deposit-plus-icon.png" alt="" />Deposit</button></div>-->
            </div>
            <div class="balance-col amount-bonus radius10" style="display: none;">
                <div class="balance-price-box">
                    <h1><?php echo Utilities::FormatCurrency($bonusBalance,$currencyCode,$dispCurrency)?></h1>
                    <span class="balance-subline-span">Bonus</span></div>
                <div class="balance-deposit-box"><a class="primary-button view-offers-link">View Offers</a></div>
            </div>
            <div class="withdrawal-balance-row radius10">
                <div class="balance-price-box">
                    <h1><?php echo Utilities::FormatCurrency($withdrawalBalance,$currencyCode,$dispCurrency)?></h1>
                    <span class="balance-subline-span">Withdrawable Balance</span></div>
<!--                <div class="balance-deposit-box"><button class="balance-deposit-button secondary-gradient" withdraw_cash_button><img src="images/pages/withdaraw-icon.png" alt="" />Withdaraw</button></div>-->
            </div>
        </div>

        <div class="balance-history-row radius10" id="with_details" style="display: none">
            <h1>Withdrawal Request Details</h1>
            <div class="tableWrap" id="with_txn_table">
                <div class="tableHead">
                    <div class="tableRow">
                        <div class="col col1">
                            <div class="labelName">S.No.</div>
                        </div>
                        <div class="col col2">
                            <div class="labelName">Time</div>
                        </div>
                        <div class="col col3">
                            <div class="labelName">Txn. ID</div>
                        </div>
                        <div class="col col4">
                            <div class="labelName">Status</div>
                        </div>
                        <div class="col col5">
                            <div class="labelName">Amount</div>
                        </div>
                    </div>
                </div>
                <div class="tableBody">

                </div>
            </div>
<!--            <div class="see-more-row"><button class="see-more-button primary-button">See more</button></div>-->
        </div>
    </div>
</section>

<script type="application/javascript">
    var page_nam = "balance";
    var fromDate = '<?php echo Constants::WITHDRAWAL_START_DATE; ?>';
    var toDate = '<?php echo date("Y-m-d"); ?>';

    var offset = 0;
    var limit = 100;

    var currencyCode = "<?php echo $currencyCode ;?>";
    var currencySymbol = "<?php echo $dispCurrency ;?>";

    var decSymbol = '<?php echo $currency_code; ?>';
    var withdrawabalBalance = parseFloat('<?php echo $playerLoginResponse->walletBean->withdrawableBal; ?>');
    var cashBalance = parseFloat('<?php echo $cashBalance ?>');
    var depositOptionsLen = $("#deposit-options tbody tr").length;
    if( depositOptionsLen > 0 ){
        $("#deposit-options").show();
    }

    var withdrawalOptionsLen = $("#withdrawal-options tbody tr").length;


    // setTimeout(function () {
    //     var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'WITHDRAWAL';
    //     startAjax("/component/betting/?task=cashier.getDepositDetails", params, getWithdrawalResponse, null);
    // },1000);


</script>
















