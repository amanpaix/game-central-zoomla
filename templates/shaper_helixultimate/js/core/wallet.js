var min_limit = 500;
var max_limit = 1000000;
var subTypeId;
var paymentTypeCode;
var paymentTypeId;
var deposit_add_account;
var deposit_verify_otp_form;
var error_callback_add_account = {};
var error_callback_deposit_verifyotp = {};
var error_callback_registration = {};
var withdrawal_form_id_for_response = {};
var withSubTypeId = '';
var withPaymentTypeCode = '';
var withPaymentTypeId = '';
var reg_form_id_for_response = "";
var payment = '';
var form_id;
var minDepositLimit = '';
var maxDepositLimit = '';
var minDepositAmount = '';
var maxDepositAmount = '';
var maxWithdrawalLimit = '';
var minWithdrawalLimit = '';
var withdrawalFields;
var cashier_withdrawal_id;
var maxWithdrawal = '';
var id;
var maxWithdrawalAmount = '';
var fromPrev = false;
var pageWindow = 5;
var startPageNo = 1;
var endPageNo = 5;
var prevFromDate = '';
var prevToDate = '';
var limitReached = false;
var lastPageNo = 0;
var fromPrev = false;
var withdrawal_add_account;
var error_callback_withdrawal_add_account = {};
var withdrawal_verify_otp_form;
var error_callback_withdrawal_verifyotp = {};
var withdrawal_verify_otp_form;
var totalBalanceAfter = 0;

$(document).ready(function () {
    $('.Withdrawal').css('display', 'none');
    $("ul#url-tabs>li").on('click', function () {
        clearSystemMessage();
        $("ul#url-tabs>li").removeClass("active");
        $(this).addClass("active");
        $("[div_id]").css("display", "none");
        $("[div_id='" + $(this).children().attr("href").replace("#", "") + "']").css("display", "block");
        $("div.heading>h2").html($(this).children().html());
    });
    var url_hash = window.location.hash;
    if (url_hash != "") {
        $("a[href='" + url_hash + "']").trigger("click");
    }
    if(depositBal != null){
    if (depositBal.MOBILE_MONEY == 'undefined') {
        depositBal['MOBILE_MONEY'] = {'min': 0, 'max': 0};
    }
    }
    if(depositBal != null){
    if (depositBal.DIGITAL_WALLET == undefined) {
        depositBal['DIGITAL_WALLET'] = {'min': 0, 'max': 0};
    }
    }
     $('[id^=select_deposit]').on('change', function () {
      if(this.value == 'other'){
      $('[id^=deposit_value]').css('display','block');
  }
      else{
     $('[id^=deposit_value]').css('display','none');
 }
    });
    $('[id^=multi-currency-deposit-select]').on('change', function () {
      if(this.value == 'other'){
      $('[id^=multi-currency-deposit-value]').css('display','block');
  }
      else{
     $('[id^=multi-currency-deposit-value]').css('display','none');
 }
    });
     var option = $('.paymentAccount > option').length;
        if (option > 0)
            $(".deposit_add_new_account").css('display','none');   
        else {
           $(".deposit_add_new_account").css('display','block');   
        }
     var withOption = $('.withdrawalAccounts > option').length;
        if (withOption > 0)
            $(".withdrawal_add_new_account").css('display','none');   
        else {
           $(".withdrawal_add_new_account").css('display','block');   
        }
    $(".deposit_add_new_account").on('click', function () {
        subTypeId = $(this).attr('subtype-id');
        paymentTypeCode = $(this).attr('paymentype-code');
        paymentTypeId = $(this).attr('paytype-id');
        var currency = $(this).attr('curr');
        $("#depositSubtypeId").val(subTypeId);
        $("#depositPaymentTypeCode").val(paymentTypeCode);
        $("#depositPayTypeId").val(paymentTypeId);
        $("#depositCurrency").val(currency);
        //var params = '&paymentTypeCode=' + paymentTypeCode; 
        //startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemAccount, "#" + deposit_add_account); 
        $("#cashier_deposit_popup").modal('show');
        showDefaultPopupContent();
    });

    $(".withdrawal_add_new_account").on('click', function () {
        withSubTypeId = $(this).attr('subtype-id');
        withPaymentTypeCode = $(this).attr('paymentype-code');
        withPaymentTypeId = $(this).attr('paytype-id');
        var withCurrency = $(this).attr('curr');
        $("#withSubtypeId").val(withSubTypeId);
        $("#withPayTypeCode").val(withPaymentTypeCode);
        $("#withPaymentTypeId").val(withPaymentTypeId);
        $("#withCurrencyCode").val(withCurrency);
        showDefaultWithdrawalContent();
    });

    $(".btn-deposit").on('click', function () {
//      $("#subType").val($(this).attr('subtype-id'));
//      $("#payTypeCode").val($(this).attr('paymentype-code'));
//      $("#paytypeId").val($(this).attr('paytype-id'));
        payment = $(this).attr('paymentype-code');
        $("input[name*='payTypeCode']").val();
        $("input[name*='subType']").val($(this).attr('subtype-id'));
        $("input[name*='paytypeId']").val($(this).attr('paytype-id'));

    });

    $("[href='#withdrawal']").live('click', function () {

        //DisableWithdrawalBtn();
        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'WITHDRAWAL';

        if ($("#system-message-container").text().trim().length > 0)
            startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getWithdrawalResponse, "nottoshow");
        else
            startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getWithdrawalResponse, '');
        
        //startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemWithdrawalAccount, "#" + '');

    });
