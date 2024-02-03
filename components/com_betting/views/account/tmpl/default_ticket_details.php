<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$transactionDetailsURL = "/component/betting/?task=transaction.getTransactionDetails";
$playerId = Utilities::getPlayerID();
$playerToken = Utilities::getPlayerToken();
$playerInfo = Utilities::getPlayerLoginResponse();
$lang = explode("-", JFactory::getLanguage()->getTag())[0];
$currencyInfo = Utilities::getCurrencyInfo();
$currencyCode = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MYACC_TRANSACTION_DETAILS; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section">
    <div class="entry-header has-post-format">
        <div class="my-acc-title mb-5">
            <h1>Ticket Details</h1>
            <p class="sub-title">Check details of your played tickets</p>
        </div>
    </div>

    <div class="transaction_details">
        <div class="transction_filter">
            <div class="row"><div class="col-md-12 col-sm-12 col-xs-12">
                    <form id="transaction-details-form">
                        <div class="filter">
                            <div class="form-group">
                                <label><?php echo JText::_("TRANSECTION_FROM"); ?></label>
                                <div class="form_item_holder date">
                                    <div class="input-group date" id="fromdatepicker">
                                        <input type="text" class="custome_input" placeholder="<?php echo JText::_("START_DATE"); ?>" id="fromDate" name="fromDate" readonly="readonly">
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
                                <label><?php echo JText::_("TRANSECTION_TO"); ?></label>
                                <div class="form_item_holder date">
                                    <div class="input-group date" id="todatepicker">
                                        <input type="text" class="custome_input" placeholder="<?php echo JText::_("TO_DATE"); ?>"  id="toDate" name="toDate" readonly="readonly">
                                        <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                                        <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <div id="error_toDate" class="manual_tooltip_error error_tooltip"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="filter">
                            <a class="btn btn_search" href="javascript:;" id="search" ><?php echo JText::_("SEARCH"); ?></a>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>

            </div></div>

        <div id="error-div" class="alert_msg_div" style="display: none;">
            <div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"><?php echo JText::_("TRANSECTION_TICKET_NO_DATA_ERROR"); ?></p></div></div>
        </div>
    </div>

    <div class="transaction_table" id="transaction-div" style="display: none;">
        <div class="heading" id="closing-balance-div" style="display: none">
            <b id="closing-balance-text" style="font-weight: normal"><?php echo JText::_("CLOSING_BALANCE"); ?>: </b>
            <strong><?php echo $dispCurrency; ?> <span id="closing-balance"></span></strong>
        </div>
        <div class="heading" id="bonus-balance-div" style="display: none;"><?php echo JText::_("TRANSECTION_BONUS_CHIP"); ?>: <strong><?php echo $dispCurrency; ?> <span id="bonus-chips"><?php echo Utilities::getPlayerLoginResponse()->walletBean->bonusBalance; ?></span></strong>
        </div>
    </div>

    <div class="row whiteBackground" id="tktContainer" style="display:none;">
        <div class="col-xs-12 col-sm-12 col-md-12" >
            <div class="row" id="myticket">
                <!--                        <div class="col-sm-6">
                                            <div class="myTicketOuterWrap">
                                                <a id="DGE-704315" onclick="//showTicket('DGE','704315','0')" title="Click For Details">
                                                    <div class="myTicketInnerWrap drawGameTicket">
                                                        <div class="myTicketInnerWrap1">
                                                            <div class="ticketGameName">Bonus Lotto </div>
                                                            <div class="transactionIDTitle">Transaction ID</div>
                                                            <div class="transactionIDNum">704315</div>
                                                            <div class="transactionTimeName">Transaction Time</div>
                                                            <div class="transactionTimeNum">11-08-2019 09:04:25</div>
                                                            <div class="ticketResultSymbol">
                                                                <img src="/images/sadfaceTicNone.png" alt="Loader">
                                                            </div>
                                                          <div class="ticketPrice"><span class="currency">$</span>1</div><input type="hidden" id="DGE-704315-amount" value="1">
                                                        </div>
                                                        <div class="myTicketInnerWrap2">
                                                            <div class="gameTypeIconTic"><img src="/images/drawIconTicket.png" alt="Draw Games"></div>
                                                            <div class="gameTypeNameTic">Draw Games</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>-->
                <!--                        <div class="col-sm-6">
                                            <div class="myTicketOuterWrap">
                                                <a id="IGE-703072" onclick="/*showTicket('IGE','703072','0')*/" title="Click For Details">
                                                    <div class="myTicketInnerWrap iwgTicket">
                                                        <div class="myTicketInnerWrap1">
                                                            <div class="ticketGameName">Instant Win</div>
                                                            <div class="transactionIDTitle">Transaction ID</div>
                                                            <div class="transactionIDNum">703072</div>
                                                            <div class="transactionTimeName">Transaction Time</div>
                                                            <div class="transactionTimeNum">09-08-2019 14:15:25<input type="hidden" id="IGE-703072-time" value="09-08-2019 14:15:25"></div>
                                                            <div class="ticketResultSymbol">

                                                                <img src="/images/waitwatchTic.png" alt="Loader">
                                                            </div>
                                                            <div class="ticketPrice"><span class="currency">$</span>0.<span class="currencyChange">50</span></div><input type="hidden" id="IGE-703072-amount" value="0.5">														 </div>
                                                        <div class="myTicketInnerWrap2">
                                                            <div class="gameTypeIconTic"><img src="/images/iwgIconTicket.png" alt="Instant win"></div>
                                                            <div class="gameTypeNameTic">Instant win</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>-->
            </div>
        </div>
    </div>
    <!--            <table id="ticket-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next" style="display: none">
        <thead>
        <tr>
            <th data-toggle="true"><?php //echo JText::_("TRANSECTION_DETAIL_TABLE_DT"); ?></th>
            <th><?php //echo JText::_("TRANSECTION_TICKET_DETAIL_TICKET"); ?></th>
            <th data-hide="phone"><?php //echo JText::_("TRANSECTION_TICKET_DETAIL_TC"); ?></th>
            <th data-hide="phone"><?php //echo JText::_("TRANSECTION_TICKET_DETAIL_PEN"); ?></th>
            <th data-hide="phone"><?php //echo JText::_("TRANSECTION_TICKET_DETAIL_EXP"); ?></th>
            <th data-hide="phone,tablet"><?php //echo JText::_("TRANSECTION_BONUS_DETAIL_STATUS"); ?></th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot class="hide-if-no-paging">
        <tr><td colspan="6"><div class="pagination pagination-centered footer-pagination-div" id="footer-pagination-div"></div></td></tr>
        </tfoot>
    </table>-->


