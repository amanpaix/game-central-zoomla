<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$transactionDetailsURL = JRoute::_('index.php?task=transaction.getTransactionDetails');
$bonusDetailsURL = JRoute::_('index.php?task=bonus.getBonusDetails');
$lang = explode("-", JFactory::getLanguage()->getTag())[0];
if($lang == 'th'){
    $transaction_option =   Constants::$txnTypes_TransactionDetails['TH'];
}else if($lang == 'mm'){
    $transaction_option =   Constants::$txnTypes_TransactionDetails['MM'];
}else if($lang == 'fr'){
    $transaction_option =   Constants::$txnTypes_TransactionDetails['FR'];
}else{
    $transaction_option =   Constants::$txnTypes_TransactionDetails['EN'];
}
$currencyInfo = Utilities::getCurrencyInfo();
$currencyCode = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
//$transaction_option = ($lang == 'th') ?  Constants::$txnTypes_TransactionDetails['TH'] : Constants::$txnTypes_TransactionDetails['EN'];
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MYACC_TRANSACTION_DETAILS; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section">
    <div class="entry-header has-post-format">
        <h2>Bonus Details</h2>
    </div>

    <div class="transaction_details">
        <div class="transction_filter">
            <div class="row"><div class="col-md-12 col-sm-12 col-xs-12">
                    <form id="transaction-details-form">
                        <div class="filter">
                            <div class="form-group">
                                <label><?php echo JText::_("TRANSECTION_FROM");?></label>
                                <div class="form_item_holder date">
                                    <div class="input-group date" id="fromdatepicker">
                                        <input type="text" class="custome_input" placeholder="<?php echo JText::_("START_DATE");?>" id="fromDate" name="fromDate" readonly="readonly">
                                        <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                                        <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <div id="error_fromDate" class="manual_tooltip_error error_tooltip"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="filter">
                            <div class="form-group">
                                <label><?php echo JText::_("TRANSECTION_TO");?></label>
                                <div class="form_item_holder date">
                                    <div class="input-group date" id="todatepicker">
                                        <input type="text" class="custome_input" placeholder="<?php echo JText::_("TO_DATE");?>"  id="toDate" name="toDate" readonly="readonly">
                                        <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                                        <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <div id="error_toDate" class="manual_tooltip_error error_tooltip"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="filter select_type" style="display: none">
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
                        <div class="filter">
                            <a class="btn btn_search" href="javascript:;" id="search" >Filter</a>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>

            </div></div>

        <div id="error-div" class="alert_msg_div" style="display: none;">
            <div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"><?php echo JText::_("TRANSECTION_TICKET_NO_DATA_ERROR");?></p></div></div>
        </div>

        <div class="transaction_table" id="transaction-div" style="display: none;">
            <div class="heading" id="closing-balance-div" style="display: none">
                <b id="closing-balance-text" style="font-weight: normal"><?php echo JText::_("CLOSING_BALANCE"); ?>: </b>
                <strong><?php echo $dispCurrency ?> <span id="closing-balance"></span></strong>
            </div>
            <div class="heading" id="bonus-balance-div" style="display: none;"><?php echo JText::_("TRANSECTION_BONUS_CHIP"); ?>: <strong><?php echo $dispCurrency ?> <span id="bonus-chips"><?php echo Utilities::getPlayerLoginResponse()->walletBean->bonusBalance; ?></span></strong>
            </div>
            <table id="transaction-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
                <thead>
                <tr>
                    <th data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO");?>.</th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT");?></th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_P");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_CR");?>.</th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DR");?>.</th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_BALANCE");?></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="7"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
                </tfoot>
            </table>

            <table id="bonus-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
                <thead>
                <tr>
                    <th onclick="sortTable(0, this)" data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT");?></th>
                    <th onclick="sortTable(1, this)" ><?php echo JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?></th>
                    <th onclick="sortTable(2, this)" data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_AMT");?></th>
                    <th onclick="sortTable(3, this)" data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_REQ");?></th>
                    <th onclick="sortTable(4, this)" data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_CONT");?></th>
                    <th onclick="sortTable(5, this)" data-hide="phone,tablet"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_RED");?></th>
                    <th onclick="sortTable(6, this)" data-hide="phone,tablet"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_BOC");?></th>
                    <th onclick="sortTable(7, this)" data-hide="phone,tablet"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_EXPD");?></th>
                    <th onclick="sortTable(8, this)" data-hide="phone,tablet"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_STATUS");?></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="9"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
                </tfoot>
            </table>

            <table id="ticket-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
                <thead>
                <tr>
                    <th data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT");?></th>
                    <th><?php echo JText::_("TRANSECTION_TICKET_DETAIL_TICKET");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_TICKET_DETAIL_TC");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_TICKET_DETAIL_PEN");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_TICKET_DETAIL_EXP");?></th>
                    <th data-hide="phone,tablet"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_STATUS");?></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="6"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
                </tfoot>
            </table>

            <table id="wager-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
                <thead>
                <tr>
                    <th data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO");?>.</th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT");?></th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_P");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_AMT");?></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="5"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
                </tfoot>
            </table>

            <table id="dwwr-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
                <thead>
                <tr>
                    <th data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO");?>.</th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT");?></th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_P");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_AMT");?></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="5"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
                </tfoot>
            </table>

        </div>

    </div>
