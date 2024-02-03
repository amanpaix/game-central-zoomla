try {
    var $ = jQuery.noConflict();
    var error_callback_registration = {};
    var error_callback_otp = {};
    var mobile_min_length = 9;
    var mobile_max_length = 9;
    var mobile_pattern = /^[7,1]{1}[0-9]{8}$/;
    var password_pattern = /^[a-z0-9]*$/;

    $(document).ready(function () {
        $.validator.addMethod('selectoption', function (value) {
            return (value != 'select');
        });

        $.validator.addMethod("exactlength", function (value, element, param) {
            return this.optional(element) || value.length == param;
        }, "Please enter exactly {0} characters.");

        $.validator.addMethod("onlynum", function (value, element) {
            return this.optional(element) || /^[0-9]*$/.test(value);
        });

        $.validator.addMethod("alphabets", function (value, element) {
            return this.optional(element) || /^[a-zA-Z]{1}[a-zA-Z ]*$/.test(value);
        });
        $.validator.addMethod("nameRegex", function (value, element) {
            return this.optional(element) || /^[a-z0-9]*$/i.test(value);
        }, Joomla.JText._('BETTING_SPECIAL_CHARACTERS_NOT_ALLOWED'));

        $.validator.addMethod("capital", function (value, element) {
            return this.optional(element) || value == value.toLowerCase();
        }, 'Uppercase letters are not allowed');

        // $("#otp_confirm_1").on("keyup", function () {
        //     $(".verify-input-group-error").empty();
        //     $("#otp_confirm_2").focus();
        // });
        // $("#otp_confirm_2").on("keyup", function () {
        //     $(".verify-input-group-error").empty();
        //     $("#otp_confirm_3").focus();
        // });
        // $("#otp_confirm_3").on("keyup", function () {
        //     $(".verify-input-group-error").empty();
        //     $("#otp_confirm_4").focus();
        // });
        // $("#otp_confirm_4").on("keyup", function () {
        //     $(".verify-input-group-error").empty();
        //     $("#otp-submit").focus();
        // });

        // $(document).on('input', '.mandatory_otp_class input[type="text"],.mandatory_otp_class input[type="password"],.mandatory_otp_class input[type="number"],.mandatory_otp_class input[type="tel"]', function(){
        $(document).on('input', '.verify-input-group input[type="text"]', function(){
            var iMaxLength = $(this).attr('maxlength1');
            if(typeof iMaxLength !== 'undefined' && iMaxLength){
                if($(this).val().length > iMaxLength){
                    $(this).val($(this).val().substr(0, iMaxLength));
                }
            }
        })

        $(document).on('keyup change', '.verify-input-group input[type="text"]', function(){
            $(".verify-input-group-error").empty();
            var $t = $(this);
            if ($t.val().length > 0) {
                $t.next().focus();
            }
        });

        $("#otp_confirm_1, #otp_confirm_2, #otp_confirm_3, #otp_confirm_4").on('keyup', function () {
            if( $("#otp_confirm_1").val().trim().length == 1 &&
                $("#otp_confirm_2").val().trim().length == 1 &&
                $("#otp_confirm_3").val().trim().length == 1 &&
                $("#otp_confirm_4").val().trim().length == 1
            )
            {
                $("#otp-submit").removeAttr("disabled");
            }
            else
            {
                $("#otp-submit").attr("disabled", "disabled");
            }
        });

    });

    var reg_form_id_for_response = "";
    var otp_form_id_for_response = "";
    $(document).ready(function () {
        $($("form[id^='registration-form']")).each(function () {
            var reg_form_id = $(this).attr('id');
            reg_form_id_for_response = reg_form_id;
            error_callback_registration[reg_form_id] = $("#" + reg_form_id).attr('error-callback');

            $(this).validate({
                showErrors: function (errorMap, errorList) {
                    var style = 'bottom';
                    if ($("#" + reg_form_id).attr('validation-style') == undefined) {
                        if ($("#" + reg_form_id).attr('submit-type') == "ajax") {
                            style = 'left';
                        }
                    } else
                        style = $("#" + reg_form_id).attr('validation-style');

                    if ($("#" + reg_form_id).attr('tooltip-mode') == "bootstrap") {
                        displayToolTip(this, errorMap, errorList, style, error_callback_registration[reg_form_id]);
                    } else if ($("#" + reg_form_id).attr('tooltip-mode') == "manual") {
                        displayToolTipManual(this, errorMap, errorList, style, error_callback_registration[reg_form_id]);
                    }
                },
                submitHandler: function () {

                    $("#" + reg_form_id + " #submit-btn").attr("disabled", "disabled");
                    if ($("#" + reg_form_id).attr('submit-type') != 'ajax') {
                        document.getElementById(reg_form_id).submit();
                    } else {

                        var params = 'mobile=' + $("#" + reg_form_id + " #mobile").val() + '&registrationType=' + $("#" + reg_form_id + " #registrationType").val();

                        if ($('#' + reg_form_id + " #chbTerms").length > 0) {
                            params += "&chbTerms=" + $("#" + reg_form_id + " #chbTerms").val();
                        }

                        if ($('#' + reg_form_id + " #mobile").length > 0) {
                            params += "&userName=" + $("#" + reg_form_id + " #mobile").val();
                        }

                        if ($('#' + reg_form_id + " #firstName").length > 0) {
                            params += "&firstName=" + $("#" + reg_form_id + " #fname").val();
                        }

                        if ($('#' + reg_form_id + " #disp_name").length > 0) {
                            params += "&firstName=" + $("#" + reg_form_id + " #disp_name").val();
                        }

                        if ($('#' + reg_form_id + " #surName").length > 0) {
                            params += "&surName=" + $("#" + reg_form_id + " #lname").val();
                        }

                        params += '&reg_password=' + encodeURIComponent($("#" + reg_form_id + " #reg_password").val());

                        if ($('#' + reg_form_id + " #confirm_password").length > 0) {
                            params += '&confirm_password=' + $("#" + reg_form_id + " #confirm_password").val();
                        }

                        if ($('#' + reg_form_id + " #g-recaptcha-response").length > 0) {
                            params += '&g-recaptcha-response=' + $("#" + reg_form_id + " #g-recaptcha-response").val();
                        }
                        var response = grecaptcha.getResponse();

                        if (response.length == 0) {
                            //reCaptcha not verified
                            alert(Joomla.JText._('BETTING_PLEASE_VERIFY_YOU_ARE_HUMAN'));
                            $("#" + reg_form_id + " #submit-btn").removeAttr("disabled");
                            return false;
                        }

                        //                        if ($('#' + reg_form_id + " #rsa").length > 0) {
                        //                            params += '&rsa=' + $("#" + reg_form_id + " #rsa").val();
                        //                        }

                        if ($('#' + reg_form_id + " #refercode").length > 0) {
                            params += "&refercode=" + $("#" + reg_form_id + " #refercode").val();
                        }

                        //                        if ($('#' + reg_form_id + " #referType").length > 0) {
                        //                            params += "&referType=" + $("#" + reg_form_id + " #referType").val();
                        //                        }

                        //                        if ($('#' + reg_form_id + " #state").length > 0) {
                        //                            params += "&state=" + $("#" + reg_form_id + " #state").val();
                        //                        }
                        //                        /*
                        //                         if(current_registration_type == "FULL") {
                        //                         params += "&fname=" + $("#"+reg_form_id+" #fname").val() + "&lname=" + $("#"+reg_form_id+" #lname").val() + "&gender=" + $('input[name="gender"]:checked', "#"+reg_form_id).val() + "&address=" + $("#"+reg_form_id+ " #address").val() + "&city=" + $("#"+reg_form_id+" #city").val() + "&dob=" + $("#"+reg_form_id+" #dob").val() + "&pincode=" + $("#"+reg_form_id+" #pincode").val();
                        //                         }
                        //                         */
                        if ($('#' + reg_form_id + " #currency").length > 0) {
                            params += "&currency=" + $("#" + reg_form_id + " #currency").val();
                        }
                        //
                        //                        if ($('#' + reg_form_id + " #emailMarkt").length > 0) {
                        //                            params += "&emailMarkt=" + $("#" + reg_form_id + " #emailMarkt").val();
                        //                        }
                        //
                        //                        if ($('#' + reg_form_id + " #smsMarkt").length > 0) {
                        //                            params += "&smsMarkt=" + $("#" + reg_form_id + " #smsMarkt").val();
                        //                        }

                        if ($("#" + reg_form_id + " .submiturl").val() != undefined) {
                            params += "&submiturl=" + $("#" + reg_form_id + " .submiturl").val();
                        }
                        reg_form_id_for_response = reg_form_id;

                        if ($("#" + reg_form_id + " #otp_enable").val() == "1") {
                            startAjax("/component/betting/?task=ram.registrationOTP", params, getOtpResponse, "#" + reg_form_id);
                        }
                        else {
                            startAjax("/component/betting/?task=registration.playerRegistration", params, getRegistrationResponse, "#" + reg_form_id);
                        }
                    }
                }
            });
            //$($('#'+reg_form_id+' #userName')).attr("pattern","/^[^A-Za-z0-9]+[a-zA-Z0-9]*$/");
            //$( '#'+reg_form_id+" #userName").attr("pattern", "^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)$");

            //$( '#'+reg_form_id+" #userName").attr("pattern", "^[a-zA-Z0-9]*$");

            if ($('#' + reg_form_id + " #userName").length > 0) {
                $($('#' + reg_form_id + ' #userName')).rules('add', {
                    required: true,
                    alphanumeric: true,
                    pattern: /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/,
                    rangelength: [8, 16],
                    messages: {
                        required: Joomla.JText._('PLEASE_ENTER_USERNAME'),
                        alphanumeric: Joomla.JText._('BETTING_ONLY_ALPHANUMERIC_CHRACTER_ACCEPTED'),
                        pattern: Joomla.JText._('BETTING_ONLY_ALPHANUMERIC_CHRACTER_ACCEPTED'),
                        rangelength: Joomla.JText._('BETTING_USERNAME_LENGTH_SHOULD_BE_EIGHT_TO_SIXTEEN')
                    }
                });
            }

            if ($('#' + reg_form_id + " #disp_name").length > 0) {
                $($('#' + reg_form_id + ' #disp_name')).rules('add', {
                    required: true,
                    alphanumeric: true,
                    rangelength: [3, 10],
                    messages: {
                        required: "Please enter display name",
                        alphanumeric: Joomla.JText._('BETTING_ONLY_ALPHANUMERIC_CHRACTER_ACCEPTED'),
                        rangelength: Joomla.JText._('BETTING_USERNAME_LENGTH_SHOULD_BE_EIGHT_TO_SIXTEEN')
                    }
                });
            }

            //            if ($('#' + reg_form_id + " #firstName").length > 0) {
            //                $($('#' + reg_form_id + ' #firstName')).rules('add', {
            //                    required: true,
            //                    alphabets: true,
            //                    rangelength: [3, 25],
            //                    maxlength: 25,
            //                    messages: {
            //                        required: "Please enter your First Name.",
            //                        alphabets: "First Name can only contain alphabets.",
            //                        rangelength: "First Name should be between contain 3 to 25 characters.",
            //                        maxlength: "Enter less than 25 characters",
            //                    }
            //                });
            //            }

            //            if ($('#' + reg_form_id + " #surName").length > 0) {
            //                $($('#' + reg_form_id + ' #surName')).rules('add', {
            //                    required: true,
            //                    alphabets: true,
            //                    rangelength: [3, 25],
            //                    maxlength: 25,
            //                    messages: {
            //                        required: "Please enter your Surname.",
            //                        alphabets: "Surname can only contain alphabets.",
            //                        rangelength: "Surname should be between 3 to 25 characters.",
            //                        maxlength: "Enter less than 25 characters",
            //                    }
            //                });
            //            }

            if ($('#' + reg_form_id + " #reg_password").length > 0) {
                $($('#' + reg_form_id + ' #reg_password')).rules('add', {
                    required: true,
                    //nameRegex:true,
                    //alphanumeric: true,
                    pattern: /^[a-zA-Z0-9^:!@#().+?,_$*&%]*$/,
                    rangelength: [3, 16],
                    messages: {
                        required: Joomla.JText._('LOGIN_ERROR'),
                        //alphanumeric: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                        pattern: Joomla.JText._('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR'),
                        rangelength: 'Password length should be between 3 to 16 characters.'
                    }
                });
            }

            if ($('#' + reg_form_id + " #confirm_password").length > 0) {
                $($('#' + reg_form_id + ' #confirm_password')).rules('add', {
                    required: true,
                    //nameRegex: true,
                    pattern: /^[a-zA-Z0-9^:!@#().+?,_$*&%]*$/,
                    rangelength: [3, 16],
                    equalTo: '#' + reg_form_id + ' #reg_password',
                    messages: {
                        required: Joomla.JText._('BETTING_PLEASE_RE_ENTER_YOUR_PASSWORD'),
                        pattern: Joomla.JText._('BETTING_INVALID_CONFIRM_PASSWORD_FORMAT'),
                        rangelength: 'Confirm Password should be between 3 to 16 characters.',
                        equalTo: Joomla.JText._('BETTING_CONFIRM_PASSWORD_NOT_EQUAL_TO_PASSWORD_FIELD'),
                    }
                });
            }

            if ($('#' + reg_form_id + " #chbTerms").length > 0) {
                $('#' + reg_form_id + ' #chbTerms').off('click').click(function () {
                    if (this.checked) {
                        $("#" + reg_form_id + " #submit-btn").removeAttr("disabled");
                    } else {
                        $("#" + reg_form_id + " #submit-btn").attr("disabled", "disabled");
                    }
                });
                $($('#' + reg_form_id + ' #chbTerms')).rules('add', {
                    required: true,
                    messages: {
                        required: "Please accept terms and conditions"
                    }
                });
            }
            /*
             var current_registration_type = $('#'+reg_form_id+' #registrationType').val();
             if(current_registration_type == 'FULL') {
             $( '#'+reg_form_id+" #fname" ).rules( "add", {
             required: true,
             alphanumeric: true,
             rangelength: [3, 50],
             messages: {
             required: "Please Enter First Name.",
             alphanumeric: "First Name can only contain alphabets, numbers and underscores.",
             rangelength: "First Name should be between contain 3 to 50 characters."
             }
             });

             $( '#'+reg_form_id+ " #lname" ).rules( "add", {
             required: true,
             alphanumeric: true,
             rangelength: [3, 50],
             messages: {
             required: "Please enter Last Name.",
             alphanumeric: "Last Name can only contain alphabets, numbers and underscores.",
             rangelength: "Last Name should be between 3 to 50 characters."
             }
             });
             }
             */

            //        $('#' + reg_form_id + " #email").attr("pattern", "^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\\.\\-]?[a-zA-Z0-9]+){0,1})\\.([A-Za-z]{2,})$");
            //            $('#' + reg_form_id + " #email").rules("add", {
            ////            required : function(element){
            ////                return $("#emailMarkt").val()=="1";
            ////            },
            //                required: true,
            //                email: true,
            //                rangelength: [3,75],
            //                pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            //                messages: {
            //                    required: "Please enter your Email Address.",
            //                    email: "Email Address is invalid",
            //                    rangelength: "Email Address should be between 3 to 75 characters.",
            //                    pattern: "Invalid Email Format",
            //                }
            //            });
            //
            //             $("#" + reg_form_id + " #mobile").attr("pattern", mobile_pattern);
            $("#" + reg_form_id + " #mobile").attr("maxlength", mobile_max_length);
            $('#' + reg_form_id + " #mobile").rules("add", {
                required: true,
                //number: true,
                //onlynum: true,
                //rangelength: [mobile_min_length, mobile_max_length],
                pattern: mobile_pattern,
                messages: {
                    required: Joomla.JText._('BETTING_PLEASE_ENTER_MOBILE_NUMBER'),
                    //number: Joomla.JText._('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC'),
                    //onlynum: Joomla.JText._('BETTING_MOBILE_NUMBER_SHOULD_BE_NUMERIC'),
                    pattern: Joomla.JText._('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER'),
                    //rangelength: (mobile_min_length == mobile_max_length) ? Joomla.JText._('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER') : Joomla.JText._('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER'),
                }
            });

            if ($('#' + reg_form_id + " #rsa").length > 0) {
                $('#' + reg_form_id + " #rsa").rules("add", {
                    required: true,
                    number: true,
                    onlynum: true,
                    rangelength: [13, 13],
                    pattern: /^(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))/,
                    validateDob: true,
                    messages: {
                        required: "Please enter your RSA ID Number.",
                        number: "RSA ID Number should be numeric.",
                        onlynum: "RSA ID Number should be numeric.",
                        rangelength: "RSA ID Number should be of 13 digits.",
                        pattern: "Invalid RSA ID Number",
                        validateDob: "Age must be greater than 18"
                    }
                });
            }

            if ($('#' + reg_form_id + " #referType").length > 0) {
                $('#' + reg_form_id + " #referType").rules("add", {
                    selectoption: true,
                    required: true,
                    messages: {
                        selectoption: "Please select Refer Type.",
                        required: 'Please select at least one option.'
                    }
                });
            }

            if ($('#' + reg_form_id + " #refercode").length > 0) {

                $("#" + reg_form_id + " #refercode").attr("maxlength", "25");
                $('#' + reg_form_id + " #refercode").rules("add", {
                    // required: true,
                    // alphanumeric: true,
                    messages: {
                        required: "Please enter refer code ",
                        alphanumeric: "Refer Code can only contain alphabets, numbers and underscores."
                    }
                });

            }

            /*
             if(current_registration_type == 'FULL') {
             $('#' + reg_form_id + " input[name='gender']").rules("add", {
             required: true,
             messages: {
             required: "Please select gender."
             }
             });

             $('#' + reg_form_id + " #address").rules("add", {
             required: true,
             rangelength: [5, 500],
             messages: {
             required: "Please Enter address.",
             rangelength: "Address should be between 5 to 500 characters"
             }
             });
             }
             */
            if ($('#' + reg_form_id + " #state").length > 0) {
                $('#' + reg_form_id + " #state").rules("add", {
                    required: true,
                    selectoption: true,
                    messages: {
                        selectoption: "Please select State.",
                        required: 'Please select at least one option.'
                    }
                });
            }

            if ($('#' + reg_form_id + " #emailMarkt").length > 0) {
                $('#' + reg_form_id + " #emailMarkt").rules("add", {
                    selectoption: true,
                    required: true,
                    messages: {
                        selectoption: "Please select Email Marketing.",
                        required: 'Please select at least one option.'
                    }
                });
            }

            if ($('#' + reg_form_id + " #smsMarkt").length > 0) {
                $('#' + reg_form_id + " #smsMarkt").rules("add", {
                    selectoption: true,
                    required: true,
                    messages: {
                        selectoption: "Please select Sms Marketing.",
                        required: 'Please select at least one option.'
                    }
                });
            }
            /*
             if(current_registration_type == 'FULL') {
             $( '#'+reg_form_id+" #city" ).rules( "add", {
             required: true,
             alphanumeric: true,
             rangelength: [3, 50],
             messages: {
             required: "Please enter city.",
             alphanumeric: "City can only contain alphabets, numbers and underscores.",
             rangelength: "City should be between 3 to 50 characters."
             }
             });

             $( '#'+reg_form_id+" #dob" ).rules( "add", {
             required: true,
             maxlength: 10,
             messages: {
             required: "Please enter Date of birth",
             }
             });

             $( '#'+reg_form_id+" #pincode" ).rules( "add", {
             required: true,
             onlynum: true,
             exactlength: 6,
             messages: {
             required: "Please enter pin code.",
             onlynum: "Pin code can only contain digits.",
             exactlength: "Pin code should be of 6 digits"
             }
             });
             }
             */
        });

        $($("form[id^='otp-registration-form']")).each(function () {
            var otp_form_id = $(this).attr('id');
            otp_form_id_for_response = otp_form_id;
            error_callback_otp[otp_form_id] = $("#" + otp_form_id).attr('error-callback');

            $(document).ready(function () {
                $("#reg_password").keyup(function () {
                    //debugger
                    if ($(this).val().length > 0) {
                        var arrValue = [32, 34, 39, 45, 47, 59, 60, 61, 62, 91, 92, 93, 96, 123, 124, 125, 126];
                        var txtlen = $(this).val().length - 1;
                        var txtVal = $(this).val();

                        for (var i = 0; i <= txtlen; i++) {
                            if (arrValue.indexOf($(this).val().charCodeAt(i)) > -1) {
                                txtVal = txtVal.replace(String.fromCharCode($(this).val().charCodeAt(i)), "");
                            }
                        }
                        $(this).val(txtVal)
                    }
                });
            });

            $(document).ready(function () {
                $("#confirm_password").keyup(function () {
                    //debugger
                    if ($(this).val().length > 0) {
                        var arrValue = [32, 34, 39, 45, 47, 59, 60, 61, 62, 91, 92, 93, 96, 123, 124, 125, 126];
                        var txtlen = $(this).val().length - 1;
                        var txtVal = $(this).val();

                        for (var i = 0; i <= txtlen; i++) {
                            if (arrValue.indexOf($(this).val().charCodeAt(i)) > -1) {
                                txtVal = txtVal.replace(String.fromCharCode($(this).val().charCodeAt(i)), "");
                            }
                        }
                        $(this).val(txtVal)
                    }
                });
            });

            $(this).validate({
                showErrors: function (errorMap, errorList) {
                    var style = 'bottom';
                    if ($("#" + otp_form_id).attr('validation-style') == undefined) {
                        if ($("#" + otp_form_id).attr('submit-type') == "ajax") {
                            style = 'left';
                        }
                    } else
                        style = $("#" + otp_form_id).attr('validation-style');

                    if ($("#" + otp_form_id).attr('tooltip-mode') == "bootstrap") {
                        displayToolTip(this, errorMap, errorList, style, error_callback_otp[otp_form_id]);
                    } else if ($("#" + otp_form_id).attr('tooltip-mode') == "manual") {
                        displayToolTipManual(this, errorMap, errorList, style, error_callback_otp[otp_form_id]);
                    }
                },
                submitHandler: function () {
                    //$('#'+reg_form_id+' #userName').val($('#'+reg_form_id+' #email').val());
                    $("#" + otp_form_id + " #otp-submit").attr("disabled", "disabled");
                    $("#resendMsg").empty();
                    $(".verify-input-group-error").empty();
                    if ($("#" + otp_form_id).attr('submit-type') != 'ajax') {
                        document.getElementById(otp_form_id).submit();
                    } else {
                        var otp_val =   $("#" + otp_form_id + " #otp_confirm_1").val().trim() +
                                        $("#" + otp_form_id + " #otp_confirm_2").val().trim() +
                                        $("#" + otp_form_id + " #otp_confirm_3").val().trim() +
                                        $("#" + otp_form_id + " #otp_confirm_4").val().trim();

                        var params = "otp_confirm=" + otp_val;
                        otp_form_id_for_response = otp_form_id;
                        startAjax("/component/betting/?task=ram.verifyOtpRegistration", params, getVerifyOtpResponse, "#" + otp_form_id_for_response);
                    }
                }
            });
            // $('#' + otp_form_id + " #otp_confirm").rules("add", {
            //     required: true,
            //     //number: true,
            //     //onlynum: true,
            //     exactlength: 4,
            //     pattern: /^[0-9]{4}$/,
            //     messages: {
            //         required: "Please enter your OTP.",
            //         //number: "OTP should be numeric.",
            //         //onlynum: "OTP should be numeric.",
            //         exactlength: "OTP No should be of 6 digits.",
            //         pattern: "Invalid OTP"
            //     }
            // });

            $("#otp_confirm_1").rules("add", {
                required: true,
                exactlength: 1,
                pattern: /^[0-9]{1}$/
            });
            $("#otp_confirm_2").rules("add", {
                required: true,
                exactlength: 1,
                pattern: /^[0-9]{1}$/
            });
            $("#otp_confirm_3").rules("add", {
                required: true,
                exactlength: 1,
                pattern: /^[0-9]{1}$/
            });
            $("#otp_confirm_4").rules("add", {
                required: true,
                exactlength: 1,
                pattern: /^[0-9]{1}$/
            });
        });

        var cookie_string = document.cookie.split(";");
        var res_gsp = "";
        for (var i = 0; i < cookie_string.length; i++) {
            var cookie_string_params = cookie_string[i].split('=');
            try {
                if (cookie_string_params[0].trim() == "GSP_uname") {
                    $('#' + reg_form_id_for_response + " #userName").val(decodeURIComponent(cookie_string_params[1]).replace(/\+/g, ' '));
                }
                if (cookie_string_params[0].trim() == "GSP_pwd") {
                    $('#' + reg_form_id_for_response + " #reg_password").val(decodeURIComponent(cookie_string_params[1]));
                }
                if (cookie_string_params[0].trim() == "GSP_mob") {
                    $('#' + reg_form_id_for_response + " #mobile").val(decodeURIComponent(cookie_string_params[1]));
                }
                if (cookie_string_params[0].trim() == "GSP_mail") {
                    $('#' + reg_form_id_for_response + " #email").val(decodeURIComponent(cookie_string_params[1]));
                }

                if (cookie_string_params[0].trim() == "GSP_res") {
                    res_gsp = decodeURIComponent(cookie_string_params[1]).replace(/\+/g, ' ');

                }
            } catch (e) {
            }
        }
        document.cookie = "GSP_uname" + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "GSP_pwd" + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "GSP_mob" + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "GSP_mail" + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "GSP_res" + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";

        if (res_gsp != "") {
            getRegistrationResponse(res_gsp);
        }

        //        $("#home_register_popup-otp").on('hidden.bs.modal', function (event) {
        //            sendPartialRegistrationCall();
        //        });

        //        window.onbeforeunload = function () {
        //            sendPartialRegistrationCall();
        //        }

        $($("form[id^='registration-form']")).each(function () {
            var reg_form_id = $(this).attr('id');
            if ($('#' + reg_form_id + " #email").attr('emailsuggestion') == "true") {
                $(function () {
                    var info = $('.info');
                    $('#' + reg_form_id + " #email").mailtip({
                        onselected: function (mail) {
                            $('#' + reg_form_id + " #mobile").focus();
                            // info.text('you choosed email: ' + mail)
                        }
                    });
                });
            }
        });


        // var $inputs = $('#email');
        // $inputs.on('keyup keypress blur', function() {
        //     $('#userName').val($(this).val());
        // });



    });


    /*
     var strarray= [ "gmail.com", "yahoo.com", "yahoo.co.in", "rediff.com", "hotmail.com",  "outlook.com" ];

     function showNameHint(str){
     //document.getElementById('popup').style.visibility = 'visible';.
     if(str.keyCode>=37 && str.keyCode <=40 ){

     }else{
     //var str = $('#email').val();
     $($("form[id^='registration-form']")).each(function()
     {
     var reg_form_id = $(this).attr('id');
     var reg_no = reg_form_id.substring(reg_form_id.lastIndexOf("-") + 1);

     // reg_form_id_for_response = reg_form_id;
     var str = $('#' + reg_form_id + " #email").val();
     if (str == "") {
     } else {
     //str = str.toLowerCase();

     var mail;
     var domain;
     //                var items = "<div id='textValue'>";
     // var items = "<div id='textValue"+reg_no+"'>";

     var items = "";
     var atIndex = str.indexOf('@');
     var hasAt = atIndex !== -1;


     if (hasAt) {
     domain = str.toLowerCase().substring(atIndex + 1);
     str = str.substring(0, atIndex);
     }

     for (var i = 0, len = strarray.length; i < len; i++) {
     mail = strarray[i].toLowerCase();

     if (hasAt && mail.indexOf(domain) !== 0) continue;

     items += '<option value="' + str + '@' + mail + '">';

     }
     // items += "</div>";
     $('#' + reg_form_id + ' #textValue'+reg_no).html(items);
     //var id='#' + reg_form_id + " #textValue";
     //document.getElementById(id).innerHTML = items;
     // document.getElementById("textValue").innerHTML = items;
     }
     });
     }

     }*/

    //    function sendPartialRegistrationCall()
    //    {
    //        var rsa_pattern = /^(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))/;
    //        var email_pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //        $($("form[id^='registration-form']")).each(function () {
    //            var formId = $(this).attr('id');
    //            if( rsa_pattern.test($("#"+ formId + " #rsa").val()) || email_pattern.test($("#"+ formId + " #email").val()) || mobile_pattern.test($("#"+ formId + " #mobile").val())  ){
    //                var params =  "emailId=" + $("#" + formId + " #email").val() + "&mobileNo=" + $("#" + formId + " #mobile").val() + "&rsaId=" + $("#" + formId + " #rsa").val() + "&registrationType=PARTIAL";
    //                startAjax("/component/Betting/?task=registration.partialRegistration", params, noFunction, "nottoshow");
    //            }
    //        });
    //    }


    function getRegistrationResponse(result) {

        if (validateSession(result) == false)
            return false;

        var res = $.parseJSON(result);
        if (res.errorCode != 0) {
            $("#" + reg_form_id_for_response + " #submit-btn").removeAttr("disabled");

            var tooltip_placement = 'left';
            if ($("#" + reg_form_id_for_response).attr("validation-style") != 'undefined' && $("#" + reg_form_id_for_response).attr("validation-style") != undefined) {
                tooltip_placement = $("#" + reg_form_id_for_response).attr("validation-style");
            }

            if ($("#" + reg_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
                if (res.errorCode == 501) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Username already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 422) {
                    showToolTipError(reg_form_id_for_response + ' #refercode', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 502) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email ID already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 503) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile No. already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 510) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email ID already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile No. already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 511) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Username already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile No. already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 512) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Username already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 513) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Username already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #email', "Email ID already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile No. already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 610) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Please Enter your Email ID.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile No.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 611) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Please Enter your Username.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile No.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 612) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Please Enter your Username.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #email', "Please Enter your Email ID.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 613) {
                    showToolTipError(reg_form_id_for_response + ' #userName', "Please Enter your Username.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #email', "Please Enter your Email.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile No.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else {
                    showToolTipError(reg_form_id_for_response + ' #userName', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                }
            } else if ($("#" + reg_form_id_for_response).attr('tooltip-mode') == "manual") {
                if (res.errorCode == 422) {
                    showToolTipErrorManual(reg_form_id_for_response + ' #refercode', res.respMsg, tooltip_placement, $("#refercode"), error_callback_registration[reg_form_id_for_response]);
                    //showToolTipError(reg_form_id_for_response + ' #refercode', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else
                    if (res.errorCode == 501) {
                        showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                    } else
                        if (res.errorCode == 502) {
                            showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                        } else
                            if (res.errorCode == 503) {
                                showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                            } else
                                if (res.errorCode == 510) {
                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                } else
                                    if (res.errorCode == 528) {
                                        showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                    } else
                                        if (res.errorCode == 534) {
                                            showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                        } else
                                            if (res.errorCode == 535) {
                                                showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                            } else
                                                if (res.errorCode == 536) {
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                }
                                                else if (res.errorCode == 543) {
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLEASE_PROVIDE_VALID_MOBILE'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                    grecaptcha.reset();
                                                }
                                                else
                                                    if (res.errorCode == 564) {
                                                        showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                        showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                    } else
                                                        if (res.errorCode == 565) {
                                                            showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                            showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                        } else
                                                            if (res.errorCode == 566) {
                                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                            } else
                                                                if (res.errorCode == 567) {
                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                } else
                                                                    if (res.errorCode == 539) {
                                                                        showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "Player must be 18 or older to play.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                        // showToolTipErrorManual(reg_form_id_for_response + ' #userName', "Username already exists.", tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                    } else
                                                                        if (res.errorCode == 568) {
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exists.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                        } else
                                                                            if (res.errorCode == 569) {
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                            } else
                                                                                if (res.errorCode == 570) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 610) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 611) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 613) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 103) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + " #g-recaptcha-response", "Capcha validation failed", "bottom", $("#g-recaptcha-response"), undefined);
                                                                                    grecaptcha.reset();
                                                                                } else {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', res.respMsg, tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                }
            }

            return false;
        }
        location.href = res.path;
    }

    //function verifyOtp()
    //{
    //    var params = "otp_confirm="+$("#registeration-otp-form #otp_confirm").val();
    //    startAjax("/component/Betting/?task=registration.verifyOtp", params, getVerifyOtpResponse, "#" + reg_form_id_for_response);
    //}

    function getVerifyOtpResponse(result) {
        if (validateSession(result) == false)
            return false;
        var res = $.parseJSON(result);
        $(".verify-input-group-error").empty().hide();
        if (res.errorCode != 0) {
            $(".verify-input-group-error").text(res.respMsg).show();
            $("#" + otp_form_id_for_response + " #otp-submit").removeAttr("disabled");
            var tooltip_placement = 'left';
            if ($("#" + otp_form_id_for_response).attr("validation-style") != 'undefined' && $("#" + otp_form_id_for_response).attr("validation-style") != undefined) {
                tooltip_placement = $("#" + otp_form_id_for_response).attr("validation-style");
            }
            if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
                if (res.errorCode == 528 || res.errorCode == 529 || res.errorCode == 530 || res.errorCode == 531 || res.errorCode == 539) {
                    showToolTipError(otp_form_id_for_response + ' #otp_confirm', res.respMsg, tooltip_placement, error_callback_otp[otp_form_id_for_response]);
                }
            } else if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "manual") {
                if (res.errorCode == 529) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_CODE_HAS_BEEN_EXPIRED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 530) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 107) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DEVICE_TYPE_NOT_SUPPLIED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 108) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USER_AGENT_TYPE_NOT_SUPPLIED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 606) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DOMAIN_NAME_NOT_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 543) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("WAVER_PLEASE_PROVIDE_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 542) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 553) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("SOME_ERROR_DURING_VALIDATION_CHECK"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 102) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 101) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 519) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("MOBILE_NUMBER_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 521) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_PIN_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 532) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 545) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USERNAME_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 546) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_REQUEST_IP_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 547) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_COUNTRY_CODE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 548) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DOMAIN_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 549) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_LOGIN_DEVICE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 550) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USER_AGENT_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 551) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_EVENT_TYPE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 554) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DEVICE_TYPE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 552) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_REGISTRATION_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 611) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_NO_PLAYER_MSG"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 508) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_PLAYER_INFO_NOT_FOUND"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 103) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_INVALID_REQUEST"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', res.errorMessage, tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                }

                $(".verify-input-group-error").html(res.errorMessage);
            }
            return false;
        }
        $('#' + otp_form_id_for_response).parents('.modal').modal('hide');
        //openLoginModal();
        location.href = res.path;

    }
    function getOtpResponse(result) {
        if (validateSession(result) == false)
            return false;
        var res = $.parseJSON(result);
        $("#" + reg_form_id_for_response + " #submit-btn").removeAttr("disabled");
        $("#" + otp_form_id_for_response + " #resendMsg").html("");
        if (res.errorCode != 0) {
            var tooltip_placement = 'left';
            if ($("#" + reg_form_id_for_response).attr("validation-style") != 'undefined' && $("#" + reg_form_id_for_response).attr("validation-style") != undefined) {
                tooltip_placement = $("#" + reg_form_id_for_response).attr("validation-style");
            }
            if ($("#" + reg_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
                if (res.errorCode == 503) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 502) {
                    showToolTipError(reg_form_id_for_response + ' #email', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 510) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 511) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 512) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 513) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 528) {
                    showToolTipError(reg_form_id_for_response + ' #rsa', "RSA already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 534) {
                    showToolTipError(reg_form_id_for_response + ' #rsa', "RSA already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 535) {
                    showToolTipError(reg_form_id_for_response + ' #rsa', "RSA already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 536) {
                    showToolTipError(reg_form_id_for_response + ' #email', "Email already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #rsa', "RSA already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Mobile already exists.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 610) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 611) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else if (res.errorCode == 613) {
                    showToolTipError(reg_form_id_for_response + ' #mobile', "Please Enter your Mobile.", tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else {
                    showToolTipError(reg_form_id_for_response + ' #mobile', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                }
            } else if ($("#" + reg_form_id_for_response).attr('tooltip-mode') == "manual") {
                if (res.errorCode == 422) {
                    showToolTipError(reg_form_id_for_response + ' #refercode', res.respMsg, tooltip_placement, error_callback_registration[reg_form_id_for_response]);
                } else
                    if (res.errorCode == 501) {
                        showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                    } else
                        if (res.errorCode == 502) {
                            showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                        } else
                            if (res.errorCode == 503) {
                                showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                            } else
                                if (res.errorCode == 510) {
                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_EMAILID_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                } else
                                    if (res.errorCode == 528) {
                                        showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                    } else
                                        if (res.errorCode == 534) {
                                            showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                        } else
                                            if (res.errorCode == 535) {
                                                showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                            } else
                                                if (res.errorCode == 536) {
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                    showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                } else
                                                    if (res.errorCode == 539) {
                                                        // showToolTipErrorManual(reg_form_id_for_response + ' #email', "Email ID already exists.", tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                        // showToolTipErrorManual(reg_form_id_for_response + ' #mobile', "Mobile Number already exists.", tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                        showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "Player must be 18 or older to play.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                    } else
                                                        if (res.errorCode == 564) {
                                                            showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                        } else
                                                            if (res.errorCode == 565) {
                                                                showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                            } else
                                                                if (res.errorCode == 566) {
                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                } else
                                                                    if (res.errorCode == 567) {
                                                                        showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                        showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                    } else
                                                                        if (res.errorCode == 568) {
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                        } else
                                                                            if (res.errorCode == 569) {
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                                showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exist.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                            } else
                                                                                if (res.errorCode == 570) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #rsa', "RSA ID already exists.", tooltip_placement, $("#rsa"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #email', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#email"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 610) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 611) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 613) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_PLASE_ENTER_YOUR_MOBILE_NO'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                } else if (res.errorCode == 511) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', Joomla.JText._('BETTING_USERNAME_ALREADY_EXIST'), tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
                                                                                }
                                                                                else if (res.errorCode == 103) {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + " #g-recaptcha-response", "Capcha validation failed", "bottom", $("#g-recaptcha-response"), undefined);
                                                                                    grecaptcha.reset();
                                                                                } else {
                                                                                    showToolTipErrorManual(reg_form_id_for_response + ' #userName', res.respMsg, tooltip_placement, $("#userName"), error_callback_registration[reg_form_id_for_response]);
                                                                                }
            }

            return false;
        }
        if( res.errorCode == 0 && res.data.otpActionType == "LOGIN" ){
            showToolTipErrorManual(reg_form_id_for_response + ' #mobile',Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
            return false;
        }
        
        // $('#home_register').css('display','none');
        if (res.errorCode == 0 && res.data.otpActionType == "LOGIN") {
            showToolTipErrorManual(reg_form_id_for_response + ' #mobile', Joomla.JText._('BETTING_MOBILE_NO_ALREADY_EXIST'), tooltip_placement, $("#mobile"), error_callback_registration[reg_form_id_for_response]);
            return false;
        }
        $("#" + reg_form_id_for_response).parents('.modal').modal('hide');
        $('#' + otp_form_id_for_response).parents('.modal').modal('show');
        $('#' + otp_form_id_for_response + " #resendMsg").html(res.data.mobVerificationCode);
        //    $("#modal-mobileNo").html('+91-'+$("#mobile").val());
        $('#' + otp_form_id_for_response + " .sent_mobile").html($("#mobile").val());
        setTimeout(function () {
            $('#' + otp_form_id_for_response).parents('.modal').modal('show');
        }, 600000);
        // location.href = res.path;
    }

    function reg_inputGroupElement(placement, $element, type) {
        if (type == "error") {
            if ($element.parents('.input-group').length > 0) {
                $element.parents('.input-group').addClass("error");
            }
        } else if (type == "success") {
            if ($element.parents('.input-group').length > 0) {
                $element.parents('.input-group').removeClass("error");
            }
        }
    }

    function resendRegOtp() {
        $(".verify-input-group-error").empty().hide();
        var params = 'mobile=' + $("#" + reg_form_id_for_response + " #mobile").val();
        startAjax("/component/betting/?task=ram.resendRegOtp", params, resendRegOtpResponse, "#" + otp_form_id_for_response);
    }


    function resendRegOtpResponse(result) {
        if (validateSession(result) == false)
            return false;
        var res = $.parseJSON(result);
        $("#" + otp_form_id_for_response + " #resendMsg").html("");
        if (res.errorCode != 0) {
            $("#" + otp_form_id_for_response + " #otp-submit").removeAttr("disabled");
            var tooltip_placement = 'left';
            if ($("#" + otp_form_id_for_response).attr("validation-style") != 'undefined' && $("#" + otp_form_id_for_response).attr("validation-style") != undefined) {
                tooltip_placement = $("#" + otp_form_id_for_response).attr("validation-style");
            }
            if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
                if (res.errorCode == 528 || res.errorCode == 529 || res.errorCode == 530 || res.errorCode == 531) {
                    showToolTipError(otp_form_id_for_response + ' #otp_confirm', res.respMsg, tooltip_placement, error_callback_otp[otp_form_id_for_response]);
                }
            } else if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "manual") {
                if (res.errorCode == 529) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_CODE_HAS_BEEN_EXPIRED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 107) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DEVICE_TYPE_NOT_SUPPLIED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 108) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USER_AGENT_TYPE_NOT_SUPPLIED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 606) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DOMAIN_NAME_NOT_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 543) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("WAVER_PLEASE_PROVIDE_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 542) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 553) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("SOME_ERROR_DURING_VALIDATION_CHECK"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 102) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 101) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 519) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("MOBILE_NUMBER_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 521) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_PIN_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 532) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 545) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USERNAME_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 546) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_REQUEST_IP_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 547) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_COUNTRY_CODE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 548) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DOMAIN_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 549) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_LOGIN_DEVICE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 550) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_USER_AGENT_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 551) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_EVENT_TYPE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 554) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_DEVICE_TYPE_IS_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 552) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_REGISTRATION_NOT_PROVIDED"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 611) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_NO_PLAYER_MSG"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 508) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_PLAYER_INFO_NOT_FOUND"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 103) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_INVALID_REQUEST"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else if (res.errorCode == 530) {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                } else {
                    showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', res.respMsg, tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
                }
            }
            return false;
        } else {
            //            if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "bootstrap") {
            //                showToolTipError(otp_form_id_for_response + ' #otp_confirm', "OTP sent successfully to your Mobile", tooltip_placement, error_callback_otp[otp_form_id_for_response]);
            //            } else if ($("#" + otp_form_id_for_response).attr('tooltip-mode') == "manual") {
            //                showToolTipErrorManual(otp_form_id_for_response + ' #otp_confirm', Joomla.JText._("OTP_SENT_TO_YOUR_PHONE_SUCCESSFULLY"), tooltip_placement, $("#otp_confirm"), error_callback_otp[otp_form_id_for_response]);
            //            }
            $("#" + otp_form_id_for_response + " #resendMsg").html(Joomla.JText._("OTP_SENT_TO_YOUR_PHONE_SUCCESSFULLY"));
            $('#' + otp_form_id_for_response + " #resendMsg").append(res.data.mobVerificationCode);

        }

    }
} catch (error) {
    errorLog(error, function () { console.log('ErrorLog Submitted Successfully!'); });
}
