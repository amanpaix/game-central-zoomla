var $ = jQuery.noConflict();
//var change_password_form_submitted = false;
var error_callback_cp = {};
var change_password_form_id;
$(document).ready(function() {
     $.validator.addMethod("nameRegex", function(value, element) {
    	        return this.optional(element) || /^[a-z0-9]*$/i.test(value);
    	    }, Joomla.JText._('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED'));
});
$(document).ready(function () {
    $($("form[id^='change-password-form']")).each(function () {
        change_password_form_id = $(this).attr('id');
        error_callback_cp["change-password-form"] = $("#change-password-form").attr('error-callback');
        $("#change-password-form").validate({
            showErrors: function (errorMap, errorList) {
                var style = 'bottom';
                if ($("#change-password-form").attr('validation-style') == undefined) {
                    if ($("#change-password-form").attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                } else
                    style = $("#change-password-form").attr('validation-style');

                if ($("#change-password-form").attr('tooltip-mode') == "bootstrap") {
                    displayToolTip(this, errorMap, errorList, style, error_callback_cp["change-password-form"]);
                } else if ($("#change-password-form").attr('tooltip-mode') == "manual") {
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_cp["change-password-form"]);
                }
            },
            rules: {
                currentPassword: {
                    required: true,
                    //nameRegex:true,
                    pattern: /^[a-zA-Z0-9^:!@#().+?,_$&%*]*$/,
                    minlength: 3,
                    maxlength:16
                },
                newPassword: {
                    required: true,
                    //nameRegex:true,
                    pattern: /^[a-zA-Z0-9^:!@#().+?,_$&%*]*$/,
                    minlength: 3,
                    maxlength:16
                },
                retypePassword: {
                    required: true,
                    //nameRegex:true,
                    pattern: /^[a-zA-Z0-9^:!@#().+?,_$&%*]*$/,
                    minlength: 3,
                    maxlength:16,
                    equalTo: "#newPassword"
                }
            },

            messages: {
                currentPassword: {
                    required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_REQUIRED'),
                    pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                    minlength: 'Your Password must be at least 3 characters long.',
                    maxlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH')


                },
                newPassword: {
                    required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED'),
                    pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                    minlength: 'Your Password must be at least 3 characters long.',
                    maxlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH')

                },
                retypePassword: {
                    required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_RETYPE_REQUIRED'),
                    pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                    equalTo: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_RETYPE_EQUAL'),
                    minlength: 'Your Password must be at least 3 characters long.',
                    maxlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH')
                }
            },
            submitHandler: function () {
//                if (change_password_form_submitted == true) {
//                    return false;
//                } else {
//                    change_password_form_submitted = true;
                    $("#change-password-form #submit").attr('disabled', 'disabled');
//                return true;
//                }
                var params = 'currentPassword=' + encodeURIComponent($("#" + change_password_form_id + " #currentPassword").val()) + '&newPassword=' + encodeURIComponent($("#" + change_password_form_id + " #newPassword").val());
                login_form_id_for_response = change_password_form_id;
                startAjax("/component/betting/?task=account.changePassword", params, getChangePasswordResponse, "#" + change_password_form_id);
            }
        });

        $("#currentPassword, #newPassword, #retypePassword").keypress(function (e) {
            if (e.which == 13) {
                $("#change-password-form #submit").trigger('click');
            }
        });
    });
});

$(document).ready(function(){
    $("#currentPassword").keyup(function(){
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
    $("#newPassword").keyup(function(){
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
    $("#retypePassword").keyup(function(){
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


function getChangePasswordResponse(result)
 {
     if (validateSession(result) == false)
         return false;
     var res = $.parseJSON(result);
     if (res.errorCode != 0) {
         $("#" + change_password_form_id + " #submit").removeAttr("disabled");
         if (res.errorCode == 102) {
         showToolTipErrorManual(change_password_form_id + ' #currentPassword', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#currentPassword"), error_callback_cp["change-password-form"]);
     }else if(res.errorCode == 408){
          showToolTipErrorManual(change_password_form_id + ' #currentPassword', Joomla.JText._('BETTING_OLD_INCORRECT_PASSWORD'), "bottom", $("#currentPassword"), error_callback_cp["change-password-form"]);
     }else if(res.errorCode == 410){
          showToolTipErrorManual(change_password_form_id + ' #newPassword', Joomla.JText._('BETTING_NEW_PASSWORD_CANT_BE_FROM_LAST'), "bottom", $("#newPassword"), error_callback_cp["change-password-form"]);
     }else if(res.errorCode == 409){
          showToolTipErrorManual(change_password_form_id + ' #newPassword', Joomla.JText._('BETTING_CURRENT_AND_NEW_PASSWORD_CANT_BE_SAME'), "bottom", $("#newPassword"), error_callback_cp["change-password-form"]);
     }else if(res.errorCode == 543){
          showToolTipErrorManual(change_password_form_id + ' #newPassword', Joomla.JText._('WAVER_PLEASE_PROVIDE_VALID'), "bottom", $("#newPassword"), error_callback_cp["change-password-form"]);
     }else { 
         showToolTipErrorManual(change_password_form_id + ' #currentPassword', res.respMsg, "bottom", $("#currentPassword"), error_callback_cp["change-password-form"]);
     }
         return false;
     }
     errorDisplay(Joomla.JText._('BETTING_PASSWORD_SUCEESSFULLY_CHANGED'), 'success');
     setTimeout(function(){ location.href = res.path; }, 650);
     //location.href = res.path;
 }
