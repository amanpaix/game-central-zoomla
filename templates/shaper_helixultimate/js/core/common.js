var $ = jQuery.noConflict();
var min_limit = 500;
var max_limit = 1000000;
var base_href = "";
var hide_decimal = false;
var cnt = 0;
var update_both_balances = false;
var error_callback_add_account = {};
var error_callback_verifyotp = {};
var error_callback_registration = {};
var depositBal = JSON.parse('{"MOBILE_MONEY":{"min":50,"max":1000},"DIGITAL_WALLET":{"min":0,"max":0}}');
var withdrawalBal = JSON.parse('{"MOBILE_MONEY":{"min":50,"max":70000}}');

/*** Clear error if exists on next key press. */

var myPagesList = {
    'play_now_twelvebytwentyfour': '/twelve-by-twentyfour',
    'play_now_powerball': '/six-by-fortytwo',
};

document.onkeypress = function (){
    clearSystemMessage();
};
/*** Fucntion to clear system message.*/
function clearSystemMessage(){
    $("#system-message-container").empty();
}
/***Function to show error message.*/
function error_message(s1, s2){
    $("#system-message-container").html("" + "<div id='system-message'>" +
            "<div class='alert alert-danger'>" +
            "<a class='close' data-bs-dismiss='alert'>x</a>" +
            "<h4 class='alert-heading'></h4>" +
            "<div>" +
            "<p>" + s1 + "</p>" +
            "</div>" +
            "</div>" +
            "</div>");
    $("#" + s2).focus();
    window.scrollTo(0, 0);
}
/***Function to show warning message.*/
function warning_message(s1){
    $("#system-message-container").html("" + "<div id='system-message'>" +
            "<div class='alert alert-warning'>" +
            "<a class='close' data-bs-dismiss='alert'>x</a>" +
            "<h4 class='alert-heading'>Warning</h4>" +
            "<div>" +
            "<p>" + s1 + "</p>" +
            "</div>" +
            "</div>" +
            "</div>");
    window.scrollTo(0, 0);
    setTimeout(function () {
        $("#system-message").empty();
    },4000);
}
/***Function to show info message.*/
function info_message(s1){
    $("#system-message-container").html("" + "<div id='system-message'>" +
            "<div class='alert alert-info'>" +
            "<a class='close' data-bs-dismiss='alert'>x</a>" +
            "<h4 class='alert-heading'>Warning</h4>" +
            "<div>" +
            "<p>" + s1 + "</p>" +
            "</div>" +
            "</div>" +
            "</div>");
    window.scrollTo(0, 0);
    setTimeout(function () {
        $("#system-message").empty();
    },4000);
}
/***Function to show success message.*/
function success_message(s1){   
    $("#system-message-container").html("" + "<div id='system-message'>" +
            "<div class='alert alert-success'>" +
            "<a class='close' data-bs-dismiss='alert'>x</a>" +
            "<h4>" + Joomla.JText._('SUCCESS') + "</h4>" +
            "<div>" +
            "<p>" + s1 + "</p>" +
            "</div>" +
            "</div>" +
            "</div>");
    window.scrollTo(0, 0);
    setTimeout(function () {
        $("#system-message").empty();
    },4000);
}
/***Function to make an ajax call.*/
function startAjax(url, params, reply, str, cashierFunc, payLoad){
    if (typeof payLoad == 'undefined') {
        payLoad = '';
    }
    var nvar = /khelplayrummy/gi;
    if (url.search(nvar) == -1)
        url = base_href + url;
    removeToolTipError('all');
    removeToolTipErrorManual('all');
    var result = '';
    if (str != "null"){
        $(str).submit(function (e) {
            e.preventDefault();
        });
    }
    // $(document).ajaxStart(function(){
    if (str != "nottoshow") {
        $("#loadingImage").remove();
        // $("body").append('<div id="loadingImage"><img src="' + base_href + '/images/loader.gif" /></div>');
        // $("body").append('<div id="loadingImage" class="loading-wrap"><div  class="loading"><div class="txt"><span>Loading</span></div></div></div>');
        $("body").append('\t\t\t<div id="loadingImage" class="loading-wrap">\n' +
            '\t\t\t<div class="sp-pre-loader">\n' +
            '                <div class="loading-wrap">\n' +
            '                    <div class="bounceballs">\n' +
            '                        <div class="ballWrap bbw1"><div class="ball ball1"><img src="images/icons/icon-ballRed.png"></div></div>\n' +
            '                        <div class="ballWrap bbw2"><div class="ball ball2"><img src="images/icons/icon-ballOrange.png"></div></div>\n' +
            '                        <div class="ballWrap bbw3"><div class="ball ball3"><img src="images/icons/icon-ballBlue.png"></div></div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '\t\t\t</div>\n' +
            '\t\t\t</div>');
        $("#loadingImage").css("display", "flex");
        $("#loadingImage").focus();
    }
    // });
    // $(document).ajaxStop(function(){
    //     if(str != "nottoshow") {
    //        $("#loadingImage").remove();
    //     }
    // });
    $.ajax({
        type: 'POST',
        async: true,
        url: url,
        data: params + "&isAjax=true",
        headers: {
            'X-Csrf-Token': Joomla.getOptions("csrf.token")
        },
        encode: true,
        timeout:30000,
        error: showNetworkErrorCommon
    }).done(function (data1) {
        if (str != "nottoshow") {
            $("#loadingImage").remove();
        }
        if (str == 'cashier-check') {
            cashierFunc();
        } else {
            reply(data1);
        }
        return;
        result = data1;
    }).fail(function (data1, jqXHR, textStatus) {
        if (textStatus == 'timeout')
        {   
            errorDisplay(Joomla.JText._('BETTING_PLEASE_TRY_AGAIN_LATER'), 'error');
            //do something. Try again perhaps?
        }
        if (str != "nottoshow") {
            $("#loadingImage").remove();
        }
        return false;
    });
    return result;
}

function showNetworkErrorCommon(request, status, err) {
//    if (status == "timeout") {
//       errorDisplay(Joomla.JText._('BETTING_NO_INTERNET_CONNECTION_MSG'), 'error');
//        //window.location.reload(); //make it comment if you don't want to reload page
//    }
    if (status == "error") {
        errorDisplay(Joomla.JText._('BETTING_NO_INTERNET_CONNECTION_MSG'), 'error');
        $('input[type="password"]').val("");
        removeToolTipErrorManual('all');
    } else {
        window.location.reload(); //make it comment if you don't want to reload page
    }
    return false;
}

/**
 *Function to make an ajax call.
 */
function startAjaxFileUpload(url, params, reply, str, beforeAjax, afterAjax){
    var nvar = /khelplayrummy/gi;
    if (url.search(nvar) == -1)
        url = base_href + url;
    removeToolTipError('all');
    removeToolTipErrorManual('all');
    var result = '';
    if (str != "null"){
        $(str).submit(function (e) {
            e.preventDefault();
        });
    }
    if (beforeAjax != undefined) {
        $(document).ajaxStart(beforeAjax);
    }
    if (afterAjax != undefined) {
        $(document).ajaxStop(afterAjax);
    }
    $.ajax({
        type: 'POST',
        async: true,
        url: url,
        data: params,
        headers: {
            'X-Csrf-Token': Joomla.getOptions("csrf.token")
        },
        processData: false,
        contentType: false
    }).done(function (data1) {
        reply(data1);
        return;
        result = data1;
    }).fail(function (data1) {
        return false;
    });
    return result;
}
/***Function to validate session through javascript.*/
function validateSession(id){
   // var res = $.parseJSON(id);
        try {
        var res = $.parseJSON(id);
    } catch (e) {
        var res = $.parseJSON('{"flag" : "EXPIRE","path" : "/"}');
    }
    if (res.flag != 'undefined') {
        if (res.flag == 'EXPIRE' || res.flag == 'ALREADY' || res.flag == 'RELOAD') {
            location.href = res.path;
            return false;
        }
    } else
        return true;
}
/***Function to show error in tooltip.*/
function showToolTipError(id, errMsg, placement, callback){
    if (placement == undefined) {
        placement = 'bottom';
    }
    var selector = id;
    if (typeof id == "string")
        selector = "#" + id;
    $(selector).addClass('error');
    $(selector).tooltip('destroy');
    $(selector).attr("data-toggle", "tooltip");
    $(selector).attr("data-placement", placement);
    $(selector).attr("title", errMsg);
    $(selector).tooltip({
        show: true,
        trigger: 'manual',
        animation: false
    });
    $(selector).tooltip('show');
    if (callback != "" && callback != undefined) {
        window[callback](placement, $("#" + id), "error");
    }
}

function showToolTipErrorManual(id, errMsg, placement, $element, callback){
    if (placement == undefined) {
        placement = 'bottom';
    }
    $("#" + id).addClass('error');
    if ($element.attr('prefix') == 'prefix') {
    $("#" + id).parent().parent().find('#error_' + $element.attr("id")).html(errMsg);   
    $("#" + id).parent().parent().find('#error_' + $element.attr("id")).css("display", "block");   
    }else{
    $("#" + id).parent().find('#error_' + $element.attr("id")).html(errMsg);
    $("#" + id).parent().find('#error_' + $element.attr("id")).css("display", "block");
    }
    if (callback != "" && callback != undefined) {
        window[callback](placement, $("#" + id), "error");
    }
}
/***Function to remove tooltip from an element.*/
function removeToolTipError(id){
    if (id == "all") {
        $("[data-toggle='tooltip']").tooltip('dispose');
        $("[data-toggle='tooltip']").removeClass('error');
        $("[data-toggle='tooltip']").removeAttr("data-placement");
        $("[data-toggle='tooltip']").removeAttr("title");
        $("[data-toggle='tooltip']").removeAttr("data-toggle");
        $("[data-toggle='tooltip']").removeAttr("data-original-title");
        $("[data-original-title]").removeClass("error");
        $("[aria-describedby]").removeAttr("aria-describedby");
        $("div.tooltip").remove();
    } else {
        $("#" + id).removeClass('error');
        $("#" + id).tooltip('dispose');
        $("#" + id).removeAttr("data-toggle");
        $("#" + id).removeAttr("data-placement");
        $("#" + id).removeAttr("title");
        $("#" + id).removeAttr("data-original-title");
        $("#" + id).removeAttr("aria-describedby");
    }
}