//     var withOption = $('[id^=withdrawalAccounts] > option').length;
//     var depOption = $('[id^=paymentAccount] > option').length;
//      if(withOption > 1){
//       $(".withdrawal_add_new_account").css('display','none');
//        }else{
//        $(".withdrawal_add_new_account").css('display','block'); 
//        }
//    if(depOption > 1){
//       $(".deposit_add_new_account").css('display','none');
//        }else{
//        $(".deposit_add_new_account").css('display','block'); 
//        }
    $($("form[id^='cashier-deposit-add-account-form']")).each(function () {
        deposit_add_account = $(this).attr('id');
        error_callback_add_account[deposit_add_account] = $("#cashier-deposit-add-account-form").attr('error-callback');
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
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_NAME'),
                    //min: Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                accNum: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER'),
                    pattern: "Please enter a valid 9 digit account number.",
                    rangelength: "Account number should be in range.",
                }
            },

            submitHandler: function () {
                if ($("#" + deposit_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + deposit_add_account + " #accNum").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #accHolderName").val() + '&subTypeId=' + $("#" + deposit_add_account + " #depositSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #depositPayTypeId").val() + '&currencyCode=' + $("#"+deposit_add_account + " #depositCurrency").val();
                    startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getOtpForAddingAccount, "#" + deposit_add_account);
                }

            }
        });
    });

    $($("form[id^='cashier-deposit-form']")).each(function () {
        var reg_form_id = $(this).attr('id');
        reg_form_id_for_response = reg_form_id;
        error_callback_registration[reg_form_id] = $("#" + reg_form_id).attr('error-callback');
        if(depositBal != null){
         minDepositAmount = depositBal[$(this).find("input[id^='payTypeCode']").val()].min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
         minDepositLimit = depositBal[$(this).find("input[id^='payTypeCode']").val()].min;
         maxDepositAmount = depositBal[$(this).find("input[id^='payTypeCode']").val()].max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
         maxDepositLimit = depositBal[$(this).find("input[id^='payTypeCode']").val()].max;
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
                deposit_value: {
                    required: true,
                    //pattern:/^[1-9][0-9]*$/,
                    min: minDepositLimit,
                    range: [minDepositLimit , maxDepositLimit]
                },
                paymentAccount: {
                    required: true,
                }
            },

            messages: {
                deposit_value: {
                    required: Joomla.JText._('PLEASE_ENTER_AMOUNT_TO_DEPOSIT'),
                    //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                    min: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + ' ' + formatCurrency(minDepositAmount,decSymbol) + Joomla.JText._('BETTING_DEPOSIT_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') + ' ' + formatCurrency(minDepositAmount,decSymbol)+ Joomla.JText._('BETTING_TO') + formatCurrency(maxDepositAmount,decSymbol)+ "."
                },
                paymentAccount: {
                    required: Joomla.JText._('WITH_SELECT_AMT'),
                }
            },

            submitHandler: function (form) {
                 if($('[id^=select_deposit]').val() == 'other')
                var deposit_value = $('[id^=deposit_value]').val();
                else
                  deposit_value = $('[id^=select_deposit]').val();
                 if($('[id^=multi-currency-deposit-select]').val() == 'other')
                var multi_deposit_value = $('[id^=multi-currency-deposit-value]').val();
                else
                  multi_deposit_value = $('[id^=multi-currency-deposit-select]').val();
                if(Object.keys(currencyList).length > 1)
                $("#deposit_value").text($(form).find("select[id^='currency']").val()+' '+multi_deposit_value);
                else
                $("#deposit_value").text($(form).find("input[id^='currency']").val()+' '+deposit_value);
                $("#paymentGatewayType").text($(form).find("input[id^='payment_gateway']").val());
                $("#deposit_confirmation_popup").modal('show');
                var id = $(form).attr('id');
                var  params = 'payTypeCode=' + $(form).find("input[id^='payTypeCode']").val() + '&paytypeId=' + $(form).find("input[id^='paytypeId']").val() + '&subType=' + $(form).find("input[id^='subType']").val() + '&paymentAccount=' + $(form).find("select[id^='paymentAccount']").val();
                if(Object.keys(currencyList).length > 1){
                  params += '&currency=' + $(form).find("select[id^='currency']").val();    
                  params += '&deposit=' + multi_deposit_value;
                }else{
                 params += '&currency=' + $(form).find("input[id^='currency']").val(); 
                 params += '&deposit=' + deposit_value;
                    }
                var form_fields = params.split("&");
                for (var i = 0; i < form_fields.length; i++) {
                    var temp = form_fields[i].split("=");
                    $('#deposit-request-form').append($('<input>', {
                        type: "hidden",
                        name: temp[0],
                        value: temp[1]
                    }));
                }
                
            }
        });
    });
    $("#deposit_confirmation_popup #doDeposit").click(function () {
    document.getElementById("deposit-request-form").submit();
    }); 
    $($("form[id^='cashier-withdrawal-add-account-form']")).each(function () {
        withdrawal_add_account = $(this).attr('id');
        error_callback_withdrawal_add_account[deposit_add_account] = $(withdrawal_add_account).attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                withAccHolderName: {
                    required: true,
                    //min:5
                },
                withAccNum: {
                    required: true,
                    pattern :"^[7,1]{1}[0-9]{8}$",
                    rangelength: [9,9],

                }
            },

            messages: {
                withAccHolderName: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_NAME'),
                    //min:Joomla.JText._('PLEASE_ENTER_YOUR_NAME')
                },
                withAccNum: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER'),
                    pattern: "Please enter a valid 9 digit account number.",
                    rangelength: "Account number should be in range.",
                }
            },

            submitHandler: function () {
                if ($("#" + withdrawal_add_account).attr('submit-type') != 'ajax') {
                    document.getElementById(withdrawal_add_account).submit();
                } else {
                    var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&isCashierUrl=' + '1' + '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
                    startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getOtpForWithdrawalAccount, "#" + withdrawal_add_account);
                }

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
                    required: Joomla.JText._('PLEASE_ENTER_AMOUNT_TO_WITHDRAW'),
                    //pattern: Joomla.JText._("PLEASE_ENTER_VALID_AMOUNT"),
                    min: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +formatCurrency(minWithdrawalLimit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_TO') +formatCurrency(maxWithdrawalAmount,decSymbol) + "."
                },
                withdrawalAccounts: {
                    required: Joomla.JText._('WITH_SELECT_AMT'),
                }
            },

            submitHandler: function (form) {
                if (Object.keys(withCurrencyList).length > 1)
                    $("#withdraw_value").text($(form).find("select[id^='withCurrency']").val() + ' ' + $(form).find("input[id^='amount_withdrawal']").val());
                else
                    $("#withdraw_value").text($(form).find("input[id^='withCurrency']").val() + ' ' + $(form).find("input[id^='amount_withdrawal']").val());
                $("#withPaymentGatewayType").text($(form).find("input[id^='with_payment_gateway']").val());
                var amount = parseInt($("#" + cashier_withdrawal_id).find("input[id^='amount_withdrawal']").val());
                totalBalanceAfter =  parseInt($("#" + cashier_withdrawal_id).find("input[id^='totalBalance']").val());
                if(amount > totalBalanceAfter)
                {
                    error_message("Insufficient Withdrawable balance");
                    return false;
                }
                else{
                $("#withdrawal_confirmation_popup").modal('show');
                }
                //console.log(form_id.find("input[id^='withdrawal']").val())
                id = cashier_withdrawal_id;
                if ($("#" + cashier_withdrawal_id).attr('submit-type') != 'ajax') {
                    document.getElementById(cashier_withdrawal_id).submit();
                } else {
                    // var amount = $("#" + cashier_withdrawal_id).find("input[id^='amount_withdrawal']").val();
                    var redeemAccId = $("#" + cashier_withdrawal_id).find("select[id^='withdrawalAccounts']").val();
                    // var totalBalance =  $("#" + cashier_withdrawal_id).find("input[id^='totalBalance']").val();
                    var withdrawalFields = 'paymentTypeId=' + payTypeId + '&subTypeId=' + sub + '&amount=' + amount + '&paymentTypeCode=' + payTypeCode + '&redeemAccId=' + redeemAccId + '&isCashierUrl=' + '1';
                   
                 if (Object.keys(withCurrencyList).length > 1) {
                        withdrawalFields += '&CurrencyCode=' + $(form).find("select[id^='withCurrency']").val();
                    } else {
                        withdrawalFields += '&CurrencyCode=' + $(form).find("input[id^='withCurrency']").val();
                    }
                    var form_fields = withdrawalFields.split("&");
                    $('#withdrawal-request-form').empty();
                    for (var i = 0; i < form_fields.length; i++) {
                        var temp = form_fields[i].split("=");
                        $('#withdrawal-request-form').append($('<input>', {
                            type: "hidden",
                            name: temp[0],
                            value: temp[1]
                        }));
                    }
                    //startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails", params, processWithdrawalRequest, "#" + cashier_withdrawal_id);

                }
            }
        });
    });
    $("#withdrawal_confirmation_popup #doWithdraw").click(function () {
   // console.log(withdrawalFields);
   var params = 'paymentTypeId=' + $('#withdrawal-request-form').find('input[name="paymentTypeId"]').val() + '&subTypeId=' + $('#withdrawal-request-form').find('input[name="subTypeId"]').val() + '&amount=' + $('#withdrawal-request-form').find('input[name="amount"]').val() + '&paymentTypeCode=' + $('#withdrawal-request-form').find('input[name="paymentTypeCode"]').val() + '&redeemAccId=' + $('#withdrawal-request-form').find('input[name="redeemAccId"]').val() + '&isCashierUrl=' + $('#withdrawal-request-form').find('input[name="isCashierUrl"]').val() + '&CurrencyCode=' + $('#withdrawal-request-form').find('input[name="CurrencyCode"]').val();
    startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails",params, processWithdrawalRequest, "#" + cashier_withdrawal_id);
    });
    $(".resp-tabs-list>li").click(function () {
        removeToolTipErrorManual("all");
    });
    var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'DEPOSIT';

    if ($("#system-message-container").text().trim().length > 0)
        startAjax("/component/Betting/?task=cashier.getDepositDetails", params, pendingDepositResponse, "nottoshow");
    else
        startAjax("/component/Betting/?task=cashier.getDepositDetails", params, pendingDepositResponse, '');

    $("[href='#deposit']").on('click', function () {


        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'DEPOSIT';

        if ($("#system-message-container").text().trim().length > 0)
            startAjax("/component/Betting/?task=cashier.getDepositDetails", params, pendingDepositResponse, "nottoshow");
        else
            startAjax("/component/Betting/?task=cashier.getDepositDetails", params, pendingDepositResponse, '');

    });
    
    $("#sp-custom-popup").on('click', "#cashier_withdrawal_resendOtp", function () {
        $("#with_otp_code").val('');
        var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&isCashierUrl=' + '1'+ '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseForWithdrawalResendOTP, "#" + withdrawal_add_account);
    });
    $("#sp-custom-popup").on('click', "#cashier_deposit_resendOtp", function () {
        $("#deposit_otp").val('');
        var params = 'accNum=' + $("#" + deposit_add_account + " #accNum").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #accHolderName").val() + '&subTypeId=' + $("#" + deposit_add_account + " #depositSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #depositPayTypeId").val() + '&currencyCode=' + $("#"+deposit_add_account + " #depositCurrency").val();
        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseForDepositResendOTP, "#" + deposit_add_account);
    });
});

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
        $('#cashier-deposit-add-account-form').css('display', 'none');
        $('#cashier-deposit-otp-verification-form').css('display', 'block');
        $(".deposit-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
        $(".deposit-footer").html(
                "<div class='button_holder'><p><span class='heighlight_color'><strong><em>" + Joomla.JText._("NO_CODE_RECEIVED") + "</em></strong> " + Joomla.JText._("REQUEST_AGAIN") + "</span></p>" +
                "<button id='cashier_deposit_resendOtp' class='resendOtp heighlight_color'>" + Joomla.JText._("RESEND_OTP") + "</button></div>" +
                "<div class='form-group text-center' style='display:none;' id='cashier-resend-link-deposit'><p  class='send_msg'>" + Joomla.JText._("OTP_CODE_HAS_BEEN_SENT") + "</p>" +
                "</div>");
        $("#cashier-deposit-mobile").html($("#accNum").val());
        setTimeout(function () {
            $("#cashier-deposit-mobile").parent().parent().css('display', 'none');
        }, 3000);

    }
    $(document).ready(function () {
        $($("form[id^='cashier-deposit-otp-verification-form']")).each(function () {
            deposit_verify_otp_form = $(this).attr('id');
            error_callback_deposit_verifyotp[deposit_verify_otp_form] = $("#cashier-deposit-otp-verification-form").attr('error-callback');
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
                        var params = 'accNum=' + $("#" + deposit_add_account + " #accNum").val() + '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_add_account + " #accHolderName").val() + '&subTypeId=' + $("#" + deposit_add_account + " #depositSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + deposit_add_account + " #depositPayTypeId").val() + '&isOtp=' + '1' + '&verifyOtp=' + $("#" + deposit_verify_otp_form + " #deposit_otp").val() + '&currencyCode=' + $("#"+deposit_add_account + " #depositCurrency").val();
                        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseForOTP, "#" + deposit_verify_otp_form);
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
        $("#cashier_deposit_popup").modal('hide');
        success_message(Joomla.JText._("ACCOUNT_ADDED_SUCCESSFULLY"));
        setTimeout(function () {
            jQuery("#system-message-container").html('')
        }, 7000);
        var params = '&paymentTypeCode=' + $("#" + deposit_add_account + " #depositPaymentTypeCode").val();
        startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemAccount, "#" + deposit_verify_otp_form);
    }
}

