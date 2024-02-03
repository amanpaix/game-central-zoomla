var $ = jQuery.noConflict();
var min_limit = 500;
var max_limit = 1000000;
var allParams = "";
var withdrawal_add_account;
var error_callback_withdrawal_add_account = {};
var error_callback_deposit_add_account = {};
var error_callback_with_upi_add_account = {};
var withdrawal_verify_otp_form;
var deposit_verify_otp_form;
var cashier_withdrawal_id;
var deposit_add_account;
var with_upi_add_account;
var error_callback_withdrawal_verifyotp = {};
var error_callback_deposit_verifyotp = {};
var withdrawal_form_id_for_response = {};
$(document).ready(function () {
   
$('.Withdrawal').css('display', 'none');
    $("ul#url-tabs>li").on('click', function () {
        clearSystemMessage();
        $("ul#url-tabs>li").removeClass("active");
        $(this).addClass("active");
        $("[div_id]").css("display", "none");
        $("[div_id='" + $(this).children().attr("href").replace("#", "") + "']").css("display", "block");
        $("div.heading>h2").html($(this).children().html());
        $("#paymentCurrencyCode input:checked").trigger('change');
    });
    var url_hash = window.location.hash;
    if (url_hash != "") {
        $("a[href='" + url_hash + "']").trigger("click");
    }
 //let param = '';
 //startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", param, getAllRedeemWithdrawalAccount, "");
    $('#bankNames').on('change', function() {
  $(this).parents().find('.payment').removeClass('active');
  if(this.value != ""){
  let subType = this.value;
  let bankName = $('#bankNames option:selected').text();
  let payTypeCode = $('#bankNames option:selected').attr('payTypeCode');
  let payTypeId = $('#bankNames option:selected').attr('payTypeId');
  allParams = 'subType='+ subType +'&bankName=' + bankName + '&payTypeCode=' + payTypeCode + '&paytypeId=' + payTypeId;
  $(".select-value").css('display','block');
  $(".select-value").text($('#bankNames option:selected').text());
  $("#bank_name").val(this.value);
  }else{
  $(".select-value").css('display','none');    
  allParams = "";
  }
   
});


    $('[id^=depositAccounts]').on('change',function() {
  if(this.value == 'UPI'){
   $("#depositTypeCode").val(this.value);
    $("#depositPaymentTypeId").val($('[id^=depositAccounts] option:selected').attr('paytypeid'));
    $("#depositCurrencyCode").val($('[id^=depositAccounts] option:selected').attr('currency'));
    $("#upiSubtypeId").val(Object.keys(banks.UPI));
    showDefaultDepositPopupContent();
  $("#cashier_add_account_deposit_popup").modal('show');  
  }
  var UpipaymentAccId = this.value;
  allParams += '&paymentAccId=' + UpipaymentAccId;
});

    $(".choose_amount").on('click','li',function (){
  $('.choose_amount li').find('button').removeClass('active');
  $(this).find('button').addClass('active');
  if($(this).text() != 'Other'){ 
  var amount= $(this).text().split(' ');
   $(".dep-amount-input").css('display','none');
   $("#deposit_amount").val(amount[1]);
   $("#amount").val('');
  }else{ 
   $(".dep-amount-input").css('display','block');
   $("#deposit_amount").val('');
  }
});

    $("#sp-custom-popup").on('click', "#cashier_withdrawal_resendOtp", function () {
        $("#with_otp_code").val('');
          var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
          if($("#" + withdrawal_add_account + " #withPayTypeCode").val() == 'UPI'){
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() 
                 }
                    else { 
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #select_bank").val();
                     params += '&ifsc=' + $("#" + withdrawal_add_account + " #ifsc").val();
                 }
        //var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&isCashierUrl=' + '1'+ '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseForWithdrawalResendOTP, "#" + withdrawal_verify_otp_form);
    });

    $(".proceed").on('click',function(){
if($("#deposit_amount").val() != ''){
 allParams += '&deposit=' +  $("#deposit_amount").val();
}else{
if($("#amount").val() != '')
allParams += '&deposit=' +  $("#amount").val();
}
if(allParams.match(/deposit/g) == null){
error_message('Please select the amount.');  
return false;
}else if(allParams.match(/payTypeCode/g) == null){
 error_message('Please select the payment type.');  
return false;   
}
console.log(allParams);
if(allParams.match(/payTypeCode/g) == 'UPI'){
if(allParams.match(/paymentAccId/g) == null){
error_message('Please select the payment Account.'); 
}

}
if(emailVerified == 'N'){
error_message('please verify you email first under my profile section.');  
return false;   
}
  

var form_fields = allParams.split("&");
  console.log(allParams);
 for (var i = 0; i < form_fields.length; i++) {
            var temp = form_fields[i].split("=");
            $('#request-deposit-form').append($('<input>', {
                type: "hidden",
                name: temp[0],
                value: temp[1]
            }));
        }
        $('#request-deposit-form').append($('<input>', {
            type: "hidden",
            name: "currency",
            value: $("#paymentCurrencyCode input:checked").val()
        }));
        $(this).attr('disabled', 'disabled');
        document.getElementById("request-deposit-form").submit();
})

    $($("form[id^='cashier-withdrawal-add-account-form']")).each(function () {
        withdrawal_add_account = $(this).attr('id');
        error_callback_withdrawal_add_account[withdrawal_add_account] = $(withdrawal_add_account).attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                withAccHolderName: {
                    required: true,
                    //min:5
                },
                select_bank: {
                    required: true,
                },
                withAccNum: {
                    required: true,
                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },
                 confirmAccount: {
                    required: true,
                    equalTo: "#withAccNum"
                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },
                 ifsc: {
                 required: true,
                 alphanumeric: true
             }
               
            },

            messages: {
                withAccHolderName: {
                    required: 'Please Enter Account holder name.',
                    //min:Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                select_bank:{
                required: 'Please select Bank',
                },
                withAccNum: {
                    required: 'Please enter account number.',
                    //pattern: "Please enter a valid 9 digit account number.",
                    //rangelength: "Account number should be in range.",
                },
                confirmAccount: {
                    required: 'Please enter Confirm account number.',
                    equalTo: 'Confirm account number is not equal to account number.',
                    //pattern: "Please enter a valid 9 digit account number.",
                    //rangelength: "Account number should be in range.",
                },
                ifsc: {
                required: "Please enter Branch Code.",
                alphanumeric: "Please enter valid Branch Code."
             }
               
            },

            submitHandler: function () {
                if ($("#" + withdrawal_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(withdrawal_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
                    if($("#" + withdrawal_add_account + " #withPayTypeCode").val() == 'UPI'){
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() 
                 }
                    else {  
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #select_bank").val(); 
                     params += '&ifsc=' + $("#" + withdrawal_add_account + " #ifsc").val();
                 }
                    startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getOtpForWithdrawalAccount, "#" + withdrawal_add_account);
                }

            }
        });
    });
    
    $($("form[id^='cashier-deposit-add-account-form']")).each(function () {
        deposit_add_account = $(this).attr('id');
        error_callback_deposit_add_account[deposit_add_account] = $(deposit_add_account).attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                depositAccHolderName: {
                    required: true,
                    //min:5
                },

                upiId: {
                    required: true,
                    pattern: /^\w.+@\w+$/,

                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },
                 confirmUpiAccount: {
                    required: true,
                    pattern: /^\w.+@\w+$/,
                    equalTo: "#upiId",
                    

                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },

               
            },

            messages: {
                depositAccHolderName: {
                    required: 'Please Enter Account holder name.',
                    //min:Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                upiId: {
                    required: 'Please enter UPI Id.',
                    pattern: "Please enter a valid UPI Id.",
                    //rangelength: "Account number should be in range.",
                },
                confirmUpiAccount: {
                    required: 'Please enter Confirm UPI Id.',
                    equalTo: 'Confirm UPI Id is not equal to UPI Id.',
                    pattern: "Please enter a valid UPI Id.",
                    //rangelength: "Account number should be in range.",
                },

               
            },

            submitHandler: function () {
                if ($("#" + deposit_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + deposit_add_account + " #upiId").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #depositAccHolderName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #depositPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + deposit_add_account + " #depositCurrencyCode").val();
                     params += '&subTypeId=' + $("#" + deposit_add_account + " #upiSubtypeId").val() 
                    startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getOtpForUpiAccount, "#" + deposit_add_account);
                }

            }
        });
    });
    
    $($("form[id^='cashier-withdrawal-upi-add-account-form']")).each(function () {
        with_upi_add_account = $(this).attr('id');
        error_callback_with_upi_add_account[with_upi_add_account] = $(with_upi_add_account).attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                upiAccountName: {
                    required: true,
                    //min:5
                },

                withUpiId: {
                    required: true,
                    pattern: /^\w.+@\w+$/,

                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },
                 confirmWithUpiId: {
                    required: true,
                    equalTo: "#withUpiId",
                    pattern: /^\w.+@\w+$/,

                   // pattern :"^[7,1]{1}[0-9]{8}$",
                    //rangelength: [9,9],

                },

               
            },

            messages: {
                upiAccountName: {
                    required: 'Please Enter Account holder name.',
                    //min:Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                withUpiId: {
                    required: 'Please enter UPI Id.',
                    pattern: "Please enter a valid UPI Id.",
                    //rangelength: "Account number should be in range.",
                },
                confirmWithUpiId: {
                    required: 'Please enter Confirm UPI Id.',
                    equalTo: 'Confirm UPI Id is not equal to UPI Id.',
                    pattern: "Please enter a valid UPI Id.",
                    //rangelength: "Account number should be in range.",
                },

               
            },

            submitHandler: function () {
                if ($("#" + with_upi_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(with_upi_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + with_upi_add_account + " #withUpiId").val() + '&paymentTypeCode=' + $("#" + with_upi_add_account + " #withUpiPayTypeCode").val() + '&accHolderName=' + $("#" + with_upi_add_account + " #upiAccountName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + with_upi_add_account + " #withUpiPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + with_upi_add_account + " #withUpiCurrencyCode").val();
                     params += '&subTypeId=' + $("#" + with_upi_add_account + " #withUpiSubTypeId").val() 
                    startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getOtpForUpiWithdrawalAddAccount, "#" + with_upi_add_account);
                }

            }
        });
    });
    
    $($("form[id^='cashier-withdrawal-otp-verification-form']")).each(function () {
      withdrawal_verify_otp_form = $(this).attr('id');
      error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form] = $(withdrawal_verify_otp_form).attr('error-callback');
      $(this).validate({
                showErrors: function (errorMap, errorList) {
                    displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
                },

                rules: {
                    withdrawal_otp: {
                        required: true,
                        //pattern: "^[-9]{0,6}(\.[0-9]{1,2})?$",
                        //notSmaller: true,
                        //decimalToTwo : true
                        rangelength: [4, 4]
                    },

                },

                messages: {
                    withdrawal_otp: {
                        required: 'Please enter OTP',
                        //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                        //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                        //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                        rangelength: Joomla.JText._('PLEASE_ENTER_VALID_OTP')
                    }
                },

                submitHandler: function () {
                    if ($("#" + withdrawal_verify_otp_form).attr('submit-type') != 'ajax') {
                        document.getElementById(withdrawal_verify_otp_form).submit();
                    } else {
                        if($("#otpVerificationParams").val()  == 'NET_BANKING'){
                        var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&accType=' + 'SAVING' + '&verifyOtp=' + $("#" + withdrawal_verify_otp_form + " #withdrawal_otp").val() + '&isOtp=' + '1' + '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
                        if($("#" + withdrawal_add_account + " #withPayTypeCode").val() == 'UPI'){
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() 
                 }
                    else  { 
                     params += '&subTypeId=' + $("#" + withdrawal_add_account + " #select_bank").val(); 
                     params += '&ifsc=' + $("#" + withdrawal_add_account + " #ifsc").val();
                 }
                }else if($("#otpVerificationParams").val()  == 'UPI'){
                   var params = 'accNum=' + $("#" + with_upi_add_account + " #withUpiId").val() + '&paymentTypeCode=' + $("#" + with_upi_add_account + " #withUpiPayTypeCode").val() + '&accHolderName=' + $("#" + with_upi_add_account + " #upiAccountName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + with_upi_add_account + " #withUpiPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + with_upi_add_account + " #withUpiCurrencyCode").val() + '&isOtp=' + '1' + '&verifyOtp=' + $("#" + withdrawal_verify_otp_form + " #withdrawal_otp").val() ;
                     params += '&subTypeId=' + $("#" + with_upi_add_account + " #withUpiSubTypeId").val() 
                }
                        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseFoWithdrawalOTP, "#" + withdrawal_verify_otp_form);
                    }

                }
            });
        });
        
    $($("form[id^='cashier-deposit-otp-verification-form']")).each(function () {
      deposit_verify_otp_form = $(this).attr('id');
      error_callback_deposit_verifyotp[deposit_verify_otp_form] = $(deposit_verify_otp_form).attr('error-callback');
      $(this).validate({
                showErrors: function (errorMap, errorList) {
                    displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
                },

                rules: {
                    upiOtp: {
                        required: true,
                        //pattern: "^[-9]{0,6}(\.[0-9]{1,2})?$",
                        //notSmaller: true,
                        //decimalToTwo : true
                        rangelength: [4, 4]
                    },

                },

                messages: {
                    upiOtp: {
                        required: 'Please enter OTP',
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
                        var params = 'accNum=' + $("#" + deposit_add_account + " #upiId").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #depositAccHolderName").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #depositPaymentTypeId").val() + '&accType=' + 'SAVING' + '&currencyCode=' + $("#" + deposit_add_account + " #depositCurrencyCode").val() + '&verifyOtp=' + $("#" + deposit_verify_otp_form + " #upiOtp").val() + '&isOtp=' + '1';
                     params += '&subTypeId=' + $("#" + deposit_add_account + " #upiSubtypeId").val() 
                        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseUpiOTP, "#" + deposit_verify_otp_form);
                    }

                }
            });
        });
        
    $($("form[id^='cashier-withdrawal-request']")).each(function () {
        cashier_withdrawal_id = $(this).attr('id');
        //withdrawal_form_id_for_response = cashier_withdrawal_id;
        withdrawal_form_id_for_response[cashier_withdrawal_id] = $("#" + cashier_withdrawal_id).attr('error-callback');
        var payTypeId = $(this).find("input[id^='withPaytypeId']").val();
        var payTypeCode = $(this).find("input[id^='withPayTypeCode']").val();
        var currency = $(this).find("input[id^='withCurrency']").val();
        if (withdrawalBal != null) {
            minWithdrawalLimit = withdrawalBal[$(this).find("input[id^='withPayTypeCode']").val()].min;
            maxWithdrawalLimit = withdrawalBal[$(this).find("input[id^='withPayTypeCode']").val()].max;
        } else {
            minWithdrawalLimit = 0;
            maxWithdrawalLimit = 0;
        }
        if ((parseFloat(maxWithdrawalLimit) < parseFloat(withdrawabalBalance) && parseFloat(maxWithdrawalLimit) < max_limit) || (withdrawabalBalance < min_limit)) {
            maxWithdrawalAmount = formatCurrency(maxWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = maxWithdrawalLimit;
        } else if (withdrawabalBalance < max_limit) {
            maxWithdrawalAmount = formatCurrency(withdrawabalBalance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = withdrawabalBalance;
        } else {
            maxWithdrawalAmount = formatCurrency(max_limit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = max_limit;
            ;
        }
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                amount_withdrawal: {
                    required: true,
                    //pattern:/^[1-9][0-9]*$/,
                    min: minWithdrawalLimit,
                    range: [minWithdrawalLimit, maxWithdrawal]
                },
                withdrawalAccounts: {
                    required: true,
                }
            },

            messages: {
                amount_withdrawal: {
                    required: 'Please enter amount to withdrawa',
                    //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                    min: 'Minimum amount should be at least ' + formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: 'Amount should be between ' +formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ 'To' +formatCurrency(maxWithdrawalAmount,decSymbol) + "."
                },
                withdrawalAccounts: {
                    required: 'Please select Account',
                }
            },

            submitHandler: function (form) {
//                $("#withdraw_value").text($(form).find("input[id^='withCurrency']").val() + ' ' + $(form).find("input[id^='amount_withdrawal']").val());
                //console.log(form_id.find("input[id^='withdrawal']").val())
                //id = cashier_withdrawal_id;
                if ($("#" + cashier_withdrawal_id).attr('submit-type') != 'ajax') {
                    document.getElementById(cashier_withdrawal_id).submit();
                } else {
                    var amount = $(form).find("input[id^='amount_withdrawal-']").val();
                    var paymentAccId = $(form).find("select[id^='withdrawalAccounts-']").val();
                    var subTypeId = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('subTypeId');
                    var ifsc = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('ifscCode');
                    var accountHolder = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('accountholder');
                    var withdrawalFields = 'paymentTypeId=' + payTypeId + '&subTypeId=' + subTypeId + '&amount=' + amount + '&paymentTypeCode=' + payTypeCode + '&paymentAccId=' + paymentAccId + '&isCashierUrl=' + '1';
                        withdrawalFields += '&CurrencyCode=' + $(form).find("input[id^='withCurrency']").val();
                    var form_fields = withdrawalFields.split("&");
                    console.log(form_fields);
                     $("#withdrawal_confirmation_popup .modal-body .confirmAmount").text($(form).find("input[id^='withCurrency']").val() + amount);
                     $("#withdrawal_confirmation_popup .modal-body  .confirmBank").text(accountHolder);
                     if(payTypeCode != 'UPI'){
                     $("#withdrawal_confirmation_popup .withdrawalDetailWrap .rowDetail:eq(3)").css("display",'block');
                     $("#withdrawal_confirmation_popup .modal-body  .confirmType").text(ifsc);
                      }else{
                       $("#withdrawal_confirmation_popup .withdrawalDetailWrap .rowDetail:eq(3)").css("display",'none');   
                      }
                     $("#withdrawal_confirmation_popup .modal-body  .confirmAcc").text($(form).find("select[id^='withdrawalAccounts-']").find('option:selected').text());
                    $("#withdrawal_confirmation_popup").modal("show");
                    for (var i = 0; i < form_fields.length; i++) {
                        var temp = form_fields[i].split("=");
                        $('#withdrawal-request-form').append($('<input>', {
                            type: "hidden",
                            name: temp[0],
                            value: temp[1]
                        }));
                    }
                    //startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails", withdrawalFields, processWithdrawalRequest, "#" + cashier_withdrawal_id);

                }
            }
        });
    });


 $("#withdrawalreq").click(function () { 
        $(this).attr('disabled', 'disabled');
     var fields = 'paymentTypeId=' + $('#withdrawal-request-form').find('input[name="paymentTypeId"]').val() + '&subTypeId=' + $('#withdrawal-request-form').find('input[name="subTypeId"]').val() + '&amount=' + $('#withdrawal-request-form').find('input[name="amount"]').val() + '&paymentTypeCode=' + $('#withdrawal-request-form').find('input[name="paymentTypeCode"]').val() + '&paymentAccId=' + $('#withdrawal-request-form').find('input[name="paymentAccId"]').val() + '&isCashierUrl=' + $('#withdrawal-request-form').find('input[name="isCashierUrl"]').val() + '&CurrencyCode=' + $('#withdrawal-request-form').find('input[name="CurrencyCode"]').val();
        startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails", fields, processWithdrawalRequest, "#" + cashier_withdrawal_id);
    });

    $("#paymentCurrencyCode input").on('change', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var fields = 'for='+ $("#url-tabs .active").text().trim().toUpperCase() +'&currencyCode='+$("#paymentCurrencyCode input:checked").val();
        startAjax("/component/Betting/?task=cashier.getpaymentOptions", fields, processPaymentOptionsResponse, 'null');
    });

});