function removeToolTipErrorManual(id, $element){
    if (id == "all") {
        $($(".manual_tooltip_error")).each(function () {
            $(this).css('display', 'none');
            $(this).html('');
            $element = $(this).attr("id").trim().replace("error_", "");
            $(this).parent().find("#" + $element).removeClass('error');
        });
    } else {
        $element.removeClass('error');
        if ($element.attr('type') == 'radio') {
            $element.parent().parent().parent().find("#error_" + $element.attr('id')).css('display', 'none');
            $element.parent().parent().parent().find("#error_" + $element.attr('id')).html('');
        } else {
            if ($element.attr('prefix') == 'prefix') {
                $element.parent().parent().find('#error_' + $element.attr("id")).css('display', 'none');
                $element.parent().parent().find('#error_' + $element.attr("id")).html('');
            } else {
            $element.parent().find("#error_" + $element.attr('id')).css('display', 'none');
            $element.parent().find("#error_" + $element.attr('id')).html('');
        }
    }
}
}
/***Function to how tooltip errors in tooltip (jQuery validation).*/
function displayToolTip(obj, errorMap, errorList, placement, callback) {
    if (placement == undefined) {
        placement = 'bottom';
    }
    $.each(obj.validElements(), function (index, element) {
        var $element = $(element);
        $element.data("title", "")
                .removeClass("error")
                .tooltip("destroy");
    });
    $.each(errorList, function (index, error) {
        var $element = $(error.element);
        $element.tooltip("destroy")
                .data({
                    "title": error.message,
                    "placement": placement
                })
                .addClass("error")
                .tooltip({
                    show: true,
                    trigger: 'manual',
                    animation: false
                });
        $($element).tooltip('show');
        if (callback != "" && callback != undefined) {
            window[callback](placement, $($element), "error");
        }
    });
}

function displayToolTipManual(obj, errorMap, errorList, placement, callback){
    if (placement == undefined) {
        placement = 'bottom';
    }
    $.each(obj.validElements(), function (index, element) {
        var $element = $(element);
        $element.data("title", "").removeClass("error"); 
        if($element.attr('prefix') == 'prefix'){
        $element.parent().parent().find('#error_' + $element.attr("id")).css("display", "none");
        }else{
         $element.parent().find('#error_' + $element.attr("id")).css("display", "none"); 
        }
        if (callback != "" && callback != undefined) {
            try {
                window[callback](placement, $($element), "success");
            } catch (e) {
            }
        }
    });
    $.each(errorList, function (index, error) {
        var $element = $(error.element);
        $element.addClass("error");
        $element.parent().addClass("error");
        if ($element.attr('type') == 'radio') {
            $element.parent().parent().parent().find('#error_' + $element.attr("id")).html(error.message);
            $element.parent().parent().parent().find('#error_' + $element.attr("id")).css("display", "block");
        } else {
            if($element.attr('prefix') == 'prefix'){
            $element.parent().parent().find('#error_' + $element.attr("id")).html(error.message);
            $element.parent().parent().find('#error_' + $element.attr("id")).css("display", "block");
           }
            $element.parent().find('#error_' + $element.attr("id")).html(error.message);
            $element.parent().find('#error_' + $element.attr("id")).css("display", "block");
        }
        if (callback != "" && callback != undefined) {
            try {
                window[callback](placement, $($element), "error");
            } catch (e) {
            }
        }
    });
}
/***Function to update balance.*/
function updateBalance(balance,currency='',dispCurrency=''){
    if( (currency) == '' ){
        currency = defaultCurrencyCode;
    }
    if( (dispCurrency) == ''  ){
        dispCurrency = defaultCurrencyDisp;
    }
    if ($(".cash-balance").length > 0){
        $(".cash-balance").html(formatCurrency(balance,currency,dispCurrency));
        $(".plr_balance strong").html(formatCurrency(balance,currency,dispCurrency));
    }

}

function updatePracticeBalance(balance,currency='',dispCurrency=''){
    if( (currency) == '' ){
        currency = defaultCurrencyCode;
    }
    if( (dispCurrency) == ''  ){
        dispCurrency = defaultCurrencyDisp;
    }
    if ($(".practice-balance").length > 0)
        $(".practice-balance").html(balance);
    if ($(".cash-balance").length > 0){
        $(".cash-balance").html(formatCurrency(balance,currency,dispCurrency));
        $(".plr_balance strong").html(formatCurrency(balance,currency,dispCurrency));
    }
}

function updateWithdrawBalance(balance,currency='',dispCurrency=''){

    if( (currency) == '' ){
        currency = defaultCurrencyCode;
    }
    if( (dispCurrency) == ''  ){
        dispCurrency = defaultCurrencyDisp;
    }

    $(".plr_with_balance").html(formatCurrency(balance,currency,dispCurrency));

    // if ($(".withdraw-balance").length > 0)
    //     $(".withdraw-balance").html(balance);
    // if ($(".withdrawl_amount").length > 0){
    //     $($('.withdrawl_amount strong')).each(function () {
    //         $(this).html(balance);
    //     });
    // }
}

$(document).ready(function () {
    //for specially IE
    if (navigator.userAgent.indexOf("MSIE") !== -1 || navigator.appVersion.indexOf('Trident/') > -1) {
        $('body').addClass('ie');
    }
    var isTab = /(ipad|iphone)/.test(navigator.userAgent.toLowerCase());
    if(isTab){
        $('body').addClass('unSupported');
    }
    navigator.sayswho = (function () {
        var supportUp = [];
        supportUp["Chrome"] = "77",
                supportUp["MSIE"] = "12",
                supportUp["IE"] = "12",
                supportUp["Edge"] = "44",
                supportUp["Firefox"] = "60",
                supportUp["Safari"] = "100"
        var ua = navigator.userAgent, tem,
                M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if (/Opera Mini/i.test(ua)) {
            $('body').addClass('unSupported');
            $('body').removeClass('supported');
            $('body').addClass('opmini');
            //            if ($('body').hasClass('opmini')) {
            $('#sp-component .modal.fade').each(function () {
                $(this).wrap('<div id="modCopyFlash"></div>');
                var modCopyFlashContent = $(this).parent().html();
                $('#sp-custom-popup > .sp-column').append('<div class="sp-module"><div class="sp-module-content"><div class="custom">' + modCopyFlashContent + '</div></div></div>');
                $('#modCopyFlash').remove();
            })
            //             }
            return "opera mini";
        }
        if (M[1] === 'Chrome') {
            tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
            if (tem != null)
                tem.slice(1).join(' ').replace('OPR', 'Opera');
        }
        M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
        if ((tem = ua.match(/version\/(\d+)/i)) != null)
            M.splice(1, 1, tem[1]);

        if (parseInt(supportUp[M[0]]) >= parseInt(M[1])) {
            $('body').addClass('unSupported');
        } else
            $('body').addClass('supported');
        $('body').addClass('br-' + M[0]);
        return M.join(' ');
    })();

    //to open login popup if not login or redirect to link
    for (var key_game_title in myPagesList) {
        var Title = key_game_title;
        $("[title='" + key_game_title + "']").attr("customclick", key_game_title);
        $("[title='" + key_game_title + "']").attr("href", myPagesList[key_game_title]);
        switch (Title) {
            case 'play_now_thailotteryhighfrequency':
                Title = "Play Now Lotto Diamond";
                break;
            case 'play_now_twelvebytwentyfour':
                Title = "Play Now Lucky Twelve";
                break;
            case 'play_now_powerball':
                Title = "Play Now SABANZURI Lotto";
                break;
            default:
                Title = getCameCase(key_game_title.replace(/_/g, ' '));
        }
        $("[title='" + key_game_title + "']").attr("title", Title);
    }

    $("[customclick]").off('click').on('click', function (e) {
        if (!e.ctrlKey) {
            e.preventDefault();
            openMyPages($(this).attr('customclick'));
            // if ($("body").hasClass("post-login") == true) {
            //     openMyPages($(this).attr('customclick'));
            // } else {
            //     //                     $('.modal').modal('hide');
            //     //                     if($("#home_login_popup").length > 0) {
            //     //                         $("#home_login_popup form").attr("from-title", $(this).attr('customclick'));
            //     //                         $("#home_login_popup form").attr("modal-id", "#home_login_popup");
            //     //                         $("#home_login_popup").modal('show');
            //     //                     }
            //     window.open($(".loginAdd").attr("href") + "?fromPage=" + btoa($(this).attr('href')), '_blank');
            // }
        }
    });

    //to start Slider Timer updater Function custom_slider
    setInterval(update_timer, 1000);
    $(document).on('hidden.bs.modal', function (event) {
        removeToolTipError("all");
        removeToolTipErrorManual('all');
        $("#" + $(event.target).attr("id")).find('form').trigger('reset');
        $("#home_login form").removeAttr("from-title");
        $("#home_login form").removeAttr("modal-id");
    });

    $(document).on('show.bs.modal', function (event) {
        $("#" + $(event.target).attr("id")).find('form').trigger('reset');
        removeToolTipError("all");
        removeToolTipErrorManual('all');
    });

    $(document).on('shown.bs.modal', function (event) {
        if ($("body").hasClass("modal-open") == false)
            $("body").addClass("modal-open");
        $("a.close-offcanvas").trigger('click');
    });

    $("li>a.log-out-menu-item, li.log-out-menu-item>a").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation()
        e.stopImmediatePropagation();
        $("a.close-offcanvas").trigger('click');
        $("#notification").modal('show');
    });

    if ($('input').length > 0) {
        $(':input').not('[type="file"]').change(function () {
            $(this).val($(this).val().trim());
        });
    }
    if ($('textarea').length > 0) {
        $('textarea').change(function () {
            $(this).val($(this).val().trim());
        });
    }

    $(document).on("click", '[role="tooltip"]', function () {
        removeToolTipError($("[aria-describedby='" + $(this).attr("id") + "']").attr("id"));
    });

    $(document).on('click', '.manual_tooltip_error', function () {
        removeToolTipErrorManual('all');
    });

    $(document).on('keypress', function () {
        $($('input[aria-invalid="false"]')).each(function () {
            removeToolTipError($(this).attr("id"));
        });
    });

    if (navigator.userAgent.match(/iPhone/i)) {
        $("*").on('click', 'a.close', function (event) {
            clearSystemMessage();
        });
    }

    $(".update_balance").on('click', function () {
        updatePlayerBalance(false);
    });

    $(".update_practice_balance").on('click', function () {
        updatePlayerBalance(true);
    });

    $("[menu-toggler='true']").click(function () {
        $("#offcanvas-toggler").trigger("click");
        $('body').addClass('stopScroll');
    });

    $('.close-offcanvas').on('click', function () {
        $('body').removeClass('stopScroll');
    });

    $("a.downarrow").on('click', function () {
        bookmarkscroll.scrollTo('sp-geting-started');
    });

    $("[open_raf='true']").attr("href", "javascript:void(0);");
    $("[open_raf='true']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            openReferAFriendPage();
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "open_raf");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });

    $("[add_cash='true']").attr("href", "/cashier-initiate");
    $("[add_cash='true']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            openCashierWindow();
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "add_cash");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });

