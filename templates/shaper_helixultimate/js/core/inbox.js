var $ = jQuery.noConflict();
var offset = 0;
var limit;
var pageWindow = 5;
var startPageNo = 1;
var endPageNo = 5;
var prevTxnType = '';
var prevFromDate = '';
var prevToDate = '';
var limitReached;
var lastPageNo = 0;
var fromPrev = false;

var totRows = 50;
var sent_action = '';
var sent_content_id = '';
var sent_msg_ids = '';

var currentMessageCount = 0;
var currentDeleteMessageCount = 0;

$(document).ready(function () {
    limit = INBOX_LIMIT;
    limitReached = print_limit_reached;
});

function updateMsgStatus(action, content_id, msg_ids)
{
    clearSystemMessage();
    var unreadCount = 0;
    if (action == "DELETE") {
        var tmp = msg_ids.split("&");
        msg_ids = tmp[0];
        unreadCount = tmp[1];
    }
    sent_action = action;
    sent_content_id = content_id;
    sent_msg_ids = msg_ids;

    var params = "activity=" + action + "&msgId=" + msg_ids;

    if (action == "DELETE") {
        if (lastPageNo == startPageNo && limitReached && currentMessageCount == currentDeleteMessageCount) {
            offset = offset - totRows;
            startPageNo = startPageNo - pageWindow;
        }
        params += "&offset=" + offset + "&limit=" + limit + "&unreadCount=" + unreadCount;
    }
    startAjax(INBOX_ACTIVITY, params, getInboxActivityResponse, 'null');
}

function getInboxActivityResponse(result)
{
    if (validateSession(result) == false)
        return false;
    var res = JSON.parse(result);
    if (res.errorCode != 0)
    {
        if (sent_action == 'DELETE') {
            if (res.errorCode == 501) {
                offset = 0;
                limit = INBOX_LIMIT;
                pageWindow = 5;
                startPageNo = 1;
                endPageNo = 5;
                prevTxnType = '';
                prevFromDate = '';
                prevToDate = '';
                limitReached = print_limit_reached;
                lastPageNo = 0;
                fromPrev = false;

                totRows = 50;
                sent_action = '';
                sent_content_id = '';
                sent_msg_ids = '';
            }
            $("#main-content-div").css("display", "none");
            $("#main-content-div>div").css("display", "none");
            $("#main_inbox>table").css('display', '');

            $('#inbox-table-footable > tbody > tr').remove();
            $('#inbox-table-footable > thead').remove();
            $('#inbox-table-footable > tbody').html('<tr><td colspan="3" class="text-center" style="border: none;height: 100px;font-size: 13px;"><div class="mail_item">' + Joomla.JText._('NO_MESSAGE_FOUND') + '</div></td></tr>');
            $("div.search_box").css('display', "none");
            $("div.mail_actionbtn").css('display', "none");
            $("div.mail_actionbtn_inner").css('display', "none");
            // $("#select_all").attr("disabled", "disabled");
            $("#delete_main").css('display', 'none');
           // $(".selectAll").css('display', 'none');
            $('#inbox-table-footable > tfoot').css('display', 'none');
            $("input[name='mail_select']").iCheck("uncheck");
            //$(".selectAll").css('display', 'none');
            updateInboxCount(0);
            return false;
        }
        error_message(res.respMsg, null);
        return false;
    }

    updateInboxCount(res.unreadMsgCount);
    if (sent_action == "READ") {
        $("#main_inbox>table").css('display', 'none');
        $("#main-content-div").css('display', 'block');
        $("#main-content-div>div").css('display', 'none');
        $("[msg-id-child='" + sent_msg_ids + "']").first().css('display', 'block');
        $("[msg-id-parent='" + sent_msg_ids + "']").parents(".mail_item").removeClass('unread');

        $("div.mail_actionbtn").css("display", "none");
        $("div.search_box").css("display", "none");
        //$(".selectAll").css("display", "none");
        $("div.mail_actionbtn_inner").css("display", "block");
    } else {
        $("#main_inbox>table").css("display", "");
        $("#main-content-div").css("display", "none");
        $("#main-content-div>div").css("display", "none");

        $("div.mail_actionbtn_inner").css("display", "none");
        $("div.mail_actionbtn").css("display", "");
        $("div.search_box").css("display", "");
        //$(".selectAll").css("display", "");

        getInboxResponse(result);
    }
}


