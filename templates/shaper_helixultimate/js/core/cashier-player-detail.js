var $ = jQuery.noConflict();
var city_list = {};
var error_callback_profile = {};
$.validator.addMethod('selectoption', function (value) {
    return (value != 'select');
});

$.validator.addMethod("exactlength", function(value, element, param) {
    return this.optional(element) || value.length == param;
});

$.validator.addMethod("onlynum", function(value, element) {
    return this.optional(element) || /^[0-9]*$/.test(value);
});

$.validator.addMethod("alphabets", function(value, element) {
    return this.optional(element) || /^[A-Za-z ]*$/.test(value);
});
$.validator.addMethod("addaddress", function(value, element) {
    console.log(value.length + (value.split(/\r|\r\n|\n/)).length);
    return this.optional(element) || (value.length + (value.split(/\r|\r\n|\n/)).length) <= 197;
});

$(document).ready(function(){
    $('input[name="gender"]').on('click', function (event) {
        removeToolTipErrorManual("gender_m", $('#gender_m'));
//        removeToolTipErrorManual("gender_f")
    });
});


$.validator.addMethod("check_date_of_birth", function (value, element) {
    var dateOfBirth = value;
    var arr_dateText = dateOfBirth.split("/");
    day = arr_dateText[0];
    month = arr_dateText[1];
    year = arr_dateText[2];

    var mydate = new Date();
    mydate.setFullYear(year, month - 1, day);

    var maxDate = new Date();
    maxDate.setYear(maxDate.getFullYear() - 18);

    if (maxDate < mydate) {
        $.validator.messages.check_date_of_birth = Joomla.JText._('FORM_JS_CHECK_DATE_OF_BIRTH');
        return false;
    }
    return true;
});