//    $("[href='/draw']").on('click', function (e) {
//        e.preventDefault();
//        if($("body").hasClass("post-login") == true) {
//            location.href = "/draw";
//        }
//        else{
//            if($("#home_login").length > 0) {
//                $("#home_login").modal('show');
//            }
//        }
//    });

//    $("[href='/en/instant-win']").on('click', function (e) {
//        e.preventDefault();
//        if($("body").hasClass("post-login") == true) {
//            location.href = "/instant-win";
//        }
//        else{
//            if($("#home_login").length > 0) {
//                $("#home_login").modal('show');
//            }
//        }
//    });


    $("[play_bingo='true'], [href='/bingo-play']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            location.href = "/bingo-play?room="+$(this).attr("room_id");
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "play_bingo");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });

    $("[play_rummy='true']").attr("href", "/rummy");
    $("[play_rummy='true'], [href='/rummy']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            openRummyWindow();
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "play_rummy");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });
    $("[title='play_new_rummy']").attr("href", "/play-html-rummy");
    $("[title='play_new_rummy'], [href='/play-html-rummy']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            openNewRummyWindow();
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "play_new_rummy");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });

    $("[title='Lotto_Tv']").on('click', function (e) {
        e.preventDefault();
        openLottoTvWindow();
    })

    $("[title='play_new_rummy']").attr("href", "/play-html-rummy");
    $("[title='play_new_rummy'], [href='/play-html-rummy']").on('click', function (e) {
        e.preventDefault();
        if ($("body").hasClass("post-login") == true) {
            openNewRummyWindow();
        } else {
            if ($("#home_login").length > 0) {
                $("#home_login form").attr("from-title", "play_rummy");
                $("#home_login form").attr("modal-id", "#home_login");
                $("#home_login").modal('show');
            }
        }
    });
    
    $("div.deep-menu li.deeper.parent").each(function(index) {
        if($(this).hasClass('active') == false){
            $(this).children('ul').slideToggle();            
        }else{
            $(this).addClass('open');
        }
    });
    
    $("div.deep-menu li.deeper.parent>a").on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        $(this).next().slideToggle();
        $(this).parent().toggleClass('open');
    });
    
    $($(".main_menu li.sp-menu-item.active").parents("li.sp-menu-item.sp-has-child").not(".active")).each(function () {
        $(this).addClass("active");
    });

    $($("a")).each(function () {
        if ($(this).attr("href") == "#")
            $(this).attr("href", "javascript:void(0);");
    });

    $("[sendAppLink='true']").on('click', function () {
        var input_id = $(this).attr('input-id');
        if (validateMobile(input_id, $("#" + input_id).val(), "manual") == false)
            return false;
        removeToolTipErrorManual("", $("#" + input_id));
        startAjax("/component/betting/?task=account.sendAppLink", "mobileNo=" + $("#" + input_id).val().trim(), sendAppLinkResponse, "null");
    });

    $("[class*='open_modal_']").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var classList = $(this).attr('class').split(/\s+/);
        var class_href = $(this).attr('href');
        $.each(classList, function (index, className) {
            if (className.search("open_modal_") != -1) {
                if ($("#" + className.replace("open_modal_", "")).length > 0)
                    $("#" + className.replace("open_modal_", "")).modal('show');
                else
                    window.location = class_href;
                return true;
            }
        });
    });

    $(document).on("keyup", ".allow_only_nums", function (e) {
        var value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        $(this).val(value);
//        var key = e.which ? e.which : e.keyCode;
//        var key1 = String.fromCharCode(key);
//
//        if( key != 8 && key != 0 && !(key1 >= 0 && key1 <= 9 )){
//            return false;
//        }

    });

    $(document).on("keyup", ".dont_allow_nums", function (e) {
        var value = $(this).val();
        value = value.replace(/[^A-Za-z ]/g, '');
        $(this).val(value);
    });

    $(document).on("keyup", ".no_special_chars", function (e) {
        var value = $(this).val();
        value = value.replace(/[^A-Za-z0-9 ]/g, '');
        $(this).val(value);
    });
    
    $(document).on("keyup", ".alphabets_only", function (e) {
        var value = $(this).val();
        value = value.replace(/[^A-Za-z ]/g, '');
        $(this).val(value);
    });

    if (myDeviceType !== "PC")
    {
        //$(".myaccount_topsection .user_details .user_details").css("display", "none");
        $('.myaccount_topsection .user_ac_details .tab_act_btn .play_now').css("display", "none");
        $('[download_app_btn="true"]').css("display", "block");
    } else if (myDeviceType == "PC" && screen.width == "1024" && screen.height == "768")
    {
        $(".myaccount_topsection .user_details .user_details").css("display", "none");
        $('[download_app_btn="true"]').css("display", "none");
        $('.myaccount_topsection .user_ac_details .tab_act_btn .play_now').css("display", "block");
    }

    if ($('body').hasClass('post-login') == true) {
        $(function () {
            "use strict";
            // if user is running mozilla then use it's built-in WebSocket
            window.WebSocket = window.WebSocket || window.MozWebSocket;
            // if browser doesn't support WebSocket, just show
            // some notification and exit
            if (!window.WebSocket) {
                return;
            }
            // open connection
            // var connection = new WebSocket(webSocketDomain + '?id=' + myId);
            var connection = null;
            // connection.onopen = function () {
            //     console.log("Connection Open");
            // };
            // connection.onerror = function (error) {
            //     console.log("Connection Error");
            // };
            // connection.onclose = function () {
            //     console.log("Connection Closed");
            // }
            // // most important part - incoming messages
            // connection.onmessage = function (message) {
            //     try {
            //         console.log(message);
            //         var json = JSON.parse(message.data);
            //         if (json.type == 'updatebalance') {
            //             updatePlayerBalance(false);
            //         } else if (json.type == 'draw-machine-start') {
            //             $("#drawmachiner_popup").modal('hide');
            //             var msg = "The " + json.gameCode + " draw is going to happen.";
            //             $("#drawmachiner_popup #drawmachine-msg").html(msg);
            //             $("#drawmachiner_popup #drawmachine-url").attr('onclick', "location.href='" + json.url + "'");
            //             $("#drawmachiner_popup").modal('show');
            //         }
            //     } catch (e) {
            //         console.log('Invalid JSON: ', message.data);
            //         return;
            //     }
            //     console.log(json);
            // };
        });
    }

    $(".toggleTypeReg").on('click', function(e){
        if($("#confirm_password").attr("type") == "password"){
            $("#confirm_password").attr("type","text");
            $(this).find("i").removeClass("fa-eye-slash");
            $(this).find("i").addClass("fa-eye");
        }
        else {
            $("#confirm_password").attr("type","password");
            $(this).find("i").removeClass("fa-eye");
            $(this).find("i").addClass("fa-eye-slash");
        }
        e.preventDefault();
        e.stopImmediatePropagation();
        return true;
    });
    $(".toggleTypeLogin").on('click', function(e){
        if($("#password").attr("type") == "password"){
            $("#password").attr("type","text");
            $(this).find("i").removeClass("fa-eye-slash");
            $(this).find("i").addClass("fa-eye");
        }
        else {
            $("#password").attr("type","password");
            $(this).find("i").removeClass("fa-eye");
            $(this).find("i").addClass("fa-eye-slash");
        }
        e.preventDefault();
        e.stopImmediatePropagation();
        return true;
    });

    $('.game_type_evolution').on('click', function (){
        if( $('body').hasClass('pre-login') ){
            openLoginModal();
            game_id_tmp = $(this).attr('game_id');
            game_cat_tmp = $(this).attr('game_cat');
            setTimeout(function () {
                $("#home_login #submiturl").val("/live-games/evolution?tableId="+game_id_tmp+"&open=lobby&gameCategory="+game_cat_tmp);
            },1000);
            return false;
        }
        else
        {
            if( $(this).attr('game_id') != null && $(this).attr('game_id') != "" && typeof $(this).attr('game_id') != "undefined")
                location.href = "/live-games/evolution?tableId="+$(this).attr('game_id')+"&open=lobby&gameCategory="+$(this).attr('game_cat');
            return true;
        }
    });

    $("#sp-position2 .game-list").on('click', '[game_id],.game_type_evolution', function () {
    // $('[game_id],.game_type_evolution').on('click', function (){
        if( $('body').hasClass('pre-login') ){
            openLoginModal();
            game_id_tmp = $(this).attr('game_id');
            game_cat_tmp = $(this).attr('game_cat');
            setTimeout(function () {
                $("#home_login #submiturl").val("/live-games/evolution?tableId="+game_id_tmp+"&open=lobby&gameCategory="+game_cat_tmp);
            },1000);
            return false;
        }
        else
        {
            if( $(this).attr('game_id') != null && $(this).attr('game_id') != "" && typeof $(this).attr('game_id') != "undefined")
            location.href = "/live-games/evolution?tableId="+$(this).attr('game_id')+"&open=lobby&gameCategory="+$(this).attr('game_cat');
            return true;
        }
    });

    $("#sp-component .game-list li").on('click', function () {
        // $('[game_id],.game_type_evolution').on('click', function (){
        if( $('body').hasClass('pre-login') ){
            openLoginModal();
            game_id_tmp = $(this).attr('betgames_game_id');
            setTimeout(function () {
                $("#home_login #submiturl").val("/live-games/betgames/betgames-play?tableId="+game_id_tmp);
            },1000);
            return false;
        }
        else
        {
            location.href = "/live-games/betgames/betgames-play?tableId="+$(this).attr('betgames_game_id');
            return true;
        }
    });

    $('.game_type_evolution_shows').on('click', function (e){
        if( $('body').hasClass('pre-login') ){
            openLoginModal();
            setTimeout(function () {
                $("#home_login #submiturl").val("/game-shows?open=lobby&gameCategory=game_shows");
            },1000);
            return false;
        }
        else
        {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
           location.href = "/game-shows?open=lobby&gameCategory=game_shows";
            return true;
        }
    });

    $('.game_type_slots').on('click', function (e){

        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if( $("body").hasClass("homePage") ){
            $("[game_type_btn='Slots']").click().focus();
        }
        else
        {
            location.href = "/#slots";
        }
        // if( $('body').hasClass('pre-login') ){
        //     openLoginModal();
        //     setTimeout(function () {
        //         $("#home_login #submiturl").val("/#slots");
        //     },1000);
        //     return false;
        // }
        // else
        // {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     e.stopImmediatePropagation();
        //
        //     if( $("body").hasClass("homePage") ){
        //         $("[game_type_btn='Slots']").click().focus();
        //     }
        //     else
        //     {
        //         location.href = "/#slots";
        //     }
        //
        //     // location.href = "/slot-games?open=lobby&gameCategory=slots";
        //     return true;
        // }
    });

    $("[game_type_btn]").on('click', function () {

        if ($(this).attr('game_type_btn') === 'all') {
            var params = "lobby_cat=all";
        }
        else
        {
            var params = "lobby_cat="+$(this).attr('game_type_btn');
        }

        gameListMasterCnt = 0;
        gameListOffset = 0;
        startAjax("index.php/component/betting/?task=api.getCasinoGameList", params, getCasinoGameListResponse, 'null');


    });

    // $("[game_type_btn]").first().click();

    $(".refresh-icon").on('click', function () {
        updatePlayerBalance(false);
    });

    $("#deposit_popup .proceed-button").on('click', function () {

    });

    document.onreadystatechange = () => {
        if (document.readyState === 'complete') {
            if( window.location.hash === "#slots" ){
                $("[game_type_btn='Slots']").click().focus();
            }
        }
    };

    $("#refresh_bal").on("click", function () {
        startAjax("/component/betting/?task=account.updatePracticeBalance", '', getPracticeBalance, 'nottoshow');
    });


});