function showDefaultPopupContent() {
    $("#cashier-deposit-otp-verification-form").css('display', 'none');
    $("#successfull-deposit-form").css('display', 'none');
    $("#successfull-deposit-form").css('display', 'none');
    $("#cashier-deposit-add-account-form").css('display', 'block');
    $(".deposit-footer").html("");
}

function showDefaultWithdrawalContent() {
    $("#cashier-withdrawal-otp-verification-form").css('display', 'none');
    $("#cashier-successfull-withdrawal-form").css('display', 'none');
    $("#cashier-withdrawal-add-account-form").css('display', 'block');
    $(".withdrawal-footer").html("");
}

function getAllRedeemAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    selectList = $(".paymentAccount");
    selectList.find("option:gt(0)").remove();

    if (res.errorCode != 0) {
        if (res.errorCode == 203)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 103)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_INVALID_REQUEST"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        else
            showToolTipErrorManual(deposit_verify_otp_form + ' #deposit_otp', res.respMsg, "bottom", $("#deposit_otp"), error_callback_deposit_verifyotp[deposit_verify_otp_form]);
        //jQuery("#system-message-container").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+res.respMsg+'</div></div>'); 
    } else {
        $(document).ready(function () {
            //if(res.bankProfile){
//    $("#cashier-deposit-otp-verification-form").display('none'); 
//     $("#cashier-deposit-add-account-form").css('display','block');
//     $(".deposit-footer").html('');
            //}
            //(".btn-deposit").css('display','block');
            $(".select_block").css('display', 'flex');
            $(".btn-deposit").css("display", "flex");
            for (var i in res.bankProfile) {
                if (subTypeId == res.bankProfile[i].subtypeId) {
                    $('.paymentAccount')
                            .append($("<option></option>")
                                    .attr({"value": i, "redeemAccId": res.bankProfile[i].paymentTypeId})
                                    .text(res.bankProfile[i].accNum));                
                }
            }

        });
    }
}