function getInboxResponse(result)
{
    $("input[name='mail_select']").iCheck("uncheck");
   // $("#select_all").iCheck("uncheck");

    var tmp_fromPrev = fromPrev;
    fromPrev = false;

    if (validateSession(result) == false)
        return false;
    var res = JSON.parse(result);
    if (res.errorCode != 0)
    {
        error_message(res.respMsg, null);
        return false;
    }

    if (res.messages.data.plrInboxList.length == 0) {
        error_message(Joomla.JText._('NO_MESSAGE_FOUND'), null);
        return false;
    }

    $('#inbox-table-footable > tbody > tr').remove();
    $("#main-content-div").html('');

    var totRows = 50;
    limitReached = false;
    lastPageNo = 0;
    if (res.messages.data.plrInboxList.length < limit) {
        totRows = res.messages.data.plrInboxList.length;
        limitReached = true;
    }

    for (var i = 0; i < totRows; i++) {
        if (typeof res.messages.data.plrInboxList[i].inboxId == 'undefined') {
            continue;
        }
        var date = res.messages.data.plrInboxList[i].mailSentDate;
        var mailSentDate = date.split(' ');
        var dateIndexOne = mailSentDate[0].split("/").reverse().join("/");
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        dateIndexOne = new Date(dateIndexOne);
        var mailSent = dateIndexOne.getDate();
        mailSent = mailSent.toString().length == 1 ? "0" + '' + mailSent : mailSent;
        var mailDate = months[dateIndexOne.getMonth()] + " " + mailSent + ", " + dateIndexOne.getFullYear() + " " + mailSentDate[1];
        var footable = $('#inbox-table-footable').data('footable');
        var newRow = "<tr><td><div class='mail_item " + (res.messages.data.plrInboxList[i].status.toLowerCase()) + "'>" +
                "<div class='mail_checkbox'><label><input type='checkbox' name='mail_select' select-one-msg-id='" + res.messages.data.plrInboxList[i].inboxId + "'><span class='chkIcon'></span></label></div>" +
                "<a href='javascript:void(0);' module-id-parent='" + res.messages.data.plrInboxList[i].inboxId + "' msg-id-parent='" + res.messages.data.plrInboxList[i].inboxId + "'><div class='mail_shortinfo'><div class='mail_title'>" + res.messages.data.plrInboxList[i].subject + "<span class='mail_date'>" + mailDate + "</span></div></div></a>" +
                "</div></td></tr>";

        footable.appendRow(newRow);
    }
    $('#inbox-table-footable').trigger('footable_redraw');
    $('#inbox-table-footable th').css("display", "");
    $('#inbox-table-footable td').css("display", "");

    if (offset == 0) {
        $('#inbox-table-footable').trigger('footable_initialize');
        $('#footer-pagination-div').children().children().first().addClass(' disabled');
        $('#inbox-table-footable > tfoot').addClass('hide-if-no-paging');
    } else {
        $('#inbox-table-footable > tfoot').removeClass('hide-if-no-paging');
        $('#footer-pagination-div').children().children().first().removeClass(' disabled');
        if (totRows <= 10)
            $('#footer-pagination-div').children().children().last().addClass(' disabled');
        resetPageNo();
    }

    if (tmp_fromPrev) {
        $("#footer-pagination-div>ul>li").last().prev().children().trigger('click');
    }

    if (limitReached && lastPageNo > pageWindow && startPageNo == lastPageNo)
        $('#footer-pagination-div').children().children().first().removeClass('disabled');

    $("input[type='checkbox']").iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%'
    });

    for (var i = 0; i < totRows; i++) {
        var date = res.messages.data.plrInboxList[i].mailSentDate
        var mailSentDate = date.split(' ');
        var dateIndexOne = mailSentDate[0].split("/").reverse().join("/");
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        dateIndexOne = new Date(dateIndexOne);
        var mailSent = dateIndexOne.getDate();
        mailSent = mailSent.toString().length == 1 ? "0" + '' + mailSent : mailSent;
        var mailDate = months[dateIndexOne.getMonth()] + " " + mailSent + ", " + dateIndexOne.getFullYear() + " " + mailSentDate[1];
        var newDiv = '<div class="inbox_inner" style="display: none" module-id-child="' + res.messages.data.plrInboxList[i].inboxId + '" msg-id-child="' + res.messages.data.plrInboxList[i].inboxId + '">' +
                '<div class="mail_heading">' + res.messages.data.plrInboxList[i].subject + ' <span class="mail_date">' + mailDate + '</span></div>' +
                '<div class="inbox_mailer">' + res.messages.data.plrInboxList[i].contentId + '</div>' +
                '</div>';

        $("#main-content-div").append(newDiv);
        appendEvents(1);
        appendEvents(0);
        $("input[name='mail_select']").iCheck('uncheck');
        //$('#select_all').iCheck('uncheck');
    }

    if (limitReached && startPageNo == 1 && endPageNo == 1)
        $('#footer-pagination-div').hide();
    else
        $('#footer-pagination-div').show();
}

