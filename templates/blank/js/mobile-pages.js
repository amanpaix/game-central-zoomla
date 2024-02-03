var $ = jQuery.noConflict();
$(document).ready(function () {
    $('.customTab > .item').on('click', function () {
        if (!$(this).hasClass('active')) {
            $('.customTab > .item.active').removeClass('active');
            $(this).addClass('active');
 	    doContentActive();
        }
    });
	doContentActive();
})
function doContentActive() {
    $('.tabContentWrap .tabContentItem').fadeOut('fast');
    $('#' + $('.customTab > .item.active').attr('data-id')).fadeIn();	
}

function gameDescription(blkItemId) {
    $('.page.page1').fadeOut('fast');
    $('.page.page2, #' + blkItemId).fadeIn();
    $(window).delay(500).scrollTop(0);
    if(typeof Android != "undefined"){
        Android.hideHeader();
    }else{
        webkit.messageHandlers.hideHeader.postMessage("*");
    }
}
function backFromGameDesc() {
    $('.page.page2, .page.page2 .itemModule').fadeOut('fast');
    $('.page.page1').fadeIn();
}

function openPDFinMobile(url){
    var pos = url.search('nationallottery.co.za/');
    if(pos !=  -1){
        url = url.substr(parseInt(pos) + parseInt("nationallottery.co.za".length));
    }

    if(typeof Android != "undefined"){
        Android.showPDF(url);
    }else{
        var dictionary = {"url":url};
        webkit.messageHandlers.showPDF.postMessage(dictionary);
    }
}