function getPracticeBalance(result)
{
    if (validateSession(result) == false)
        return false;
    var res = JSON.parse(result);

    if (res.errorCode == 0) {
        if (update_both_balances == true) {
            update_both_balances = false;
            updateBalance(getFormattedAmount(parseFloat(res.wallet.cashBal, 2).toFixed(2)),res.wallet.currency);
            updatePracticeBalance(parseInt(res.wallet.practiceBalance));
            updateWithdrawBalance(res.wallet.withdrawableBal);
        } else if (res.refill == false || res.refill == 'false') {
            updateBalance(getFormattedAmount(parseFloat(res.wallet.cashBal, 2).toFixed(2)),res.wallet.currency, res.wallet.currencyDisplayCode);
            updateWithdrawBalance(getFormattedAmount(parseFloat(res.wallet.withdrawableBal, 2).toFixed(2)));
        } else {
            updatePracticeBalance(getFormattedAmount(parseFloat(res.wallet.practiceBal, 2).toFixed(2)),res.currency, res.currencyDisplayCode);

        }
        if( typeof max_limit !== undefined && typeof max_amount !== undefined  && typeof min_limit !== undefined && parseFloat(res.wallet.withdrawableBal, 2) > min_limit ){
            max_limit = getFormattedAmount(parseFloat(res.wallet.withdrawableBal, 2).toFixed(2));
            max_amount = parseFloat(res.wallet.withdrawableBal, 2).toFixed(2);
        }
        totalBalanceAfter = parseFloat(res.wallet.withdrawableBal, 2).toFixed(2);
    }

}
function getCasinoGameListResponse(result)
{
    $("body").attr("loaded",true);
    if( result.data.length > 0 ){

        if( gameListOffset === 0 ){
            if( result.type == "all" ){
                $("[game_type]").remove();
            }
            else {
                $("[game_type='"+ result.data[0].lobby_cat +"']").remove();
            }
        }

        gameListOffset = gameListOffset + gameListLimit;

        var html = '';
        for ( var i in result.data ){
            gameListMasterCnt++;
            html = '<li game_id="'+ result.data[i].tableId +'" game_cat="'+ result.data[i].gameTypeUnified +'" game_type="'+ result.data[i].lobby_cat +'" style="display: list-item;">\n' +
                '                <a href="javascript:void(0);">\n' +
                '                    <span class="item">\n' +
                '                        <img data-data-src="'+ result.data[i].img_src +'" alt="" class="lazyloaded " data-src="'+ result.data[i].img_src +'" src="'+ result.data[i].img_src +'">\n' +
                '                    </span>\n' +
                '                </a>\n' +
                '            </li>';
            $("ul.game-list").append(html);
        }

        if (result.type === 'all') {
            $("[game_id]").fadeIn(450);
        } else {
            var $ele = $("[game_type='"+result.type+"']").fadeIn(450);
            $("[game_id]").not($ele).hide();
        }
        $("[game_type_btn]").removeClass('active');
        $("[game_type_btn='"+ result.type +"']").addClass('active');
    }

}
function openReferAFriendPage(){
    window.location.href = "/refer-a-friend";
}