function resetPageNo() {
    var pageNo = startPageNo;
    $($('#footer-pagination-div').children().children()).each(function () {
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

function updateInboxCount(count)
{
    updateMessageCount(count);
    if(count == 0)
    {
        document.title = "Inbox";
        $("#mail_count_view").html("");
    }
    else
        document.title = "Inbox ("+count+")";
}

function appendEvents(type)
{
    if (type == 1) {
        //$("#select_all").off('ifClicked');
        $("[select-one-msg-id]").off('ifClicked');
        $("#delete_main").off('click');
        $("[delete_msg]").off('click');
        $("a[msg-id-parent]").off('click');
        $(".back_btn").off('click');
    } else {
//        $("#select_all").on('ifClicked', function () {
//            if ($(this).parent().hasClass("checked")) {
//                $(this).iCheck('uncheck');
//                $($("div.icheckbox_square-green")).each(function () {
//                    if ($(this).children().first().attr("id") != "select_all") {
//                        $(this).iCheck('uncheck');
//                    }
//                });
//            } else {
//                $(this).iCheck('check');
//                $($("div.icheckbox_square-green")).each(function () {
//                    if ($(this).children().first().attr("id") != "select_all" && $(this).parents('tr').css("display") !== "none") {
//                        $(this).iCheck('check');
//                    }
//                });
//            }
//        });
//
//        $("[select-one-msg-id]").on('ifClicked', function (event) {
//            if ($(this).parent().hasClass("checked")) {
//                $(this).parent().removeClass("checked");
//                $("#select_all").iCheck('uncheck');
//            } else {
//                $(this).iCheck('check');
//                var unchecked_found = false;
//                $($("div.icheckbox_square-green")).each(function () {
//                    if ($(this).children().first().attr("id") != "select_all" && $(this).parents('tr').css("display") !== "none" && $(this).hasClass("checked") == false) {
//                        unchecked_found = true;
//                    }
//                });
//                if (!unchecked_found)
//                    $("#select_all").iCheck('check');
//            }
//        });

        $("#delete_main").click(function () {
            var checked_found = false;
            var ids = "";
            var unreadCount = 0;
            currentMessageCount = $("[select-one-msg-id]").length;
            currentDeleteMessageCount = 0;
            $($("[select-one-msg-id]")).each(function () {
                if ($(this).prop('checked') == true) {
                    currentDeleteMessageCount++;
                    checked_found = true;
                    ids += $(this).attr("select-one-msg-id") + "AND";

                    if ($(this).parent().parent().next().hasClass('unread')) {
                        unreadCount++;
                    }
                }
                //}
            });

            if (checked_found == false) {
                error_message(Joomla.JText._('BETTING_JS_CHECK_MSG_ERROR'), null);
                return false;
            }
            ids = ids.substring(0, (ids.length - 3));
            ids = ids + "&" + unreadCount;
            updateMsgStatus('DELETE', '', ids);
        });

        $("[delete_msg]").on('click', function () {
            updateMsgStatus('DELETE', '', $(this).attr("delete_msg") + "&0");
        });

        $("a[msg-id-parent]").click(function () {
            clearSystemMessage();
            $("[delete_msg]").attr("delete_msg", $(this).attr("msg-id-parent"));
            $("input[name='mail_select']").iCheck("uncheck");

            if ($(this).parents(".mail_item").hasClass('unread')) {
                updateMsgStatus('READ', $(this).attr('module-id-parent'), $(this).attr('msg-id-parent'));
            } else {
                $("#main_inbox>table").css("display", "none");
                $("#main-content-div").css("display", "block");
                $("#main-content-div>div").css("display", "none");
                $("[msg-id-child='" + $(this).attr('msg-id-parent') + "']").first().css('display', 'block');

                $("div.mail_actionbtn").css("display", "none");
                //$(".selectAll").css("display", "none");
                $("div.search_box").css("display", "none");
                $("div.mail_actionbtn_inner").css("display", "block");
            }
        });

        $(".back_btn").click(function() {
            $("#main-content-div").css("display", "none");
            $("#main-content-div>div").css("display", "none");
            $("#main_inbox>table").css('display', '');

            $("div.mail_actionbtn").css("display", "block");
            //$(".selectAll").css("display", "block");
            $("div.search_box").css("display", "block");
            $("div.mail_actionbtn_inner").css("display", "none");
        });
    }
}


$(document).ready(function () {

    // $("#filter").on('keyup', function () {
    //   if( $("#inbox-table-footable tbody tr:hidden").length == $("#inbox-table-footable tbody tr").length ){
    //         $("#filter-not-found").show();
    //   }
    //   else{
    //       $("#filter-not-found").hide();
    //   }
    // });

    $('#footer-pagination-div').click(function (event) {
        clearSystemMessage();
//         $("#select_all").iCheck('check');
//        var unchecked_found = false;
//        $($("div.icheckbox_square-green")).each(function () {
//            if ($(this).children().first().attr("id") != "select_all" && $(this).parents('tr').css("display") !== "none" && $(this).hasClass("checked") == false) {
//                unchecked_found = true;
//            }
//        });
//        if (unchecked_found) {
//            $("#select_all").iCheck('uncheck');
//            $($("div.icheckbox_square-green")).each(function () {
//                if ($(this).children().first().attr("id") != "select_all") {
//                    $(this).iCheck('uncheck');
//                }
//            });
//        }

        if (limitReached == true && $('li.footable-page.active a').text() == lastPageNo && $(this).find("li.footable-page.active a").text() != startPageNo) {
            $('li.footable-page.active').next().addClass(' disabled');
            $(this).children().children().last().removeClass('loadnext');
            if (!$('li.footable-page.active a').prev().hasClass('loadprev'))
                $(this).children().children().first().removeClass('loadprev');
            return;
        } else {
            $(this).children().children().last().removeClass(' disabled');
        }

        if ($('li.footable-page.active a').text() == 1) {
            $(this).children().children().first().addClass(' disabled');
        } else {
            $(this).children().children().first().removeClass(' disabled');
        }

        if ($('li.footable-page.active a').text() == endPageNo) {

            $('li.footable-page.active').next().addClass(' loadnext');
            $(this).children().children().first().removeClass('loadprev');

        } else if ($('li.footable-page.active a').text() == startPageNo) {

            if ($('li.footable-page.active').children().text() != 1)
                $('li.footable-page.active').prev().addClass(' loadprev');
            $(this).children().children().last().removeClass('loadnext');

        } else {
            $(this).children().children().first().removeClass('loadprev');
            $(this).children().children().last().removeClass('loadnext');
        }
    });

    $('#footer-pagination-div').on('click', '.loadnext', function (event) {
        clearSystemMessage();
        offset = offset + limit;
        startPageNo = startPageNo + pageWindow;
        endPageNo = endPageNo + pageWindow;

        offset = offset - 1;
        var params = 'offset=' + offset + '&limit=' + limit;
        startAjax(PLAYER_INBOX, params, getInboxResponse, 'null');
    });

    $('#footer-pagination-div').on('click', '.loadprev', function (event) {
        clearSystemMessage();
        offset = offset - limit;
        startPageNo = startPageNo - pageWindow;
        endPageNo = endPageNo - pageWindow;

        offset = offset + 1;
        var params = 'offset=' + offset + '&limit=' + limit;

        fromPrev = true;
        startAjax(PLAYER_INBOX, params, getInboxResponse, 'null');
    });
    appendEvents(0);
});
