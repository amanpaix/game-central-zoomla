var $ = jQuery.noConflict();
var login_form_id_for_response = "";
var error_callback_login = {};
var mobile_min_length = 9;
var mobile_max_length = 9;
var retailer_login_id;
var error_callback_retailer = {};
var fakeLoginParams = "";

function addPasswordRules(form_id)
{
     $('#'+form_id+' #password').rules('add', {
        required: true,
        pattern : /^[a-zA-Z0-9^:!@#().+?,_$&%*]*$/,
        rangelength: [3, 16],
        messages: {
            required: Joomla.JText._('LOGIN_ERROR'),
            pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
            rangelength: Joomla.JText._('BETTING_PASSWORD_SHOULD_BE_IN_RANGE')
        }
    });
}

$(document).ready(function() {
    // debugger;
    $($("form[id^='login-form']")).each(function() {
        var login_form_id = $(this).attr('id');
        error_callback_login[login_form_id] = $("#"+login_form_id).attr('error-callback');
        $(this).validate({
            showErrors: function(errorMap, errorList) {
                var style = 'bottom';
                if($("#"+login_form_id).attr('validation-style') == undefined) {
                    if ($("#"+login_form_id).attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                }
                else
                    style = $("#"+login_form_id).attr('validation-style');

                if($("#"+login_form_id).attr('tooltip-mode') == "bootstrap") {
                    displayToolTip(this, errorMap, errorList, style, error_callback_login[login_form_id]);
                }
                else if($("#"+login_form_id).attr('tooltip-mode') == "manual") {
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_login[login_form_id]);
                }
            },
            submitHandler: function() {
               if( $("#"+login_form_id+" #password").attr("type") == "text")
                {
                    $(".toggleTypeLogin").click();
                }
                var encPwd = MD5(encodeURIComponent($("#"+login_form_id+" #password").val()));
                $("#"+login_form_id+" #password").rules( "remove", "rangelength" );
                $("#"+login_form_id+" #password").val(MD5(MD5($("#"+login_form_id+" #password").val()) + loginToken));
                $("#"+login_form_id+" #submit-btn").attr("disabled", "disabled");
                if($("#"+login_form_id).attr('submit-type') != 'ajax') {
                    $("#"+login_form_id+" #loginToken").val(loginToken);
                    $("#"+login_form_id+" #encPwd").val(encPwd);
                    document.getElementById(login_form_id).submit();
                }
                else {
                    var params = 'userName_email='+$("#"+login_form_id+" #userName_email").val()+'&password='+$("#"+login_form_id+ " #password").val()+'&loginToken='+loginToken+'&encPwd='+encPwd;
                    if($("#"+login_form_id+" #submiturl").val() != undefined)
                        if ($("#" + login_form_id + " #submiturl").val()){
                            if( (window.location.hash.match(/#/g) || []).length > 1 ){
                                window.location.hash = "";
                            }
                            params += "&submiturl=" + encodeURIComponent($("#" + login_form_id + " #submiturl").val() + window.location.hash);
                        }
                        else{
                            params += "&submiturl=" + window.location.href + window.location.hash;
                        }

                    login_form_id_for_response = login_form_id;

                    startAjax("/component/betting/?task=ram.playerLogin",params,getLoginResponse,"#"+login_form_id);
                }
            }
        });

        $('#'+login_form_id+' #userName_email').rules('add', {
            required: true,
            pattern :"^[6,7,8,9]{1}[0-9]{9}$",
            rangelength: [10,10],
            messages: {
                required: Joomla.JText._('BETTING_PLEASE_ENTER_MOBILE_NUMBER'),
                pattern: Joomla.JText._("BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER"),
                rangelength: Joomla.JText._('BETTING_USERNAME_MOBILE_SHOULD_BE_IN_RANGE')
            }
        });

        addPasswordRules(login_form_id);

        $('#'+login_form_id+' #password').on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                $('#'+login_form_id).submit();
            }
        });

    });
    $("#login_otp-submit").on('click', function () {

        startAjax("/component/betting/?task=ram.playerLogin", fakeLoginParams, getLoginResponse, "#" + login_form_id_for_response);
    });




});