function initValidations()
{
    console.log("Hit");
    $($("form[id^='cashier-withdrawal-request']")).each(function () {
        cashier_withdrawal_id = $(this).attr('id');
        //withdrawal_form_id_for_response = cashier_withdrawal_id;
        withdrawal_form_id_for_response[cashier_withdrawal_id] = $("#" + cashier_withdrawal_id).attr('error-callback');
        var payTypeId = $(this).find("input[id^='withPaytypeId']").val();
        var payTypeCode = $(this).find("input[id^='withPayTypeCode']").val();
        var currency = $(this).find("input[id^='withCurrency']").val();
        if (withdrawalBal != null) {
            minWithdrawalLimit = withdrawalBal[$(this).find("input[id^='withPayTypeCode']").val()].min;
            maxWithdrawalLimit = withdrawalBal[$(this).find("input[id^='withPayTypeCode']").val()].max;
        } else {
            minWithdrawalLimit = 0;
            maxWithdrawalLimit = 0;
        }
        if ((parseFloat(maxWithdrawalLimit) < parseFloat(withdrawabalBalance) && parseFloat(maxWithdrawalLimit) < max_limit) || (withdrawabalBalance < min_limit)) {
            maxWithdrawalAmount = formatCurrency(maxWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = maxWithdrawalLimit;
        } else if (withdrawabalBalance < max_limit) {
            maxWithdrawalAmount = formatCurrency(withdrawabalBalance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = withdrawabalBalance;
        } else {
            maxWithdrawalAmount = formatCurrency(max_limit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            maxWithdrawal = max_limit;
            ;
        }
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                amount_withdrawal: {
                    required: true,
                    //pattern:/^[1-9][0-9]*$/,
                    min: minWithdrawalLimit,
                    range: [minWithdrawalLimit, maxWithdrawal]
                },
                withdrawalAccounts: {
                    required: true,
                }
            },

            messages: {
                amount_withdrawal: {
                    required: 'Please enter amount to withdrawa',
                    //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                    min: 'Minimum amount should be at least ' + formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: 'Amount should be between ' +formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ 'To' +formatCurrency(maxWithdrawalAmount,decSymbol) + "."
                },
                withdrawalAccounts: {
                    required: 'Please select Account',
                }
            },

            submitHandler: function (form) {
//                $("#withdraw_value").text($(form).find("input[id^='withCurrency']").val() + ' ' + $(form).find("input[id^='amount_withdrawal']").val());
                //console.log(form_id.find("input[id^='withdrawal']").val())
                //id = cashier_withdrawal_id;
                if ($("#" + cashier_withdrawal_id).attr('submit-type') != 'ajax') {
                    document.getElementById(cashier_withdrawal_id).submit();
                } else {
                    var amount = $(form).find("input[id^='amount_withdrawal-']").val();
                    var paymentAccId = $(form).find("select[id^='withdrawalAccounts-']").val();
                    var subTypeId = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('subTypeId');
                    var ifsc = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('ifscCode');
                    var accountHolder = $(form).find("select[id^='withdrawalAccounts']").find('option:selected').attr('accountholder');
                    var withdrawalFields = 'paymentTypeId=' + payTypeId + '&subTypeId=' + subTypeId + '&amount=' + amount + '&paymentTypeCode=' + payTypeCode + '&paymentAccId=' + paymentAccId + '&isCashierUrl=' + '1';
                    withdrawalFields += '&CurrencyCode=' + $(form).find("input[id^='withCurrency']").val();
                    var form_fields = withdrawalFields.split("&");
                    console.log(form_fields);
                    $("#withdrawal_confirmation_popup .modal-body .confirmAmount").text($(form).find("input[id^='withCurrency']").val() + amount);
                    $("#withdrawal_confirmation_popup .modal-body  .confirmBank").text(accountHolder);
                    if(payTypeCode != 'UPI'){
                        $("#withdrawal_confirmation_popup .withdrawalDetailWrap .rowDetail:eq(3)").css("display",'block');
                        $("#withdrawal_confirmation_popup .modal-body  .confirmType").text(ifsc);
                    }else{
                        $("#withdrawal_confirmation_popup .withdrawalDetailWrap .rowDetail:eq(3)").css("display",'none');
                    }
                    $("#withdrawal_confirmation_popup .modal-body  .confirmAcc").text($(form).find("select[id^='withdrawalAccounts-']").find('option:selected').text());
                    $("#withdrawal_confirmation_popup").modal("show");
                    for (var i = 0; i < form_fields.length; i++) {
                        var temp = form_fields[i].split("=");
                        $('#withdrawal-request-form').append($('<input>', {
                            type: "hidden",
                            name: temp[0],
                            value: temp[1]
                        }));
                    }
                    //startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails", withdrawalFields, processWithdrawalRequest, "#" + cashier_withdrawal_id);

                }
            }
        });
    });
}