</div>

<?php
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "/templates/shaper_helix3/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/placeholder.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.scrollbar.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/isotope.pkgd.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/custome.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.tools.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.revolution.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/revolution.extension.slideanims.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/revolution.extension.navigation.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/bootstrap-datepicker.lang.js");

?>

<script>

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

    function autoDefaultTrigger() {
        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val(autoStartDate);
        var toDate = $('#toDate').val(autoEndDate);
        fromDate = autoStartDate;
        toDate = autoEndDate;

        txnType = 'BONUS_DETAILS';
        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;

        if(txnType == "<?php echo Constants::TXNTYPE_BONUS_DETAILS;?>")
            startAjax(<?php echo json_encode($bonusDetailsURL);?>, params, processBonusDetails, 'null');
        else if(txnType == "<?php echo Constants::TXNTYPE_TICKET_DETAILS;?>")
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTicketDetails, 'null');
        else
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTransactionDetails, 'null');
    }

    autoDefaultTrigger();
    function checkPrevCall(txnType, fromDate, toDate) {

        if(txnType == prevTxnType && fromDate == prevFromDate && toDate == prevToDate) {
            //return false;
        }

        prevTxnType = txnType;
        prevFromDate = fromDate;
        prevToDate = toDate;

        offset = 0;
        pageWindow = 5;
        startPageNo = 1;
        endPageNo = 5;
        limitReached = false;
        lastPageNo = 0;
        fromPrev = false;
        return true;
    }

    $(document).ready(function () {
        var d = new Date();
        var year = d.getFullYear();
        if((d.getMonth()+1) < 10)
            var month = "0" + (d.getMonth()+1);
        else
            var month = d.getMonth()+1;

        if(d.getDate() < 10)
            var day = "0" + d.getDate();
        else
            var day = d.getDate();

        var current = day + '/' + month + '/' + year;
//        $('#toDate').val(current);

        var defaultViewDate = new Date(new Date().setDate(new Date().getDate() - 30));
        var defaultViewDate_year = defaultViewDate.getFullYear();
        if((defaultViewDate.getMonth()+1) < 10)
            var defaultViewDate_month = "0" + (defaultViewDate.getMonth()+1);
        else
            var defaultViewDate_month = defaultViewDate.getMonth()+1;

        if(defaultViewDate.getDate() < 10)
            var defaultViewDate_day = "0" + defaultViewDate.getDate();
        else
            var defaultViewDate_day = defaultViewDate.getDate();

        var defaultDate = defaultViewDate_day + '/' + defaultViewDate_month + '/' + defaultViewDate_year;
//        $('#fromDate').val(defaultDate);
        $('#fromdatepicker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            startDate: '01/01/1900',
            endDate: "0d",
            orientation: 'top',
            todayHighlight: true,
            language : '<?php echo $lang; ?>'
        }).on('changeDate', function(e){
            $('#todatepicker').datepicker('setStartDate', e.date);
            if(e.date > $('#todatepicker').datepicker('getDate') && $("#toDate").val() != "" )
                $('#todatepicker').datepicker('setDate', e.date);
        });
        $('#todatepicker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            startDate: '01/01/1900',
            endDate: "0d",
            orientation: 'top',
            todayHighlight: true,
            language : '<?php echo $lang; ?>'
        });
        $('#todatepicker').datepicker('setStartDate', defaultViewDate);
    });

    $('#search').click(function(event) {
        $('#transaction-div').css('display', 'none');
        $("#bonus-table").css("display", "none");
        $("#ticket-table").css("display", "none");
        $("#wager-table").css("display", "none");
        $("#dwwr-table").css("display", "none");
        $("#transaction-table").css("display", "none");
        $("#error-div").css("display", "none");

        if(!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        txnType = 'BONUS_DETAILS';


        if(!checkPrevCall(txnType, fromDate, toDate)) {
            return false;
        }

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;

        if(txnType == "<?php echo Constants::TXNTYPE_BONUS_DETAILS;?>")
            startAjax(<?php echo json_encode($bonusDetailsURL);?>, params, processBonusDetails, 'null');
        else if(txnType == "<?php echo Constants::TXNTYPE_TICKET_DETAILS;?>")
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTicketDetails, 'null');
        else
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTransactionDetails, 'null');
    });

    function processTicketDetails(result)
    {
        var tmp_fromPrev = fromPrev;
        fromPrev = false;
        if(validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if(res.errorCode != 0)
        {
            $('#ticket-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message(res.respMsg, null);
            $("#error-div").html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"></p></div></div>');
            $("#error-div .error-div-txt").html(res.respMsg);
            $("#error-div").css("display", "");
            return false;
        }
        if(res.ticketDetails.length <= 0)
        {
            $('#ticket-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message("No Ticket Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('TRANSECTION_JS_NO_TICKET'));
            $("#error-div").css("display", "");
            return false;
        }

        clearSystemMessage();

        $('#transaction-div').css('display', 'block');
        $("#ticket-table").css("display", "");

        $('#ticket-table tbody > tr').remove();

        var totRows = 50;
        limitReached = false;
        lastPageNo = 0;
        if(res.ticketDetails.length <= limit) {
            totRows = res.ticketDetails.length;
            limitReached = true;
        }

        $('#closing-balance-div').css('display', 'none');
        $('#bonus-balance-div').css('display', 'none');

        for(var i = 0; i < totRows; i++) {
            var footable = $('#ticket-table').data('footable');

            var ticketDate = '';
            var ticket = '';
            var ticketCount = '';
            var pendingTickets = '';
            var expiryDate = '';
            var status = '';

            if(typeof res.ticketDetails[i].receivedDate != 'undefined') {
                ticketDate = res.ticketDetails[i].receivedDate;
                var tmp = ticketDate.lastIndexOf(".");
                ticketDate = ticketDate.substring(0, tmp);
            }
            if(typeof res.ticketDetails[i].ticketCode != 'undefined')
                ticket = res.ticketDetails[i].ticketCode;
            if(typeof res.ticketDetails[i].ticketCount != 'undefined')
                ticketCount = res.ticketDetails[i].ticketCount;
            if(typeof res.ticketDetails[i].pendingTickets != 'undefined')
                pendingTickets = res.ticketDetails[i].pendingTickets;
            if(typeof res.ticketDetails[i].expiredDate != 'undefined') {
                expiryDate = res.ticketDetails[i].expiredDate;
                if(expiryDate.indexOf("3000") != -1) {
                    expiryDate = "NONE";
                }
                else {
                    var tmp = expiryDate.lastIndexOf(".");
                    expiryDate = expiryDate.substring(0, tmp);

                    var tmp2 = expiryDate.split(" ");
                    var tmp_expired_date = tmp2[0].split("-");
                    var tmp_expired_time = tmp2[1];
                    expiryDate = tmp_expired_date[2]+"/"+tmp_expired_date[1]+"/"+tmp_expired_date[0];
                }
            }
            if(typeof res.ticketDetails[i].status != 'undefined')
                status = res.ticketDetails[i].status;

            var newRow = '<tr>' +
                '<td>'+ticketDate+'</td>' +
                '<td>'+ticket+'</td>' +
                '<td>'+ticketCount+'</td>' +
                '<td>'+pendingTickets+'</td>' +
                '<td>'+expiryDate+'</td>' +
                '<td>'+status+'</td>' +
                '</tr>';

            footable.appendRow(newRow);
        }

        $('#ticket-table').trigger('footable_redraw');
        if(offset == 0) {
            $('#ticket-table').trigger('footable_initialize');
            $('.footer-pagination-div').children().children().first().addClass(' disabled');
            $('#ticket-table tfoot').addClass('hide-if-no-paging');
        }
        else {
            $('#ticket-table tfoot').removeClass('hide-if-no-paging');
            if(totRows < 10)
                $('.footer-pagination-div').children().children().last().addClass(' disabled');
            resetPageNo($("#ticket-table .footer-pagination-div"));
        }
        if(tmp_fromPrev){
            $(".footer-pagination-div>ul>li").last().prev().children().trigger('click');
        }
    }

    function processBonusDetails(result)
    {
        var tmp_fromPrev = fromPrev;
        fromPrev = false;
        if(validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if(res.errorCode != 0)
        {
            $('#bonus-table tbody > tr').remove();
            $('#wager-table tbody > tr').remove();
            $('#dwwr-table tbody > tr').remove();
            $('#transaction-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message(res.respMsg, null);
            $("#error-div").html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"></p></div></div>');
            $("#error-div .error-div-txt").html(res.respMsg);
            $("#error-div").css("display", "");
            return false;
        }
        if(res.bonusList.length <= 0)
        {
            $('#transaction-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message("No Bonus Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html('No Transaction Details Found For Selected Date Range.');
            $("#error-div").css("display", "");
            return false;
        }

        clearSystemMessage();

        $('#transaction-div').css('display', 'block');
        $("#bonus-table").css("display", "");
        if(offset == 0) {
//            $('#bonus-chips').text(res.bonusList[0].amount);
        }
        $('#bonus-table tbody > tr').remove();

        var totRows = 50;
        limitReached = false;
        lastPageNo = 0;
        if(res.bonusList.length <= limit) {
            totRows = res.bonusList.length;
            limitReached = true;
        }

        $('#closing-balance-div').css('display', 'none');
        $('#bonus-balance-div').css('display', 'block');

        for(var i = 0; i < totRows; i++) {
            var footable = $('#bonus-table').data('footable');

            var receivedDate = '';
            var bonusCode = '';
            var amount = '';
            var target = '';
            var wrRequirement = '';
            var redeemedAmount = '';
            var bonusCriteria = '';
            var expiredDate = '';
            var status = '';

            if(typeof res.bonusList[i].receivedDate != 'undefined'){
                txndate = res.bonusList[i].receivedDate;
                var tmp = txndate.lastIndexOf(".");
                receivedDate = txndate.substring(0, tmp);
                receivedDate = receivedDate.split(' ');
                dateIndexOne = receivedDate[0];
                const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne)
                dateIndexOne = new Date(dateIndexOne)
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                receivedDate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + receivedDate[1]
            }

            if(typeof res.bonusList[i].bonusCode != 'undefined')
                bonusCode = res.bonusList[i].bonusCode;
            if(typeof res.bonusList[i].amount != 'undefined')
                amount = res.bonusList[i].amount;
            if(typeof res.bonusList[i].target != 'undefined')
                target = res.bonusList[i].target;
            if(typeof res.bonusList[i].contribution != 'undefined')
                wrRequirement = '<?php echo $this->CurrData['decSymbol'];?>'+res.bonusList[i].contribution;
            if(typeof res.bonusList[i].redeemedAmount != 'undefined')
                redeemedAmount = res.bonusList[i].redeemedAmount;
            if(typeof res.bonusList[i].bonusCriteria != 'undefined')
                bonusCriteria = res.bonusList[i].bonusCriteria;

            if(typeof res.bonusList[i].expiredDate != 'undefined') {
                expiredDate = res.bonusList[i].expiredDate;
                if(expiredDate.indexOf("3000") != -1) {
                    expiredDate = "NONE";
                }
                else {
                    var tmp = expiredDate.lastIndexOf(".");
                    expiredDate = expiredDate.substring(0, tmp);

                    var tmp2 = expiredDate.split(" ");
                    var tmp_expired_date = tmp2[0].split("-");
                    var tmp_expired_time = tmp2[1];
                    expiredDate = tmp_expired_date[2]+"/"+tmp_expired_date[1]+"/"+tmp_expired_date[0];
                }
            }
            if(typeof res.bonusList[i].status != 'undefined')
                status = res.bonusList[i].status;

            var newRow = '<tr>' +
                '<td>'+receivedDate+'</td>' +
                '<td>'+bonusCode+'</td>' +
                '<td><?php echo $this->CurrData['decSymbol'];?>'+amount+'</td>' +
                '<td><?php echo $this->CurrData['decSymbol'];?>'+target+'</td>' +
                '<td>'+wrRequirement+'</td>' +
                '<td><?php echo $this->CurrData['decSymbol'];?>'+redeemedAmount+'</td>' +
                '<td>'+bonusCriteria+'</td>' +
                '<td>'+expiredDate+'</td>' +
                '<td>'+status+'</td>' +
                '</tr>';

            footable.appendRow(newRow);
        }

        $('#bonus-table').trigger('footable_redraw');
        if(offset == 0) {
            $('#bonus-table').trigger('footable_initialize');
            $('.footer-pagination-div').children().children().first().addClass(' disabled');
            $('#bonus-table tfoot').addClass('hide-if-no-paging');
        }
        else {
            $('#bonus-table tfoot').removeClass('hide-if-no-paging');
            if(totRows < 10)
                $('.footer-pagination-div').children().children().last().addClass(' disabled');
            resetPageNo($("#bonus-table .footer-pagination-div"));
        }
        if(tmp_fromPrev){
            $(".footer-pagination-div>ul>li").last().prev().children().trigger('click');
        }
    }

    function processTransactionDetails(result)
    {
        var tmp_fromPrev = fromPrev;
        fromPrev = false;
        if(validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if(res.errorCode != 0)
        {
            $('#wager-table tbody > tr').remove();
            $('#dwwr-table tbody > tr').remove();
            $('#transaction-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message(res.respMsg, null);
            $("#error-div").html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"></p></div></div>');
            $("#error-div .error-div-txt").html(res.respMsg);
            $("#error-div").css("display", "");
            return false;
        }
        if(res.txnList.length <= 0)
        {
            $('#wager-table tbody > tr').remove();
            $('#dwwr-table tbody > tr').remove();
            $('#transaction-table tbody > tr').remove();
            $('#transaction-div').css('display', 'none');
            //error_message("No Transaction Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('TRANSECTION_TICKET_NO_DATA_ERROR'));
            $("#error-div").css("display", "");
            return false;
        }

        clearSystemMessage();
        $('#transaction-div').css('display', 'block');
        if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
            $("#wager-table").css("display", "");
        else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
            $("#dwwr-table").css("display", "");
        else
            $("#transaction-table").css("display", "");

        if(offset == 0) {
            if(typeof res.txnList[0].balance != 'undefined') {
                $('#closing-balance-div').css('display', 'block');
                $('#bonus-balance-div').css('display', 'none');
                if($('#txnType').val() == '<?php echo Constants::TXNTYPE_ALL; ?>') {
                    if($(".cash-balance").length > 0 && $("#toDate").val().trim() == '<?php echo date('d/m/Y');?>')
                    {
                        //updateBalance(parseFloat(res.cashBalance).toFixed(2));
                        updateBalance(res.cashBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);
                        updateWithdrawBalance(res.withdrawableBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }


                    $('#closing-balance-text').html(Joomla.JText._('BETTING_CLOSING_BALANCE'));
                    $('#closing-balance').html(formatCurrency(res.txnList[0].balance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_DEPOSIT'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WITHDRAWAL; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WT'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WAGER'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_PERIOD'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WINNING_P'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WITHDRAWL_C'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_BONUS_TRANSFER; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_BONUS_TRANSFER'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
                else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_BO_CORRECTION; ?>') {
                    $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_PAYMENT_CORRECTION'));
                    $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
                }
            }
            else {
                $('#closing-balance-div').css('display', 'none');
                $('#bonus-balance-div').css('display', 'none');
                $('#closing-balance-text').html('');
                $('#closing-balance').html('');
            }
        }
        $('#wager-table tbody > tr').remove();
        $('#dwwr-table tbody > tr').remove();
        $('#transaction-table tbody > tr').remove();

        var totRows = 50;
        limitReached = false;
        lastPageNo = 0;
        if(res.txnList.length <= limit) {
            totRows = res.txnList.length;
            limitReached = true;
        }
        if(totRows < 11) {
            $('#transaction-table tfoot .pagination').css("display", "none");
            $('#dwwr-table tfoot .pagination').css("display", "none");
            $('#wager-table tfoot .pagination').css("display", "none");
            $('#bonus-table tfoot .pagination').css("display", "none");
            $('#ticket-table tfoot .pagination').css("display", "none");
        }
        else{
            $('#transaction-table tfoot .pagination').css("display", "block");
            $('#dwwr-table tfoot .pagination').css("display", "block");
            $('#wager-table tfoot .pagination').css("display", "block");
            $('#bonus-table tfoot .pagination').css("display", "block");
            $('#ticket-table tfoot .pagination').css("display", "block");
        }

        for(var i = 0; i < totRows; i++) {
            if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
                var footable = $('#wager-table').data('footable');
            else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
                var footable = $('#dwwr-table').data('footable');
            else
                var footable = $('#transaction-table').data('footable');

            var txndate = '';
            var txnid = '';
            var particular = '';
            var crAmount = '';
            var dateIndexOne = '';
            var drAmount = '';
            var balance = '';

            var applyColor = "";
            if(typeof res.txnList[i].transactionDate != 'undefined'){
                txndate = res.txnList[i].transactionDate;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
                txndate = txndate.split(' ');
                dateIndexOne = txndate[0];
                const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne)
                dateIndexOne = new Date(dateIndexOne)
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                txndate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + txndate[1]
            }
            if(typeof res.txnList[i].transactionId != 'undefined')
                txnid = res.txnList[i].transactionId;
            if(typeof res.txnList[i].particular != 'undefined')
                particular = res.txnList[i].particular;
            if(typeof res.txnList[i].creditAmount != 'undefined')
                crAmount = formatCurrency(parseFloat(res.txnList[i].creditAmount).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);
            else
                drAmount = formatCurrency(parseFloat(res.txnList[i].debitAmount).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);

            if(typeof res.txnList[i].balance != 'undefined') {
                if(res.txnList[i].subwalletTxn == "NO") {
                    balance = res.txnList[i].balance+'';
                    var tmp = balance.lastIndexOf('.');
                    if(tmp >0) {
                        balance = parseFloat(balance);
                        balance = balance.toFixed(2);
                    }
                    balance = parseFloat(balance).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    balance = formatCurrency(balance,currencyCode, currencySymbol);
                }
                else {
                    balance = '';
                    applyColor = "style='color: blue;'";
                }
            }

            var newRow = '';
            if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
            {
                newRow = '<tr '+applyColor+'>' +
                    '<td>'+(offset + i + 1)+'</td>' +
                    '<td>'+txndate+'</td>' +
                    '<td>'+txnid+'</td>' +
                    '<td>'+particular+'</td>' +
                    '<td>'+drAmount+'</td>' +
                    '</tr>';
            }
            else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
            {
                newRow = '<tr '+applyColor+'>' +
                    '<td>'+(offset + i + 1)+'</td>' +
                    '<td>'+txndate+'</td>' +
                    '<td>'+txnid+'</td>' +
                    '<td>'+particular+'</td>' +
                    '<td>'+crAmount+'</td>' +
                    '</tr>';
            }
            else
            {
                newRow = '<tr '+applyColor+'>' +
                    '<td>'+(offset + i + 1)+'</td>' +
                    '<td>'+txndate+'</td>' +
                    '<td>'+txnid+'</td>' +
                    '<td>'+particular+'</td>' +
                    '<td>'+crAmount+'</td>' +
                    '<td>'+drAmount+'</td>' +
                    '<td>'+balance+'</td>' +
                    '</tr>';
            }

            applyColor = "";
            footable.appendRow(newRow);
        }

        if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
            $('#wager-table').trigger('footable_redraw');
        else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
            $('#dwwr-table').trigger('footable_redraw');
        else
            $('#transaction-table').trigger('footable_redraw');

        if(offset == 0) {
            if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
            {
                $('#wager-table').trigger('footable_initialize');
                $('.footer-pagination-div').children().children().first().addClass(' disabled');
                $('#wager-table tfoot').addClass('hide-if-no-paging');
            }
            else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
            {
                $('#dwwr-table').trigger('footable_initialize');
                $('.footer-pagination-div').children().children().first().addClass(' disabled');
                $('#dwwr-table tfoot').addClass('hide-if-no-paging');
            }
            else
            {
                $('#transaction-table').trigger('footable_initialize');
                $('.footer-pagination-div').children().children().first().addClass(' disabled');
                $('#transaction-table tfoot').addClass('hide-if-no-paging');
            }

        }
        else {
            if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER; ?>')
            {
                $('#wager-table tfoot').removeClass('hide-if-no-paging');
                if(totRows < 10)
                    $('.footer-pagination-div').children().children().last().addClass(' disabled');
                resetPageNo($("#wager-table .footer-pagination-div"));
            }
            else if($('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WAGER_REFUND; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_WINNING; ?>' || $('#txnType').val() == '<?php echo Constants::TXNTYPE_PLR_DEPOSIT; ?>')
            {
                $('#dwwr-table tfoot').removeClass('hide-if-no-paging');
                if(totRows < 10)
                    $('.footer-pagination-div').children().children().last().addClass(' disabled');
                resetPageNo($("#dwwr-table .footer-pagination-div"));
            }
            else
            {
                $('#transaction-table tfoot').removeClass('hide-if-no-paging');
                if(totRows < 10)
                    $('.footer-pagination-div').children().children().last().addClass(' disabled');
                resetPageNo($("#transaction-table .footer-pagination-div"));
            }

        }

        if(tmp_fromPrev){
            $(".footer-pagination-div>ul>li").last().prev().children().trigger('click');
        }
    }

    $('.footer-pagination-div').click(function(event) {
        if(limitReached == true && $(this).find("li.footable-page.active a").text()==lastPageNo && $(this).find("li.footable-page.active a").text()!=startPageNo) {
            $(this).find('li.footable-page.active').next().addClass(' disabled');
            $(this).children().children().last().removeClass('loadnext');
            if(!$(this).find('li.footable-page.active a').prev().hasClass('loadprev'))
                $(this).children().children().first().removeClass('loadprev');
            return;
        } else {
            $(this).children().children().last().removeClass(' disabled');
        }

        if($(this).find('li.footable-page.active a').text() == 1){
            $(this).children().children().first().addClass(' disabled');
        } else {
            $(this).children().children().first().removeClass(' disabled');
        }

        if($(this).find('li.footable-page.active a').text() == endPageNo) {

            $(this).find('li.footable-page.active').next().addClass(' loadnext');
            $(this).children().children().first().removeClass('loadprev');

        } else if($(this).find('li.footable-page.active a').text() == startPageNo) {

            if($(this).find('li.footable-page.active').children().text()!=1)
                $(this).find('li.footable-page.active').prev().addClass(' loadprev');
            $(this).find(this).children().children().last().removeClass('loadnext');

        } else {
            $(this).children().children().first().removeClass('loadprev');
            $(this).children().children().last().removeClass('loadnext');
        }

        if(limitReached) {
            setLastPageNo($(this));
            if($(this).find('li.footable-page.active a').text()==lastPageNo)
                $(this).find('li.footable-page.active').next().addClass(' disabled');
            return;
        }

    });

    $('.footer-pagination-div').on('click', '.loadnext' , function(event) {
        $('#transaction-div').css('display', 'none');
        $("#bonus-table").css("display", "none");
        $("#ticket-table").css("display", "none");
        $("#wager-table").css("display", "none");
        $("#dwwr-table").css("display", "none");
        $("#transaction-table").css("display", "none");

        if(!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset + limit;
        startPageNo = startPageNo + pageWindow;
        endPageNo = endPageNo + pageWindow;

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;

        if(txnType == "<?php echo Constants::TXNTYPE_BONUS_DETAILS; ?>")
            startAjax(<?php echo json_encode($bonusDetailsURL);?>, params, processBonusDetails, 'null');
        else if(txnType == "<?php echo Constants::TXNTYPE_TICKET_DETAILS;?>")
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTicketDetails, 'null');
        else
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTransactionDetails, 'null');
    });

    $('.footer-pagination-div').on('click', '.loadprev' , function(event) {
        $('#transaction-div').css('display', 'none');
        $("#bonus-table").css("display", "none");
        $("#transaction-table").css("display", "none");
        $("#ticket-table").css("display", "none");
        $("#wager-table").css("display", "none");
        $("#dwwr-table").css("display", "none");

        if(!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset - limit;
        startPageNo = startPageNo - pageWindow;
        endPageNo = endPageNo - pageWindow;

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;

        fromPrev = true;
        if(txnType == "<?php echo Constants::TXNTYPE_BONUS_DETAILS; ?>")
            startAjax(<?php echo json_encode($bonusDetailsURL);?>, params, processBonusDetails, 'null');
        else if(txnType == "<?php echo Constants::TXNTYPE_TICKET_DETAILS;?>")
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTicketDetails, 'null');
        else
            startAjax(<?php echo json_encode($transactionDetailsURL);?>, params, processTransactionDetails, 'null');
    });

    function resetPageNo(obj) {
        var pageNo = startPageNo;
        $(obj.children().children()).each(function() {
            if($(this).children().attr('data-page') == "prev"){
                $(this).addClass(' loadprev');
            }
            if($(this).children().attr('data-page') != "prev" && $(this).children().attr('data-page') != "next")
            {
                $(this).children().text(pageNo);
                if(limitReached == true)
                    lastPageNo = pageNo;
                pageNo++;
            }
        });
    }


    function setLastPageNo(obj) {
        var pageNo = startPageNo;
        $(obj.children().children()).each(function() {
            if($(this).children().attr('data-page') != "prev" && $(this).children().attr('data-page') != "next") {
                if(limitReached == true)
                    lastPageNo = pageNo;
                pageNo++;
            }
        });
    }

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg != value;
    }, "Value must not equal arg.");

    $("#transaction-details-form").validate({
        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            if($(".datepicker.datepicker-dropdown.dropdown-menu").css("display") == "block")
                removeToolTipErrorManual('all');
        },
        rules: {
            fromDate: {
                required: true,
                dateITA : true
            },
            toDate: {
                required: true,
                dateITA : true
            },
            txnType: {
                required:true,
                valueNotEquals: "",
            }
        },
        messages: {
            fromDate: {
                required: Joomla.JText._('TRANSECTION_TICKET_DETAIL_FEOM_BLANK_ERROR'),
                dateITA : Joomla.JText._('TRANSECTION_TICKET_DETAIL_FROM_BLANK')
            },
            toDate: {
                required: Joomla.JText._('TRANSECTION_TICKET_DETAIL_TO_BLANK_ERROR'),
                dateITA: Joomla.JText._('TRANSECTION_TICKET_DETAIL_FROM_BLANK')
            },
            txnType: {
                valueNotEquals: Joomla.JText._('TRANSECTION_TICKET_DETAIL_TRAN_TYPR'),
                required :Joomla.JText._('TRANSECTION_TICKET_DETAIL_TRAN_TYPR')
            }
        }
    });

    $("#txnType").on('change', function () {
        $('#transaction-div').css('display', 'none');
        $("#transaction-table").css("display", "none");
        $('#transaction-table tbody > tr').remove();
        $("#bonus-table").css("display", "none");
        $('#bonus-table tbody > tr').remove();
        $("#ticket-table").css("display", "none");
        $('#ticket-table tbody > tr').remove();
        $("#wager-table").css("display", "none");
        $('#wager-table tbody > tr').remove();
        $("#dwwr-table").css("display", "none");
        $('#dwwr-table tbody > tr').remove();
    });

    $(document).ready(function () {
        // $("#search").trigger('click');
    });
</script>

<script>
    function sortTable(n, $this) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("bonus-table");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = $('#bonus-table tbody tr').get();
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 0; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if( isNaN(x.innerText.toLowerCase()) ){
                        if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                    else{
                        console.log(parseInt(x.innerText.toLowerCase()),parseInt(y.innerText.toLowerCase()));
                        if (parseInt(x.innerText.toLowerCase()) > parseInt(y.innerText.toLowerCase())) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }

                } else if (dir == "desc") {
                    if( isNaN(x.innerText.toLowerCase()) ){
                        if (x.innerText.toLowerCase() < y.innerText.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                    else
                    {
                        if (parseInt(x.innerText.toLowerCase()) < parseInt(y.innerText.toLowerCase())) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }

                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount ++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
        $($this).parent().find('th').removeClass('sort-asc');$($this).parent().find('th').removeClass('sort-desc');
        $($this).addClass('sort-'+dir);
    }
</script>
