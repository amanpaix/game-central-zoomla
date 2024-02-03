<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$transactionDetailsURL = "/component/betting/?task=transaction.getTransactionDetails";


$playerInfo = Utilities::getPlayerLoginResponse();
$currencyInfo = Utilities::getCurrencyInfo();
$currencyCode = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];

$transaction_option =   Constants::$txnTypes_TransactionDetails['EN'];
?>

<section class="pb35" >
    <div class="container">
        <div class="my-profile-title">
            <h1>My Transactions</h1>
            <p class="sub-title">Here trust is the highest priority and we promise to deliver it</p>
        </div>
    </div>
</section>
<section class="pb20">
    <div class="container">
        <div class="transaction-date-row radius10">
            <div class="transaction-date-range-tabs">
                <button class="date-range-tab active" id="last10txn">Last 10 Txn.</button>
                <button class="date-range-tab" id="last20txn" >Last 20 Txn.</button>
                <button class="date-range-tab" id="lastweektxn">Last Week Txn.</button>
                <button class="date-range-tab" id="lastmonthtxn">Last Month Txn.</button>
                <button class="date-range-tab " id="customtxn">Custom</button>
            </div>
            <div class="transaction-date-range-view" style="display: none;"><span class="date-label">Select Date Range</span>
                <form id="transaction-details-form">
                    <div class="range-date-picker form_item_holder date">
                        <div class="input-group date" data-provide="datepicker" id="fromdatepicker">
                            <input type="text" class="form-control" name="fromDate" id="fromDate" readonly="readonly" autocomplete="off" >
                            <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                            <div id="error_fromDate" class="manual_tooltip_error error_tooltip"></div>
                        </div>
                        <div class="input-group date" data-provide="datepicker" id="todatepicker" >
                            <input type="text" class="form-control"  name="toDate" id="toDate" readonly="readonly" autocomplete="off" >
                            <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                            <div id="error_toDate" class="manual_tooltip_error error_tooltip"></div>
                        </div>
                    </div>
                    <div class="filter select_type">
                        <div class="form-group">
                            <label><?php echo JText::_("TRANSECTION_TYPE");?></label>
                            <div class="select_box">
                                <?php // $transaction_option = ($lang == 'th') ? $txnTypes_TransactionDetails['TH'] : $txnTypes_TransactionDetails['EN']; ?>
                                <select class="custome_input" id="txnType" name="txnType" tabindex="10">
                                    <option value="" disabled selected><?php echo JText::_("SELECT");?></option>
                                    <?php
                                    foreach($transaction_option AS $key => $value)
                                    {
                                        ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div id="error_txnType" class="manual_tooltip_error error_tooltip"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="transaction-range-buttons">
                        <button class="range-search-button primary-gradient" type="button" id="search"> <?php echo JText::_("SEARCH");?></button>
                        <button type="reset" id="reset" class="range-reset-button primary-button">Reset</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="mainDiv" class="balance-history-row radius10" style="display: none">
            <h2 class="transaction-title" id="txn_date_span" style="display: none">Transaction Details: <span >17 Dec 2022 to 18 Dec 2022</span></h2>
            <div id="error-div" class="alert_msg_div" style="display: none;">
                <div class="alert alert-danger alert-dismissible " role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div>
                        <p class="error-div-txt"><?php echo JText::_("TRANSECTION_TICKET_NO_DATA_ERROR");?></p>
                    </div>
                </div>
            </div>
            <div id="mainDivContent"  class="tableWrap" style="display: none;">
                <div class="tableHead" >
                    <div class="tableRow">
<!--                        <div class="col col1">-->
<!--                            <div class="labelName">Sr.No.</div>-->
<!--                        </div>-->
                        <div class="col col2">
                            <div class="labelName">Time</div>
                        </div>
                        <div class="col col3">
                            <div class="labelName">Details</div>
                        </div>
                        <div class="col col4">
                            <div class="labelName">Txn. ID</div>
                        </div>
                        <div class="col col5">
                            <div class="labelName">Amount</div>
                        </div>
                    </div>
                </div>
                <div class="tableBody">

                </div>
            </div>
        </div>
    </div>
</section>




<?php
Html::addJs("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js");
Html::addCss("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/core/transaction.js");

?>

<script type="application/javascript">
    var todayDate = '<?php echo date('d/m/Y');?>';
    var autoStartDate = '<?php echo date('d/m/Y',strtotime("-1 month")); ?>';
    var autoEndDate = '<?php echo date('d/m/Y'); ?>';
    var offset = 0;
    var limit = <?php echo Constants::MAX_ROW_LIMIT; ?>;
    var pageWindow = 5;
    var startPageNo = 1;
    var endPageNo = 5;
    var prevTxnType = '';
    var prevFromDate = '';
    var prevToDate = '';
    var limitReached = false;
    var lastPageNo = 0;
    var fromPrev = false;
    var currencyCode = "<?php echo $currencyCode ;?>";
    var currencySymbol = "<?php echo $dispCurrency ;?>";


</script>