function openCashierWindow(){
    try {
        var left = (screen.width / 2) - (840 / 2);
        var top = (screen.height / 2) - (640 / 2);
        window.open(base_href + "/cashier-initiate", "cashierWindow", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=no, copyhistory=no, width=840, height=665, top=0, left=' + left);
    } catch (e) {
        $("#loadingImage").css("display", "none");
    }
}

function openRummyWindow(){
    try {
        var left = (screen.width / 2) - (1000 / 2);
        var top = (screen.height / 2) - (650 / 2);
        window.open(base_href + "/rummy", "rummyWindow", "height=650,width=1000,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no, left=" + left);
    } catch (e) {
        $("#loadingImage").css("display", "none");
    }
}

function updatePlayerBalance(refill, str){
    if (!str == "nottoshow")
        str = "null";
    if (refill == 'both') {
        update_both_balances = true;
    }
    if (refill == true) {
        startAjax("/component/betting/?task=account.getPlayerBalance", 'refill=true', getBalance, 'nottoshow');
    } else {
        startAjax("/component/betting/?task=account.getPlayerBalance", '', getBalance, 'nottoshow');
    }
}

function refillPracticeBalance(str){
    updatePlayerBalance(true, str);
}

function noFunction(result){
    return false;
}

function getBalance(result){
    if (validateSession(result) == false)
        return false;
    var res = JSON.parse(result);
    console.log(res);
    if (res.errorCode == 0) {
        if (update_both_balances == true) {
            update_both_balances = false;
            updateBalance(getFormattedAmount(parseFloat(res.wallet.cashBal, 2).toFixed(2)),res.wallet.currency);
            updatePracticeBalance(parseInt(res.wallet.practiceBalance));
            updateWithdrawBalance(res.wallet.withdrawableBal);
        } else if (res.refill == false || res.refill == 'false') {
            updateBalance(getFormattedAmount(parseFloat(res.wallet.cashBal, 2).toFixed(2)),res.wallet.currency, res.wallet.currencyDisplayCode);
            updateWithdrawBalance(getFormattedAmount(parseFloat(res.wallet.withdrawableBal, 2).toFixed(2)));
        } else {
            updatePracticeBalance(parseInt(res.wallet.practiceBalance));
        }
       if( typeof max_limit !== undefined && typeof max_amount !== undefined  && typeof min_limit !== undefined && parseFloat(res.wallet.withdrawableBal, 2) > min_limit ){
             max_limit = getFormattedAmount(parseFloat(res.wallet.withdrawableBal, 2).toFixed(2));
             max_amount = parseFloat(res.wallet.withdrawableBal, 2).toFixed(2);
       }
       totalBalanceAfter = parseFloat(res.wallet.withdrawableBal, 2).toFixed(2);
    }
}

function logout(){
    window.location.href = base_href + "/log-out";
}

function updateMessageCount(count){
    $("span.mail_count").html(count);
    if (count == 0)
        $("#mail_count_view").html("");
    else
        $("#mail_count_view").html("(" + count + ")");
}

function validateMobile(id, mobile, errType){
    if (mobile.length == 0) {
        if (errType == "manual")
            showToolTipErrorManual(id, "Enter Mobile no.", "bottom", $("#" + id), undefined);
        else
            showToolTipError(id, "Enter Mobile no.", "bottom", undefined);
        return false;
    }
    if (mobile.length != 10) {
        if (errType == "manual")
            showToolTipErrorManual(id, "Mobile no. should be of 10 digits", "bottom", $("#" + id), undefined);
        else
            showToolTipError(id, "Mobile no. should be of 10 digits", "bottom", undefined);
        return false;
    }
    var myRegEx = /^[7-9]{1}[0-9]{9}$/;
    if (!myRegEx.test(mobile)) {
        if (errType == "manual")
            showToolTipErrorManual(id, "Invalid mobile no.", "bottom", $("#" + id), undefined);
        else
            showToolTipError(id, "Invalid mobile no.", "bottom", undefined);
        return false;
    }
    return true;
}

function sendAppLinkResponse(result){
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    $.each($('.success-msg'), function (index, value) {
        if ($(value).parent(":visible").length > 0)
        {
            if (res.respMsg == "msgAlreadySent") {
                $(value).find('span').html("You have already requested the download link, please check SMS on your mobile. If not received try again after 48 hours.");
            }
            $(".download_app_mobile").css("display", "none");
            $(value).css("display", "block");
            return false;
        }
    });
    /*   if(res.respMsg == "msgAlreadySent") {
     $("#successDiv #msgDiv").html("You have already requested the download link, please check SMS on your mobile. If not received try again after 48 hours.");
     }
     $(".download_app_mobile").css("display", "none");
     $("#successDiv").css("display", "block"); */
}

function openLiveChat(){
    $zopim.livechat.window.openPopout();
}

String.prototype.capitalizeFirstLetter = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

Date.prototype.monthNames = [
    "January", "February", "March",
    "April", "May", "June",
    "July", "August", "September",
    "October", "November", "December"
];

Date.prototype.getMonthName = function () {
    return this.monthNames[this.getMonth()];
};

$(document).ready(function () {
    var cookie_string = document.cookie.split(";");
    var cookie_launchCashierAfterLogin = false;
    var cookie_launchRummyAfterLogin = false;
    var cookie_launchPokerAfterLogin = false;
    var cookie_launchReferAFriendAfterLogin = false;
    var cookie_launchHtmlRummyAfterLogin = false;
    for (var i = 0; i < cookie_string.length; i++) {
        var cookie_string_params = cookie_string[i].split('=');
        try {
            if (cookie_string_params[0].trim() == "launchCashierAfterLogin") {
                cookie_launchCashierAfterLogin = cookie_string_params[1];
            }
            if (cookie_string_params[0].trim() == "launchRummyAfterLogin") {
                cookie_launchRummyAfterLogin = cookie_string_params[1];
            }
            if (cookie_string_params[0].trim() == "launchPokerAfterLogin") {
                cookie_launchPokerAfterLogin = cookie_string_params[1];
            }
            if (cookie_string_params[0].trim() == "launchReferAFriendAfterLogin") {
                cookie_launchReferAFriendAfterLogin = cookie_string_params[1];
            }
            if (cookie_string_params[0].trim() == "launchHtmlRummyAfterLogin") {
                cookie_launchHtmlRummyAfterLogin = cookie_string_params[1];
            }
        } catch (e) {
        }
    }
    if (cookie_launchCashierAfterLogin == true || cookie_launchCashierAfterLogin == 'true') {
        document.cookie = "launchCashierAfterLogin=";
        openCashierWindow();
    }
    if (cookie_launchRummyAfterLogin == true || cookie_launchRummyAfterLogin == 'true') {
        document.cookie = "launchRummyAfterLogin=";
        openRummyWindow();
    }
    if (cookie_launchPokerAfterLogin == true || cookie_launchPokerAfterLogin == 'true') {
        document.cookie = "launchPokerAfterLogin=";
        openPokerWindow();
    }
    if (cookie_launchReferAFriendAfterLogin == true || cookie_launchReferAFriendAfterLogin == 'true') {
        document.cookie = "launchReferAFriendAfterLogin=";
        openReferAFriendPage();
    }
    if (cookie_launchHtmlRummyAfterLogin == true || cookie_launchHtmlRummyAfterLogin == 'true') {
        document.cookie = "launchHtmlRummyAfterLogin=";
        openNewRummyWindow();
    }

    $("[add_cash_button]").on('click', function () {
        var params = "for=DEPOSIT";
        startAjax("/index.php/component/betting/?task=cashier.getPaymentOptions", params, paymentOptionsResponse, 'null');
    });

    $("[withdraw_cash_button]").on('click', function () {
        var params = "for=WITHDRAWAL";
        startAjax("/index.php/component/betting/?task=cashier.getPaymentOptions", params, paymentOptionsResponse, 'null');

    });

    $("[title='open_casino']").on('click', function () {
        if( $("body").hasClass('pre-login') ){
            openLoginModal();
            return false;
        }

    });


    $(".wallTxn-amount-input-field").on('click', function () {
        $(".wallTxn-amount-input-field").removeClass('active');
        $(".wallTxn-amount-input-field-max").removeClass('active');
        $(".wallTxn-amount-input-field-max").val("");
        $(this).addClass('active');
        removeToolTipErrorManual('all');

        // var tmpValue = $(this).attr('name').split("_")[1];
        // if( $(this).closest("form").attr('id').includes("deposit") ){
        //     $("#deposit_value").val(tmpValue);
        // }
    });


    $("#withdrawal_confirmation_popup #doWithdraw").click(function () {
        // console.log(withdrawalFields);

    });


    $($("form[id^='cashier-add-account-form']")).each(function () {
        deposit_add_account = $(this).attr('id');
        error_callback_add_account[deposit_add_account] = $("#cashier-add-account-form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                accHolderName: {
                    required: true,
                    //min: 5
                },
                accNum: {
                    required: true,
                    pattern :"^[7,1]{1}[0-9]{8}$",
                    rangelength: [9,9],

                }
            },

            messages: {
                accHolderName: {
                    required: "Please Enter Your Name",
                    //min: Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                accNum: {
                    required: "Please enter Account Number.",
                    pattern: "Please enter a valid 9 digit account number.",
                    rangelength: "Account number should be in range.",
                }
            },
            submitHandler: function () {
                if ($("#" + deposit_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + deposit_add_account + " #accNum").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #accPaymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #accHolderName").val() + '&subTypeId=' + $("#" + deposit_add_account + " #accSubTypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #accPayTypeId").val() + '&currencyCode=' + $("#"+deposit_add_account + " #accCurrency").val();
                    if( $("#addAccFor").val() == "WITHDRAWAL" ){
                        params += "forattr=withdraw_cash_button";
                    }
                    else
                    {
                        params += "forattr=add_cash_button";
                    }

                    startAjax("/component/betting/?task=cashier.AddNewAccount", params, getOtpForAddingAccount, "#" + deposit_add_account);
                }

            }
        });
    });

    $($("form[id^='cashier-deposit-form']")).each(function () {
        var reg_form_id = $(this).attr('id');
        reg_form_id_for_response = reg_form_id;
        error_callback_registration[reg_form_id] = $("#" + reg_form_id).attr('error-callback');
        if(depositBal != null){
            minDepositAmount = $("#deposit_popup .mini-text").attr('value');
            minDepositLimit = $("#deposit_popup .mini-text").attr('value');
            maxDepositAmount = $("#deposit_popup .max-text").attr('value');
            maxDepositLimit = $("#deposit_popup .max-text").attr('value');
        }else{
            minDepositAmount = 0;
            minDepositLimit = 0;
            maxDepositLimit = 0;
            minDepositLimit = 0;
        }
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                // deposit_value: {
                //     required: true,
                //     //pattern:/^[1-9][0-9]*$/,
                //     min: minDepositLimit,
                //     range: [minDepositLimit , maxDepositLimit]
                // },
                paymentAccount: {
                    required: true,
                }
            },

            messages: {
                // deposit_value: {
                //     required: "Please enter amount to deposit.",
                //     //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                //     min: "Minimum amount should be " + ' ' + formatCurrency(minDepositAmount,"KES") + " to deposit.",
                //     //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                //     range: "Amount should be between " + ' ' + formatCurrency(minDepositAmount,"KES")+ " to " + formatCurrency(maxDepositAmount,"KES")+ "."
                // },
                paymentAccount: {
                    required: Joomla.JText._('WITH_SELECT_AMT'),
                }
            },

            submitHandler: function (form) {

                var validator = $("#cashier-deposit-form-1").validate();
                var errors = "";
                if( $("#deposit_popup").find('.wallTxn-amount-input-field.active').length == 0 && $('#deposit_popup [name^=deposit_value]').val().trim() == '' ){
                    errors = { deposit_value: "Please enter amount to deposit." };
                    validator.showErrors(errors);
                    return false;
                }

                if( $("#deposit_popup").find('.wallTxn-amount-input-field.active').length == 0 ){
                    if( parseInt($('#deposit_popup [name^=deposit_value]').val().trim()) < $("#deposit_popup .mini-text").attr('value') ){
                        errors = { deposit_value: "Minimum amount should be KES "+$("#deposit_popup .mini-text").attr('value') };
                        validator.showErrors(errors);
                        return false;
                    }

                    if( ( parseInt($('#deposit_popup [name^=deposit_value]').val().trim()) < $("#deposit_popup .mini-text").attr('value')) || ( parseInt($('#deposit_popup [name^=deposit_value]').val().trim()) > $("#deposit_popup .max-text").attr('value') )  ){
                        errors = { deposit_value:  "Amount should be between " + ' ' + formatCurrency($("#deposit_popup .mini-text").attr('value'),"KES","KES")+ " to " + formatCurrency($("#deposit_popup .max-text").attr('value'),"KES","KES")+ "." };
                        validator.showErrors(errors);
                        return false;
                    }
                }


                var deposit_value = "";
                if( $("#deposit_popup").find('.wallTxn-amount-input-field.active').length > 0 ){
                    deposit_value = $("#deposit_popup").find('.wallTxn-amount-input-field.active').val().split(" ")[1];
                }
                else
                {
                    deposit_value = $('#deposit_popup [name^=deposit_value]').val();
                }

                //$("#deposit_confirmation_popup").modal('show');
                var id = $(form).attr('id');
                var  params = 'payTypeCode=' + $(form).find("input[name^='payTypeCode']").val() + '&paytypeId=' + $(form).find("input[name^='paytypeId']").val() + '&subType=' + $(form).find("input[name^='subType']").val() + '&paymentAccount=' + $(form).find("input[name^='payment_account']").val();
                params += '&currency=KES';
                params += '&deposit=' + deposit_value;
                var form_fields = params.split("&");
                for (var i = 0; i < form_fields.length; i++) {
                    var temp = form_fields[i].split("=");
                    $('#deposit-request-form').append($('<input>', {
                        type: "hidden",
                        name: temp[0],
                        value: temp[1]
                    }));
                }

                $('#deposit-request-form').submit();
            }
        });
    });

    $($("form[id^='cashier-withdrawal-form']")).each(function () {
        var cashier_withdrawal_id = $(this).attr('id');
        withdrawal_form_id_for_response = cashier_withdrawal_id;
        withdrawal_form_id_for_response[cashier_withdrawal_id] = $("#" + cashier_withdrawal_id).attr('error-callback');
        var sub = $(this).find("input[id^='withSubType']").val();
        var payTypeCode = $(this).find("input[id^='withPayTypeCode']").val();
        var payTypeId = $(this).find("input[id^='withPaytypeId']").val();
        var currency = $(this).find("input[id^='withCurrency']").val();
        if (withdrawalBal != null) {
            minWithdrawalLimit = 50;
            maxWithdrawalLimit = 7000;
        } else {
            minWithdrawalLimit = 0;
            maxWithdrawalLimit = 0;
        }

        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                // amount_withdrawal: {
                //     required: true,
                //     //pattern:/^[1-9][0-9]*$/,
                //     min: minWithdrawalLimit,
                //     range: [50, 7000]
                // },
                // withdrawalAccounts: {
                //     required: true,
                // }
            },

            messages: {
                // amount_withdrawal: {
                //     required: "Please enter amount to Withdraw.",
                //     //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                //     min: "Minimum amount should be at least " + formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),"KES"),
                //     //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                //     range: "Amount should be between " + formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),"KES")+ " to " + formatCurrency(7000,"KES") + "."
                // },
                // withdrawalAccounts: {
                //     required: "please select account",
                // }
            },

            submitHandler: function (form) {


                var validator = $("#cashier-withdrawal-form-1").validate();
                var errors = "";
                if( $("#withdrawal_popup").find('.wallTxn-amount-input-field.active').length == 0 && $('#withdrawal_popup [name^=amount_other]').val().trim() == '' ){
                    errors = { amount_other: "Please enter amount to withdraw." };
                    validator.showErrors(errors);
                    return false;
                }

                if( $("#withdrawal_popup").find('.wallTxn-amount-input-field.active').length == 0 ){
                    if( parseInt($('#withdrawal_popup [name^=amount_other]').val().trim()) < $("#withdrawal_popup .mini-text").attr('value') ){
                        errors = { amount_other: "Minimum amount should be KES "+$("#withdrawal_popup .mini-text").attr('value') };
                        validator.showErrors(errors);
                        return false;
                    }

                    if( ( parseInt($('#withdrawal_popup [name^=amount_other]').val().trim()) < $("#withdrawal_popup .mini-text").attr('value')) || ( parseInt($('#withdrawal_popup [name^=amount_other]').val().trim()) > $("#withdrawal_popup .max-text").attr('value') )  ){
                        errors = { amount_other:  "Amount should be between " + ' ' + formatCurrency($("#withdrawal_popup .mini-text").attr('value'),"KES","KES")+ " to " + formatCurrency($("#withdrawal_popup .max-text").attr('value'),"KES","KES")+ "." };
                        validator.showErrors(errors);
                        return false;
                    }
                }



                var with_value = "";
                if( $("#withdrawal_popup").find('.wallTxn-amount-input-field.active').length > 0 ){
                    with_value = $("#withdrawal_popup").find('.wallTxn-amount-input-field.active').val().split(" ")[1];
                }
                else
                {
                    with_value = $('#withdrawal_popup [name^=amount_other]').val();
                }

                var params = 'paymentTypeId=' + $("#with_paytypeId").val() +
                    '&subTypeId=' + $("#with_subType").val() +
                    '&amount=' + with_value +
                    '&paymentTypeCode=' + $("#with_payTypeCode").val() +
                    '&redeemAccId=' + $("#with_payment_account").val() +
                    '&isCashierUrl=1' +
                    '&CurrencyCode=KES';

                startAjax("/component/betting/?task=withdrawal.requestWithdrawalDetails",params, processWithdrawalRequest, "#" + cashier_withdrawal_id);

            }
        });
    });


    $($("form[id^='add-email-form']")).each(function () {
        var add_email_form = $(this).attr('id');
        withdrawal_form_id_for_response = add_email_form;
        withdrawal_form_id_for_response[add_email_form] = $("#" + add_email_form).attr('error-callback');


        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                add_email: {
                    required: true,
                    pattern: "^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                }
            },

            messages: {
                add_email: {
                    required: "Please enter email address.",
                    pattern: "Please enter valid email address"
                }
            },

            submitHandler: function (form) {


                $("#add_email_modal input[name='email']").val($("#add_email").val());

                var form = $(document.createElement('form'));
                $(form).attr("action", "/component/betting/?task=ram.updatePlayerProfile");
                $(form).attr("method", "POST");

                var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "email")
                    .val($("#add_email").val());

                $(form).append($(input));

                form.appendTo(document.body)

                $(form).submit();
                // $("#add-email-form").attr("action","/component/betting/?task=ram.updatePlayerProfile");
                // $("#add-email-form").submit();

                // $("#otpcode-email").val('');
                // $("#email_verify").modal('show');
                // $("#resend-link-show-email").css("display", "none");
                // $("#modal-email").html($("#email").val());
                // sendVerificationCode('/component/betting/?task=ram.sendVerificationCode', "email", "email_verify", "btn",$("#add_email").val());


            }
        });
    });

    $(".wallTxn-amount-input-field-max").on('keyup', function () {
        $(".wallTxn-amount-input-field").removeClass('active');
        $(this).addClass('active');
    });


    $("[profile_email]").on("click", function () {
        if( $(this).attr("profile_email") == "ADD" ){
            $("#add_email_modal .h2-title").html('Add Email?');
            $("#add_email_modal").modal('show');
        }
        else if( $(this).attr("profile_email") == "CHANGE" ) {
            $("#add_email_modal").modal('show');
            $("#add_email_modal .h2-title").html('Change Email?');
            $("#add_email").val($(this).attr('change_email'));
        }
        else if( $(this).attr("profile_email") == "VERIFY" ) {
            $("#otpcode-email").val('');
            $("#email_verify").modal('show');
            $("#resend-link-show-email").css("display", "none");
            $("#modal-email").html($(this).attr('change_email'));
            sendVerificationCode('/component/betting/?task=ram.sendVerificationCode', "email", "email_verify", "btn");
        }

    });


});