function processPaymentOptionsResponse(result)
{
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    $("[div_id]").hide();
    $("#system-message-container").html('');
    if (res.errorCode != 0) {
        $("#system-message-container").html(res.errorMsg);
        return false;
    }

    var currTab = $("[div_id='"+ res.for.toLowerCase() +"']");
    switch (res.for.toLowerCase())
    {
        case "deposit":
            currTab.find('.choose_amount').empty();
            chooseAmountMap.forEach(function (value) {
                currTab.find('.choose_amount').append('<li class="item-wrap"><button class="item">'+ $("#paymentCurrencyCode input:checked").val() +' '+ parseFloat(value).toFixed(2) +'</button></li>');
            });

            currTab.find('.choose_amount').append(' <li class="item-wrap"><button class="item">Other</button></li>');
            currTab.find('.dep-sub-block').empty();
            var payCnt = 0;
            for (const [key1, value1] of Object.entries(res.payTypeMap)) {
                payCnt++;
                switch (res.payTypeMap[key1].payTypeCode)
                {
                    case "NET_BANKING":
                        currTab.find('.dep-sub-block').append('<div class="dep-sub-block-title">NET BANKING</div>');
                        currTab.find('.dep-sub-block').append('<div class="list-btn-wrap"><ul class="list-btn">');
                        var netBankLimit = 8;
                        for (const [key, value] of Object.entries(res.payTypeMap[key1].subTypeMap)) {
                            if( netBankLimit <= 0 ){
                                break;
                            }
                            currTab.find('.dep-sub-block .list-btn').append('<li class="item-wrap"><button class="item payment" onclick=\'selectPaymentType(this,"'+ value.trim() +'","'+ key +'","'+ res.payTypeMap[key1].payTypeCode +'","'+ res.payTypeMap[key1].payTypeId +'")\' ><span class="icon"><img src="/images/payment-icons/'+ value +'.png"></span>'+ value +'</button></li>');
                            netBankLimit--;
                        }
                        currTab.find('.dep-sub-block').append('</ul></div>');
                        currTab.find('.dep-sub-block').append('<div class="choosen-list"><div class="select-wrap">');
                        currTab.find('.dep-sub-block .choosen-list .select-wrap').append('<select name="" id="bankNames"><option value="">Select Bank</option>');

                        for (const [key, value] of Object.entries(res.payTypeMap[key1].subTypeMap)) {
                            currTab.find('.dep-sub-block #bankNames').append('<option value="'+ key +'" paytypeid="'+ res.payTypeMap[key1].payTypeId +'" paytypecode="'+ res.payTypeMap[key1].payTypeDispCode +'">'+ value +'</option>');
                        }

                        currTab.find('.dep-sub-block .choosen-list .select-wrap').append('</select>');
                        currTab.find('.dep-sub-block .choosen-list').append('<div class="select-value"><span class="icon"></span></div>');
                        currTab.find('.dep-sub-block').append('</div>');

                        break;

                    case "UPI":
                        currTab.find('.dep-sub-block').append('<div class="dep-sub-block-title">UPI</div>');
                        currTab.find('.dep-sub-block').append('<div class="list-btn-wrap upi-list"><ul class="list-btn upi">');
                        for (const [key, value] of Object.entries(res.payTypeMap[key1].subTypeMap)) {
                            currTab.find('.dep-sub-block .upi').append('<li class="item-wrap"><button class="item payment" onclick=\'selectPaymentType(this,"'+ value.trim() +'","'+ key +'","'+ res.payTypeMap[key1].payTypeCode +'","'+ res.payTypeMap[key1].payTypeId +'")\'><span class="icon"><img src="/images/payment-icons/'+ value +'.png"></span>'+ value +'</button></li>');
                        }
                        currTab.find('.dep-sub-block .upi').append('</ul>');
                        currTab.find('.dep-sub-block .upi-list').append('<div class="field-wrap select-parent">\n' +
                            '                                    <div class="form-group">\n' +
                            '                                        <div class="label"></div>\n' +
                            '                                        <select name="depositAccounts" class="depositAccounts" id="depositAccounts-'+ res.payTypeMap[key1].payTypeId +'">\n' +
                            '                                        <option value="">Select Account</option>\n' +
                            '                                           \n' +
                            '                                    <option value="UPI" currency="'+ $("#paymentCurrencyCode input:checked").val() +'" paytypeid="'+ res.payTypeMap[key1].payTypeId +'">Add New Account</option>\n' +
                            '                                        </select>\n' +
                            '                                    <div id="error_depositAccounts-'+ res.payTypeMap[key1].payTypeId +'" class="manual_tooltip_error error_tooltip" style="display: none;"></div>\n' +
                            '                                </div>\n' +
                            '                      </div>');
                        currTab.find('.dep-sub-block').append('</div>');
                        break;
                    default:
                        currTab.find('.dep-sub-block').append('<div class="dep-sub-block-title">'+ res.payTypeMap[key1].payTypeDispCode +'</div>');
                        currTab.find('.dep-sub-block').append('<div class="list-btn-wrap payList-'+ payCnt +'"><ul class="list-btn payOption-'+ payCnt +'">');
                        for (const [key, value] of Object.entries(res.payTypeMap[key1].subTypeMap)) {
                            currTab.find('.dep-sub-block .payOption-'+ payCnt +'').append('<li class="item-wrap"><button class="item payment" onclick=\'selectPaymentType(this,"'+ value.trim() +'","'+ key +'","'+ res.payTypeMap[key1].payTypeCode +'","'+ res.payTypeMap[key1].payTypeId +'")\'><span class="icon"><img src="/images/payment-icons/'+ value +'.png"></span>'+ value +'</button></li>');
                        }
                        currTab.find('.dep-sub-block .payOption-'+ payCnt +'').append('</ul>');
                        currTab.find('.dep-sub-block .payList-'+ payCnt +'').append('</div>');
                }
            }
            break;
        case "withdrawal":
            currTab.find('.my-wallet-list-wrap').empty();
            banks = [];
            var whtml = ''
            for (const [key1, value1] of Object.entries(res.payTypeMap)) {
                banks[res.payTypeMap[key1].payTypeCode] = res.payTypeMap[key1].subTypeMap;
                whtml = '<div class="my-wallet-list">\n' +
                    '                    <div class="my-wallet-list-item fig-wrap">\n' +
                    '                        <div class="fig-blk"><img src="/images/payment-icons/'+ res.payTypeMap[key1].payTypeCode +'.png" alt="'+ res.payTypeMap[key1].payTypeCode +'"></div>\n' +
                    '                    </div>\n' +
                    '                    <div class="my-wallet-list-item min-wrap">\n' +
                    '                        <div class="min-blk">\n' +
                    '                            <div class="blk-label">Min</div>\n' +
                    '                            <div class="blk-value">\n' +
                    '                                <div class="currency-wrap">\n' +
                    '                                    <div class="cu-value">'+ $("#paymentCurrencyCode input:checked").val() +' '+ formatCurrency(res.payTypeMap[key1].minValue.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) +'</div>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                    <div class="my-wallet-list-item max-wrap">\n' +
                    '                        <div class="max-blk">\n' +
                    '                            <div class="blk-label">Max</div>\n' +
                    '                            <div class="blk-value">\n' +
                    '                                <div class="currency-wrap">\n' +
                    '                                    <div class="cu-value">'+ $("#paymentCurrencyCode input:checked").val() +' '+ formatCurrency(res.payTypeMap[key1].maxValue.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) +'</div>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                                    <div class="my-wallet-list-item action-wrap">\n' +
                    '                        <div class="action-blk">\n' +
                    '<!--                            <div class="blk-info-button">\n' +
                    '                                <button class="btn btn-info">i</button>\n' +
                    '                            </div>-->\n' +
                    '                            <div class="blk-main-action">\n' +
                    '                         <form name="cashier-withdrawal-request" id="cashier-withdrawal-request-'+ res.payTypeMap[key1].payTypeId +'" action="#" method="post" submit-type="ajax" validation-style="left" home-forgot-modal="true" tooltip-mode="manual" novalidate="novalidate">  \n' +
                    '                            <div class="section sec1 withdrawal_block">\n' +
                    '                                                            <div class="field-wrap select-parent">\n' +
                    '                                    <div class="form-group">\n' +
                    '                                        <div class="label"></div>\n' +
                    '                                        <select name="withdrawalAccounts" class="withdrawalAccounts" id="withdrawalAccounts-'+ res.payTypeMap[key1].payTypeId +'">\n' +
                    '                                        <option value="">Select Account</option>\n';

                try{
                    withdrawalAccounts.forEach(function (item) {
                        if( res.payTypeMap[key1].payTypeId == item.paymentTypeId ){
                            whtml +='                                    <option value="'+ item.paymentAccId +'" subtypeid="'+ item.subTypeId +'" ifsccode="'+ item.ifscCode +'" accountholder="'+ item.accHolderName +'">'+ item.accNum +'</option>\n';
                        }
                    });
                }
                catch (e) {

                }

                whtml +='                                     \n' +
                    '                                        </select>\n' +
                    '                                    <div id="error_withdrawalAccounts-'+ res.payTypeMap[key1].payTypeId +'" class="manual_tooltip_error error_tooltip"></div>\n' +
                    '                                </div>\n' +
                    '                                    </div>\n' +
                    '                                                                                                                            <div class="field-wrap input-parent">\n' +
                    '                                    <div class="form-group">\n' +
                    '                                        <div class="label">AMOUNT (in '+ $("#paymentCurrencyCode input:checked").val() +')</div>\n' +
                    '                                        <input type="text" name="amount_withdrawal" id="amount_withdrawal-'+ res.payTypeMap[key1].payTypeId +'" value="">\n' +
                    '                                     <div id="error_amount_withdrawal-'+ res.payTypeMap[key1].payTypeId +'" class="manual_tooltip_error error_tooltip"></div>\n' +
                    '                                    </div>\n' +
                    '                                    </div>\n' +
                    '                                \n' +
                    '                        <input type="hidden" name="withCurrency" id="withCurrency-'+ res.payTypeMap[key1].payTypeId +'" value="'+ $("#paymentCurrencyCode input:checked").val() +'">                                <!--                                <div class="field-wrap input-parent">\n' +
                    '                            <div class="label">AMOUNT</div>\n' +
                    '                            <div class="input-group">\n' +
                    '                                <div class="input-group-prepend open input-currency">\n' +
                    '                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"\n' +
                    '                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">CFA</button>\n' +
                    '                                    <div class="dropdown-menu">\n' +
                    '                                         <select name="withCurrency" id="withCurrency-" onchange="changeWithCurrency(this)">\n' +
                    '                                                                                <option value="INR">INR</option>\n' +
                    '                                        <a class="dropdown-item" href="#">INR</a>\n' +
                    '                                        <a class="dropdown-item" href="#"></a>\n' +
                    '                                        <a class="dropdown-item" href="#"></a>\n' +
                    '                                                                            </select>\n' +
                    '                                    </div>\n' +
                    '                                </div>\n' +
                    '                                <input type="text" name="amount_withdrawal" id="amount_withdrawal-85" value="">\n' +
                    '                            </div>\n' +
                    '                        </div>-->\n' +
                    '                                                                </div>\n' +
                    '                                <div class="section sec2">\n' +
                    '                                    <div class="buttonWrap">\n' +
                    '                                        <button class="btn btnStyle1 btn-deposit withdrawal_btn" paytype-id="'+ res.payTypeMap[key1].payTypeId +'" paymentype-code="'+ res.payTypeMap[key1].payTypeCode +'" subtype-id="85">Withdrawal</button>\n' +
                    '                                                                    <button type="button" class="btn btnOutline btn-addaccount withdrawal_add_new_account" onclick=\'AddNewAccount("'+ res.payTypeMap[key1].payTypeCode +'","'+ $("#paymentCurrencyCode input:checked").val() +'","'+ res.payTypeMap[key1].payTypeId +'")\'>Add New Account</button>\n' +
                    '                                                                 </div>\n' +
                    '                                </div>\n' +
                    '                    <input type="hidden" id="withPaytypeId-'+ res.payTypeMap[key1].payTypeId +'" name="withPaytypeId" value="'+ res.payTypeMap[key1].payTypeId +'">\n' +
                    '                    <input type="hidden" id="withPayTypeCode-'+ res.payTypeMap[key1].payTypeId +'" name="withPayTypeCode" value="'+ res.payTypeMap[key1].payTypeCode +'">\n' +
                    '<!--                    <input type="hidden" id="withSubType-7" name = "withSubType" value="85">\n' +
                    '                    <input type="hidden" id="with_payment_gateway-7" name = "with_payment_gateway" value="DEBIT_CARD">-->\n' +
                    '                         </form>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                                </div>';
                currTab.find('.my-wallet-list-wrap').append(whtml);
            }

            break;
    }

    currTab.show();
    initValidations();
    return true;
}

