<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$withdrawalDetailsURL = JRoute::_('index.php?task=withdrawal.getWithdrawalDetails');
$cancelWithdrawalURL = JRoute::_('index.php?task=withdrawal.cancelPendingWithdrawal');
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section">
    <div class="heading">
        <h2><?php echo JText::_("WITHDRAWL_STATUS");?></h2>
        <ul class="refer_friend_menu">
            <li><a href="<?php echo Redirection::WITHDRAWAL_PROCESS; ?>"><?php echo JText::_("WITHDRAWL_CASH");?></a></li>
            <li class="active"><a href="<?php echo Redirection::WITHDRAWAL_REQUEST; ?>"><?php echo JText::_("VIEW_STATUS");?></a></li>
			<!--<li ><a href="<?php //echo Redirection::CASHIER_HELP; ?>"><?php //echo JText::_("VIEW_STATUS");?></a></li>-->
        </ul>
    </div>

    <div class="transaction_details">
        <div class="transction_filter">
            <form id="withdrawal-details-form">
                <div class="filter">
                    <div class="form-group">
                        <label><?php echo JText::_("TRANSECTION_FROM");?></label>
                        <div class="input-group date" id="fromdatepicker">
                            <input type="text" class="custome_input" placeholder="Start Date" id="fromDate" name="fromDate" readonly="readonly">
                            <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <div id="error_fromDate" class="manual_tooltip_error error_tooltip"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                </div>
                <div class="filter">
                    <div class="form-group">
                        <label><?php echo JText::_("TRANSECTION_TO");?></label>
                        <div class="input-group date" id="todatepicker">
                            <input type="text" class="custome_input" placeholder="End Date" id="toDate" name="toDate" readonly="readonly" >
                            <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <div id="error_toDate" class="manual_tooltip_error error_tooltip"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="filter">
                    <a class="brown_bg btn_search btnStyle1" id="search" href="javascript:;"><?php echo JText::_("SEARCH");?></a>
                    <div class="clear"></div>
                </div>
            </form>
        </div>

		<div id="error-div" class="alert_msg_div" style="display: none;">
            <div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt">No Withdrawal Details Found For Selected Date Range.</p></div></div>
        </div>
		
        <div class="transaction_table Withdraw_status" id="withdrawal-div" style="display: none">
            <table id="withdrawal-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next">
                <thead>
                <tr>
                    
                    <th data-toggle="true"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO");?>.</th>
                    <th><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID");?></th>
                    <th><?php echo JText::_("REQUEST_DATE");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_AMT");?></th>
                    <th data-hide="phone"><?php echo JText::_("REFRENCE_NO");?>.</th>
                    <th data-hide="phone"><?php echo JText::_("REFRENCE_DATE");?></th>
                    <th data-hide="phone"><?php echo JText::_("TRANSECTION_BONUS_DETAIL_STATUS");?></th>
                    <th><?php echo JText::_("ACTION");?></th>

                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="hide-if-no-paging">
                <tr><td colspan="8"><div class="pagination pagination-centered"></div></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!--redeem_merchandise_confirmation_popup Model-->
<div class="modal fade withdraw_status_popup" id="Cancel_Withdrawal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></button>
            <div class="modal-body">
                <div class="text">
                    <div class="success_title"><?php echo JText::_("WITHDRAWL_CANCEL");?></div>
                    <p><?php echo JText::_("WITHDRAWL_CANCEL_MESSAGE");?></p>
                    <div class="form-group">
                        <button type="button" data-dismiss="modal" id="doWithdrawal" class="btn outlineBtn"><?php echo JText::_("YES_BUTTON");?></button><button type="button" class="btn" data-dismiss="modal" aria-label="Close"><?php echo JText::_("NO_BUTTON");?></button>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--redeem_merchandise_confirmation_popup Model-->


<!--redeem_merchandise_confirmation_popup Model-->
<div class="modal fade withdraw_status_popup" id="Withdrawal_success">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></button>
            <div class="modal-body">
                <div class="text">
                    <h4 class="success_title">Success</h4>
                    <p>Your withdrawal request cancelled successfully</p>
                </div>
                <div class="clear"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--redeem_merchandise_confirmation_popup Model-->