function pendingDepositResponse(result) {
    var tmp_fromPrev = fromPrev;
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    console.log(res.errorCode);
    if (res.errorCode == 0) {
        $("#deposit-table").show();
        //console.log(res)
        //$("[id='Withrawal_wallet']").css("display", "block");

        if (res.txnList.length <= 0)
        {
            $('#deposit-table tbody > tr').remove();
            $('#diposit-div').css('display', 'none');
            //error_message("No Withdrawal Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('WITHDRAWL_NO_DETAIL'));
            $("#error-div").css("display", "");
            return false;
        }
        // clearSystemMessage();
        $('#deposit-div').css('display', 'block');
        $('#deposit-table tbody > tr').remove();

        var totRows = 100;
        limitReached = false;
        lastPageNo = 0;

        if (res.txnList.length <= limit) {
            totRows = res.txnList.length;
            limitReached = true;
        }
        if (totRows < 11)
            $('#deposit-table tfoot .pagination').css("display", "none");
        else
            $('#deposit-table tfoot .pagination').css("display", "block");
        for (var i = 0; i < totRows; i++) {

            var footable = $('#deposit-table').data('footable');

            var txnid = '';
            var sNo = '';
            var txndate = '';
            var amount = '';
            var refTxnNo = '';
//            var refTxnDate = '';
//            var status = '';
//            var otp = '';

            if (typeof res.txnList[i].userTxnId != 'undefined')
                txnid = res.txnList[i].userTxnId;
            // if(typeof res.withdrawalList[i].transactionDate != 'undefined') {
            //     txndate = res.withdrawalList[i].transactionDate;
            //     var tmp = txndate.lastIndexOf(".");
            //     txndate = txndate.substring(0, tmp);
            // }
            sNo = i + 1;
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
//            if(typeof res.withdrawalList[i].refTxnDate != 'undefined') {
//                refTxnDate = res.withdrawalList[i].refTxnDate;
//                var tmp = refTxnDate.lastIndexOf(".");
//                refTxnDate = refTxnDate.substring(0, tmp);
//            }
//            if(typeof res.withdrawalList[i].status != 'undefined')
//                status = res.withdrawalList[i].status;
//
//            if( res.withdrawalList[i].verificationCode != 'undefined' )
//                otp = res.withdrawalList[i].verificationCode;

//            var cancelIcon = '<a href="javascript:;" style="color: red !important;"><span class="icon-remove-1" trans-id="'+txnid+'"><img src="/templates/shaper_helix3/images/my_account/cancel_icon.png">'+Joomla.JText._('BETTING_CANCEL_REQUEST')+'</span></a>';
//
//            if(res.withdrawalList[i].status.toUpperCase() != "PENDING") {
//                if(res.withdrawalList[i].status.toUpperCase() == "DONE")
//                    cancelIcon = '<a><img src="/templates/shaper_helix3/images/my_account/done-icon.png"></a>';
//            }

            var newRow = '<tr style="text-align: center">' +
                    '<td>' + sNo + '</td>' +
                    '<td>' + txndate + '</td>' +
                    '<td>' + txnid + '</td>' +
                    '<td>' + formatCurrency(amount, decSymbol) + '</td>' +
                    '</tr>';

            footable.appendRow(newRow);
        }

        $('#deposit-table').trigger('footable_redraw');
        if (offset == 1) {
            $('#deposit-table').trigger('footable_initialize');
            $('#deposit-footer-pagination-div').children().children().first().addClass(' disabled');
            $('#deposit-table tfoot').addClass('hide-if-no-paging');
        } else {
            $('#deposit-table tfoot').removeClass('hide-if-no-paging');
            if (totRows < 10)
                $('#deposit-footer-pagination-div').children().children().last().addClass(' disabled');
            //resetPageNo();
        }
        if (tmp_fromPrev) {
            $("#deposit-footer-pagination-div>ul>li").last().prev().children().trigger('click');
        }


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
        //$("[id='Withrawal_wallet']").css("display", "none");
        //$(".iniWithBTN").show();
        //$('#msg').text(otp);
    }
}

