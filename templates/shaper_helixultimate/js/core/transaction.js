var $ = jQuery.noConflict();
var currFromDate = "";
var currToDate = "";
var masterCnt = 0;

const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
];


$(window).scroll(function() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {

        var txnType = $('#txnType').val() == null ? "ALL" : $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        offset = offset + limit;
        if (masterCnt >= offset) {

            var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit+"&callCat=LAZY";

            if(txnType == "BONUS_DETAILS")
                startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processBonusDetails, 'null');
            else if(txnType == "ticket")
                startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processTicketDetails, 'null');
            else
                startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processTransactionDetails, 'null');
        }

    }
});

$(document).ready(function () {



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
        orientation: 'top',
        todayHighlight: true
    });
    $('#todatepicker').datepicker('setStartDate', defaultViewDate);


    $('#search').click(function(event) {
        $('#transaction-div').css('display', 'none');
        $("#error-div").css("display", "none");

        if(!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();


        // if(!checkPrevCall(txnType, fromDate, toDate)) {
        //     return false;
        // }

        var params = 'fromDate='+fromDate+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;

        if(txnType == "BONUS_DETAILS")
            startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processBonusDetails, 'null');
        else if(txnType == "ticket")
            startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processTicketDetails, 'null');
        else
            startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processTransactionDetails, 'null');

        return false;
    });


    $("#reset").on('click', function () {
        try{
            $("#transaction-details-form")[0].reset();
        }
        catch (e){

        }

    });

    $(".date-range-tab").on('click', function () {
        $(".date-range-tab").removeClass('active');
        $(".balance-history-row").hide();
        $("#reset").trigger('click');
        $("#error-div").empty();
        $("#fromDate").val('');
        $("#toDate").val('');
        $(this).addClass('active');
        offset = 0;
        limit = 50;
        masterCnt=0;
        if( $(this).attr('id') == "customtxn" ){
            $(".transaction-date-range-view").show();
        }
        else {
            $(".transaction-date-range-view").hide();
        }
    });

    $("#last10txn").on('click', function () {
        triggerTxnDetails('','','LAST10',0,10);
    });

    $("#last20txn").on('click', function () {
        triggerTxnDetails('','','LAST20',0,20);
    });

    $("#lastweektxn").on('click', function () {
        var startDate = new Date();
        startDate.setDate(startDate.getDate() - 7);
        startDate = ((startDate.getDate()) < 10 ? '0' : '') + startDate.getDate() + "/" + ((startDate.getMonth() + 1) < 10 ? '0' : '') + (startDate.getMonth() + 1) + "/" + startDate.getFullYear();
        var toDate = new Date();
        toDate = ((toDate.getDate()) < 10 ? '0' : '') + toDate.getDate() + "/" + ((toDate.getMonth() + 1) < 10 ? '0' : '') + (toDate.getMonth() + 1) + "/" + toDate.getFullYear();

        $("#fromDate").val(startDate);
        $("#toDate").val(toDate);
        triggerTxnDetails(startDate,toDate,'ALL',offset,limit);
    });

    $("#lastmonthtxn").on('click', function () {
        var startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);
        startDate = ((startDate.getDate()) < 10 ? '0' : '') + startDate.getDate() + "/" + ((startDate.getMonth() + 1) < 10 ? '0' : '') + (startDate.getMonth() + 1) + "/" + startDate.getFullYear();
        var toDate = new Date();
        toDate = ((toDate.getDate()) < 10 ? '0' : '') + toDate.getDate() + "/" + ((toDate.getMonth() + 1) < 10 ? '0' : '') + (toDate.getMonth() + 1) + "/" + toDate.getFullYear();


        $("#fromDate").val(startDate);
        $("#toDate").val(toDate);
        triggerTxnDetails(startDate,toDate,'ALL',offset,limit);
    });
    setTimeout(function () {
        $("#last10txn").trigger('click');
    },1000);

});

function triggerTxnDetails(fromD,toDate,txnType,offset,limit)
{
    $('#transaction-div').css('display', 'none');
    $("#error-div").css("display", "none");
    $("mainDivContent .tableBody").empty();

    var params = 'fromDate='+fromD+'&toDate='+toDate+'&txnType='+txnType+'&offset='+offset+'&limit='+limit;
    startAjax("/component/betting/?task=transaction.getTransactionDetails", params, processTransactionDetails, 'null');
}