$(document).ready(function(){
    $("#password").keyup(function(){
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

function getLoginResponse(result)
{
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);

    if(res.errorCode != 0) {
        addPasswordRules(login_form_id_for_response);
        $("#"+login_form_id_for_response+" #submit-btn").removeAttr("disabled");
        $("#" + login_form_id_for_response + " #password").val('');

        var tooltip_placement = 'left';
        if($("#"+login_form_id_for_response).attr("validation-style") != 'undefined' && $("#"+login_form_id_for_response).attr("validation-style") != undefined) {
            tooltip_placement = $("#"+login_form_id_for_response).attr("validation-style");
        }

        if($("#"+login_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
            showToolTipError(login_form_id_for_response+ ' #userName_email', res.respMsg, tooltip_placement, error_callback_login[login_form_id_for_response]);
        }
        else if($("#"+login_form_id_for_response).attr('tooltip-mode') == "manual") {
            if(res.errorCode == 406)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email', Joomla.JText._('BETTING_EITHER_USERNAME_OR_PASSWORD_IS_INVALID'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
            else if(res.errorCode == 514)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_PLAYER_STATUS_IS_INACTIVE'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
           else if(res.errorCode == 107)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_DEVICE_TYPE_NOT_SUPPLIED'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
           else if(res.errorCode == 108)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_USER_AGENT_TYPE_NOT_SUPPLIED'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 102)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 110)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_APPTYPE_OR_LOGINDEVICE_MISSING'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 606)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_INVALID_ALIAS_NAME'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 605)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_APP_DOES_NOT_SUPPORT_YOUR_LOCATION'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 112)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_OPERATION_NOT_SUPPORTED'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 601)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_INVALID_DOMAIN'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 101)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_HIBERNATE_EXCEPTION'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 412)
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',Joomla.JText._('BETTING_YPUR_VERIFICATION_IS_PENDING'), tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else if(res.errorCode == 10567)
                showToolTipErrorManual(login_form_id_for_response+ ' #userName_email','Player is blocked. Please contact The Administrator.', tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
          else
            showToolTipErrorManual(login_form_id_for_response+ ' #userName_email',res.errorMessage, tooltip_placement, $("#userName_email"), error_callback_login[login_form_id_for_response]);
        }
        return false;
    }
    if(res.autoPassword == 'N'){
    $('#home_login').modal('hide');
    $('#retailer_login').modal('show');
    
    $(document).ready(function() {
     $.validator.addMethod("nameRegex", function(value, element) {
    	        return this.optional(element) || /^[a-z0-9]*$/i.test(value);
    	    }, Joomla.JText._('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED'));
     $.validator.addMethod("onlynum", function (value, element) {
               return this.optional(element) || /^[0-9]*$/.test(value);
        });
    $($("form[id^='retailer_login-form']")).each(function() {
    retailer_login_id = $(this).attr('id');   
    error_callback_retailer[retailer_login_id] = $("#"+retailer_login_id).attr('error-callback');
    $(this).validate({
     showErrors: function(errorMap, errorList) {
                var style = 'bottom';
                if($("#"+retailer_login_id).attr('validation-style') == undefined) {
                    if ($("#"+retailer_login_id).attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                }
                else
                    style = $("#"+retailer_login_id).attr('validation-style');
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_retailer[retailer_login_id]);
            },
            
            rules: {
                password_ret: {
                    required: true,
                    //nameRegex:true,
                    pattern: /^[a-z0-9]*$/,
                    minlength: 8,
                    maxlength: 16
                },
                confirm_password_ret: {
                    required: true,
                    //nameRegex:true,
                    pattern: /^[a-z0-9]*$/,
                    minlength: 8,
                    maxlength: 16,  
                    equalTo: "#password_ret",
                },
                phone: {
                required: true,
                //number: true,
                //onlynum: true,
                pattern: /^((?!(00))[0-9]{10})$/,
                rangelength: [mobile_min_length, mobile_max_length],
                },
                email: {
                email: true,
                pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                rangelength: [3,75],
                },
            },

            messages: {
                password_ret: {
                    required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED'),
                    pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                    minlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MINLENGTH'),
                    maxlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH')

                },
                confirm_password_ret: {
                    required: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_RETYPE_REQUIRED'),
                    pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                    minlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MINLENGTH'),
                    maxlength: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH'),
                    equalTo: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_RETYPE_EQUAL')
                },
                phone: {
                    required: Joomla.JText._('BETTING_PLEASE_ENTER_MOBILE_NUMBER'),
                    //number: Joomla.JText._('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC'),
                    //onlynum: Joomla.JText._('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC'),
////                exactlength: "Mobile No should be of "+mobile_max_length+" digits.",
                    pattern: Joomla.JText._('PLEASE_ENTER_A_VALID_MOBILE_NO'),
                    rangelength: (mobile_min_length == mobile_max_length) ? Joomla.JText._('BETTING_MOBILE_NO_MSG') + mobile_max_length + Joomla.JText._('BETTING_DIGITS') : Joomla.JText._('BETTING_MOBILE_NO_MSG') + mobile_min_length + " to " + mobile_max_length + Joomla.JText._('BETTING_MOBILE_NO_MSG'),
                },
                email: {
                    email: Joomla.JText._('FORM_JS_EMAIL_ADDRESS_IS_INVALID'),
                    pattern: Joomla.JText._('INVALID_EMAIL_FORMAT'),
                    rangelength: Joomla.JText._('EMAIL_ADDRESS_SHOULD_BE_BETWEEN_THREE_MSG'),
                }
            },
        submitHandler: function () {
                $("#retailer_login-form #submit").attr('disabled', 'disabled');
                var params = 'password=' + $("#" + retailer_login_id + " #password_ret").val() + '&requestType=' + 'PORTAL' + '&mobileNo=' + $("#" + retailer_login_id + " #phone").val() + '&emailId=' + $("#" + retailer_login_id + " #email").val() + "&submiturl=" + $("#" + retailer_login_id + " #submiturl").val();
                startAjax("/component/betting/?task=authorisation.getRetailLogin", params, getRetailLoginResponse, "#" + retailer_login_id);
            }
    });        
     });   
     });
    }
    else{
    if($("#"+login_form_id_for_response).attr("from-title") == "add_cash") {
        document.cookie = "launchCashierAfterLogin=true";
    }

    if($("#"+login_form_id_for_response).attr("from-title") == "play_rummy") {
        document.cookie = "launchRummyAfterLogin=true";
    }

    if($("#"+login_form_id_for_response).attr("from-title") == "play_new_rummy") {
        document.cookie = "launchHtmlRummyAfterLogin=true";
    }
	
	if($("#"+login_form_id_for_response).attr("from-title") == "open_raf") {
        //document.cookie = "launchReferAFriendAfterLogin=true";
        location.href = res.path+'refer-a-friend';
    }
    else
    {
        if ((window.location.href).includes("register")) {
            location.href = '/';
        } else {
             location.href = decodeURIComponent(res.path);
            // location.reload();
            // location.href = '/';
        }
    }
    }
}

function showPwd()
{
  alert("hi");
}

function topLoginBox(placement, $element)
{
    if(placement == "left") {
        $("div.tooltip#"+$element.attr("aria-describedby")).addClass("error_"+$element.attr("id"));
        $("div.tooltip#"+$element.attr("aria-describedby")).css({
            top : "",
            left : "-"+$("div.tooltip#"+$element.attr("aria-describedby")).css("width")
        });
    }
}

function login_inputGroupElement(placement, $element, type)
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

function checkLength(len,ele){
  var fieldLength = ele.value.length;
  if(fieldLength <= len){
    return true;
  }
  else
  {
    var str = ele.value;
    str = str.substring(0, str.length - 1);
    ele.value = str;
  }
}

function getRetailLoginResponse(result){
 if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);

    if(res.errorCode != 0) {
        $("#"+retailer_login_id+" #submit").removeAttr("disabled");
        if(res.errorCode == 102)
         showToolTipErrorManual(retailer_login_id + ' #password_ret', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 553)
        showToolTipErrorManual(retailer_login_id + ' #password_ret', Joomla.JText._('SOME_ERROR_DURING_VALIDATION_CHECK'), "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 519)
        showToolTipErrorManual(retailer_login_id + ' #phone', Joomla.JText._('MOBILE_NUMBER_IS_NOT_PROVIDED'), "bottom", $("#phone"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 408)
        showToolTipErrorManual(retailer_login_id + ' #password_ret', Joomla.JText._('OLD_PASSWORD_INCORRECT'), "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 409)
        showToolTipErrorManual(retailer_login_id + ' #confirm_password_ret', Joomla.JText._('BETTING_CURRENT_AND_NEW_PASSWORD_CANT_BE_SAME'), "bottom", $("#confirm_password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 410)
        showToolTipErrorManual(retailer_login_id + ' #confirm_password_ret', Joomla.JText._('BETTING_NEW_PASSWORD_CANT_BE_FROM_LAST'), "bottom", $("#confirm_password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 505)
        showToolTipErrorManual(retailer_login_id + ' #password_ret', Joomla.JText._('PLAYER_ALREADY_EXIST'), "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 502)
        showToolTipErrorManual(retailer_login_id + ' #email', Joomla.JText._('EMAIL_ID_ALREADY_EXIST'), "bottom", $("#email"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 543)
        showToolTipErrorManual(retailer_login_id + ' #email', Joomla.JText._('BETTING_PLEASE_PROVIDE_VALID_EMAIL_ID'), "bottom", $("#email"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 503)
        showToolTipErrorManual(retailer_login_id + ' #phone', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), "bottom", $("#phone"), error_callback_retailer["retailer_login-form"]);
        else if(res.errorCode == 103)
        showToolTipErrorManual(retailer_login_id + ' #password_ret', Joomla.JText._('BETTING_INVALID_REQUEST'), "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        else 
        showToolTipErrorManual(retailer_login_id + ' #password_ret', res.respMsg, "bottom", $("#password_ret"), error_callback_retailer["retailer_login-form"]);
        return false;
        }
        location.href = res.path;
}

      $(document).on("keyup", "#email", function (e) {
        var value = $(this).val();
        value = value.replace(/[^a-zA-Z0-9@._]/g, '');
        $(this).val(value);

   });