function getOtpForWithdrawalAccount(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    //console.log(res);
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
        else
            showToolTipErrorManual(withdrawal_add_account + ' #withAccNum', res.errorMsg, "bottom", $("#withAccNum"), error_callback_withdrawal_add_account[withdrawal_add_account]);
    } else {
        $('#cashier-withdrawal-add-account-form').css('display', 'none');
        $('#cashier-withdrawal-otp-verification-form').css('display', 'block');
        //$(".deposit-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
        $(".withdrawal-footer").html(
                "<div class='button_holder'><p><span class='heighlight_color'><strong><em>" + Joomla.JText._("NO_CODE_RECEIVED") + "</em></strong> " + Joomla.JText._("REQUEST_AGAIN") + "</span></p>" +
                "<button id='cashier_withdrawal_resendOtp' class='resendOtp heighlight_color'>" + Joomla.JText._("RESEND_OTP") + "</button></div>" +
                "<div class='form-group text-center' style='display:none;' id='cashier-resend-link-withdrawal'><p  class='send_msg'>" + Joomla.JText._("OTP_CODE_HAS_BEEN_SENT") + "</p>" +
                "</div>");
        $("#cashier-withdrawal-mobile").html($("#withAccNum").val());
        setTimeout(function () {
            $("#cashier-withdrawal-mobile").parent().parent().css('display', 'none');
        }, 3000);

    }
    $(document).ready(function () {
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
                        required: Joomla.JText._('BETTING_PLAESE_ENTER_OTP'),
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
                        var params = 'accNum=' + $("#" + withdrawal_add_account + " #withAccNum").val() + '&paymentTypeCode=' + $("#" + withdrawal_add_account + " #withPayTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_add_account + " #withAccHolderName").val() + '&subTypeId=' + $("#" + withdrawal_add_account + " #withSubtypeId").val() + '&isNewRedeemAcc=' + 'Y' + '&paymentTypeId=' + $("#" + withdrawal_add_account + " #withPaymentTypeId").val() + '&isCashierUrl=' + '1' + '&verifyOtp=' + $("#" + withdrawal_verify_otp_form + " #withdrawal_otp").val() + '&isOtp=' + '1' + '&currencyCode=' + $("#" + withdrawal_add_account + " #withCurrencyCode").val();
                        startAjax("/component/Betting/?task=cashier.AddNewAccount", params, getResponseFoWithdrawalOTP, "#" + withdrawal_verify_otp_form);
                    }

                }
            });
        });
    });
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
        success_message(Joomla.JText._("ACCOUNT_ADDED_SUCCESSFULLY"));
        setTimeout(function () {
            jQuery("#system-message-container").html('')
        }, 7000);
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
            //if(res.bankProfile){