function processTransactionDetails(result)
{
    var tmp_fromPrev = fromPrev;
    fromPrev = false;

    currFromDate = $("#fromDate").val();
    currToDate = $("#toDate").val();

    if(validateSession(result) == false)
        return false;
    var res = JSON.parse(result);
    if(res.errorCode != 0)
    {
        $('#transaction-div').css('display', 'none');
        $("#txn_date_span").hide();
        $("#txn_date_span span").empty();
        $("#mainDivContent").hide();
        //error_message(res.respMsg, null);
        $("#error-div").html('<div class="alert alert-danger alert-dismissible" role="alert">\n' +
            '                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>\n' +
            '                    <div>\n' +
            '                        <p class="error-div-txt"></p>\n' +
            '                    </div>\n' +
            '                </div>');
        $("#error-div .error-div-txt").html(res.respMsg);
        $("#error-div").css("display", "");
        $("#mainDiv").css("display", "");
        return false;
    }
    if(res.txnList.length <= 0)
    {
        $("#txn_date_span").hide();
        $("#txn_date_span span").empty();
        $("#mainDivContent").hide();
        //error_message("No Transaction Details Found For Selected Date Range.", null);
        $("#error-div .error-div-txt").html(Joomla.JText._('TRANSECTION_TICKET_NO_DATA_ERROR'));
        $("#error-div").css("display", "");
        $('#mainDiv').show();
        return false;
    }

    clearSystemMessage();
    $("#mainDivContent").show();

    var fromDateStr = currFromDate.split("/");
        fromDateStr = fromDateStr[0] + " " + monthNames[parseInt(fromDateStr[1])-1] + " " + fromDateStr[2];

    var toDateStr = currToDate.split("/");
        toDateStr = toDateStr[0] + " " + monthNames[parseInt(toDateStr[1])-1] + " " + toDateStr[2];

    if(res.callType == "AUTO" ){
        $("#txn_date_span").hide();
        $("#txn_date_span span").empty();
    }
    else
    {
        $("#txn_date_span span").html(fromDateStr + " to " + toDateStr);
        $("#txn_date_span").show();
    }


    if($('#txnType').val() == 'PLR_WAGER')
        $("#wager-table").css("display", "");
    else if($('#txnType').val() == 'PLR_WAGER_REFUND' || $('#txnType').val() == 'PLR_WINNING' || $('#txnType').val() == 'PLR_DEPOSIT')
        $("#dwwr-table").css("display", "");
    else
        $("#transaction-table").css("display", "");

    if(offset == 0) {
        if(typeof res.txnList[0].balance != 'undefined') {
            $('#closing-balance-div').css('display', 'block');
            $('#bonus-balance-div').css('display', 'none');
            if($('#txnType').val() == 'ALL') {
                if($(".cash-balance").length > 0 && $("#toDate").val().trim() == todayDate)
                {
                    //updateBalance(parseFloat(res.cashBalance).toFixed(2));
                    //updateBalance(res.cashBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);
                    //updateWithdrawBalance(res.withdrawableBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }


                $('#closing-balance-text').html(Joomla.JText._('BETTING_CLOSING_BALANCE'));
                $('#closing-balance').html(formatCurrency(res.txnList[0].balance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_DEPOSIT') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_DEPOSIT'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_WITHDRAWAL') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WT'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_WAGER') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WAGER'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_WAGER_REFUND') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_PERIOD'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_WINNING') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WINNING_P'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_DEPOSIT_AGAINST_CANCEL') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_WITHDRAWL_C'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'PLR_BONUS_TRANSFER') {
                $('#closing-balance-text').html(Joomla.JText._('TRANSECTION_JS_TOTAL_BONUS_TRANSFER'));
                $('#closing-balance').html(formatCurrency(res.txnTotalAmount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol));
            }
            else if($('#txnType').val() == 'BO_CORRECTION') {
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
    if( res.callCat !== "LAZY" ){
        $('#wager-table tbody > tr').remove();
        $('#dwwr-table tbody > tr').remove();
        $('#transaction-table tbody > tr').remove();
        $("#mainDivContent .tableBody").empty();
    }


    var totRows = 50;
    limitReached = false;
    lastPageNo = 0;
    if(res.txnList.length <= limit) {
        totRows = res.txnList.length;
        limitReached = true;
    }
    if(totRows <= 10){
        $('#transaction-table tfoot .pagination').css("display", "none");
        $('#dwwr-table tfoot .pagination').css("display", "none");
        $('#wager-table tfoot .pagination').css("display", "none");
    }else {
        $('#transaction-table tfoot .pagination').css("display", "block");
        $('#dwwr-table tfoot .pagination').css("display", "block");
        $('#wager-table tfoot .pagination').css("display", "block");
    }
    for(var i = 0; i < totRows; i++) {
        masterCnt++;
        var txndate = '';
        var txnid = '';
        var particular = '';
        var crAmount = '';
        var dateIndexOne = '';
        var drAmount = '';
        var balance = '';

        var txnDayMonth = '';
        var txnYearTime = '';

        var applyColor = "";
        try {
            if(typeof res.txnList[i].transactionDate != 'undefined'){
                txndate = res.txnList[i].transactionDate;
                // var tmp = txndate.lastIndexOf(".");
                // txndate = txndate.substring(0, tmp);
                txndate = txndate.split(' ');
                dateIndexOne = txndate[0];
                const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne)
                dateIndexOne = new Date(dateIndexOne)
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                txndate = dateIndexOne.getFullYear() + " " + txndate[1];

                txnDayMonth = date + " " + months[dateIndexOne.getMonth()];
                txnYearTime = dateIndexOne.getFullYear();

            }
        }
        catch (e) {

        }

        if(typeof res.txnList[i].transactionId != 'undefined')
            txnid = res.txnList[i].transactionId;
        if(typeof res.txnList[i].particular != 'undefined'){
            particular = res.txnList[i].particular;
            //particular = particular.replace(/_/g, '');
        }
        if(typeof res.txnList[i].creditAmount != 'undefined')
            crAmount = formatCurrency(parseFloat(res.txnList[i].creditAmount).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);
        else
            drAmount = formatCurrency(parseFloat(res.txnList[i].txnAmount).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),currencyCode, currencySymbol);

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


        var finalAmount = "";
        var amountClass = "success-text";
        if( crAmount != '' ){
            finalAmount = "+ "+crAmount;
            amountClass = "success-text";
        }
        else {
            finalAmount = "- "+drAmount;
            amountClass = "error-text";
        }

        var finalParticular = "";
        try {
            particular = particular.split("_");
            if( particular.length > 1 )
                finalParticular = '<label class="table-heading-label">'+ particular[0] +'</label> <span class="table-heading-span">'+ particular[1] +'</span>';
            else
                finalParticular = '<label class="table-heading-label">'+ particular[0] +'</label> <span class="table-heading-span"></span>';
        }
        catch (e) {
            finalParticular = '<span class="">'+ particular +'</span>';
        }

        var newRow = '';
        if($('#txnType').val() == 'PLR_WAGER')
        {
            newRow = '<tr '+applyColor+'>' +
                '<td>'+(offset + i + 1)+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>'+particular+'</td>' +
                '<td>'+drAmount+'</td>' +
                '</tr>';

            newRow = '<div class="tableRow">\n' +
                // '                        <div class="col col1">\n' +
                // '                            <span class="">'+ (offset + i + 1) +'</span>\n' +
                // '                        </div>\n' +
                '                        <div class="col col2">\n' +
                '                            <div class="table-inner-data"><label class="table-heading-label">'+ txnDayMonth +'</label> <span class="table-heading-span">'+ txndate +'</span></div>\n' +
                '                        </div>\n' +
                '                        <div class="col col3">\n' +
                '                            <div class="table-inner-data">'+ finalParticular +'</div>\n' +
                '                        </div>\n' +
                '                        <div class="col col4">\n' +
                '                            <p class="table-heading-p">'+txnid+'</p>\n' +
                '                        </div>\n' +
                '                        <div class="col col5">\n' +
                '                            <div class="table-inner-data"><label class="'+ amountClass +' table-heading-label ">'+ finalAmount +'</label> <span class="table-heading-span">'+ balance +'</span></div>\n' +
                '                        </div>\n' +
                '                    </div>';
        }
        else if($('#txnType').val() == 'PLR_WAGER_REFUND' || $('#txnType').val() == 'PLR_WINNING' || $('#txnType').val() == 'PLR_DEPOSIT')
        {
            newRow = '<tr '+applyColor+'>' +
                '<td>'+(offset + i + 1)+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>'+particular+'</td>' +
                '<td>'+crAmount+'</td>' +
                '</tr>';

            newRow = '<div class="tableRow">\n' +
                // '                        <div class="col col1">\n' +
                // '                            <span class="">'+ (offset + i + 1) +'</span>\n' +
                // '                        </div>\n' +
                '                        <div class="col col2">\n' +
                '                            <div class="table-inner-data"><label class="table-heading-label">'+ txnDayMonth +'</label> <span class="table-heading-span">'+ txndate +'</span></div>\n' +
                '                        </div>\n' +
                '                        <div class="col col3">\n' +
                '                            <div class="table-inner-data">'+ finalParticular +'</div>\n' +
                '                        </div>\n' +
                '                        <div class="col col4">\n' +
                '                            <p class="table-heading-p">'+txnid+'</p>\n' +
                '                        </div>\n' +
                '                        <div class="col col5">\n' +
                '                            <div class="table-inner-data"><label class="'+ amountClass +' table-heading-label ">'+ finalAmount +'</label> <span class="table-heading-span">'+ balance +'</span></div>\n' +
                '                        </div>\n' +
                '                    </div>';
        }
        else
        {
            newRow = '<tr '+applyColor+'>' +
                // '<td>'+(offset + i + 1)+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>'+particular+'</td>' +
                '<td>'+crAmount+'</td>' +
                '<td>'+drAmount+'</td>' +
                '<td>'+balance+'</td>' +
                '</tr>';

            newRow = '<div class="tableRow">\n' +
                // '                        <div class="col col1">\n' +
                // '                            <span class="">'+ (offset + i + 1) +'</span>\n' +
                // '                        </div>\n' +
                '                        <div class="col col2">\n' +
                '                            <div class="table-inner-data"><label class="table-heading-label">'+ txnDayMonth +'</label> <span class="table-heading-span">'+ txndate +'</span></div>\n' +
                '                        </div>\n' +
                '                        <div class="col col3">\n' +
                '                            <div class="table-inner-data">'+ finalParticular +'</div>\n' +
                '                        </div>\n' +
                '                        <div class="col col4">\n' +
                '                            <p class="table-heading-p">'+txnid+'</p>\n' +
                '                        </div>\n' +
                '                        <div class="col col5">\n' +
                '                            <div class="table-inner-data"><label class="'+ amountClass +' table-heading-label ">'+ finalAmount +'</label> <span class="table-heading-span">'+ balance +'</span></div>\n' +
                '                        </div>\n' +
                '                    </div>';
        }

        applyColor = "";
        // footable.appendRow(newRow);
        $("#mainDivContent .tableBody").append(newRow);
    }

    if($('#txnType').val() == 'PLR_WAGER')
        $('#wager-table').trigger('footable_redraw');
    else if($('#txnType').val() == 'PLR_WAGER_REFUND' || $('#txnType').val() == 'PLR_WINNING' || $('#txnType').val() == 'PLR_DEPOSIT')
        $('#dwwr-table').trigger('footable_redraw');
    else
        $('#transaction-table').trigger('footable_redraw');

    if(offset == 0) {
        if($('#txnType').val() == 'PLR_WAGER')
        {
            $("#mainDiv").show();
        }
        else if($('#txnType').val() == 'PLR_WAGER_REFUND' || $('#txnType').val() == 'PLR_WINNING' || $('#txnType').val() == 'PLR_DEPOSIT')
        {
            $("#mainDiv").show();
        }
        else
        {
            // $('#transaction-table').trigger('footable_initialize');
            // $('.footer-pagination-div').children().children().first().addClass(' disabled');
            // $('#transaction-table tfoot').addClass('hide-if-no-paging');
            $("#mainDiv").show();
        }

    }
    else {
        // if($('#txnType').val() == 'PLR_WAGER')
        // {
        //     $('#wager-table tfoot').removeClass('hide-if-no-paging');
        //     if(totRows < 10)
        //         $('.footer-pagination-div').children().children().last().addClass(' disabled');
        //     resetPageNo($("#wager-table .footer-pagination-div"));
        // }
        // else if($('#txnType').val() == 'PLR_WAGER_REFUND' || $('#txnType').val() == 'PLR_WINNING' || $('#txnType').val() == 'PLR_DEPOSIT')
        // {
        //     $('#dwwr-table tfoot').removeClass('hide-if-no-paging');
        //     if(totRows < 10)
        //         $('.footer-pagination-div').children().children().last().addClass(' disabled');
        //     resetPageNo($("#dwwr-table .footer-pagination-div"));
        // }
        // else
        // {
        //     $('#transaction-table tfoot').removeClass('hide-if-no-paging');
        //     if(totRows < 10)
        //         $('.footer-pagination-div').children().children().last().addClass(' disabled');
        //     resetPageNo($("#transaction-table .footer-pagination-div"));
        // }

    }

    if(tmp_fromPrev){
        // $(".footer-pagination-div>ul>li").last().prev().children().trigger('click');
    }
}