function selectPaymentType(element,bankName,subType,payTypeCode,payTypeId){
        $("#bankName").val('');
        $("#bankName").text('');
        $(".select-value").text('');
        $(element).parents().find('.payment').removeClass('active');
        $(element).addClass('active');
        allParams = 'subType='+ subType +'&bankName=' + bankName + '&payTypeCode=' + payTypeCode + '&paytypeId=' + payTypeId;
}

function AddNewAccount(payTypeCode, currency, payTypeId) {
  var selectList = $("#select_bank");
    selectList.find("option:gt(0)").remove();
    if (payTypeCode == 'NET_BANKING'){
       $(".branch_code").css('display','block')
        var bank = banks.NET_BANKING;
        
        for (var i in bank) {
//                if (subTypeId == res.bankProfile[i].subtypeId) {
        $('#select_bank')
                .append($("<option></option>")
                        .attr({"value": i})
                        .text(bank[i]));
        // }
    }
    $("#select_bank").css('display','block');
    $("#withPayTypeCode").val(payTypeCode);
    $("#withPaymentTypeId").val(payTypeId);
    $("#withCurrencyCode").val(currency);
    $("#cashier-withdrawal-add-account-form").css('display','block');
    $("#cashier-withdrawal-upi-add-account-form").css('display','none');
    }
    else{
        var bank = banks.UPI;
       $("#withUpiPayTypeCode").val(payTypeCode);
       $("#withUpiPaymentTypeId").val(payTypeId);
       $("#withUpiCurrencyCode").val(currency);
       $("#withUpiSubTypeId").val(Object.keys(bank));
        $("#cashier-withdrawal-add-account-form").css('display','none');
        $("#cashier-withdrawal-upi-add-account-form").css('display','block');
        
    }

    showDefaultPopupContent(payTypeCode);
    $("#cashier_add_account_withdrawal_popup").modal("show");
}