//    $("#cashier-deposit-otp-verification-form").display('none'); 
//     $("#cashier-withdrawal-otp-verification-form").css('display','block');
//     $(".withdrawal-footer").html('');

            //}
            //(".btn-deposit").css('display','block');
            $(".withdrawal_block").css('display', 'flex');
            $(".withdrawal_btn").css("display", "flex");
            for (var i in res.bankProfile) {
                if (withSubTypeId == res.bankProfile[i].subtypeId) {
               // if(mobileNo === res.bankProfile[i].accNum){
                    $('.withdrawalAccounts')
                            .append($("<option></option>")
                                    .attr({"value": i, "redeemAccId": res.bankProfile[i].paymentTypeId})
                                    .text(res.bankProfile[i].accNum));
//                $(".withdrawal_add_new_account").css('display','none');
//                }else{
//                $(".withdrawal_add_new_account").css('display','block');   
//                }
          }
           }

        });
    }
}

function processWithdrawalRequest(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
     $("#" + id).trigger("reset");
    if (res.errorCode != 0) {
          $('[id^=error_amount_withdrawal]').html(res.errorMsg).show();
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
        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit + '&isCashierUrl=' + '1' + '&txnType=' + 'WITHDRAWAL';
        startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getWithdrawalResponse, null);
        //$("#withdrawal-amount-form").css('display','none');
        //$(".withdrawal-title").text('');
        //$("#successfull-withdrawal-form").css('display','block');
        //$("#withTrnx_id").text(res.txnId);
    }
}