$(document).ready(function () {
    error_callback_profile[$("#player-profile-form").attr('id')] = $("#player-profile-form").attr('error-callback');

    $("#player-profile-form").validate({
        showErrors: function(errorMap, errorList) {
            var style = 'bottom';
            if($("#player-profile-form").attr('validation-style') == undefined) {
                if ($("#player-profile-form").attr('submit-type') == "ajax") {
                    style = 'left';
                }
            }
            else
                style = $("#player-profile-form").attr('validation-style');

            if($("#player-profile-form").attr('tooltip-mode') == "bootstrap") {
                displayToolTip(this, errorMap, errorList, style, error_callback_profile[$("#player-profile-form").attr('id')]);
            }
            else if($("#player-profile-form").attr('tooltip-mode') == "manual") {
                displayToolTipManual(this, errorMap, errorList, style, error_callback_profile[$("#player-profile-form").attr('id')]);
            }
        },
        rules: {
            gender: {
                required: true,
            },
            fname: {
                required: true,
                //alphabets: true,
                rangelength: [2, 25],
                maxlength: 25
            },
            lname: {
                required: true,
                //alphabets: true,
                rangelength: [2, 25],
                maxlength: 25
                
            },
            email: {
                required: true,
                email: true,
                rangelength: [3, 50],
                pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            },
            mobile : {
                required: true,
                pattern: /^[6,7,8,9]{1}[0-9]{9}$/,
                //onlynum: true,
                exactlength: 10,
            },
            dob: {
                required: true,                
                check_date_of_birth : true,
            },
            address: {
                required: true,
                addaddress: true,
                rangelength: [2, 197]
            },
            state: {
                required: true,
                selectoption: true
            },
            city: {
                required: true,
                pattern: /^[a-zA-Z-.,()\[\] ]{2,56}$/,
                rangelength: [3, 50]
            },
            pincode: {
                required: true,
                onlynum: true,
                exactlength: 6
            }
        },
        messages : {
            gender: {
                required: Joomla.JText._('BETTING_SELECT_THE_GENDER'),
            },
            fname: {
                required: Joomla.JText._('PLEASE_ENTER_YOUR_FIRST_NAME'),
                //alphabets: Joomla.JText._('FORM_JS_FIRST_NAME'),
                rangelength: Joomla.JText._('BETTING_PLASE_ENTER_ATLEAST_TWO_CHARACTERS'),
                maxlength: Joomla.JText._('FORM_JS_MAXIMUM_CHARACTERS_ALLOWED')
            },
            lname: {
                required: Joomla.JText._('PLEASE_ENTER_YOUR_LAST_NAME'),
                //alphabets: Joomla.JText._('FORM_JS_LAST_NAME'),
                rangelength: Joomla.JText._('BETTING_PLASE_ENTER_ATLEAST_TWO_CHARACTERS'),
                maxlength: Joomla.JText._('FORM_JS_MAXIMUM_CHARACTERS_ALLOWED')
            },
            email: {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_EMAIL_ADDR'),
                pattern: Joomla.JText._('FORM_JS_EMAIL_ADDRESS_IS_INVALID'),
                email: Joomla.JText._('FORM_JS_EMAIL_ADDRESS_IS_INVALID'),
                rangelength: Joomla.JText._('FORM_JS_EMAIL_ID_CAN_CONTAIN')
            },
            mobile : {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_MOBILE'),
                pattern: Joomla.JText._('PLEASE_ENTER_A_VALID_MOBILE_NO'),
                //onlynum: Joomla.JText._('BETTING_ONLY_NUMBERS_ARE_ALLOWED'),
                exactlength: Joomla.JText._('BETTING_MOBILE_NO_SHOULD_BE_OF_TEN_DIGITS'),
            },
            dob: {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_DOB'),
                //dateITA : "dfgdsgfdgdgdfg"
            },
            address: {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_ADDRESS'),
                addaddress: Joomla.JText._('BETTING_ADDRESS_CAN_CONTAIN_CHARACTERS')
            },
            state: {
                selectoption: Joomla.JText._('BETTING_PLEASE_SELECT_STATE')
            },
            city: {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_CITY'),
                pattern : Joomla.JText._('BETTING_CITY_CAN_CONTAIN_ONLY_ALPHABETS'),
                rangelength: Joomla.JText._('BETTING_CITY_CAN_CONTAIN_CHARACTERS')
            },
            pincode: {
                required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_PINCODE'),
                onlynum: Joomla.JText._('BETTING_PIN_CODE_SHOULD_CONTAIN_ONLY_DIGITS'),
                exactlength: Joomla.JText._('BETTING_CODE_SHOULD_BE_OF_SIX_DIGITS')
            }
        },
        submitHandler: function() {
            if(ask_for_validation == true) {
                var tooltip_placement = 'left';
                if($("#player-profile-form").attr("validation-style") != 'undefined' && $("#player-profile-form").attr("validation-style") != undefined) {
                    tooltip_placement = $("#player-profile-form").attr("validation-style");
                }

                var error_found = false;
                if($("#mobile_div").hasClass("verify") == false) {
                    error_found = true;
                    if($("#player-profile-form").attr('tooltip-mode') == "bootstrap") {
                        showToolTipError($("#player-profile-form").attr('id')+ ' #mobile', "Please verify your mobile number.", tooltip_placement, error_callback_profile[$("#player-profile-form").attr('id')]);
                    }
                    else if($("#player-profile-form").attr('tooltip-mode') == "manual") {
                        showToolTipErrorManual($("#player-profile-form").attr('id')+ ' #mobile', "Please verify your mobile number.", tooltip_placement, $("#mobile"), error_callback_profile[$("#player-profile-form").attr('id')]);
                    }
                }
                if($("#email_div").hasClass("verify") == false) {
                    error_found = true;
                    if($("#player-profile-form").attr('tooltip-mode') == "bootstrap") {
                        showToolTipError($("#player-profile-form").attr('id')+ ' #email', "Please verify your email-id.", tooltip_placement, error_callback_profile[$("#player-profile-form").attr('id')]);
                    }
                    else if($("#player-profile-form").attr('tooltip-mode') == "manual") {
                        showToolTipErrorManual($("#player-profile-form").attr('id')+ ' #email', "Please verify your email-id.", tooltip_placement, $("#email"), error_callback_profile[$("#player-profile-form").attr('id')]);
                    }
                }

                if(error_found)
                    return false;
            }
            document.getElementById("player-profile-form").submit();
            $("#profile-form-submit").attr('disabled', 'disabled');
        }
    });

    $( "input[name='gender']" ).rules( "add", {
        required: true,
        messages: {
            required: Joomla.JText._('FORM_JS_PLEASE_SELECT_GENDER')
        }
    });
});

