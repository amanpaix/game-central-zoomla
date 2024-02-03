var $ = jQuery.noConflict();
$(document).ready(function () {
    $("#email").keyup(function () {
        var empty = false;
        $('#email').each(function () {
            if ($(this).val() == '') {
                empty = true;
            }
        });

        if (empty || !$(this).valid()) {
            $('.btn_do_verify').attr('disabled', true);
            $('.btn_do_verify').addClass('disabled');
        } else {
            $('.btn_do_verify').attr('disabled', false);
            $('.btn_do_verify').removeClass('disabled');
        }
        //    $(".btn_do_verify").attr('disabled', false);
    });
    if ($("#email").val() == '')
        $(".btn_do_verify").attr('disabled', true);
    else
        $(".btn_do_verify").attr('disabled', false);


    $(".btn_do_verify").click(function (event) {

    });

    $(".btn_do_verify").click(function (event) {
        $("#otpcode-email").val('');
        // $("#email_verify").modal({
        //     show: true,
        //     // keyboard: false,
        //     // backdrop: "static"
        // });
        $("#email_verify").modal('show');
        // return false;
        $("#resend-link-show-email").css("display", "none");
        // $("#email_verify").modal("hide");
        $("#modal-email").html($("#email").val());
        sendVerificationCode('/component/betting/?task=ram.sendVerificationCode', "email", "email_verify", "btn");
    });



    $("#emailVerificationModal-resend-link").click(function () {
        $("#resend-link-show-email").css("display", "block");
        sendVerificationCode('/component/betting/?task=ram.sendVerificationCode', "email", "email_verify", "resend-link", "#resend-link-show");
    });

    $("#mobileVerify-btn").click(function (event) {
        $("#otpcode-mobile").val('');
        $("#resend-link-show-mobile").css("display", "none");
        $("#mobile_verify").modal("hide");
        $("#modal-mobileNo").html($("#mobile").val());
        sendVerificationCode('/component/betting/?task=account.sendVerificationCode', "mobile", "mobile_verify", "btn");
    });
    $("#mobileVerificationModal-resend-link").click(function (event) {
        $("#resend-link-show-mobile").css("display", "block");
        $("#err-div").css("display", "none");
        sendVerificationCode('/component/betting/?task=account.sendVerificationCode', "mobile", "mobile_verify", "resend-link", "#resend-link-show-mobile");
    });

    $("#continue-btn-mobile").click(function () {
        $("#resend-link-show-mobile").css("display", "none");

        if ($("#otpcode-mobile").val() == "") {
            showToolTipErrorManual('otpcode-mobile', Joomla.JText._('FORM_JS_ENTER_CODE_MSG'), "bottom", $("#otpcode-mobile"), undefined);
            return false;
        }
        else if ($("#otpcode-mobile").val().trim().length != 4) {
            showToolTipErrorManual('otpcode-mobile', Joomla.JText._('FORM_JS_ENTER_CODE_SIZE_ERROR'), "bottom", $("#otpcode-mobile"), undefined);
            return false;
        }
        else if (isNaN($("#otpcode-mobile").val())) {
            showToolTipErrorManual('otpcode-mobile', Joomla.JText._('FORM_JS_ENTER_CODE_TYPE_ERROR'), "bottom", $("#otpcode-mobile"), undefined);
            return false;
        }

        verifyPlayer('/component/betting/?task=ram.verifyPlayer', $("#otpcode-mobile").val(), "mobile");
    });

    $("#continue-btn-email").click(function () {
        $("#resend-link-show-email").css("display", "none");

        if ($("#otpcode-email").val() == "") {
            showToolTipErrorManual('otpcode-email', Joomla.JText._('FORM_JS_ENTER_CODE_MSG'), "bottom", $("#otpcode-email"), undefined);
            return false;
        }
        else if ($("#otpcode-email").val().trim().length != 4) {
            showToolTipErrorManual('otpcode-email', Joomla.JText._('FORM_JS_ENTER_CODE_SIZE_ERROR'), "bottom", $("#otpcode-email"), undefined);
            return false;
        }
        else if (isNaN($("#otpcode-email").val())) {
            showToolTipErrorManual('otpcode-email', Joomla.JText._('FORM_JS_ENTER_CODE_TYPE_ERROR'), "bottom", $("#otpcode-email"), undefined);
            return false;
        }

        verifyPlayer('/component/betting/?task=ram.verifyPlayer', $("#otpcode-email").val(), "email");
    });

    $("#email_verify").on("hidden.bs.modal", function (e) {
        $("#resend-link-show-email").css("display", "none");
    });
    $("#mobile_verify").on("hidden.bs.modal", function (e) {
        $("#resend-link-show-mobile").css("display", "none");
    });
});