function paymentOptionsResponse(result) {
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);
    if(res.errorCode != 0) {
        return false;
    }

    try {

        var payTypeId =  res.payTypeMap[Object.keys(res.payTypeMap)[0]].payTypeId;
        var subTypeId = Object.keys(res.payTypeMap[Object.keys(res.payTypeMap)[0]].subTypeMap)[0];
        var paymentGateway = res.payTypeMap[Object.keys(res.payTypeMap)[0]].subTypeMap[Object.keys(res.payTypeMap[Object.keys(res.payTypeMap)[0]].subTypeMap)];
        var paymentTypeCode = res.payTypeMap[Object.keys(res.payTypeMap)[0]].payTypeCode;
        var curr = res.payTypeMap[Object.keys(res.payTypeMap)[0]].currencyMap[Object.keys(res.payTypeMap[Object.keys(res.payTypeMap)[0]].currencyMap)];
        var minVal = res.payTypeMap[Object.keys(res.payTypeMap)[0]].minValue;
        var maxVal = res.payTypeMap[Object.keys(res.payTypeMap)[0]].maxValue;

        if( typeof res.paymentAccounts == "undefined" )
        {
            $("#addAccFor").val(res.for);

            $("#accSubTypeId").val(subTypeId);
            $("#accPaymentTypeCode").val(paymentTypeCode);
            $("#accPayTypeId").val(payTypeId);
            $("#accCurrency").val(curr);

            $("#add_account_modal").modal('show');
            return true;
        }

        var payAccId = res.paymentAccounts[Object.keys(res.paymentAccounts)].paymentAccId;

        updatePlayerBalance(false);
        if( res.for == "DEPOSIT" ){

            $("#paytypeId-1").val(payTypeId);
            $("#payTypeCode-1").val(paymentTypeCode);
            $("#subType-1").val(subTypeId);
            $("#payment_gateway-1").val(paymentGateway);
            $("#payment_account").val(payAccId);


            $("#deposit_popup .mini-text").attr('value',minVal);
            $("#deposit_popup .max-text").attr('value',maxVal);
            $("#deposit_popup .mini-text").text("Min. Amount: KES " + parseInt(minVal).toFixed(2));
            $("#deposit_popup .max-text").text("Max. Amount: KES "+ parseInt(maxVal).toFixed(2));

            //addRulesDeposit();
            $("#deposit_popup").modal('show');
        }
        else if( res.for == "WITHDRAWAL" ){

            $("#with_paytypeId").val(payTypeId);
            $("#with_payTypeCode").val(paymentTypeCode);
            $("#with_subType").val(subTypeId);
            $("#with_payment_gateway").val(paymentGateway);
            $("#with_payment_account").val(payAccId);

            $("#withdrawal_popup .mini-text").attr('value',minVal);
            $("#withdrawal_popup .max-text").attr('value',maxVal);
            $("#withdrawal_popup .mini-text").text("Min. Amount: KES "+ parseInt(minVal).toFixed(2));
            $("#withdrawal_popup .max-text").text("Max. Amount: KES "+ parseInt(maxVal).toFixed(2));

            $("#withdrawal_popup").modal('show');
        }
    }
    catch (e) {
        console.log(e);
    }

    return true;

}

function openNewRummyWindow(){
    try {
        var left = (screen.width / 2) - (1000 / 2);
        var top = (screen.height / 2) - (650 / 2);
        window.open(base_href + "/play-html-rummy", "HTMLrummyWindow", "height=650,width=1000,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no, left=" + left);
    } catch (e) {
        $("#loadingImage").css("display", "none");
    }
}

$(window).load(function () {
    if (window.opener != null)
        $(".zopim").hide();
});

function openLottoTvWindow()
{
    try {
        var left = (screen.width / 2) - (1000 / 2);
        var top = (screen.height / 2) - (650 / 2);
        window.open("http://35.156.30.113:8081/DrawGameWeb/DrawGameMachine.swf", "LottoTvWindow", "height=563,width=1000,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no, left=" + left);
    } catch (e) {
        $("#loadingImage").css("display", "none");
    }
}

function sprintf(lang_str, args) {
    var count = (lang_str.match(/%s/g)).length;
    for (i = 0; i < count; i++) {
        lang_str = lang_str.replace('%s', args[i]);
    }
    return lang_str;
}

function openLoginModal() {
    $("#home_login #submiturl").val("");
    $('#home_login').modal('show');
}

function getFormattedAmount(amount) {
    amount = amount + "";
    amount = amount.replace(",", "");
    var tmp = amount.split(".");
    if (tmp.length == 2) {
        if (tmp[1].length > 2) {
            if (tmp[1].substr(0, 2) != "00")
                return number_format(amount, 2);
        } else if (tmp[1].length == 2) {
            if (tmp[1] != "00")
                return number_format(amount, 2);
        } else if (tmp[1].length == 1) {
            if (tmp[1] != "0")
                return number_format(amount, 2);
        } else {
            return number_format(amount, 2);
        }
    }
    return number_format(amount, 2);
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '')
            .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k)
                        .toFixed(prec);
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
            .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
            .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
                .join('0');
    }
    return s.join(dec);
}


function errorDisplay($msg, $type, $redirct) {
    $('#error_popup').find('.msg').html('<p>' + $msg + '</p>');
    if ($type == 'error') {
        $('#error_popup').find('.oopsDiv .imgWrap').show();
    } else if ($type == 'info') {
        $('#error_popup').find('.oopsDiv .imgWrap').hide();
    } else if ($type == 'success') {
        $('#error_popup').find('.oopsDiv .imgWrap').hide();
    }
    if ($redirct) {
        $(this).on('hidden.bs.modal', function (event) {
            window.location = "/";
        });
    }
    $('#error_popup').modal('show');
    return true;
}

function openDGEUrl() {
    location.href = domain + "/draw";
}

function openDepositUrl() {
    location.href = domain + "/my-wallet";
}

function getCurrentTime() {
    var params = "";
    startAjax(GET_SERVER_TIME, params, formateServerTime, 'nottoshow');
}

function getServerTime(date, className) {
    var params = "date=" + date + "&className=" + className;
    startAjax(GET_SERVER_TIME, params, parseServerTimeResponse, 'null');
}

function parseServerTimeResponse(result) {
    var res = JSON.parse(result);
    $(res.className + " .gameTimer .daytime").html(res.days);
    $(res.className + " .gameTimer .hrtime").html(res.hrs);
    $(res.className + " .gameTimer .mintime").html(res.min);
    $(res.className + " .gameTimer .sectime").html(res.sec);
}

function getGameTime(gamename) {
    var game = {
        "LOTTO": "20:30:00",
        "LOTTOPLUS": "20:30:00",
        "LOTTOPLUS2": "20:30:00",
        "POWERBALL": "20:30:00",
        "POWERBALLPLUS": "20:30:00",
        "PICK3": "20:00:00",
        "RAPIDO": "23:45:00",
        "DAILYLOTTO": "21:00:00",
        "SPORTSTAKE": "19:30:00",
        "SS08": "18:30:00"
    }
    return game[gamename];
}

function parseDate(input, format) {
    format = format || 'yyyy-mm-dd H:i:s'; // default format
    var parts = input.match(/(\d+)/g),
        i = 0, fmt = {};
    // extract date-part indexes from the format
    format.replace(/(yyyy|dd|mm|H|i|s)/g, function (part) {
        fmt[part] = i++;
    });
    return new Date(parts[fmt['yyyy']], parts[fmt['mm']] - 1, parts[fmt['dd']], parts[fmt['H']], parts[fmt['i']], parts[fmt['s']]);
}

