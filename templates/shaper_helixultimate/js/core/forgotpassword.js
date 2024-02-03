  var password_pattern = /^[a-zA-Z0-9^:!@#().+?,_$&%*]*$/;
  
  $(document).ready(function () {
       $.validator.addMethod("nameRegex", function(value, element) {
    	        return this.optional(element) || /^[a-z0-9]*$/i.test(value);
    	    }, Joomla.JText._('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED'));
  });

var $ = jQuery.noConflict();
var forgot_password_form_id = "";
var forgot_password_form_id_for_response = "";
var forgot_confirm_form_id_for_response = "";
var error_callback_forgot_password = {};
var error_callback_forgot_confirm = {};
var mobile = "";
var mobile_pattern = /^[6,7,8,9]{1}[0-9]{9}$/;

$(document).ready(function() {
    $($("form[id^='forgot-password']")).each(function() {
        var forgot_password_form_id = $(this).attr('id');
        error_callback_forgot_password[forgot_password_form_id] = $("#"+forgot_password_form_id).attr('error-callback');
        $(this).validate({
            showErrors: function(errorMap, errorList) {
                var style = 'bottom';
                if($("#"+forgot_password_form_id).attr('validation-style') == undefined) {
                    if ($("#"+forgot_password_form_id).attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                }
                else
                    style = $("#"+forgot_password_form_id).attr('validation-style');

                if($("#"+forgot_password_form_id).attr('tooltip-mode') == "bootstrap") {
                    displayToolTip(this, errorMap, errorList, style, error_callback_forgot_password[forgot_password_form_id]);
                }
                else if($("#"+forgot_password_form_id).attr('tooltip-mode') == "manual") {
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_forgot_password[forgot_password_form_id]);
                }
            },
            submitHandler: function() {
                var forgotLandingPageCall = false;
                if($('#forgotLandingPageCall').val() != 'undefined')
                    forgotLandingPageCall = true;

                if($("#"+forgot_password_form_id).attr('submit-type') != 'ajax') {
                    document.getElementById(forgot_password_form_id).submit();
                }
                else {
                    var params = 'forgot_email='+$("#"+forgot_password_form_id+" #forgot_username").val()+'&forgotLandingPageCall='+forgotLandingPageCall;
                    mobile = $("#"+forgot_password_form_id+" #forgot_username").val();
                    if($("#"+forgot_password_form_id+" #success-url").val() != undefined) {
                        params += 'success-url='+$("#"+forgot_password_form_id+" #success-url").val();
                    }
                    forgot_password_form_id_for_response = forgot_password_form_id;
                    startAjax("/component/betting/?task=forgotpassword.forgotPassword",params,getForgotPasswordResponse,"#"+forgot_password_form_id);
                }
            }
        });

        //$($('#'+forgot_password_form_id+' #forgot_email')).attr("pattern", "^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\\.\\-]?[a-zA-Z0-9]+){0,1})\\.([A-Za-z]{2,})$");
        $($('#'+forgot_password_form_id+' #forgot_username')).rules('add', {
            required: true,
            pattern: mobile_pattern,
            rangelength: [10,10],
            messages: {
                required: Joomla.JText._('BETTING_PLEASE_ENTER_YOUR_USERNAME_MOBILE'),
                pattern: Joomla.JText._("BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER"),
                rangelength: Joomla.JText._('BETTING_USERNAME_MOBILE_SHOULD_BE_IN_RANGE')
            }
        });
    });

    $($("form[id^='forgot-confirm']")).each(function() {
        var forgot_confirm_form_id = $(this).attr('id');
        error_callback_forgot_password[forgot_confirm_form_id] = $("#"+forgot_confirm_form_id).attr('error-callback');
        $(this).validate({
            showErrors: function(errorMap, errorList) {
                var style = 'bottom';
                if($("#"+forgot_confirm_form_id).attr('validation-style') == undefined) {
                    if ($("#"+forgot_confirm_form_id).attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                }
                else
                    style = $("#"+forgot_confirm_form_id).attr('validation-style');

                if($("#"+forgot_confirm_form_id).attr('tooltip-mode') == "bootstrap") {
                    displayToolTip(this, errorMap, errorList, style, error_callback_forgot_password[forgot_confirm_form_id]);
                }
                else if($("#"+forgot_confirm_form_id).attr('tooltip-mode') == "manual") {
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_forgot_password[forgot_confirm_form_id]);
                }
            },
            submitHandler: function() {
                {
                    var params = 'newPassword='+ encodeURIComponent($("#"+forgot_confirm_form_id+" #forgot_newpassword").val())+'&retypePassword='+ encodeURIComponent($("#"+forgot_confirm_form_id+" #forgot_confirmpassword").val())+'&playerotp='+$("#"+forgot_confirm_form_id+" #forgot_otp").val()+'&forgot_mobile='+mobile;
                    if($("#"+forgot_confirm_form_id+" #success-url").val() != undefined) {
                        params += 'success-url='+$("#"+forgot_confirm_form_id+" #success-url").val();
                    }
                    forgot_confirm_form_id_for_response = forgot_confirm_form_id;
                    startAjax("/component/betting/?task=forgotpassword.resetPasswordForgot",params,getForgotPasswordCOnfirmationResponse,"#"+forgot_confirm_form_id);
                }
            }
        });

        //$($('#'+forgot_password_form_id+' #forgot_email')).attr("pattern", "^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\\.\\-]?[a-zA-Z0-9]+){0,1})\\.([A-Za-z]{2,})$");
        $($('#'+forgot_confirm_form_id+' #forgot_newpassword')).rules('add', {
            required: true,
            //nameRegex:true,
            //alphanumeric: true,
            pattern: password_pattern,
            rangelength: [3,16],
            messages: {
                required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED'),
                //alphanumeric: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                rangelength: Joomla.JText._('BETTING_PASSWORD_SHOULD_BE_IN_RANGE')
            }
        });
        $($('#'+forgot_confirm_form_id+' #forgot_otp')).rules('add', {
            required: true,
            rangelength: [4,4],
            messages: {
                required: Joomla.JText._('BETTING_PLAESE_ENTER_OTP'),
                rangelength: Joomla.JText._('BETTING_OTP_SHOULD_BE_IN_RANGE')
            }
        });
        $($('#'+forgot_confirm_form_id+' #forgot_confirmpassword')).rules('add', {
            required: true,
            //nameRegex:true,
            //alphanumeric: true,
            pattern: password_pattern,
            rangelength: [8,16],
            equalTo : '#' + forgot_confirm_form_id + ' #forgot_newpassword',
            messages: {
                required: Joomla.JText._('BETTING_PLEASE_ENTER_YOUR_CONFIRM_PASSWORD'),
                //alphanumeric: Joomla.JText._('BETTING_INVALID_CONFIRM_PASSWORD_FORMAT'),
                pattern: Joomla.JText._('BETTING_INVALID_CONFIRM_PASSWORD_FORMAT'),
                rangelength: Joomla.JText._('BETTING_CONFIRM_PASSWORD_SHOULD_BE_IN_RANGE'),
                equalTo: Joomla.JText._('BETTING_CONFIRM_PASSWORD_NOT_EQUAL'),
            }
        });
    });
});

$(document).ready(function(){
    $("#forgot_newpassword").keyup(function(){
        //debugger
        if($(this).val().length >0 ){
            var arrValue = [32,34,39,45,47,59,60,61,62,91,92,93,96,123,124,125,126];
            var txtlen = $(this).val().length -1;
            var txtVal = $(this).val();
            
            for(var i = 0; i <= txtlen ; i++){
                if (arrValue.indexOf($(this).val().charCodeAt(i)) > -1)
                {
                    txtVal = txtVal.replace(String.fromCharCode($(this).val().charCodeAt(i)), "");
                }
            }
            $(this).val(txtVal)
        }
    });
});

$(document).ready(function(){
    $("#forgot_confirmpassword").keyup(function(){
        //debugger
        if($(this).val().length >0 ){
            var arrValue = [32,34,39,45,47,59,60,61,62,91,92,93,96,123,124,125,126];
            var txtlen = $(this).val().length -1;
            var txtVal = $(this).val();
            
            for(var i = 0; i <= txtlen ; i++){
                if (arrValue.indexOf($(this).val().charCodeAt(i)) > -1)
                {
                    txtVal = txtVal.replace(String.fromCharCode($(this).val().charCodeAt(i)), "");
                }
            }
            $(this).val(txtVal)
        }
    });
});


function getForgotPasswordCOnfirmationResponse(result)
{
    if(validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    if(res.errorCode != 0) {
        var tooltip_placement = 'left';
        if($("#"+forgot_confirm_form_id_for_response).attr("validation-style") != 'undefined' && $("#"+forgot_confirm_form_id_for_response).attr("validation-style") != undefined) {
            tooltip_placement = $("#"+forgot_confirm_form_id_for_response).attr("validation-style");
        }

        if($("#"+forgot_confirm_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
            showToolTipError(forgot_confirm_form_id_for_response+ ' #forgot_newpassword', res.respMsg, tooltip_placement, error_callback_forgot_confirm[forgot_password_form_id_for_response]);
        }
        else if($("#"+forgot_confirm_form_id_for_response).attr('tooltip-mode') == "manual") {
           if (res.errorCode == 529) {
             showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_otp', Joomla.JText._('BETTING_OTP_CODE_HAS_BEEN_EXPIRED'), tooltip_placement, $("#forgot_otp"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 530) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_otp', Joomla.JText._('BETTING_OTP_CODE_IS_NOT_VALID'), tooltip_placement, $("#forgot_otp"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 609) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('BETTING_INVALID_PASSWORD'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 103) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('BETTING_INVALID_REQUEST'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 606) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('BETTING_INVALID_ALIAS_NAME'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 543) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('WAVER_PLEASE_PROVIDE_VALID'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 542) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('BETTING_IS_NOT_PROVIDED'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else if(res.errorCode == 553) {
               showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', Joomla.JText._('SOME_ERROR_DURING_VALIDATION_CHECK'), tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
           }else{
            showToolTipErrorManual(forgot_confirm_form_id_for_response + ' #forgot_newpassword', res.respMsg, tooltip_placement, $("#forgot_newpassword"), error_callback_forgot_password[forgot_password_form_id_for_response]);
        }
    }
        return false;

    }

        $("#"+forgot_confirm_form_id_for_response).parents("div.modal").modal('hide');
        $("#"+forgot_confirm_form_id_for_response+" #forgot_username").val("");
        errorDisplay(Joomla.JText._('BETTING_PASSWORD_RESET_SUCCESSFULLY'), 'success');

        return true;
}

function getForgotPasswordResponse(result)
{
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);
    if(res.errorCode != 0) {
        var tooltip_placement = 'left';
        if($("#"+forgot_password_form_id_for_response).attr("validation-style") != 'undefined' && $("#"+forgot_password_form_id_for_response).attr("validation-style") != undefined) {
            tooltip_placement = $("#"+forgot_password_form_id_for_response).attr("validation-style");
        }

        if($("#"+forgot_password_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
            showToolTipError(forgot_password_form_id_for_response+ ' #forgot_username', res.respMsg, tooltip_placement, error_callback_forgot_password[forgot_password_form_id_for_response]);
        }
        else if($("#"+forgot_password_form_id_for_response).attr('tooltip-mode') == "manual") {
            if (res.errorCode == 543)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_PLEASE_PROVIDE_VALID_USERNAME_MOBILE'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 606)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_INVALID_ALIAS_NAME'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 112)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_OPERATION_NOT_SUPPORTED'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 606)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_INVALID_ALIAS_NAME'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 601)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_INVALID_DOMAIN'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 101)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_HIBERNATE_EXCEPTION'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 102)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 542)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_IS_NOT_PROVIDED'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 103)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_PLEASE_PROVIDE_VALID_MOBILE'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 514)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_PLAYER_STATUS_IS_INACTIVE'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 508)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_PLAYER_INFO_NOT_FOUND'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else if (res.errorCode == 533)
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', Joomla.JText._('BETTING_RSA_ID_NOT_FOUND'), tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
            else
                showToolTipErrorManual(forgot_password_form_id_for_response + ' #forgot_username', res.respMsg, tooltip_placement, $("#forgot_username"), error_callback_forgot_password[forgot_password_form_id_for_response]);
        }
        return false;
    }

    if($("#"+forgot_password_form_id_for_response).attr("home-forgot-modal") == 'true') {
        // errorDisplay("An OTP has been sent via SMS to your mobile number. Please enter the OTP to reset your PIN.", 'success');
        $("#"+forgot_password_form_id_for_response).parents("div.modal").modal('hide');
        $("#home_forgot_thank").modal("show");
        $("#home_forgot_thank").find("#response_email").html($("#"+forgot_password_form_id_for_response+" #forgot_username").val());
        $("#"+forgot_password_form_id_for_response+" #forgot_username").val("");
    }
    else {
        location.href = res.path;
    }
}

function fp_inputGroup(placement, $element, type)
{
    if(type == "error") {
        if($element.parents('.input-group').length > 0) {
            $element.parents('.input-group').addClass("error");
        }
    }
    else if(type == "success") {
        if($element.parents('.input-group').length > 0) {
            $element.parents('.input-group').removeClass("error");
        }
    }
}