<?php
Html::addJs(JUri::base()."/templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base()."/templates/shaper_helix3/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base()."/templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base()."/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
?>

<script>

    var offset = 0;
    var limit = <?php echo Constants::MAX_ROW_LIMIT; ?>;
    var pageWindow = 5;
    var startPageNo = 1;
    var endPageNo = 5;
    var prevFromDate = '';
    var prevToDate = '';
    var limitReached = false;
    var lastPageNo = 0;
    var transIdToCancel = '';
    var rowToCancel = '';
    var footableToCancel = '';
    var fromPrev = false;

    function checkPrevCall(fromDate, toDate) {

        if(fromDate == prevFromDate && toDate == prevToDate) {
            //return false;
        }

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
        //  $('#toDate').val(current);

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
        //   $('#fromDate').val(defaultDate);
        $('#fromdatepicker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            startDate: '01/01/1900',
            endDate: "0d",
			todayHighlight: true
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
			todayHighlight: true
        });
        $('#todatepicker').datepicker('setStartDate', defaultViewDate);
    });

    $('#search').click(function(event) {
		
		$("#error-div").css("display", "none");

        if(!$('#withdrawal-details-form').valid())
            return false;

        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        if(!checkPrevCall(fromDate, toDate)) {
            return false;
        }

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&offset='+offset+'&limit='+limit;

        startAjax(<?php echo json_encode($withdrawalDetailsURL);?>, params, processWithdrawalDetails, 'null')
    });

    function processWithdrawalDetails(result)
    {
        var tmp_fromPrev = fromPrev;
        fromPrev = false;
        if(validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if(res.errorCode != 0)
        {
            $('#withdrawal-table tbody > tr').remove();
            $('#withdrawal-div').css('display', 'none');
            //error_message(res.respMsg, null);
			$("#error-div .error-div-txt").html(res.respMsg);
            $("#error-div").css("display", "");
            return false;
        }
        if(res.withdrawalList.length <= 0)
        {
            $('#withdrawal-table tbody > tr').remove();
            $('#withdrawal-div').css('display', 'none');
            //error_message("No Withdrawal Details Found For Selected Date Range.", null);
			$("#error-div .error-div-txt").html(Joomla.JText._('WITHDRAWL_NO_DETAIL'));
            $("#error-div").css("display", "");
            return false;
        }

        clearSystemMessage();

        $('#withdrawal-div').css('display', 'block');

        $('#withdrawal-table tbody > tr').remove();

        var totRows = 50;
        limitReached = false;
        lastPageNo = 0;
        if(res.withdrawalList.length <= limit) {
            totRows = res.withdrawalList.length;
            limitReached = true;
        }

        for(var i = 0; i < totRows; i++) {

            var footable = $('#withdrawal-table').data('footable');

            var txnid = '';
            var txndate = '';
            var amount = '';
            var refTxnNo = '';
            var refTxnDate = '';
            var status = '';

            if(typeof res.withdrawalList[i].transactionId != 'undefined')
                txnid = res.withdrawalList[i].transactionId;
            if(typeof res.withdrawalList[i].transactionDate != 'undefined') {
                txndate = res.withdrawalList[i].transactionDate;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
            }
            if(typeof res.withdrawalList[i].amount != 'undefined')
                amount = res.withdrawalList[i].amount;
            if(typeof res.withdrawalList[i].refTxnNo != 'undefined')
                refTxnNo = res.withdrawalList[i].refTxnNo;
            if(typeof res.withdrawalList[i].refTxnDate != 'undefined') {
                refTxnDate = res.withdrawalList[i].refTxnDate;
                var tmp = refTxnDate.lastIndexOf(".");
                refTxnDate = refTxnDate.substring(0, tmp);
            }
            if(typeof res.withdrawalList[i].status != 'undefined')
                status = res.withdrawalList[i].status;

            var cancelIcon = '<a href="javascript:;" style="color: red !important;"><span class="icon-remove-1" trans-id="'+txnid+'"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/cancel_icon.png"></span></a>';

            if(res.withdrawalList[i].status.toUpperCase() != "PENDING") {
                if(res.withdrawalList[i].status.toUpperCase() == "DONE")
                    cancelIcon = '<a><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/done-icon.png"></a>';
                else
                    cancelIcon = '<a style="color: red !important;"><i class="fa fa-ban"></i></a>';
            }

            var newRow = '<tr>' +
                '<td>'+(offset + i + 1)+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td><?php echo $this->CurrData['decSymbol'];?>'+amount+'</td>' +
                '<td>'+refTxnNo+'</td>' +
                '<td>'+refTxnDate+'</td>' +
                '<td>'+status+'</td>' +
                '<td style="text-align: center">'+cancelIcon+'</td>' +
                '</tr>';

            footable.appendRow(newRow);
        }

        $('#withdrawal-table').trigger('footable_redraw');
        if(offset == 0) {
            $('#withdrawal-table').trigger('footable_initialize');
            $('#footer-pagination-div').children().children().first().addClass(' disabled');
            $('#withdrawal-table tfoot').addClass('hide-if-no-paging');
        }
        else {
            $('#withdrawal-table tfoot').removeClass('hide-if-no-paging');
            if(totRows < 10)
                $('#footer-pagination-div').children().children().last().addClass(' disabled');
            resetPageNo();
        }
        if(tmp_fromPrev){
            $("#footer-pagination-div>ul>li").last().prev().children().trigger('click');
        }
    }

    $('#footer-pagination-div').click(function(event) {

        if(limitReached == true && $('li.footable-page.active a').text()==lastPageNo  && $(this).find("li.footable-page.active a").text()!=startPageNo) {
            $('li.footable-page.active').next().addClass(' disabled');
            $(this).children().children().last().removeClass('loadnext');
            if(!$('li.footable-page.active a').prev().hasClass('loadprev'))
                $(this).children().children().first().removeClass('loadprev');
            return;
        } else {
            $(this).children().children().last().removeClass(' disabled');
        }

        if($('li.footable-page.active a').text() == 1){
            $(this).children().children().first().addClass(' disabled');
        } else {
            $(this).children().children().first().removeClass('disabled');
        }

        if($('li.footable-page.active a').text() == endPageNo) {

            $('li.footable-page.active').next().addClass(' loadnext');
            $(this).children().children().first().removeClass('loadprev');

        } else if($('li.footable-page.active a').text() == startPageNo) {

            if($('li.footable-page.active').children().text()!=1)
                $('li.footable-page.active').prev().addClass(' loadprev');
            $(this).children().children().last().removeClass('loadnext');

        } else {

            $(this).children().children().first().removeClass('loadprev');
            $(this).children().children().last().removeClass('loadnext');
        }
    });

    $('#footer-pagination-div').on('click', '.loadnext' , function(event) {

        if(!$('#withdrawal-details-form').valid())
            return false;

        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset + limit;
        startPageNo = startPageNo + pageWindow;
        endPageNo = endPageNo + pageWindow;

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&offset='+offset+'&limit='+limit;

        startAjax(<?php echo json_encode($withdrawalDetailsURL);?>, params, processWithdrawalDetails, 'null')
    });

    $('#footer-pagination-div').on('click', '.loadprev' , function(event) {

        if(!$('#withdrawal-details-form').valid())
            return false;

        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset - limit;
        startPageNo = startPageNo - pageWindow;
        endPageNo = endPageNo - pageWindow;

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&offset='+offset+'&limit='+limit;

        fromPrev = true;
        startAjax(<?php echo json_encode($withdrawalDetailsURL);?>, params, processWithdrawalDetails, 'null')
    });

    function resetPageNo() {
        var pageNo = startPageNo;
        $($('#footer-pagination-div').children().children()).each(function() {
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

    $('#withdrawal-table tbody').on('click', '.icon-remove-1' , function(event) {

        clearSystemMessage();
        footableToCancel = $('#withdrawal-table').data('footable');
        rowToCancel = $(this).parents('tr:first');
//        transIdToCancel = rowToCancel.children().first().next().text();
        transIdToCancel = $(this).attr("trans-id");
        $('#Cancel_Withdrawal').modal('show');
    });

    $('#doWithdrawal').click(function(event) {

        if(transIdToCancel == '' || rowToCancel == '') {
            error_message(Joomla.JText._('SOMTHING_WRONG_MSG'), null);
            return false;
        }

//        var cancel_amount = rowToCancel.children().eq(3).html().replace('<?php echo $this->CurrData['decSymbol'];?>', '');
        var cancel_amount = $("td:contains("+transIdToCancel+")").first().parent().children().eq(3).html().replace('<?php echo $this->CurrData['decSymbol'];?>', '');
        cancel_amount = parseInt(cancel_amount);

        var params = 'transactionId='+transIdToCancel+'&cancelAmount='+cancel_amount;

        startAjax(<?php echo json_encode($cancelWithdrawalURL);?>, params, processWithdrawalCancel, 'null')
    });

    function processWithdrawalCancel(result) {
        if(validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if(res.errorCode != 0)
        {
            error_message(res.respMsg, null);
            return false;
        }

        if($(".cash-balance").length > 0) {
            updateBalance(res.cashBalance);
        }

        footableToCancel.removeRow(rowToCancel);
        var i=1;
        $($("td.footable-first-column")).each(function () {
            $(this).html('<span class="footable-toggle"></span>'+i);
            i++;
        });
        resetPageNo();
//        success_message(res.respMsg);
        $('#Withdrawal_success').modal('show');
    }

    $("#withdrawal-details-form").validate({
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
            }
        }
    });

    $(document).ready(function () {
        //$("#search").trigger('click');
    });
</script>