</div>

<?php
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "/templates/shaper_helixultimate/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js");

?>

<script>

    var autoStartDate = '<?php echo date('d/m/Y',strtotime("-1 week")); ?>';
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

    function autoDefaultTrigger() {
        var txnType = $('#txnType').val("ticket");
        var fromDate = $('#fromDate').val(autoStartDate);
        var toDate = $('#toDate').val(autoEndDate);
        fromDate = autoStartDate;
        toDate = autoEndDate;
        txnType = "ticket";

        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&txnType=' + txnType + '&offset=' + offset + '&limit=' + limit;
        startAjax(<?php echo json_encode($transactionDetailsURL); ?>, params, processTicketDetails, 'null');
    }


    function checkPrevCall(txnType, fromDate, toDate) {

        if (txnType == prevTxnType && fromDate == prevFromDate && toDate == prevToDate) {
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
        setTimeout(function () {
            autoDefaultTrigger();
        },1000);

        var d = new Date();
        var year = d.getFullYear();
        if ((d.getMonth() + 1) < 10)
            var month = "0" + (d.getMonth() + 1);
        else
            var month = d.getMonth() + 1;

        if (d.getDate() < 10)
            var day = "0" + d.getDate();
        else
            var day = d.getDate();

        var current = day + '/' + month + '/' + year;
//        $('#toDate').val(current);

        var defaultViewDate = new Date(new Date().setDate(new Date().getDate() - 30));
        var defaultViewDate_year = defaultViewDate.getFullYear();
        if ((defaultViewDate.getMonth() + 1) < 10)
            var defaultViewDate_month = "0" + (defaultViewDate.getMonth() + 1);
        else
            var defaultViewDate_month = defaultViewDate.getMonth() + 1;

        if (defaultViewDate.getDate() < 10)
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
        }).on('changeDate', function (e) {
            $('#todatepicker').datepicker('setStartDate', e.date);
            if (e.date > $('#todatepicker').datepicker('getDate') && $("#toDate").val() != "")
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
    });

    $('#search').click(function (event) {
        //$('#transaction-div').css('display', 'none');
        $("#tktContainer").css("display", "none");
        $("#error-div").css("display", "none");

        if (!$('#transaction-details-form').valid())
            return false;

        var txnType = "ticket";
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();



        if (!checkPrevCall(txnType, fromDate, toDate)) {
            return false;
        }

        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&txnType=' + txnType + '&offset=' + offset + '&limit=' + limit;
        startAjax(<?php echo json_encode($transactionDetailsURL); ?>, params, processTicketDetails, 'null');

    });

    function processTicketDetails(result)
    {
        document.getElementById("myticket").innerHTML = "";
        var tmp_fromPrev = fromPrev;
        fromPrev = false;
        if (validateSession(result) == false)
            return false;
        var res = JSON.parse(result);
        if (res.errorCode != 0)
        {
            // $('#ticket-table tbody > tr').remove();
            //$('#transaction-div').css('display', 'none');
            //error_message(res.respMsg, null);
            $("#error-div").html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><div><p class="error-div-txt"></p></div></div>');
            $("#error-div .error-div-txt").html(res.respMsg);
            $("#error-div").css("display", "");
            return false;
        }
        if (res.ticketList.length <= 0)
        {
            //$('#ticket-table tbody > tr').remove();
            //$('#transaction-div').css('display', 'none');
            error_message("No Ticket Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('TRANSECTION_JS_NO_TICKET'));
            $("#error-div").css("display", "");
            return false;
        }

        clearSystemMessage();

//        $('#transaction-div').css('display', 'block');
//        $("#ticket-table").css("display", "");
        $("#tktContainer").css("display", "");
//
//        $('#ticket-table tbody > tr').remove();
//
        var totRows = 50;
        limitReached = false;
        lastPageNo = 0;
        if (res.ticketList.length <= limit) {
            totRows = res.ticketList.length;
            limitReached = true;
        }
        console.log(res);
//
//        $('#closing-balance-div').css('display', 'none');
//        $('#bonus-balance-div').css('display', 'none');
        for (var i = 0; i < totRows; i++) {
            // var footable = $('#myticket').data('footable');

            var amount = '';
            var gameId = '';
            var gameName = '';
            var transactionDate = '';
            var dateIndexOne = '';
            var gameType = '';

            if (typeof res.ticketList[i].transactionDate != 'undefined') {
                transactionDate = res.ticketList[i].transactionDate;
                var tmp = transactionDate.lastIndexOf(".");
                transactionDate = transactionDate.substring(0, tmp);
                transactionDate = transactionDate.split(' ');
                dateIndexOne = transactionDate[0];
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne)
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;
                transactionDate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + transactionDate[1]
            }
            if (typeof res.ticketList[i].transactionId != 'undefined')
                gameId = res.ticketList[i].transactionId;
            if (typeof res.ticketList[i].gameName != 'undefined')
                gameName = res.ticketList[i].gameName;
            if (typeof res.ticketList[i].amount != 'undefined')
                amount = res.ticketList[i].amount;
            if (typeof res.ticketList[i].gameType != 'undefined')
                gameType = res.ticketList[i].gameType;
            // var wrraper = document.createElement('div');
            var ticketDomain = "<?php echo Configuration::DOMAIN ?>";
            var player_id = "<?php echo $playerId ?>";
            var player_name = '<?php echo $playerInfo->userName; ?>';
            var balance = '<?php echo (float) $playerInfo->walletBean->totalBalance; ?>';
            var session_id = "<?php echo $playerToken ?>";
            var currency = '<?php echo $this->CurrData["curCode"]; ?>';
            var symbol = "<?php echo $currencyCode ?>";
            var dispsymbol = "<?php echo $dispCurrency ?>";
            var alias = '<?php echo Configuration::DOMAIN_NAME; ?>';
            var lang = '<?php echo $lang; ?>';
            var serviceCode = res.ticketList[i].serviceCode;
            var ticketURL = "";
            var url = "";
            var end = "";
            var gameImage = gameName;
            var gameText = gameName;
            if (serviceCode == "DGE") {
                ticketURL = ticketDomain + "/view-ticket#dge,0," + res.ticketList[i].transactionId;
                url = '<a href="' + ticketURL + '" target="_blank" title="Click For Details">';
                end = '</a>';
            } else if (serviceCode == "SLE") {
                ticketURL = ticketDomain + "/view-ticket#sle,0," + res.ticketList[i].transactionId;
                url = '<a href="' + ticketURL + '" target="_blank" title="Click For Details">';
                end = '</a>';
//                gameImage = 'sportsLottery';
//                gameText = 'Sports Lottery';
            } else {
                ticketURL = "";
            }
            ticketURL = "";
            url = "";
            end = "";
            if(gameName == 'Lucky 6 Prime')
                gameImage = 'logo-luckySix';

            if(gameName == 'Lucky Number+ 5/90'){
                gameImage = 'logo-luckyNumber';
            }
            if(gameName == 'PowerPlay 12/24'){
                gameImage = 'PowerPlay';
            }
            if(gameName.toLowerCase() == 'sabanzuri lotto'){
                gameImage = 'SABANZURI';
            }
            if(gameName.toLowerCase() == 'lucky twelve'){
                gameImage = 'PowerPlay';
            }
            gameImage = "gameName";
            var newRow =
                `<div class="col-sm-6">
                <div class="myTicketOuterWrap">` + url +
                `<div class="myTicketInnerWrap drawGameTicket">
                <div class="myTicketInnerWrap1">
                <div class="ticketGameName">` + gameName + `</div>
                <div class="transactionIDTitle">Transaction ID</div>
                <div class="transactionIDNum">` + gameId + `</div>
                <div class="transactionTimeName">Transaction Time</div>
                <div class="transactionTimeNum">` + transactionDate + `</div>

                <div class="ticketPrice"><span class="currencyChange">` + formatCurrency((parseFloat(amount).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),symbol,dispsymbol) +`</span><span class="currency"></span></div><input type="hidden" id="DGE-657937-amount" value="` + (parseFloat(amount).toFixed(2)) + `"></div>
                <div class="myTicketInnerWrap2">
                <div class="gameTypeIconTic"><img src="images/gamelogo/` + gameImage + `.png"" alt="` + gameName + `"></div>
                <div class="gameTypeNameTic">` + gameText + `</div>
                </div>
                </div>
                ` + end + `</div></div>`;
            var container = document.getElementById("myticket");
            container.innerHTML += newRow;

        }

    }


    $('.footer-pagination-div').click(function (event) {
        if (limitReached == true && $(this).find("li.footable-page.active a").text() == lastPageNo && $(this).find("li.footable-page.active a").text() != startPageNo) {
            $(this).find('li.footable-page.active').next().addClass(' disabled');
            $(this).children().children().last().removeClass('loadnext');
            if (!$(this).find('li.footable-page.active a').prev().hasClass('loadprev'))
                $(this).children().children().first().removeClass('loadprev');
            return;
        } else {
            $(this).children().children().last().removeClass(' disabled');
        }

        if ($(this).find('li.footable-page.active a').text() == 1) {
            $(this).children().children().first().addClass(' disabled');
        } else {
            $(this).children().children().first().removeClass(' disabled');
        }

        if ($(this).find('li.footable-page.active a').text() == endPageNo) {

            $(this).find('li.footable-page.active').next().addClass(' loadnext');
            $(this).children().children().first().removeClass('loadprev');

        } else if ($(this).find('li.footable-page.active a').text() == startPageNo) {

            if ($(this).find('li.footable-page.active').children().text() != 1)
                $(this).find('li.footable-page.active').prev().addClass(' loadprev');
            $(this).find(this).children().children().last().removeClass('loadnext');

        } else {
            $(this).children().children().first().removeClass('loadprev');
            $(this).children().children().last().removeClass('loadnext');
        }

        if (limitReached) {
            setLastPageNo($(this));
            if ($(this).find('li.footable-page.active a').text() == lastPageNo)
                $(this).find('li.footable-page.active').next().addClass(' disabled');
            return;
        }

    });

    $('.footer-pagination-div').on('click', '.loadnext', function (event) {
        $('#transaction-div').css('display', 'none');
        $("#bonus-table").css("display", "none");
        $("#ticket-table").css("display", "none");
        $("#wager-table").css("display", "none");
        $("#dwwr-table").css("display", "none");
        $("#transaction-table").css("display", "none");

        if (!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset + limit;
        startPageNo = startPageNo + pageWindow;
        endPageNo = endPageNo + pageWindow;

        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&txnType=' + txnType + '&offset=' + offset + '&limit=' + limit;
        startAjax(<?php echo json_encode($transactionDetailsURL); ?>, params, processTicketDetails, 'null');

    });

    $('.footer-pagination-div').on('click', '.loadprev', function (event) {
        $('#transaction-div').css('display', 'none');
        $("#bonus-table").css("display", "none");
        $("#transaction-table").css("display", "none");
        $("#ticket-table").css("display", "none");
        $("#wager-table").css("display", "none");
        $("#dwwr-table").css("display", "none");

        if (!$('#transaction-details-form').valid())
            return false;

        var txnType = $('#txnType').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        offset = offset - limit;
        startPageNo = startPageNo - pageWindow;
        endPageNo = endPageNo - pageWindow;

        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&txnType=' + txnType + '&offset=' + offset + '&limit=' + limit;

        fromPrev = true;
//        if(txnType == "<?php //echo Constants::TXNTYPE_BONUS_DETAILS;  ?>")
//            startAjax(<?php //echo json_encode($bonusDetailsURL); ?>, params, processBonusDetails, 'null');
//        else if(txnType == "<?php //echo Constants::TXNTYPE_TICKET_DETAILS; ?>")
        startAjax(<?php echo json_encode($transactionDetailsURL); ?>, params, processTicketDetails, 'null');
//        else
//            startAjax(<?php //echo json_encode($transactionDetailsURL); ?>, params, processTransactionDetails, 'null');
    });

    function resetPageNo(obj) {
        var pageNo = startPageNo;
        $(obj.children().children()).each(function () {
            if ($(this).children().attr('data-page') == "prev") {
                $(this).addClass(' loadprev');
            }
            if ($(this).children().attr('data-page') != "prev" && $(this).children().attr('data-page') != "next")
            {
                $(this).children().text(pageNo);
                if (limitReached == true)
                    lastPageNo = pageNo;
                pageNo++;
            }
        });
    }


    function setLastPageNo(obj) {
        var pageNo = startPageNo;
        $(obj.children().children()).each(function () {
            if ($(this).children().attr('data-page') != "prev" && $(this).children().attr('data-page') != "next") {
                if (limitReached == true)
                    lastPageNo = pageNo;
                pageNo++;
            }
        });
    }

    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $("#transaction-details-form").validate({
        showErrors: function (errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            if ($(".datepicker.datepicker-dropdown.dropdown-menu").css("display") == "block")
                removeToolTipErrorManual('all');
        },
        rules: {
            fromDate: {
                required: true,
                dateITA: true
            },
            toDate: {
                required: true,
                dateITA: true
            }
        },
        messages: {
            fromDate: {
                required: Joomla.JText._('TRANSECTION_TICKET_DETAIL_FEOM_BLANK_ERROR'),
                dateITA: Joomla.JText._('TRANSECTION_TICKET_DETAIL_FROM_BLANK')
            },
            toDate: {
                required: Joomla.JText._('TRANSECTION_TICKET_DETAIL_TO_BLANK_ERROR'),
                dateITA: Joomla.JText._('TRANSECTION_TICKET_DETAIL_FROM_BLANK')
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