var modal_id = "";
var calFrom = "";
var linkId = '';
function sendVerificationCode(url, verificationField, id, call_from, link_id,mail_id='') {
    modal_id = id;
    calFrom = call_from;
    linkId = link_id;
    var params = "verificationField=" + verificationField.toUpperCase();
    if (verificationField.toUpperCase() == "MOBILE") {
        if ($("#mobile").val().length == 0) {
            showToolTipErrorManual('mobile', Joomla.JText._('FORM_JS_PLEASE_ENTER') + " " + Joomla.JText._('BETTING_FORM_MOBILE'), "bottom", $("#mobile"), undefined);
            return false;
        }
        if ($("#mobile").val().length != 10) {
            showToolTipErrorManual('mobile', Joomla.JText._('FORM_MOBILE_ERROR_LENGTH'), "bottom", $("#mobile"), undefined);
            return false;
        }
        if (isNaN($("#mobile").val())) {
            showToolTipErrorManual('mobile', Joomla.JText._('FORM_MOBILE_TYPE_ERROR'), "bottom", $("#mobile"), undefined);
            return false;
        }

        var pattern_regex = /^[0-9]{10}$/;
        if (pattern_regex.test($("#mobile").val()) == false) {
            showToolTipErrorManual('mobile', Joomla.JText._('FORM_MOBILE_INVALID_ERROR'), "bottom", $("#mobile"), undefined);
            return false;
        }

        params += "&mobileNo=" + $("#mobile").val();
        //$("#modal-mobileNo").html('+91-'+$("#mobile").val());
    } else {
        if( mail_id != '' ){
            params += "&emailId=" + mail_id;
        }
        else
        {
            params += "&emailId=" + $("#modal-email").text();
        }

        params += "&isOtpVerification=YES";
    }
    startAjax(url, params, getResponse, "null")
}

function getResponse(result) {
    if (validateSession(result) == false)
        return false;

    var res = JSON.parse(result);
    if (res.errorCode != 0) {
        $('#add_email_modal').modal('hide');
        $('#' + modal_id).modal('hide');
        error_message(res.errorMessage, null);
        return false;
    }
    if (calFrom == 'resend-link') {
        if (res.errorCode != 0) {
            $(linkId).html(res.respMsg);
        }
        $(linkId).css("display", "");
    }

    $('#' + modal_id).modal({
        show: true,
        // keyboard: false,
        // backdrop: 'static'
    });
}

function verifyPlayer(url, verificationCode, type) {
    $("#err-div").html('');
    $("#err-div").css('display', 'none');
    var param = "verificationField=" + type + "&verificationCode=" + verificationCode + "&verificationType=PROFILE" + "&emailId=" + $("#modal-email").text();
    startAjax(url, param, getResponseA, "null")
}

function getResponseA(result) {
    if (validateSession(result) == false)
        return false;

    var res = JSON.parse(result);
    if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_INVALID_ALIAS_NAME'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 101)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_HIBERNATE_EXCEPTION'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 102)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 10010) {
            if (res.verificationField == 'mobile')
                showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_PLEASE_ENTER_VALID_CODE_EMAIL'), "bottom", $("#otpcode-" + res.verificationField), undefined);
            else if (res.verificationField == 'email')
                showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_PLEASE_ENTER_VALID_CODE_MOBILE'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        }
        else if (res.errorCode == 2015)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_HIBERNATE_EXCEPTION_MSG'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 2001)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_ERROR_IN_COMMUNICATION_MODULE'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 530)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_OTP_CODE_IS_NOT_VALID'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 529)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_VERIFICATION_CODE_EXPIRED'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 10000)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_VERIFICAION_ERROR'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 419)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_VERIFICATION_CODE_IS_ALREADY_USED_TO_VERIFY'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 418)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_INVALID_VERIFICATION_CODE'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 103)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_INVALID_REQUEST'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else if (res.errorCode == 414)
            showToolTipErrorManual('otpcode-' + res.verificationField, Joomla.JText._('BETTING_PLAYER_ALREADY_EXIST'), "bottom", $("#otpcode-" + res.verificationField), undefined);
        else
            showToolTipErrorManual('otpcode-' + res.verificationField, res.errorMessage, "bottom", $("#otpcode-" + res.verificationField), undefined);
        return false;
    }
    $('#' + modal_id).modal('hide');
    $('#' + res.verificationField + '_div').removeClass('do_verify');
    $('#' + res.verificationField + '_div').addClass('verify');
    $('#' + res.verificationField).attr('readonly', 'readonly');
    location.reload();
}