function formateServerTime(result) {
    var res = JSON.parse(result);
    if (typeof timerArray != undefined) {
        timerArray.forEach(function (item) {

            try{
                if( typeof item.unitCostJson != "undefined" ) {
                    var costJson = JSON.parse(item.unitCostJson);
                    for( var i in costJson ){
                        if( costJson[i].currency == defaultCurrencyCode ){
                            var jpAmt = item.jackpotAmount * costJson[i].price;
                            var jpTitle = formatCurrency(number_format(jpAmt), defaultCurrencyCode, defaultCurrencyDisp);
                            $(item.class + " .imgDesc").attr("data-text", jpTitle);
                            $(item.class + " .imgDesc").html(jpTitle);
                        }

                    }
                }
            }
            catch (e) {
            }

            var extra_data = {};
            var date_now = res.dateTime.date;
            if (item.game == "RAFFLE") {
                var date_new = item.date;
            } else{
                // var date_new = item.date + ' ' + getGameTime(item.game);
                var date_new = item.date;
            }
            
            //INIT DRAW_DATE TO INIT extra_data
            if (item.draw_date && item.draw_date != "") {
                var draw_date = parseDate(item.draw_date);
            }
            var date_now_obj = parseDate(date_now);
            var date_new_obj = parseDate(date_new);
            if (draw_date) {
                if (date_now_obj < draw_date) {
                    var date_new_obj = parseDate(item.draw_date);
                    extra_data['title'] = "Opening Date";
                } else if (date_now_obj < date_new_obj) {
                    var date_new_obj = parseDate(date_new);
                    extra_data['title'] = "Closing:";
                } else {
                    extra_data['title'] = "Draw Closed";
                    if (item.game == "RAFFLE") {
                        extra_data['date'] = item.date;
                    } else
                        extra_data['date'] = item.date + " " + getGameTime(item.game);
                }
            }
            var date_now_time = date_now_obj.getTime();
            var date_new_time = date_new_obj.getTime();
            var diff = date_new_time - date_now_time;
            if (date_now_time < date_new_time){
                if (item.game == "POWERBALL") {
                    let days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var dayToshow = new Date(date_new);
                    dayToshow = days[dayToshow.getDay()];
                    //$(".sliderDay span").text('monday');
                    $(item.class).find(".sliderDay span").text(dayToshow.toUpperCase());
                }
                difference_ms = diff / 1000;
                var seconds = Math.floor(difference_ms % 60);
                difference_ms = difference_ms / 60;
                var minutes = Math.floor(difference_ms % 60);
                difference_ms = difference_ms / 60;
                var hours = Math.floor(difference_ms % 24);
                var days = Math.floor(difference_ms / 24);
                days = days > 9 ? "" + days : "0" + days;
                hours = hours > 9 ? "" + hours : "0" + hours;
                minutes = minutes > 9 ? "" + minutes : "0" + minutes;
                seconds = seconds > 9 ? "" + seconds : "0" + seconds;
                $(item.class + " .gameTimer .daytime").html(days);
                $(item.class + " .gameTimer .hrtime").html(hours);
                $(item.class + " .gameTimer .mintime").html(minutes);
                $(item.class + " .gameTimer .sectime").html(seconds);
            } else {
                $(item.class + " .contentWrap").addClass('noTimer');
                $(item.class + " .gameTimer").hide();
                $(item.class).find(".sliderDay").hide();
            }
            //formateFooterTime(item.game, days, hours, minutes, seconds, extra_data)
        });
    }
}

function getCameCase(str) {
    var words = str.split(" ");
    var CamelString = "";
    words.forEach(function (value, key) {
        CamelString += value.charAt(0).toUpperCase() + value.substr(1) + " ";
    })
    return CamelString;
}

function openMyPages(title){
    window.location.href = myPagesList[title];
}

/**To Update Slider Timer of all pages (ITHUBA SPECIFIC custom_slider) */
function update_timer() {
    $(".gameTimer").map(function (key, val) {
        var days = $(val).find(".daytime")[0];
        var hrs = $(val).find(".hrtime")[0];
        var min = $(val).find(".mintime")[0];
        var sec = $(val).find(".sectime")[0];
        if ($(days).html() == 0) {
            $($(val).find(".daytime")[0]).addClass('hide');
            $($(val).find(".sectime")[0]).removeClass('hide');
        } else{
            $($(val).find(".daytime")[0]).removeClass('hide');
            //$($(val).find(".sectime")[0]).addClass('hide');
        }
        if ($(sec).html() == 0) {
            if ($(min).html() == 0) {
                if ($(hrs).html() == 0) {
                    if ($(days).html() == 0) {
                        return false;
                    } else {
                        hrs.innerHTML = 23;
                        var tempDays = $(days).html() - 1
                        days.innerHTML = tempDays <= 9 ? "0" + tempDays : tempDays;
                    }
                } else {
                    min.innerHTML = 59;
                    var tempHrs = $(hrs).html() - 1
                    hrs.innerHTML = tempHrs <= 9 ? "0" + tempHrs : tempHrs;
                }
            } else {
                sec.innerHTML = 59;
                var tempmin = $(min).html() - 1;
                min.innerHTML = tempmin <= 9 ? "0" + tempmin : tempmin;
            }
        } else {
            var tempsec = $(sec).html() - 1;
            sec.innerHTML = tempsec <= 9 ? "0" + tempsec : tempsec;
        }
        $(val).removeClass('loader');
        $(val).parent().find(".imgDesc").removeClass('loader');
    });
}


function formatCurrency(amount,currency,dispCurrency) {
    if ((amount != undefined) || (currency != undefined)) {

        if(hide_decimal == true){
         if (amount != Math.floor(amount)) {
           var check_value = amount.split('.');
            if(check_value[1] == 0)
            amount = check_value[0];     
           } 
        }

        switch (currency) {
            case 'CFA':
                if ((amount != undefined) && (currency != undefined)) {
                    amount = amount.toString().replace(/,/g, " ");
                    amount = amount.replace('.', ",");
                    return amount + ' ' + dispCurrency;
                } else if (amount == undefined) {
                    return dispCurrency;
                } else if (currency == undefined) {
                    amount = amount.toString().replace(/,/g, " ");
                    amount = amount.replace('.', ",");
                    return amount;
                }
                break;

            default:
                if ((amount != undefined) && (currency != undefined)) {
                    return dispCurrency + ' ' + amount;
                } else if (amount == undefined) {
                    return dispCurrency;
                } else if (currency == undefined) {
                    return amount;
                }
                break;
        }

    } else {
        return '';
    }
}


function getOtpForAddingAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    //console.log(res);
    if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(deposit_add_account + ' #accNum', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
        else
            showToolTipErrorManual(deposit_add_account + ' #accNum', res.errorMsg, "bottom", $("#accNum"), error_callback_add_account["cashier-deposit-add-account-form"]);
    } else {
        $('#cashier-add-account-form').css('display', 'none');
        $('#cashier-otp-verification-form').css('display', 'block');
        $(".h2-title").text("Verify Account");
        $(".deposit-footer").html(
            "<div class='button_holder'><p><span class='heighlight_color'><strong><em>" + Joomla.JText._("NO_CODE_RECEIVED") + "</em></strong> " + Joomla.JText._("REQUEST_AGAIN") + "</span></p>" +
            "<button id='cashier_deposit_resendOtp' class='resendOtp heighlight_color'>" + Joomla.JText._("RESEND_OTP") + "</button></div>" +
            "<div class='form-group text-center' style='display:none;' id='cashier-resend-link-deposit'><p  class='send_msg'>" + Joomla.JText._("OTP_CODE_HAS_BEEN_SENT") + "</p>" +
            "</div>");
        $("#cashier-mobile").html($("#accNum").val());
        setTimeout(function () {
            $("#cashier-mobile").parent().parent().css('display', 'none');
        }, 3000);

    }
    $(document).ready(function () {
        $($("form[id^='cashier-otp-verification-form']")).each(function () {
            deposit_verify_otp_form = $(this).attr('id');
            error_callback_verifyotp[deposit_verify_otp_form] = $("#cashier-otp-verification-form").attr('error-callback');
            $(this).validate({
                showErrors: function (errorMap, errorList) {
                    displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
                },

                rules: {
                    deposit_otp: {
                        required: true,
                        //pattern: "^[-9]{0,6}(\.[0-9]{1,2})?$",
                        //notSmaller: true,
                        //decimalToTwo : true
                        rangelength: [4, 4]
                    },

                },

                messages: {
                    deposit_otp: {
                        required: Joomla.JText._('BETTING_PLAESE_ENTER_OTP'),
                        //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                        //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                        //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                        rangelength: Joomla.JText._('PLEASE_ENTER_VALID_OTP')
                    }
                },

                submitHandler: function () {
                    if ($("#" + deposit_verify_otp_form).attr('submit-type') != 'ajax') {
                        document.getElementById(deposit_verify_otp_form).submit();
                    } else {
                        var params = 'accNum=' + $("#" + deposit_add_account + " #accNum").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #accPaymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #accHolderName").val() + '&subTypeId=' + $("#" + deposit_add_account + " #accSubTypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #accPayTypeId").val() + '&isOtp=' + '1' + '&verifyOtp=' + $("#" + deposit_verify_otp_form + " #account_otp").val() + '&currencyCode=' + $("#"+deposit_add_account + " #accCurrency").val();
                        if( $("#addAccFor").val() == "WITHDRAWAL" ){
                            params += "&forattr=withdraw_cash_button";
                        }
                        else
                        {
                            params += "&forattr=add_cash_button";
                        }
                        startAjax("/component/betting/?task=cashier.AddNewAccount", params, getResponseForOTP, "#" + deposit_verify_otp_form);
                    }

                }
            });
        });
    });
}


function getResponseForOTP(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    //console.log(res);
    if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 530)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', res.errorMsg, "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
    } else {
        $("#add_account_modal").modal('hide');
        success_message(Joomla.JText._("ACCOUNT_ADDED_SUCCESSFULLY"));
        setTimeout(function () {
            jQuery("#system-message-container").html('')
        }, 7000);
        // var params = '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val();
        // startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemAccount, "#" + deposit_verify_otp_form);

        $("["+ res.forattr +"]").click();

        return true;
    }
}