function getWithdrawalResponse(result)
{
    var tmp_fromPrev = fromPrev;
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    console.log(res);
    if (res.errorCode == 0) {
        $("#withdrawal-table").show();
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
            $('#cashier-withdrawal-table tbody > tr').remove();
            $('#cashier-withdrawal-div').css('display', 'none');
            //error_message("No Withdrawal Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('WITHDRAWL_NO_DETAIL'));
            $("#error-div").css("display", "");
            return false;
        }
        // clearSystemMessage();
        $('#cashier-withdrawal-div').css('display', 'block');
        $('#cashier-withdrawal-table tbody > tr').remove();

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
            var newRow = '<tr style="text-align: center">' +
                    '<td>' + sNo + '</td>' +
                    '<td>' + txndate + '</td>' +
                    '<td>' + txnid + '</td>' +
                    '<td>' + formatCurrency(amount, decSymbol) + '</td>' +
//                    '<td>' + otp + '</td>' +
                    '<td>' + status + '</td>' +
                    //'<td style="text-align: left">'+cancelIcon+'</td>' +
                    '</tr>';

            footable.appendRow(newRow);
        }

        $('#cashier-withdrawal-table').trigger('footable_redraw');
        if (offset == 1) {
            $('#cashier-withdrawal-table').trigger('footable_initialize');
            $('#footer-pagination-div-withdrawal').children().children().first().addClass(' disabled');
            $('#cashier-withdrawal-table tfoot').addClass('hide-if-no-paging');
        } else {
            $('#cashier-withdrawal-table tfoot').removeClass('hide-if-no-paging');
            if (totRows < 10)
                $('#footer-pagination-div-withdrawal').children().children().last().addClass(' disabled');
            //resetPageNo();
        }
        if (tmp_fromPrev) {
            $("#footer-pagination-div-withdrawal>ul>li").last().prev().children().trigger('click');
        }


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