function showDefaultPopupContent(payTypeCode) {
      $("#cashier-withdrawal-otp-verification-form").css('display', 'none');
    $("#cashier-successfull-withdrawal-form").css('display', 'none');
   if(payTypeCode == 'UPI'){
   $("#cashier-withdrawal-add-account-form").css('display','none');
   $("#cashier-withdrawal-upi-add-account-form").css('display','block');    
   }else{
     $("#cashier-withdrawal-add-account-form").css('display','block');
     $("#cashier-withdrawal-upi-add-account-form").css('display','none');   
   }
  
    $(".withdrawal-footer").html("");
}

function getOtpForWithdrawalAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
     if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
         else if (res.errorCode == 318)
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', res.errorMsg, "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
        else
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', res.errorMsg, "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
    } else {
        $('#cashier-withdrawal-add-account-form').css('display', 'none');
        showFooterHtml(res.paymentType);
        

    }
}

function getResponseFoWithdrawalOTP(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    //console.log(res);
    if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 530)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', res.respMsg, "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
    } else {
        success_message('Account Added Successfully');
        setTimeout(function () {
            jQuery("#system-message-container").html('')
        }, 7000);
        $("#cashier_add_account_withdrawal_popup").modal("hide");
        var params = '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withdrawalPaymentTypeCode").val();
        startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemWithdrawalAccount, "#" + withdrawal_verify_otp_form);
    }
}

function getAllRedeemWithdrawalAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    var selectList = $(".withdrawalAccounts");
    selectList.find("option:gt(0)").remove();

    if (res.errorCode != 0) {
        if (res.errorCode == 203)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 103)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_INVALID_REQUEST"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        else
            showToolTipErrorManual(withdrawal_verify_otp_form + ' #withdrawal_otp', res.respMsg, "bottom", $("#withdrawal_otp"), error_callback_withdrawal_verifyotp[withdrawal_verify_otp_form]);
        //jQuery("#system-message-container").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+res.respMsg+'</div></div>'); 
    } else {
        $(document).ready(function () {
            for (var i in res.bankProfile) {
                    $('#withdrawalAccounts-'+res.bankProfile[i].paymentTypeId)
                            .append($("<option></option>")
                                    .attr({"value": i, "subTypeId": res.bankProfile[i].subtypeId, "ifscCode": res.bankProfile[i].ifscCode, "accHolderName": res.bankProfile[i].accHolderName})
                                    .text(res.bankProfile[i].accNum));
           }

        });
    }
}

function processWithdrawalRequest(result){
   if (validateSession(result) == false)
        return false;
   $("#withdrawalreq").attr("disabled",false);
   $("#withdrawal_confirmation_popup").modal("hide");
    var res = $.parseJSON(result); 
   if(res.errorCode != 0){
    error_message(res.errorMsg);
   }else{
    success_message(res.respMsg);  
   }
    
}