function processWithdrawalRequest(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    // $("#" + id).trigger("reset");
    $('.modal').modal('hide');
    if (res.errorCode != 0) {
        //$('[id^=error_amount_withdrawal]').html(res.errorMsg).show();

        if( typeof res.respMsg != "undefined" ){
            error_message(res.respMsg,"system-message");
        }
        else
        {
            error_message(res.errorMsg,"system-message");
        }


//      if(res.errorCode == 308)
//      showToolTipErrorManual(form_id + ' input[id^="withdrawal"]', Joomla.JText._("BETTING_INSUFFICIENT_PLAYER_BALANCE"), "bottom", $("#"+form_id).find("input[id^='withdrawal']"), withdrawal_form_id_for_response[form_id]);
//      else if(res.errorCode == 1102)
//      showToolTipErrorManual(form_id + ' input[id^="withdrawal"]', Joomla.JText._("BETTING_INSUFFICIENT_PLAYER_BALANCE"), "bottom", $("#"+form_id).find("input[id^='withdrawal']"), withdrawal_form_id_for_response[form_id]);
//      else if(res.errorCode == 317)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 1013)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("INVALID_SUBTYPE_ID"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 318)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_NOT_EXIST"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 423)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_DOES_NOT_EXIST_FOR_THIS_PLAYER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 1012)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 121)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("INVALID_CURRENCY"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 308)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 210)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("YOUR_TRANSACTION_HAS_BEEN_BLOCKED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 102)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 601)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_INVALID_DOMAIN"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 112)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_OPERATION_NOT_SUPPORTED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 601)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_INVALID_DOMAIN"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 602)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("UNAUTHENTIC_SERVICE_USER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else if(res.errorCode == 309)
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("YOUR_PAYMENT_HAS_BEEN_FAILED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
//      else
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', res.respMsg, "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
    } else {
        updatePlayerBalance();
        withdrawabalBalance = getFormattedAmount(parseFloat(res.withdrawableBal, 2)).replace(",", "");
        //DisableWithdrawalBtn();
        success_message('Withdrawal Request Initiate Successfully');
        //var params = 'fromDate=2020-02-13&toDate=2023-02-25&offset=0&limit=50&isCashierUrl=' + '1' + '&txnType=' + 'WITHDRAWAL';
        if( typeof page_nam != 'undefined' && page_nam === "balance"){
            var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'WITHDRAWAL';
            startAjax("/component/betting/?task=cashier.getDepositDetails", params, getWithdrawalResponse, null);
        }

        //$("#withdrawal-amount-form").css('display','none');
        //$(".withdrawal-title").text('');
        //$("#successfull-withdrawal-form").css('display','block');
        //$("#withTrnx_id").text(res.txnId);
    }
}



function getWithdrawalResponse(result) {
    // var tmp_fromPrev = fromPrev;
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    console.log(res);
    if (res.errorCode == 0) {
        $("#with_details").show();
        updatePlayerBalance();
        var amount = $('#amount').val();
        $("[id='Withrawal_wallet']").css("display", "block");
        // $(".iniWithBTN").hide();
        $('#redeem_amount').text(amount);
        // $('#otp_one').text(otp[0]);
        // $('#otp_two').text(otp[1]);
        // $('#otp_three').text(otp[2]);
        // $('#otp_four').text(otp[3]);
        // $('#otp_five').text(otp[4]);
        // $('#otp_six').text(otp[5]);
        $('#msg').text('');

        if (res.txnList.length <= 0)
        {
            $("#with_details").hide();
            $('#with_txn_table').hide();
            $('#cashier-withdrawal-div').css('display', 'none');
            //error_message("No Withdrawal Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('WITHDRAWL_NO_DETAIL'));
            $("#error-div").css("display", "");
            return false;
        }
        // clearSystemMessage();
        $('#cashier-withdrawal-div').css('display', 'block');
        $('#with_txn_table .tableBody').empty();

        var totRows = 100;
        limitReached = false;
        lastPageNo = 0;
        if (res.txnList.length <= limit) {
            totRows = res.txnList.length;
            limitReached = true;
        }

        if (totRows < 11)
            $('#cashier-withdrawal-table tfoot .pagination').css("display", "none");
        else
            $('#cashier-withdrawal-table tfoot .pagination').css("display", "block")

        for (var i = 0; i < totRows; i++) {

            var footable = $('#cashier-withdrawal-table').data('footable');

            var txnid = '';
            var txndate = '';
            var amount = '';
            var refTxnNo = '';
            var refTxnDate = '';
            var status = '';
            var otp = '';
            var sNo = '';


            if (typeof res.txnList[i].userTxnId != 'undefined')
                txnid = res.txnList[i].userTxnId;
            if (typeof res.txnList[i].txnDate != 'undefined') {
                txndate = res.txnList[i].txnDate;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
            }

            if (typeof res.txnList[i].txnDate != 'undefined') {
                txndate = res.txnList[i].txnDate;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
                txndate = txndate.split(' ');
                dateIndexOne = txndate[0];
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne);
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                txndate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + txndate[1]
            }

            if (typeof res.txnList[i].amount != 'undefined')
                amount = getFormattedAmount(parseFloat(res.txnList[i].amount, 2));
//            if(typeof res.withdrawalList[i].refTxnNo != 'undefined')
//                refTxnNo = res.withdrawalList[i].refTxnNo;
//            if(typeof res.withdrawalList[i].refTxnDate != 'undefined') {
//                refTxnDate = res.withdrawalList[i].refTxnDate;
//                var tmp = refTxnDate.lastIndexOf(".");
//                refTxnDate = refTxnDate.substring(0, tmp);
//            }
            if (typeof res.txnList[i].status != 'undefined')
                status = res.txnList[i].status;
//
//            if( res.withdrawalList[i].verificationCode != 'undefined' && res.withdrawalList[i].txnType.toUpperCase() == "OFFLINE")
//                otp = res.withdrawalList[i].verificationCode;
//            else
//                otp = '';
//            var cancelIcon = '';
//
//            if(res.withdrawalList[i].status.toUpperCase() != "PENDING") {
//                if(res.withdrawalList[i].status.toUpperCase() == "DONE")
//                    cancelIcon = '<a><img src="/templates/shaper_helix3/images/my_account/done-icon.png"></a>';
//                else if(res.withdrawalList[i].status.toUpperCase() == "INITIATED" || res.withdrawalList[i].txnType.toUpperCase() == "OFFLINE")
//                    cancelIcon = '<a href="javascript:;" style="color: red !important;"><span class="icon-remove-1" trans-id="'+txnid+'"><img src="/templates/shaper_helix3/images/my_account/cancel_icon.png">'+Joomla.JText._('BETTING_CANCEL_REQUEST')+'</span></a>';
//            }
            sNo = i + 1;
            var txnDayMonth = '';
            var txnYearTime = '';
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

            var newRow = '<tr style="text-align: center">' +
                '<td>' + sNo + '</td>' +
                '<td>' + txndate + '</td>' +
                '<td>' + txnid + '</td>' +
                '<td>' + formatCurrency(amount, decSymbol) + '</td>' +
                //                    '<td>' + otp + '</td>' +
                '<td>' + status + '</td>' +
                //'<td style="text-align: left">'+cancelIcon+'</td>' +
                '</tr>';


            newRow = '<div class="tableRow">\n' +
                '                        <div class="col col1">\n' +
                '                            <span class="">'+ (offset + i + 1) +'</span>\n' +
                '                        </div>\n' +
                '                        <div class="col col2">\n' +
                '                            <div class="table-inner-data"><label class="table-heading-label">'+ txnDayMonth +'</label> <span class="table-heading-span">'+ txndate +'</span></div>\n' +
                '                        </div>\n' +
                '                        <div class="col col3">\n' +
                '                            <p class="table-heading-p">'+txnid+'</p>\n' +
                '                        </div>\n' +
                '                        <div class="col col4">\n' +
                '                            <span class="status-tag">'+ status +'</span>\n' +
                '                        </div>\n' +
                '                        <div class="col col5">\n' +
                '                            <div class="table-inner-data"><label class="table-heading-label ">'+ formatCurrency(amount,currencyCode,currencySymbol) +'</label></div>\n' +
                '                        </div>\n' +
                '                    </div>';


            // footable.appendRow(newRow);
            $("#with_txn_table .tableBody").append(newRow);
        }

        // $('#cashier-withdrawal-table').trigger('footable_redraw');
        // if (offset == 1) {
        //     $('#cashier-withdrawal-table').trigger('footable_initialize');
        //     $('#footer-pagination-div-withdrawal').children().children().first().addClass(' disabled');
        //     $('#cashier-withdrawal-table tfoot').addClass('hide-if-no-paging');
        // } else {
        //     $('#cashier-withdrawal-table tfoot').removeClass('hide-if-no-paging');
        //     if (totRows < 10)
        //         $('#footer-pagination-div-withdrawal').children().children().last().addClass(' disabled');
        //     //resetPageNo();
        // }
        // if (tmp_fromPrev) {
        //     $("#footer-pagination-div-withdrawal>ul>li").last().prev().children().trigger('click');
        // }


    } else {
//        if(res.errorCode == 434){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_PLAYER_AMOUNT_LIMIT_EXCEEDS'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }else if(res.errorCode == 209){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INSUFFICIENT_PLAYER_BALANCE'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }else if(res.errorCode == 205){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INVALID_AMOUNT'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }else if(res.errorCode == 103){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INVALID_REQUEST'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }else if(res.errorCode == 102){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }
//        else if( res.errorCode == 308 ){
//            showToolTipErrorManual(withdraw_form_id + ' #amount', "No payment options available.", "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
//        }
//        $("[id='Withrawal_wallet']").css("display", "none");
//        $(".iniWithBTN").show();
//        //$('#msg').text(otp);
    }
}


function addRulesDeposit()
{
    minDepositAmount = $("#deposit_popup .mini-text").attr('value');
    minDepositLimit = $("#deposit_popup .mini-text").attr('value');
    maxDepositAmount = $("#deposit_popup .max-text").attr('value');
    maxDepositLimit = $("#deposit_popup .max-text").attr('value');

    $("form[id^='cashier-deposit-form'] #deposit_value").rules( "add", {
        required: true,
        min: minDepositLimit,
        range: [minDepositLimit , maxDepositLimit],
        messages: {
            required: "Please enter amount to deposit.",
            //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
            min: "Minimum amount should be " + ' ' + formatCurrency(minDepositAmount,"KES","KES") + " to deposit.",
            //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
            range: "Amount should be between " + ' ' + formatCurrency(minDepositAmount,"KES","KES")+ " to " + formatCurrency(maxDepositAmount,"KES","KES")+ "."
        }
    });
}


// $(window).scroll(function() {
//     if( $('body').hasClass('homePage') ){
//         // if ($(window).scrollTop() == $(document).height() - $(window).height()) {
//         if ( $("body").attr("loaded") === "true" && $(window).scrollTop() + $(window).height() + 100 >= $('#sp-bottom').offset().top) {
//
//             if (gameListMasterCnt >= gameListOffset) {
//                 $("body").attr("loaded",false);
//                 var params = "lobby_cat="+$("[game_type_btn].active").attr('game_type_btn')+"&limit="+ gameListLimit +"&offset=" + gameListOffset;
//                 startAjax("index.php/component/betting/?task=api.getCasinoGameList", params, getCasinoGameListResponse, 'null');
//             }
//
//         }
//     }
//
// });