$(window).load(function(){
    if( location.hash == "#withdrawal" ){
        $("[href='#withdrawal']").trigger("click");
    }
    if (location.hash == "#deposit") {
        $("[href='#deposit']").trigger("click");
    }
});

function getResponseForWithdrawalResendOTP(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
   if(res.errorCode != 0) {
     $("#cashier-withdrawal-mobile").parent().parent().css({'display':'block','color':'red'});
     //$("#modal-withmobile-no").parent().text(''); 
     $("#cashier-withdrawal-mobile").parent().parent().text(res.respMsg);   
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', res.respMsg, "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
   }else{
     
     $("#cashier-withdrawal-mobile").parent().parent().css('display','block');
     setTimeout(function(){$("#cashier-withdrawal-mobile").parent().parent().css('display','none');}, 3000);
     //$("#resend-link-withdrawal").css('display','block');
   }       
 }

 function getResponseForDepositResendOTP(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    if (res.errorCode != 0) {
                $("#cashier-deposit-mobile").parent().parent().css({'display':'block','color':'red'});
        if(res.errorCode == 606)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("BETTING_INVALID_ALIAS_NAME"));
     else if(res.errorCode == 102)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"));
     else if(res.errorCode == 203)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("PLAYER_NOT_LOGGED_IN"));
     else if(res.errorCode == 101)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"));
    else if(res.errorCode == 1012)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"));
    else if(res.errorCode == 307)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"));
    else if(res.errorCode == 317)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"));
    else if(res.errorCode == 1003)
    $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"));
    else if(res.errorCode == 1005)
    $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"));
    else if(res.errorCode == 434)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"));
    else if(res.errorCode == 308)
     $("#cashier-deposit-mobile").parent().parent().text(Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"));
    else
    $("#cashier-deposit-mobile").parent().parent().text(res.respMsg);
    //$("#resend-link-deposit").css('color', 'red');
    } else {
        
       $("#cashier-deposit-mobile").parent().parent().css('display', 'block');
       setTimeout(function(){$("#cashier-deposit-mobile").parent().parent().css('display','none');}, 3000); 
   }
}