function getResponseForWithdrawalResendOTP(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
   if(res.errorCode != 0) {
      $("#error_withdrawal_otp").text(res.errorMsg);
      $("#error_withdrawal_otp").css('display','block');
     //$("#cashier-withdrawal-mobile").parent().parent().css({'display':'block','color':'red'});
     //$("#modal-withmobile-no").parent().text(''); 
     //$("#cashier-withdrawal-mobile").parent().parent().text(res.respMsg);   
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', res.respMsg, "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
   }else{
     $(".send_msg").css('display','block');
     //$("#cashier-withdrawal-mobile").parent().parent().css('display','block');
     //setTimeout(function(){$("#cashier-withdrawal-mobile").parent().parent().css('display','none');}, 3000);
     //$("#resend-link-withdrawal").css('display','block');
   }       
 }
 
 function showDefaultDepositPopupContent(){
   $('[name="depositAccounts"]').prop('selectedIndex',0);
   $("#cashier-withdrawal-otp-verification-form").css('display', 'none');
    $("#cashier-successfull-withdrawal-form").css('display', 'none');
    $("#cashier-deposit-add-account-form").css('display', 'block');
    $(".deposit-footer").html("");   
 }
 
 function getOtpForUpiAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
     if (res.errorCode != 0) {

            showToolTipErrorManual(deposit_add_account + ' #upiId', res.errorMsg, "bottom", $("#upiId"), error_callback_deposit_add_account[deposit_add_account]);
    } else {
        $('#cashier-deposit-add-account-form').css('display', 'none');
        $('#cashier-deposit-otp-verification-form').css('display', 'block');
        //$(".deposit-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
        $(".deposit-footer").html(
                "<div class='button_holder'><p><span class='heighlight_color'><strong><em>NO CODE RECEIVED?</em></strong>REQUEST AGAIN</span></p>" +
                "<button id='cashier_deposit_resendOtp' class='resendOtp btn btnStyle1'>RESEND OTP</button></div>" +
                "<div class='form-group text-center send_msg' style='display:none;'><p>OTP code has been sent to your mobile successfully.</p>" +
                "</div>");
        $("#cashier-deposit-mobile").html($("#withAccNum").val());
        setTimeout(function () {
            $("#cashier-deposit-mobile").parent().parent().css('display', 'none');
        }, 3000);

    }
}

 function getResponseUpiOTP(result){
  if(validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);   
      if (res.errorCode != 0) {
            showToolTipErrorManual(deposit_verify_otp_form + ' #upiOtp', res.respMsg, "bottom", $("#upiOtp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
    } else {
        success_message('Account Added Successfully');
        setTimeout(function () {
            jQuery("#system-message-container").html('')
        }, 7000);
        $("#cashier_add_account_deposit_popup").modal("hide");
        var params = '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val();
        startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllDepositAccount, "#" + withdrawal_verify_otp_form);
    }
}

function getAllDepositAccount(result){
  if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    var selectList = $(".depositAccounts");
    selectList.find("option:gt(0)").remove();   
        if (res.errorCode != 0) {
            showToolTipErrorManual(deposit_verify_otp_form + ' #upiOtp', res.respMsg, "bottom", $("#upiOtp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        //jQuery("#system-message-container").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+res.respMsg+'</div></div>'); 
    } else {
        $(document).ready(function () {
            for (var i in res.bankProfile) {
                if(res.bankProfile[i].paymentType == 'UPI'){
                 var payCode = res.bankProfile[i].paymentType;
                 var payTypeId = res.bankProfile[i].paymentTypeId;
                }
                    $('#depositAccounts-'+res.bankProfile[i].paymentTypeId)
                            .append($("<option></option>")
                                    .attr({"value": i, "subTypeId": res.bankProfile[i].subtypeId,"accHolderName": res.bankProfile[i].accHolderName})
                                    .text(res.bankProfile[i].accNum));
           }
           $('#depositAccounts-'+payTypeId)
                            .append('<option value="'+payCode+'" payTypeId= "'+payTypeId+'" currency="'+'INR'+'">Add New Account</option>');

        });
    }
}

function getOtpForUpiWithdrawalAddAccount(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
     if (res.errorCode != 0) {
            showToolTipErrorManual(with_upi_add_account + ' #withUpiId', res.errorMsg, "bottom", $("#withUpiId"), error_callback_with_upi_add_account[with_upi_add_account]);
    } else {
        $('#cashier-withdrawal-upi-add-account-form').css('display', 'none');
        showFooterHtml(res.paymentType,res.accNum);

    }
}

function showFooterHtml(paymentType, accNum){
    $("#otpVerificationParams").val(paymentType)
    $('#cashier-withdrawal-otp-verification-form').css('display', 'block');
        //$(".deposit-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
        $(".withdrawal-footer").html(
                "<div class='button_holder'><p><span class='heighlight_color'><strong><em>NO CODE RECEIVED?</em></strong>REQUEST AGAIN</span></p>" +
                "<button id='cashier_withdrawal_resendOtp' class='resendOtp btn btnStyle1'>RESEND OTP</button></div>" +
                "<div class='form-group text-center send_msg' style='display:none;'><p>OTP code has been sent to your mobile successfully.</p>" +
                "</div>");
        if(paymentType == 'UPI'){
        $("#cashier-withdrawal-mobile").text(accNum);
    }
        else{
         $("#cashier-withdrawal-mobile").text(accNum);
     }
        setTimeout(function () {
            $("#cashier-withdrawal-mobile").parent().parent().css('display', 'none');
        }, 3000);
}
$(window).load(function(){
    if( location.hash == "#withdrawal" ){
        $("[href='#withdrawal']").trigger("click");
    }
    if (location.hash == "#deposit") {
        $("[href='#deposit']").trigger("click");
    }
});