$(document).ready(function(){
    // $("#alert-modal").modal({
    //     show: true,
    //     keyboard: false,
    //     backdrop: 'static'
    // });

//    function loadCities()
//    {
//        var state = $("#state").val();
//        if(city_list[state] == undefined) {
//            if($("#state").val() != "select"){
//                startAjax(fetchCityList, "stateId="+$("#state").val(), appendCities, "nottoshow");
//            }
//        }
//    }

    //loadCities();

//    $("#state").on("change", function () {
//        if($(this).val() != 'select')
//            //loadCities();
//    });

//    $( "#city" ).autoComplete({
//        minChars: 1,
//        cache: false,
//        source: function(term, suggest){
//            term = term.toLowerCase();
//            var matches = [];
//            if(city_list[$("#state").val()] == 'undefined' || $("#state").val() == 'select')
//                return;
//            var choices = city_list[$("#state").val()];
//            for (i=0; i<choices.length; i++)
//                if (~choices[i].toLowerCase().indexOf(term))
//                    matches.push(choices[i]);
//            suggest(matches);
//        }
//    });
});

//function appendCities(result)
//{
//    if(validateSession(result) == false)
//        return false;
//    var res = JSON.parse(result);
//    if(res.errorCode == 0)
//    {
//        var stateCode;
//        var tmp_cities = [];
//        for(var i=0; i<res.cityList.length; i++) {
//            tmp_cities.push(res.cityList[i].value);
//            stateCode = res.cityList[i].stateCode;
//        }
//
//        if(res.cityList.length > 0) {
//            city_list[stateCode] = tmp_cities;
//        }
//    }
//}

$(document).keypress(function(e) {
    if($("#mobile_verify").length > 0 && $("#mobile_verify").css("display") != "none" && e.which == 13) {
        $("#continue-btn").trigger("click");
    }
    else if($('#state').is(':focus') == false && $('#city').is(':focus') == false && e.which == 13) {
        $("#profile-form-submit").trigger('click');
    }
});

$(document).on("keyup", "#address", function (e) {
    var value = $(this).val();
    value = value.replace(/[^a-zA-Z0-9,-/\n ]/g, '');
    $(this).val(value);

});

$(document).on("keyup", "#email", function (e) {
    var value = $(this).val();
    value = value.replace(/[^a-zA-Z0-9@._]/g, '');
    $(this).val(value);

});

function checkAddressLength() {
    if ($("#address").val().length + $("#address").val().split(/\r|\r\n|\n/).length > 197) {
        var str = $('#address').val();
        str = str.substring(0, str.length - 1);
        $('#address').val(str);
    }
}

function checkEmoji(){
  var str = $('#fname').val()
   var ranges = [
        '\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
        '\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
        '\ud83d[\ude80-\udeff]' // U+1F680 to U+1F6FF
    ];
    if (str.match(ranges.join('|'))) {
        return true;
    } else {
        return false;
}
}

//$('#mobile').on('keypress',function(e){ 
// var v = $(this).val();
// if(v.length >=2 && v[0]=='0' && v[1]=='0') {
//    return false;
// }
//});
