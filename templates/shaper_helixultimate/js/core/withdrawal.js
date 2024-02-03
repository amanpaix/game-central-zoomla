var $ = jQuery.noConflict();
var min_limit = 500;
var max_limit = 1000000;
var max_amount;
var max_limit_deposit = 1000000;
var max_amount_deposit;
var max_limit_momo;
var max_amount_momo;
var max_amount_ola;
var max_limit_ola;
$(document).ready(function(){
    $.validator.addMethod("onlynum", function(value, element) {
        return this.optional(element) || /^[0-9]*$/.test(value);
    });

    $.validator.addMethod("notGreater", function(value, element) {
        if(value > withdrawabalBalance)
            return false;
        return true;
    });
    $.validator.addMethod("notSmaller", function(value, element) {
        if(value < min_limit)
            return false;
        return true;
    });
    if(aarayAmount.MOBILE_MONEY == undefined){
     aarayAmount['MOBILE_MONEY'] = {'min': 0, 'max':0};
    }
    if(aarayAmount.CASH_PAYMENT == undefined){
     aarayAmount['CASH_PAYMENT'] = {'min': 0, 'max':0};
    }
    DisableWithdrawalBtn();
    $.validator.addMethod('selectoption', function (value) {
        return (value != 'select');
    });
      $(".MOBILE_MONEY_depositBtn").live('click',function() {
        var subTypeId = $(this).attr("subtype-id");
        var paymentTypeCode = $(this).attr("paytype-code");
        $("#subTypeId").val(subTypeId);
        $("#paymentTypeCode").val(paymentTypeCode);
        var params = '&paymentTypeCode=' + paymentTypeCode;
        startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemAccount, '');
        var option = $('#accNum > option').length;
        if (option > 0)
            $("#isNewRedeemAcc").val('NO');
        else {
            $("#isNewRedeemAcc").val('YES');
        }
        showDefaultContent();
    });
    
    $(".btn-MOBILE_MONEY").live('click',function() {
        var withsubTypeId = $(this).attr("subtype-id");
        var withpaymentTypeCode = $(this).attr("paytype-code");
        $("#withSubTypeId").val(withsubTypeId);
        $("#withPaymentTypeCode").val(withpaymentTypeCode);
        var params = '&paymentTypeCode=' + withpaymentTypeCode;
        startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getWithdrawalRedeemAccount, '');
        var withrawalOption = $('#withdrawal_account > option').length;
        if (withrawalOption > 0)
            $("#isNewWithRedeemAcc").val('NO');
        else {
            $("#isNewWithRedeemAcc").val('YES');
        }
        showDefaultForWithdrawal();
    });
    
    $(".btn-CASH_PAYMENT").live('click',function() {
    var OlapayTypeCode = $(this).attr("paytype-code");
    var OlapayTypeId = $(this).attr("paytype-id");
    $("#OlapayTypeCode").val(OlapayTypeCode);
    $("#OlapayType").val(OlapayTypeId);
    });
    
    $("#selectAccount").on("change", function () {
        if(this.options[this.selectedIndex].getAttribute('acc_status') == "NEW") {
            showToolTipErrorManual('selectAccount', "The account you have selected is not approved.", "bottom", $("#selectAccount"), undefined);
            $( "#selectAccount" ).focus();
            return false;
        }
        else {
            removeToolTipErrorManual("", $("selectAccount"));
        }
    });
    if ((parseFloat(aarayAmount.MOBILE_MONEY.max) < withdrawabalBalance && parseFloat(aarayAmount.MOBILE_MONEY.max) < max_limit) || (withdrawabalBalance < min_limit)) {
        max_limit_momo = formatCurrency(aarayAmount.MOBILE_MONEY.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(aarayAmount.MOBILE_MONEY.max);
    } else if (withdrawabalBalance < max_limit) {
        max_limit_momo = formatCurrency(withdrawabalBalance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(withdrawabalBalance);
    } else {
        max_limit_momo = formatCurrency(max_limit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(max_limit);;
    }
    
    if ((parseFloat(aarayAmount.CASH_PAYMENT.max) < withdrawabalBalance && parseFloat(aarayAmount.CASH_PAYMENT.max) < max_limit) || (withdrawabalBalance < min_limit)) {
        max_limit_ola = formatCurrency(aarayAmount.CASH_PAYMENT.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(aarayAmount.CASH_PAYMENT.max);
    } else if (withdrawabalBalance < max_limit) {
        max_limit_ola = formatCurrency(withdrawabalBalance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(withdrawabalBalance);
    } else {
        max_limit_ola = formatCurrency(max_limit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(max_limit);
    }
//    if(withdrawabalBalance < max_limit) {
//        max_limit = withdrawabalBalance;
//        max_amount = parseFloat(max_limit.toString().replace(/\,/g,""));
//        //max_amount = max_amount.replace(/\.00/g, ' ')
//        // console.log(max_amount);
//    }
//    else{
//        max_amount = max_limit;
//    }
    
    if(maxValueDeposit < max_limit_deposit) {
        max_mtn_deposit = formatCurrency(maxValueDeposit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_deposit = parseFloat(maxValueDeposit);
    }
    else{
        max_mtn_deposit = formatCurrency(max_limit_deposit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_deposit = parseFloat(max_limit_deposit);
    }


    $("#submit-without-add").on('click', function () {
        $("#bank-withdrawal-form").submit();
    });

    $("#submit-with-add").on('click', function () {
        $("#add-new-form").submit();
    });

    $("#cash-withdrawal-form").validate({

        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
        },

        rules: {
            cashAmount: {
                required: true,
                number: true,
                notGreater: true,
                range: [min_limit, max_limit]
            }
        },

        messages: {
            cashAmount: {
                required: 'Please enter amount to be withdrawn.',
                number: 'Withdrawable amount should be numeric.',
                notGreater: 'Entered amount should be less than your withdrawable balance.',
                range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN')+min_limit+Joomla.JText._('BETTING_TO')+max_limit+"."
            }
        },

        submitHandler: function() {
            document.getElementById('cash-withdrawal-form').submit();
            $("#cash-withdrawal-submit-btn").attr('disabled', 'disabled');
        }
    });

    $("#cheque-withdrawal-form").validate({

        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
        },

        rules: {
            chequeAmount: {
                required: true,
                number: true,
                notGreater: true,
                range: [min_limit, max_limit]
            }
        },

        messages: {
            chequeAmount: {
                required: 'Please enter amount to be withdrawn.',
                number: 'Withdrawable amount should be numeric.',
                notGreater: 'Entered amount should be less than your withdrawable balance.',
                range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN')+min_limit+Joomla.JText._('BETTING_TO')+max_limit+"."
            }
        },

        submitHandler: function() {
            document.getElementById('cheque-withdrawal-form').submit();
            $("#cheque-withdrawal-submit-btn").attr('disabled', 'disabled');
        }
    });

    $("#bank-withdrawal-form").validate({

        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
        },

        rules: {
            bankAmount: {
                required: true,
                number: true,
                notGreater: true,
                range: [min_limit, max_limit]
            }
        },

        messages: {
            bankAmount: {
                required: 'Please enter amount to be withdrawn.',
                number: 'Withdrawable amount should be numeric.',
                notGreater: 'Entered amount should be less than your withdrawable balance.',
                range: "Amount should be between "+min_limit+" to "+max_limit+"."
            }
        },

        submitHandler: function() {
            var select_tag = document.getElementById('selectAccount');

            if(select_tag == null || $("#add_account").css("display") == "block")
            {
                $("#submit-with-add").trigger('click');
                return false;
            }

            if(select_tag.options[select_tag.selectedIndex].getAttribute('acc_status') == "NEW") {
                showToolTipErrorManual('selectAccount', "The account you have selected is not approved.", "bottom", $("#selectAccount"), undefined);
                $( "#selectAccount" ).focus();
                return false;
            }
            removeToolTipErrorManual("", $("selectAccount"));
            submitWithoutAdd();
            return false;
        }
    });



    $("#add-new-form").validate({

        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
        },

        rules: {
            accNo: {
                required: true,
                onlynum: true
            },
            retypeAccNo: {
                required: true,
                equalTo: "#accNo"
            },
            bankName: {
                required: true,
                selectoption: true
            },
            branchCity: {
                required: true,
                pattern: /^[a-zA-Z-]*$/
            },
            ifsc: {
                required: true
            }
        },

        messages: {
            accNo: {
                required: "Please enter account number.",
                onlynum: "A/c no. should be numeric."
            },
            retypeAccNo: {
                required: "Please confirm your account number.",
                equalTo: "A/c no. and confirm A/c no. should be same."
            },
            bankName: {
                required: "Please select Bank name.",
                selectoption: "Please select Bank name."
            },
            branchCity: {
                required: "Please enter branch city.",
                pattern: "Invalid branch city"
            },
            ifsc: {
                required: "please enter ifsc code."
            }
        },

        submitHandler: function() {
            if($("#bank-withdrawal-form #bankAmount").val() == "") {
                showToolTipErrorManual("bank-withdrawal-form #bankAmount", "Please enter amount to be withdrawn.", "bottom", $("#bankAmount"), undefined);
                $( "#bank-withdrawal-form #bankAmount" ).focus();
                return false;
            }
            if(isNaN($("#bank-withdrawal-form #bankAmount").val())) {
                showToolTipErrorManual("bank-withdrawal-form #bankAmount", "Withdrawable amount should be numeric.", "bottom", $("#bankAmount"), undefined);
                $( "#bank-withdrawal-form #bankAmount" ).focus();
                return false;
            }
            if($("#bank-withdrawal-form #bankAmount").val() > withdrawabalBalance) {
                showToolTipErrorManual("bank-withdrawal-form #bankAmount", "Entered amount should be less than your withdrawable balance.", "bottom", $("#bankAmount"), undefined);
                $( "#bank-withdrawal-form #bankAmount" ).focus();
                return false;
            }
            if($("#bank-withdrawal-form #bankAmount").val() < min_limit || $("#bank-withdrawal-form #bankAmount").val() > max_limit) {
                showToolTipErrorManual("bank-withdrawal-form #bankAmount", "Amount should be between "+min_limit+" to "+max_limit+".", "bottom", $("#bankAmount"), undefined);
                $( "#bank-withdrawal-form #bankAmount" ).focus();
                return false;
            }

            removeToolTipErrorManual("", $("#bank-withdrawal-form #bankAmount"));

            //var select_tag = document.getElementById('selectAccount');
            //if(select_tag.options[select_tag.selectedIndex].getAttribute('acc_status') == "NEW") {
            //    showToolTipError("selectAccount", "The account you have selected is not approved.", "top");
            //    $( "#selectAccount" ).focus();
            //    return false;
            //}
            //removeToolTipError("selectAccount");

            $('#add-new-form').append($('<input>', {
                type: "hidden",
                name: "isNewRedeemAcc",
                value: "Y"
            }));

            $('#add-new-form').append($('<input>', {
                type: "hidden",
                name: "bankAmount",
                value: $("#bank-withdrawal-form #bankAmount").val()
            }));

            var selected_bank = document.getElementById('bankName');
            $('#add-new-form').append($('<input>', {
                type: "hidden",
                name: "subTypeName",
                value: selected_bank.options[selected_bank.selectedIndex].innerHTML
            }));

            document.getElementById("add-new-form").submit();
            $("#submit-with-add").attr('disabled', 'disabled');
        }
    });

    $('#add-new-btn').click(function() {
        $("#bank-withdrawal-form #selectAccount").val("select");
        $("#selectAccount-div").css("display", "none");
        $('#add_account').css("display", 'block');
        $('#add-new-div').css("display", 'none');
        removeToolTipErrorManual("all");
        removeRules();
    });

    $('#cancel-add').click(function() {
        $("#bank-withdrawal-form #selectAccount").val("select");
        $("#selectAccount-div").css("display", "block");
        $('#add_account').css("display", 'none');
        $('#add-new-div').css("display", 'block');
        addRules();
    });

    $('#withdrawal-table tbody').on('click', '.icon-remove-1' , function(event) {

        clearSystemMessage();
        footableToCancel = $('#withdrawal-table').data('footable');
        rowToCancel = $(this).parents('tr:first');
//        transIdToCancel = rowToCancel.children().first().next().text();
        transIdToCancel = $(this).attr("trans-id");
        $('#Cancel_Withdrawal').modal('show');
    });
    
});

function addRules()
{
    $( "#initiate_withdrawal-form #amount" ).rules( "add", {
        required: true,
        notSmaller: true,
        range: [parseFloat(aarayAmount.CASH_PAYMENT.min), max_amount_ola],
        messages: {
            required: Joomla.JText._('BETTING_PLEASE_ENTER_AMOUNT_TO_BE_WITHDRAWN'),
            notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') +formatCurrency(aarayAmount.CASH_PAYMENT.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_REDEEM_AMOUNT'),
            range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +formatCurrency(aarayAmount.CASH_PAYMENT.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_TO') +max_limit_ola + "."
        }
    });
}

function removeRules()
{
    $( "#initiate_withdrawal-form #amount" ).rules( "remove" );
}

function removeMomoWithdrawalRules()
{
    $( "#withdrawal-amount-form #withdrawal" ).rules( "remove" );
}

function submitWithoutAdd()
{
    $('#bank-withdrawal-form').append($('<input>', {
        type: "hidden",
        name: "isNewRedeemAcc",
        value: "N"
    }));

    document.getElementById("bank-withdrawal-form").submit();
    $("#submit-without-add").attr('disabled', 'disabled');
}

function addMomoWithdrawalRules()
{
    $( "#withdrawal-amount-form #withdrawal" ).rules( "add", {
        required: true,
        notSmaller: true,
        range: [parseFloat(aarayAmount.MOBILE_MONEY.min), max_amount_momo],
        messages: {
            required: Joomla.JText._('PLEASE_ENTER_AMOUNT_TO_WITHDRAW'),
            notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + formatCurrency(aarayAmount.MOBILE_MONEY.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_REDEEM_AMOUNT'),
            range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN')+formatCurrency(aarayAmount.MOBILE_MONEY.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol) + Joomla.JText._('BETTING_TO') +max_limit_momo + "."
        }
    });
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        $(".withdrawal").each(function () {
            if($(this).parent().css('display') == "block") {

                if($(this).attr("paytype") == CASH_PAYMENT) {
                    $("#cash-withdrawal-submit-btn").trigger('click');
                }

                else if($(this).attr("paytype") == CHEQUE_TRANS) {
                    $("#cheque-withdrawal-submit-btn").trigger('click');
                }
                else if($(this).attr("paytype") == BANK_TRANS) {
                    if($("#add_account").css("display")== "none") {
                        //$("#bank-withdrawal-form").submit();
                        $("#submit-without-add").trigger('click');
                    }
                    else {
                        $("#submit-with-add").trigger('click');
                    }
                }
            }
        });
    }
});

$(document).ready(function () {
    $(".resp-tabs-list>li").click(function () {
        removeToolTipErrorManual("all");
    });
     var params = '&offset=' + offset + '&limit=' + limit;

    if ($("#system-message-container").text().trim().length > 0)
        startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getDepositResponse, "nottoshow");
    else
        startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getDepositResponse, "#" + deposit_amount_id);
    });

var error_callback_cp = {};
var error_callback_deposit_otp = {};
var error_callback_deposit_amount = {};
var error_callback_account_details = {};
var error_callback_withdrawal_account_details = {};
var error_callback_withdrawal_amount = {};
var error_callback_withdrawal_otp = {};
var withdraw_form_id;
var offset = 0;
var limit = 100;
var pageWindow = 5;
var startPageNo = 1;
var endPageNo = 5;
var prevFromDate = '';
var prevToDate = '';
var limitReached = false;
var lastPageNo = 0;
var transIdToCancel = '';
var rowToCancel = '';
var footableToCancel = '';
var fromPrev = false;
var deposit_amount_id;
var deposit_account_details;
var deposit_otp_form_id;
var withdrawal_amount_id;
var withdrawal_account_details;
var withdrawal_otp_form_id;
var withdrawal_balance;

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

    $('.iniWithBTN').on('click', function () {
        $("#initiate_withdrawal").modal('show');

    });

    //var payType = JSON.parse('<?php echo $payTypeMap ?>');
    //var payTypeCode = payType.payTypeMap[1].payTypeCode;
    $('#payTypeCode').val('WITHDRAWAL');
//        $('#payTypeId').val(payType.payTypeMap[1].payTypeId);
//        $('#payTypeName').val(payType.payTypeMap[1].payTypeDispCode);
//        $('#subTypeName').val(payType.payTypeMap[1].payTypeCode);
});





$(document).ready(function () {
    //var  balance = "<?php //echo $cashBalance?>";
//      $.validator.addMethod("notGreater", function (value, element) {
//        if( parseFloat(value) > parseFloat(50))
//            return false;
//        return true;
//      });
//       $.validator.addMethod("decimalToTwo", function (value, element) {
//        return this.optional(element) || /^[1-9]{1}[0-9]{0,6}([.]{0,1}[0-9]{1,2}){0,1}$/.test(value);
//       });
    $($("form[id^='initiate_withdrawal-form']")).each(function () {
        withdraw_form_id = $(this).attr('id');
        error_callback_cp["initiate_withdrawal-form"] = $("#initiate_withdrawal-form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                amount: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    notSmaller: true,
                    //decimalToTwo : true
                    range: [parseFloat(aarayAmount.CASH_PAYMENT.min), max_amount_ola]
                }
            },

            messages: {
                amount: {
                    required: Joomla.JText._('BETTING_PLEASE_ENTER_AMOUNT_TO_BE_WITHDRAWN'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED')+formatCurrency(aarayAmount.CASH_PAYMENT.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol) + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +formatCurrency(aarayAmount.CASH_PAYMENT.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_TO') +max_limit_ola+ "."
                }
            },

            submitHandler: function () {
                //document.getElementById('cash-withdrawal-form').submit();
                $("#cash-withdrawal-submit-btn").attr('disabled', 'disabled');
                if ($("#initiate_withdrawal-form").attr('submit-type') != 'ajax') {
                    document.getElementById('amount').submit();
                } else {
                    var params = 'payTypeCode=' + $("#" + withdraw_form_id + " #OlapayTypeCode").val() + '&cashAmount=' + $("#" + withdraw_form_id + " #amount").val() + '&paymentTypeId=' + $("#" + withdraw_form_id + " #OlapayType").val();
                    startAjax("/component/Betting/?task=withdrawal.withdrawalRequest", params, processWithdrawalRequest, "#" + withdraw_form_id);
                }

            }
        });
    });
    
    $($("form[id^='deposit_amount_form']")).each(function () {
        deposit_amount_id = $(this).attr('id');
        error_callback_deposit_amount["deposit_amount_form"] = $("#deposit_amount_form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                deposit: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    notSmaller: true,
                    //decimalToTwo : true
                    range: [minValueDeposit, max_amount_deposit]
                },
                 accNum: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    //rangelength: [9, 9]
                }
                
            },

            messages: {
                deposit: {
                    required: Joomla.JText._('PLEASE_ENTER_AMOUNT_TO_DEPOSIT'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED')+formatCurrency(minValueDeposit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_DEPOSIT_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +formatCurrency(minValueDeposit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_TO') +max_mtn_deposit+ "."
                },
                accNum: {
                    required: Joomla.JText._('PLEASE_SELECT_ACCOUNT_NUMBER'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    //rangelength: Joomla.JText._("PLEASE_ENTER_VALID_ACCOUNT_NUMBER")
                }
                
            },

            submitHandler: function () {
                //$("#account_deposit").click(function() {
                if ($("#"+deposit_amount_id).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_amount_id).submit();
                } else {
                    var subtype = $('#accNum option:selected').attr('subtypeId');
                    var paymentType = $('#accNum option:selected').attr('paymentTypeId');
                    var redeemAccId = $('#accNum option:selected').attr('redeemAccId');
                    var paymentTypeCode = $('#accNum option:selected').attr('paymentTypeCode');
                    var params = 'paymentTypeId=' + paymentType + '&subTypeId=' + subtype + '&amount=' + $("#" + deposit_amount_id + " #deposit").val() + '&paymentTypeCode=' + paymentTypeCode  + '&redeemAccId=' + redeemAccId ; 
                    startAjax("/component/Betting/?task=cashier.depositRequest", params, processDepositRequest, "#" + deposit_amount_id);
                }

        }
        });
    });
    
    $($("form[id^='withdrawal-amount-form']")).each(function () {
        withdrawal_amount_id = $(this).attr('id');
        error_callback_withdrawal_amount["withdrawal-amount-form"] = $("#withdrawal-amount-form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                withdrawal: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    notSmaller: true,
                    //decimalToTwo : true
                    range: [parseFloat(aarayAmount.MOBILE_MONEY.min), max_amount_momo]
                },
                 withdrawal_account: {
                    required: true,
                }
                
            },

            messages: {
                withdrawal: {
                    required: Joomla.JText._('PLEASE_ENTER_AMOUNT_TO_WITHDRAW'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED')+formatCurrency(aarayAmount.MOBILE_MONEY.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol)+ Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    range:Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN')+formatCurrency(aarayAmount.MOBILE_MONEY.min.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),decSymbol) + Joomla.JText._('BETTING_TO') +max_limit_momo + "."
                },
                withdrawal_account: {
                    required: Joomla.JText._('PLEASE_SELECT_ACCOUNT_NUMBER'),
                }
                
            },

            submitHandler: function () {
                //$("#account_deposit").click(function() {
                if ($("#"+withdrawal_amount_id).attr('submit-type') != 'ajax') {
                    document.getElementById(withdrawal_amount_id).submit();
                } else {
                    var withSubtype = $('#withdrawal_account option:selected').attr('withSubtypeId');
                    var withPaymentType = $('#withdrawal_account option:selected').attr('withPaymentTypeId');
                    var withRedeemAccId = $('#withdrawal_account option:selected').attr('withRedeemAccId');
                    var withPaymentTypeCode = $('#withdrawal_account option:selected').attr('withPaymentTypeCode');
                    var params = 'paymentTypeId=' + withPaymentType + '&subTypeId=' + withSubtype + '&amount=' + $("#" + withdrawal_amount_id + " #withdrawal").val() + '&redeemAccId=' + withRedeemAccId + '&paymentTypeCode=' + withPaymentTypeCode;
                    startAjax("/component/Betting/?task=withdrawal.requestWithdrawalDetails", params, processWithdrawalDetails, "#" + withdrawal_amount_id);
                }
        }
        });
    });
    
    $($("form[id^='deposit_account_details']")).each(function () {
        deposit_account_details = $(this).attr('id');
        error_callback_account_details["deposit_account_details"] = $("#deposit_account_details").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                c_name: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    //range: [min_limit, max_amount]
                },
                account_no: {
                    required: true,
                    pattern: "^[26]{1}[0-9]{8}$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    rangelength: [9, 9]
                }
            },

            messages: {
                c_name: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_NAME'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    //range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +decSymbol +min_limit+ Joomla.JText._('BETTING_TO') +decSymbol+max_limit+"."
                },
                account_no: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER'),
                    pattern: Joomla.JText._('PLEASE_ENTER_VALID_ACCOUNT_NUMBER'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    rangelength: Joomla.JText._('PLEASE_ENTER_VALID_ACCOUNT_NUMBER')
                }
            },

            submitHandler: function () {
                if ($("#"+deposit_account_details).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_account_details).submit();
                } else {
                    var params = 'accNum=' + $("#" + deposit_account_details + " #account_no").val() + '&paymentTypeCode=' + $("#" + deposit_account_details + " #paymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_account_details + " #c_name").val() + '&subTypeId=' + $("#" + deposit_account_details + " #subTypeId").val() + '&isNewRedeemAcc=' + $("#" + deposit_account_details + " #isNewRedeemAcc").val(); 
                    startAjax("/component/Betting/?task=cashier.AddNewDepositAccount", params, getOtpForAddingAccount, "#" + deposit_account_details);
                }

            }
        });
    });
    
    $($("form[id^='withdrawal-account-details-form']")).each(function () {
        withdrawal_account_details = $(this).attr('id');
        error_callback_withdrawal_account_details[withdrawal_account_details] = $("#withdrawal-account-details-form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                acc_holder_name: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    //range: [10, max_am]
                },
                withdrawal_account_no: {
                    required: true,
                    pattern: "^[26]{1}[0-9]{8}$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    rangelength: [9, 9]
                }
            },

            messages: {
                acc_holder_name: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_NAME'),
                    pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    //range: Joomla.JText._('BETTING_PLEASE_SELECT_BETWEEN') +decSymbol +min_limit+ Joomla.JText._('BETTING_TO') +decSymbol+max_limit+"."
                },
                withdrawal_account_no: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER'),
                    pattern: Joomla.JText._('PLEASE_ENTER_VALID_ACCOUNT_NUMBER'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    rangelength: Joomla.JText._("PLEASE_ENTER_VALID_ACCOUNT_NUMBER")
                }
            },

            submitHandler: function () {
                if ($("#"+withdrawal_account_details).attr('submit-type') != 'ajax') {
                    document.getElementById(withdrawal_account_details).submit();
                } else {
                    var params = 'accNum=' + $("#" + withdrawal_account_details + " #withdrawal_account_no").val() + '&paymentTypeCode=' + $("#" + withdrawal_account_details + " #withPaymentTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_account_details + " #acc_holder_name").val() + '&subTypeId=' + $("#" + withdrawal_account_details + " #withSubTypeId").val() + '&isNewRedeemAcc=' + $("#" + withdrawal_account_details + " #isNewWithRedeemAcc").val(); 
                    startAjax("/component/Betting/?task=cashier.AddNewDepositAccount", params, getOtpForWithdrawalAccount, "#" + withdrawal_account_details);
                }

            }
        });
        });

//    $(document).on("keypress","#amount", function(e){
//
//        var key = e.which ? e.which : e.keyCode;
//        var decimal = this.value;
//        decimal = decimal.split('.');
//        var dotcontains = this.value.indexOf(".") != -1;
//        if (dotcontains)
//            if (key == 46) return false;
//        if ((decimal[1] && decimal[1].length > 1))
//        {
//            return false;
//        }
//        if ((this.value.length == 0) && (key == 48 || key == 46)){
//            return false;
//        }
//    });
//    $(document).on('keyup','#amount',function(e){
//        var value = $(this).val();
//        value = value.replace(/[^0-9.]/g, '');
//        $(this).val(value);
//
//    });

    $('#doWithdrawal').click(function(event) {

        if(transIdToCancel == '' || rowToCancel == '') {
            error_message('Something Wrong Happened. Please Try Again.', null);
            return false;
        }


//        var cancel_amount = rowToCancel.children().eq(3).html().replace('<span class="rupees-symbol">`</span>', '');
        //var cancel_amount = $("td:contains("+transIdToCancel+")").first().parent().children().eq(3).html().replace('<span class="rupees-symbol">`</span>', '');
        var cancel_amount = $("td:contains("+transIdToCancel+")").first().parent().children().eq(3).html().replace('Ã Â¸Â¿',"").replace(" ","");
        cancel_amount = cancel_amount.replace('CFA', '');
        cancel_amount = cancel_amount.replace(' ', '');
        cancel_amount = parseInt(cancel_amount);

        var params = 'transactionId='+transIdToCancel+'&cancelAmount='+cancel_amount;

        startAjax(cancelWithdrawalURL, params, processWithdrawalCancel, 'null')
    });

    $("[href='#withdrawal']").on('click', function () {

       DisableWithdrawalBtn();
        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit;

        if($("#system-message-container").text().trim().length > 0)
            startAjax("/component/Betting/?task=withdrawal.getWithdrawalDetails", params, getWalletResponse, "nottoshow" );
        else
            startAjax("/component/Betting/?task=withdrawal.getWithdrawalDetails", params, getWalletResponse, "#" + withdraw_form_id);


    });

   $("[href='#deposit']").on('click', function () {


       var params = '&offset=' + offset + '&limit=' + limit;

       if($("#system-message-container").text().trim().length > 0)
           startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getDepositResponse, "nottoshow" );
       else
           startAjax("/component/Betting/?task=cashier.getDepositDetails", params, getDepositResponse, "#" + deposit_amount_id);

   });
    
    $("#add_account_btn").click(function(){
     $("#deposit_amount_form").css("display",'none');
     $(".deposit-title").text(Joomla.JText._("ADD_REDEEM_ACCOUNT"));
     $("#deposit_account_details").css("display",'block');   
     $("#otp_code").val('');
     $("#c_name").val('');
     $("#account_no").val('');
    });
    
    $("#withdrawal_account_btn").click(function(){
     $("#withdrawal-amount-form").css("display",'none');
     $(".withdrawal-title").text(Joomla.JText._("ADD_REDEEM_ACCOUNT"));
     $("#withdrawal-account-details-form").css("display",'block');
     $("#acc_holder_name").val('');
     $("#withdrawal_account_no").val('');
     $("#with_otp_code").val('');
    });
    
    
    $("#sp-custom-popup").on('click', "#withdrawal_resendOtp", function () {
        $("#with_otp_code").val('');
        var params = 'accNum=' + $("#" + withdrawal_account_details + " #withdrawal_account_no").val() + '&paymentTypeCode=' + $("#" + withdrawal_account_details + " #withPaymentTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_account_details + " #acc_holder_name").val() + '&subTypeId=' + $("#" + withdrawal_account_details + " #withSubTypeId").val() + '&isNewRedeemAcc=' + $("#" + withdrawal_account_details + " #isNewWithRedeemAcc").val();
        startAjax("/component/Betting/?task=cashier.AddNewDepositAccount", params, getResponseForWithdrawalResendOTP, "#" + withdrawal_account_details);
    });
    $("#sp-custom-popup").on('click', "#deposit_resendOtp", function () {
        $("#otp_code").val('');
        var params = 'accNum=' + $("#account_no").val() + '&paymentTypeCode=' + $("#paymentTypeCode").val() + '&accHolderName=' + $("#c_name").val() + '&subTypeId=' + $("#subTypeId").val() + '&isNewRedeemAcc=' + $("#isNewRedeemAcc").val();
        startAjax("/component/Betting/?task=cashier.AddNewDepositAccount", params, getResponseForDepositResendOTP, "#" + deposit_account_details);
    });
});

function managePlayerBalance(balance){
    removeRules();
    withdrawabalBalance = balance;
    max_limit_ola = withdrawabalBalance;
    max_amount_ola = parseFloat(max_limit_ola.toString().replace(/\,/g,""));
    addRules();
    if( parseFloat(max_amount_ola) > parseFloat(max_limit_ola) ){
        $("#insufficient-balance-div").hide();
        $("#sufficient-balance-div").show();
    }
}

function processWithdrawalCancel(result) {
    if(validateSession(result) == false)
        return false;
    var res = JSON.parse(result);

    // updatePlayerBalance();
    clearSystemMessage();
    if(res.errorCode != 0)
    {
        error_message(res.respMsg, null);
        return false;
    }

    updatePlayerBalance();
    if($(".cash-balance").length > 0) {
        updateBalance(res.cashBalance,decSymbol);
    }

    footableToCancel.removeRow(rowToCancel);


    // var i=1;
    // $($("td.footable-first-column")).each(function () {
    //     $(this).html('<span class="footable-toggle"></span>'+i);
    //     i++;
    // });
    // resetPageNo();
//        success_message(res.respMsg);
    $("td:contains("+ transIdToCancel + ")").first().parent().parent().remove();

    removeRules();
    removeMomoWithdrawalRules();
    // max_limit = parseFloat(res.cashBalance).toFixed(2);
    cashBalance = getFormattedAmount(parseFloat(res.cashBalance,2)).replace(",","");
    withdrawal_balance = getFormattedAmount(parseFloat(res.withdrawableBal,2)).replace(",","");
    if ((parseFloat(aarayAmount.CASH_PAYMENT.max) < withdrawal_balance && parseFloat(aarayAmount.CASH_PAYMENT.max) < parseFloat(max_limit_deposit.replace(/ /g,""))) || (withdrawal_balance < min_limit)) {
        max_limit_ola = formatCurrency(aarayAmount.CASH_PAYMENT.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(aarayAmount.CASH_PAYMENT.max);
        } else if (withdrawal_balance < parseFloat(max_limit_deposit.replace(/ /g,""))) {
        max_limit_ola = formatCurrency(withdrawal_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(withdrawal_balance);
        } else {
        max_limit_ola = formatCurrency(parseFloat(max_limit_deposit.replace(/ /g,"")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(max_limit_deposit);
        }
        DisableWithdrawalBtn();
    if ((parseFloat(aarayAmount.MOBILE_MONEY.max) < withdrawal_balance && parseFloat(aarayAmount.MOBILE_MONEY.max) < parseFloat(max_limit_deposit.replace(/ /g,""))) || (withdrawal_balance < min_limit)) {
        max_limit_momo = formatCurrency(aarayAmount.MOBILE_MONEY.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(aarayAmount.MOBILE_MONEY.max);
        } else if (withdrawal_balance < parseFloat(max_limit_deposit.replace(/ /g,""))) {
        max_limit_momo = formatCurrency(withdrawal_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(withdrawal_balance);
        } else {
        max_limit_momo = max_limit_deposit;
        max_amount_momo = parseFloat(max_limit_deposit);
        }
    //max_amount = res.withdrawableBal;
    if( parseFloat(max_limit) > parseFloat(aarayAmount.CASH_PAYMENT.min) ){
        $("#insufficient-balance-div").hide();
        $("#sufficient-balance-div").show();
    }
    addRules();
    addMomoWithdrawalRules();
    if( $("#withdrawal-table tbody tr").length <= 0 ){
        $("#withdrawal-table").hide();
    }

    $('#Withdrawal_success').modal('show');
}

function resetPageNo() {
    var pageNo = startPageNo;
    $($('#footer-pagination-div').children().children()).each(function() {
        if($(this).children().attr('data-page') == "prev"){
            $(this).addClass(' loadprev');
        }
        if($(this).children().attr('data-page') != "prev" && $(this).children().attr('data-page') != "next")
        {
            $(this).children().text(pageNo);
            if(limitReached == true)
                lastPageNo = pageNo;
            pageNo++;
        }
    });
}

function processWithdrawalRequest(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    clearSystemMessage();
    if (res.errorCode == 0) {
        removeRules();
        removeMomoWithdrawalRules();
        updatePlayerBalance();
        cashBalance = getFormattedAmount(parseFloat(res.cashBalnce,2)).replace(",","");
        withdrawal_balance = getFormattedAmount(parseFloat(res.withdrawableBal,2)).replace(",","");
        if ((parseFloat(aarayAmount.CASH_PAYMENT.max) < withdrawal_balance && parseFloat(aarayAmount.CASH_PAYMENT.max) < parseFloat(max_limit_deposit.replace(/ /g,""))) || (withdrawal_balance < min_limit)) {
        max_limit_ola = formatCurrency(aarayAmount.CASH_PAYMENT.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(max_limit_ola.toString().replace(/ /g,""));
        } else if (withdrawal_balance < parseFloat(max_limit_deposit.replace(/ /g,""))) {
        max_limit_ola = formatCurrency(withdrawal_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_ola = parseFloat(max_limit_ola.toString().replace(/ /g,""));
        } else {
        max_limit_ola = max_limit_deposit;
        max_amount_ola =  parseFloat(max_limit_ola.toString().replace(/ /g,""));;
        }
        if ((parseFloat(aarayAmount.MOBILE_MONEY.max) < withdrawal_balance && parseFloat(aarayAmount.MOBILE_MONEY.max) < parseFloat(max_limit_deposit.replace(/ /g,""))) || (withdrawal_balance < min_limit)) {
        max_limit_momo = formatCurrency(aarayAmount.MOBILE_MONEY.max.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(max_limit_momo.toString().replace(/ /g,""));
        } else if (withdrawal_balance < parseFloat(max_limit_deposit.replace(/ /g,""))) {
        max_limit_momo = formatCurrency(withdrawal_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        max_amount_momo = parseFloat(max_limit_momo.toString().replace(/ /g,""));
        } else {
        max_limit_momo = max_limit_deposit;
        max_amount_momo =  parseFloat(max_limit_momo.toString().replace(/ /g,""));
        }
        DisableWithdrawalBtn();
        if( parseFloat(max_limit) < parseFloat(aarayAmount.CASH_PAYMENT.min) ){
            $("#sufficient-balance-div").hide();
            $("#insufficient-balance-div").show();
        }
        success_message(Joomla.JText._('BETTING_WITHDRAWAL_INITIATE_MSG'));
        addRules();
        addMomoWithdrawalRules();
        var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit;
        startAjax("/component/Betting/?task=withdrawal.getWithdrawalDetails", params, getWalletResponse, "#" + withdraw_form_id);
    }
    else{
        showToolTipErrorManual(withdraw_form_id + ' #amount', res.respMsg, "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
    }


    return  false;
}

function getWalletResponse(result)
{
    var tmp_fromPrev = fromPrev;
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    if (res.errorCode == 0) {
        $("#initiate_withdrawal").modal('hide');
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

        if(res.withdrawalList.length <= 0)
        {
            $('#withdrawal-table tbody > tr').remove();
            $('#withdrawal-div').css('display', 'none');
            //error_message("No Withdrawal Details Found For Selected Date Range.", null);
            $("#error-div .error-div-txt").html(Joomla.JText._('WITHDRAWL_NO_DETAIL'));
            $("#error-div").css("display", "");
            return false;
        }
        // clearSystemMessage();
        $('#withdrawal-div').css('display', 'block');
        $('#withdrawal-table tbody > tr').remove();

        var totRows = 100;
        limitReached = false;
        lastPageNo = 0;
        if(res.withdrawalList.length <= limit) {
            totRows = res.withdrawalList.length;
            limitReached = true;
        }
        
         if(totRows < 11)
         $('#withdrawal-table tfoot .pagination').css("display", "none");
         else
         $('#withdrawal-table tfoot .pagination').css("display", "block")

        for(var i = 0; i < totRows; i++) {

            var footable = $('#withdrawal-table').data('footable');

            var txnid = '';
            var txndate = '';
            var amount = '';
            var refTxnNo = '';
            var refTxnDate = '';
            var status = '';
            var otp = '';
            var sNo = '';

            if(typeof res.withdrawalList[i].transactionId != 'undefined')
                txnid = res.withdrawalList[i].transactionId;
            // if(typeof res.withdrawalList[i].transactionDate != 'undefined') {
            //     txndate = res.withdrawalList[i].transactionDate;
            //     var tmp = txndate.lastIndexOf(".");
            //     txndate = txndate.substring(0, tmp);
            // }

            if(typeof res.withdrawalList[i].transactionDate != 'undefined'){
                txndate = res.withdrawalList[i].transactionDate;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
                txndate = txndate.split(' ');
                dateIndexOne = txndate[0];
                const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne);
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                txndate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + txndate[1]
            }

            if(typeof res.withdrawalList[i].amount != 'undefined')
                amount = getFormattedAmount(parseFloat(res.withdrawalList[i].amount,2));
            if(typeof res.withdrawalList[i].refTxnNo != 'undefined')
                refTxnNo = res.withdrawalList[i].refTxnNo;
            if(typeof res.withdrawalList[i].refTxnDate != 'undefined') {
                refTxnDate = res.withdrawalList[i].refTxnDate;
                var tmp = refTxnDate.lastIndexOf(".");
                refTxnDate = refTxnDate.substring(0, tmp);
            }
            if(typeof res.withdrawalList[i].status != 'undefined')
                status = res.withdrawalList[i].status;

            if( res.withdrawalList[i].verificationCode != 'undefined' && res.withdrawalList[i].txnType.toUpperCase() == "OFFLINE")
                otp = res.withdrawalList[i].verificationCode;
            else
                otp = '';
            var cancelIcon = '';

            if(res.withdrawalList[i].status.toUpperCase() != "PENDING") {
                if(res.withdrawalList[i].status.toUpperCase() == "DONE")
                    cancelIcon = '<a><img src="/templates/shaper_helix3/images/my_account/done-icon.png"></a>';
                else if(res.withdrawalList[i].status.toUpperCase() == "INITIATED" || res.withdrawalList[i].txnType.toUpperCase() == "OFFLINE")
                    cancelIcon = '<a href="javascript:;" style="color: red !important;"><span class="icon-remove-1" trans-id="'+txnid+'"><img src="/templates/shaper_helix3/images/my_account/cancel_icon.png">'+Joomla.JText._('BETTING_CANCEL_REQUEST')+'</span></a>';
            }
            sNo = i+1;
            var newRow = '<tr style="text-align: center">' +
                '<td>'+sNo+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>'+formatCurrency(amount,decSymbol)+'</td>' +
                '<td>'+ otp +'</td>' +
//                '<td>'+status+'</td>' +
                '<td style="text-align: left">'+cancelIcon+'</td>' +
                '</tr>';

            footable.appendRow(newRow);
        }

        $('#withdrawal-table').trigger('footable_redraw');
        if(offset == 0) {
            $('#withdrawal-table').trigger('footable_initialize');
            $('#footer-pagination-div-withdrawal').children().children().first().addClass(' disabled');
            $('#withdrawal-table tfoot').addClass('hide-if-no-paging');
        }
        else {
            $('#withdrawal-table tfoot').removeClass('hide-if-no-paging');
            if(totRows < 10)
                $('#footer-pagination-div-withdrawal').children().children().last().addClass(' disabled');
            resetPageNo();
        }
        if(tmp_fromPrev){
            $("#footer-pagination-div-withdrawal>ul>li").last().prev().children().trigger('click');
        }


    } else {
        if(res.errorCode == 434){
            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_PLAYER_AMOUNT_LIMIT_EXCEEDS'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }else if(res.errorCode == 209){
            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INSUFFICIENT_PLAYER_BALANCE'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }else if(res.errorCode == 205){
            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INVALID_AMOUNT'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }else if(res.errorCode == 103){
            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_INVALID_REQUEST'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }else if(res.errorCode == 102){
            showToolTipErrorManual(withdraw_form_id + ' #amount', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }
        else if( res.errorCode == 308 ){
            showToolTipErrorManual(withdraw_form_id + ' #amount', "No payment options available.", "bottom", $("#amount"), error_callback_cp["initiate_withdrawal-form"]);
        }
        $("[id='Withrawal_wallet']").css("display", "none");
        $(".iniWithBTN").show();
        //$('#msg').text(otp);
    }
}

function processDepositRequest(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
   if(res.errorCode != 0) {
     if(res.errorCode == 107)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("BETTING_DEVICE_TYPE_NOT_SUPPLIED"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 108)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("BETTING_USER_AGENT_TYPE_NOT_SUPPLIED"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 606)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 203)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 102)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 101)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 318)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("REDEEM_ACCOUNT_NOT_EXIST"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 423)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("REDEEM_ACCOUNT_DOES_NOT_EXIST_FOR_THIS_PLAYER"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 1012)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 308)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 1011)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("MOMO_MTN_IS_INACTIVE"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 121)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("INVALID_CURRENCY"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else if(res.errorCode == 309)
      showToolTipErrorManual(deposit_amount_id + ' #deposit', Joomla.JText._("YOUR_PAYMENT_HAS_BEEN_FAILED"), "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]);
     else
     showToolTipErrorManual(deposit_amount_id + ' #deposit', res.respMsg, "bottom", $("#deposit"), error_callback_deposit_amount[deposit_amount_id]); 
   }else{
     updatePlayerBalance();
     $("#deposit_amount_form").css('display','none');
     $(".deposit-title").text('');
     $("#successfull-deposit-form").css('display','block');
   }   
}

function getDepositResponse(result){
    var tmp_fromPrev = fromPrev;
if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    //console.log(res);
  if (res.errorCode == 0) {
        $("#deposit-table").show();
        //console.log(res)
        $("[id='Withrawal_wallet']").css("display", "block");

        if(res.response.length <= 0)
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
        if(res.response.length <= limit) {
            totRows = res.response.length;
            limitReached = true;
        }
        if(totRows < 11)
         $('#deposit-table tfoot .pagination').css("display", "none");
         else
         $('#deposit-table tfoot .pagination').css("display", "block");  
        for(var i = 0; i < totRows; i++) {

            var footable = $('#deposit-table').data('footable');

            var txnid = '';
            var sNo = '';
            var txndate = '';
            var amount = '';
            var refTxnNo = '';
//            var refTxnDate = '';
//            var status = '';
//            var otp = '';

            if(typeof res.response[i].transactionId != 'undefined')
                txnid = res.response[i].transactionId;
            // if(typeof res.withdrawalList[i].transactionDate != 'undefined') {
            //     txndate = res.withdrawalList[i].transactionDate;
            //     var tmp = txndate.lastIndexOf(".");
            //     txndate = txndate.substring(0, tmp);
            // }
             sNo = i+1;
            if(typeof res.response[i].date != 'undefined'){
                txndate = res.response[i].date;
                var tmp = txndate.lastIndexOf(".");
                txndate = txndate.substring(0, tmp);
                txndate = txndate.split(' ');
                dateIndexOne = txndate[0];
                const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                dateIndexOne = new Date(dateIndexOne);
                var date = dateIndexOne.getDate();
                date = date.toString().length == 1 ? "0" + '' + date : date;

                txndate = months[dateIndexOne.getMonth()] + " " + date + ", " + dateIndexOne.getFullYear() + " " + txndate[1]
            }

            if(typeof res.response[i].amount != 'undefined')
                amount = getFormattedAmount(parseFloat(res.response[i].amount,2));
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
                '<td>'+sNo+'</td>' +
                '<td>'+txndate+'</td>' +
                '<td>'+txnid+'</td>' +
                '<td>' +formatCurrency(amount,decSymbol) + '</td>' +
                '</tr>';

            footable.appendRow(newRow);
        }

        $('#deposit-table').trigger('footable_redraw');
        if(offset == 0) {
            $('#deposit-table').trigger('footable_initialize');
            $('#deposit-footer-pagination-div').children().children().first().addClass(' disabled');
            $('#deposit-table tfoot').addClass('hide-if-no-paging');
        }
        else {
            $('#deposit-table tfoot').removeClass('hide-if-no-paging');
            if(totRows < 10)
              $('#deposit-footer-pagination-div').children().children().last().addClass(' disabled');
            resetPageNo();
        }
        if(tmp_fromPrev){
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

function getOtpForAddingAccount(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res);
    if (res.errorCode != 0) {
        if (res.errorCode == 606)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(deposit_account_details + ' #account_no', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
        else
            showToolTipErrorManual(deposit_account_details + ' #account_no', res.respMsg, "bottom", $("#account_no"), error_callback_cp["deposit_account_details"]);
    }else{
    $('#deposit_account_details').css('display','none');
    $('#deposit_otp_verification_form').css('display','block');
    $(".deposit-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
    $(".deposit-footer").html(  
     "<div class='button_holder'><p><span class='heighlight_color'><strong><em>"+Joomla.JText._("NO_CODE_RECEIVED")+"</em></strong> "+Joomla.JText._("REQUEST_AGAIN")+"</span></p>"+
     "<button id='deposit_resendOtp' class='resendOtp heighlight_color'>"+Joomla.JText._("RESEND_OTP")+"</button></div>"+
     "<div class='form-group text-center' style='display:none;' id='resend-link-deposit'><p  class='send_msg'>"+Joomla.JText._("OTP_CODE_HAS_BEEN_SENT")+"</p>"+
     "</div>");
      $("#modal-mobile-no").html($("#account_no").val());
      setTimeout(function(){$("#modal-mobile-no").parent().css('display','none');}, 3000);
     
  }
    $(document).ready(function() {
     $($("form[id^='deposit_otp_verification_form']")).each(function () {
        deposit_otp_form_id = $(this).attr('id');
        error_callback_deposit_otp[deposit_otp_form_id] = $("#deposit_otp_verification_form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                otp_code: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    rangelength: [6, 6]
                }
            },

            messages: {
                otp_code: {
                    required: Joomla.JText._('BETTING_PLAESE_ENTER_OTP'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    rangelength: Joomla.JText._('PLEASE_ENTER_VALID_OTP')
                }
            },

            submitHandler: function () {
                if ($("#"+deposit_otp_form_id).attr('submit-type') != 'ajax') {
                    document.getElementById(deposit_otp_form_id).submit();
                } else {
                    var params = 'accNum=' + $("#" + deposit_account_details + " #account_no").val() + '&paymentTypeCode=' + $("#" + deposit_account_details + " #paymentTypeCode").val() + '&accHolderName=' + $("#" + deposit_account_details + " #c_name").val() + '&subTypeId=' + $("#" + deposit_account_details + " #subTypeId").val() + '&isNewRedeemAcc=' + $("#" + deposit_account_details + " #isNewRedeemAcc").val() + '&verifyOtp=' + $("#" + deposit_otp_form_id + " #otp_code").val(); 
                    startAjax("/component/Betting/?task=cashier.verfyOtpCode", params, getResponseForOTP, "#" + deposit_otp_form_id);
                }

            }
        });
    });   
    });
}

function getResponseForOTP(result){
   if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res);
   if(res.errorCode != 0) {
    if (res.errorCode == 606)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
         else if (res.errorCode == 530)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
        else 
            showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', res.respMsg, "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);  
    }else{  
     success_message(Joomla.JText._("ACCOUNT_ADDED_SUCCESSFULLY"));
     setTimeout(function(){jQuery("#system-message-container").html('')}, 3000);
     var params = '&paymentTypeCode=' + $("#" + deposit_account_details + " #paymentTypeCode").val(); 
     startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getAllRedeemAccount, "#" + deposit_otp_form_id);
    }
}
function getAllRedeemAccount(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res); 
    selectList =  $("#accNum");
    selectList.find("option:gt(0)").remove();

     if(res.errorCode != 0) {
      if(res.errorCode == 203)
         showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
       else if(res.errorCode == 102)
         showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
       else if(res.errorCode == 103)
         showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_INVALID_REQUEST"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
       else if(res.errorCode == 101)
         showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);
       else
        showToolTipErrorManual(deposit_otp_form_id + ' #otp_code', res.respMsg, "bottom", $("#otp_code"), error_callback_deposit_otp[deposit_otp_form_id]);  
      //jQuery("#system-message-container").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+res.respMsg+'</div></div>'); 
    }else{ 
     $(document).ready(function(){
     $("#deposit_otp_verification_form").css('display','none');
     $("#deposit_amount_form").css('display','block');
     $("#deposit_amount_form").css('display','block');
     $(".deposit-footer").html('');
      $(".deposit-title").text(Joomla.JText._("ADD_DEPOSIT_ACCOUNT"));
     for(var i in res.bankProfile){
      $('#accNum')
         .append($("<option></option>")
                    .attr({"value": res.bankProfile[i].mobileNo, "paymentTypeCode":res.bankProfile[i].paymentType ,"subtypeId":res.bankProfile[i].subtypeId , "paymentTypeId":res.bankProfile[i].paymentTypeId, "redeemAccId":res.bankProfile[i].redeemAccId})
                    .text(res.bankProfile[i].mobileNo));    
     }

     });
    }
}

function getOtpForWithdrawalAccount(result) {
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res);
  if (res.errorCode != 0) {
     if (res.errorCode == 606)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
        else
            showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', res.respMsg, "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);
    //showToolTipErrorManual(withdrawal_account_details + ' #withdrawal_account_no', res.respMsg, "bottom", $("#withdrawal_account_no"), error_callback_withdrawal_account_details[withdrawal_account_details]);  
  }else{
    $(".withdrawal-title").text(Joomla.JText._("VERIFY_ACCOUNT"));
    $('#withdrawal-account-details-form').css('display','none');
    $('#withdrawal-otp-verification-form').css('display','block');
    $(".withdrawal-footer").html(  
     "<div class='button_holder'><p><span class='heighlight_color'><strong><em>"+Joomla.JText._("NO_CODE_RECEIVED")+"</em></strong> "+Joomla.JText._("REQUEST_AGAIN")+"</span></p>"+
     "<button id='withdrawal_resendOtp' class='resendOtp heighlight_color'><strong><em>"+Joomla.JText._("RESEND_OTP")+"</button></div>"+
     "<div class='form-group text-center' style='display:none;' id='resend-link-withdrawal'><p  class='send_msg'>"+Joomla.JText._("OTP_CODE_HAS_BEEN_SENT")+"</p>"+
"</div>");
      $("#modal-withmobile-no").html($("#withdrawal_account_no").val());
      setTimeout(function(){$("#modal-withmobile-no").parent().css('display','none');}, 3000);
  }
    $(document).ready(function() {
     $($("form[id^='withdrawal-otp-verification-form']")).each(function () {
        withdrawal_otp_form_id = $(this).attr('id');
        error_callback_withdrawal_otp[withdrawal_otp_form_id] = $("#withdrawal-otp-verification-form").attr('error-callback');
        $(this).validate({
            showErrors: function (errorMap, errorList) {
                displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
            },

            rules: {
                with_otp_code: {
                    required: true,
                    //pattern: "^[0-9]{0,6}(\.[0-9]{1,2})?$",
                    //notSmaller: true,
                    //decimalToTwo : true
                    rangelength: [6, 6]
                }
            },

            messages: {
                with_otp_code: {
                    required: Joomla.JText._('BETTING_PLAESE_ENTER_OTP'),
                    //pattern: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE'),
                    //notSmaller: Joomla.JText._('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED') + decSymbol + Joomla.JText._('BETTING_REDEEM_AMOUNT'),
                    //decimalToTwo: Joomla.JText._('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE')
                    rangelength: Joomla.JText._('PLEASE_ENTER_VALID_OTP')
                }
            },

            submitHandler: function () {
                if ($("#"+withdrawal_otp_form_id).attr('submit-type') != 'ajax') {
                    document.getElementById(withdrawal_otp_form_id).submit();
                } else {
                    var params = 'accNum=' + $("#" + withdrawal_account_details + " #withdrawal_account_no").val() + '&paymentTypeCode=' + $("#" + withdrawal_account_details + " #withPaymentTypeCode").val() + '&accHolderName=' + $("#" + withdrawal_account_details + " #acc_holder_name").val() + '&subTypeId=' + $("#" + withdrawal_account_details + " #withSubTypeId").val() + '&isNewRedeemAcc=' + $("#" + withdrawal_account_details + " #isNewWithRedeemAcc").val() + '&verifyOtp=' + $("#" + withdrawal_otp_form_id + " #with_otp_code").val();
                    startAjax("/component/Betting/?task=cashier.verfyOtpCode", params, getResponseForWithdrawalOTP, "#" + deposit_otp_form_id);
                }

            }
        });
    });   
    });   
}

function getResponseForWithdrawalOTP(result){
   if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res);
   if(res.errorCode != 0) {
    if (res.errorCode == 606)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_INVALID_ALIAS_NAME"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 102)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 530)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_OTP_CODE_IS_NOT_VALID"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 203)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 101)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 1012)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 307)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 1003)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 1005)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 434)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 308)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else if (res.errorCode == 317)
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
        else  
            showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', res.respMsg, "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);  
    }else{  
     var params = '&paymentTypeCode=' + $("#" + withdrawal_account_details + " #withPaymentTypeCode").val(); 
     startAjax("/component/Betting/?task=cashier.fetchRedeemAccount", params, getWithdrawalRedeemAccount, "#" + withdrawal_otp_form_id);
    }
}

function getWithdrawalRedeemAccount(result) {
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);  
    //console.log(res); 
    selectList =  $("#withdrawal_account");
    selectList.find("option:gt(0)").remove();

     if(res.errorCode != 0) {
       if(res.errorCode == 203)
         showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("PLAYER_NOT_LOGGED_IN"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
       else if(res.errorCode == 102)
         showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
       else if(res.errorCode == 103)
         showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_INVALID_REQUEST"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
       else if(res.errorCode == 101)
         showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"), "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
       else
        showToolTipErrorManual(withdrawal_otp_form_id + ' #with_otp_code', res.respMsg, "bottom", $("#with_otp_code"), error_callback_withdrawal_otp[withdrawal_otp_form_id]);
    }else{ 
     $(document).ready(function(){
     $("#withdrawal-otp-verification-form").css('display','none');
     $("#withdrawal-amount-form").css('display','block');
     $(".withdrawal-footer").html('');
      $(".withdrawal-title").text(Joomla.JText._("ADD_WITHDRAWAL_ACCOUNT"));
     //var totrows =  res.bankProfile.length;
     for(var i in res.bankProfile){
      $('#withdrawal_account')
         .append($("<option></option>")
                    .attr({"value": res.bankProfile[i].mobileNo, "withSubtypeId":res.bankProfile[i].subtypeId , "withPaymentTypeId":res.bankProfile[i].paymentTypeId, "withRedeemAccId":res.bankProfile[i].redeemAccId, "withPaymentTypeCode":res.bankProfile[i].paymentType})
                    .text(res.bankProfile[i].mobileNo));    
     }

     });
    }
}

function showDefaultContent(){
  $("#modal-mobile-no").parent().css('color','');
  $("#deposit_account_details").css('display','none');
  $("#deposit_otp_verification_form").css('display','none');
  $("#deposit_amount_form").css('display','block');
  //$("#successfull-deposit-form").css('display','block');
  $(".deposit-title").text(Joomla.JText._("ADD_DEPOSIT_ACCOUNT"));
  $(".deposit-footer").html(""); 
}

 function processWithdrawalDetails(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
   if(res.errorCode != 0) {
      if(res.errorCode == 209)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_INSUFFICIENT_PLAYER_BALANCE"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 557)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("INSUFFICIENT_BALANCE_AFTER_APPLYING_MSG"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 317)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 1013)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("INVALID_SUBTYPE_ID"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 318)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_NOT_EXIST"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 423)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_DOES_NOT_EXIST_FOR_THIS_PLAYER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 1012)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 121)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("INVALID_CURRENCY"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 308)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 210)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("YOUR_TRANSACTION_HAS_BEEN_BLOCKED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 102)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 601)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_INVALID_DOMAIN"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 112)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_OPERATION_NOT_SUPPORTED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 601)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("BETTING_INVALID_DOMAIN"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 602)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("UNAUTHENTIC_SERVICE_USER"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else if(res.errorCode == 309)
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', Joomla.JText._("YOUR_PAYMENT_HAS_BEEN_FAILED"), "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
      else
      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', res.respMsg, "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
   }else{
     updatePlayerBalance();
     cashBalance = getFormattedAmount(parseFloat(res.cashBalance,2)).replace(",","");
     DisableWithdrawalBtn();
     var params = 'fromDate=' + fromDate + '&toDate=' + toDate + '&offset=' + offset + '&limit=' + limit;
     startAjax("/component/Betting/?task=withdrawal.getWithdrawalDetails", params, getWalletResponse, "#" + withdraw_form_id);
     $("#withdrawal-amount-form").css('display','none');
     $(".withdrawal-title").text('');
     $("#successfull-withdrawal-form").css('display','block');
     $("#withTrnx_id").text(res.txnId);
   }   
}

 function getResponseForWithdrawalResendOTP(result){
 if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
   if(res.errorCode != 0) {
     $("#modal-withmobile-no").parent().css({'display':'block','color':'red'});
     //$("#modal-withmobile-no").parent().text(''); 
     $("#modal-withmobile-no").parent().text(res.respMsg);   
//      showToolTipErrorManual(withdrawal_amount_id + ' #withdrawal', res.respMsg, "bottom", $("#withdrawal"), error_callback_withdrawal_amount[withdrawal_amount_id]);
   }else{
     
     $("#modal-withmobile-no").parent().css('display','block');
     setTimeout(function(){$("#modal-withmobile-no").parent().css('display','none');}, 3000);
     //$("#resend-link-withdrawal").css('display','block');
   }       
 }

 function getResponseForDepositResendOTP(result) {
    if (validateSession(result) == false)
        return false;
    var res = $.parseJSON(result);
    if (res.errorCode != 0) {
        $("#modal-mobile-no").parent().css({'display':'block','color':'red'});
        if(res.errorCode == 606)
     $("#modal-mobile-no").parent().text(Joomla.JText._("BETTING_INVALID_ALIAS_NAME"));
     else if(res.errorCode == 102)
     $("#modal-mobile-no").parent().text(Joomla.JText._("BETTING_SOME_INTERNAL_ERROR"));
     else if(res.errorCode == 203)
     $("#modal-mobile-no").parent().text(Joomla.JText._("PLAYER_NOT_LOGGED_IN"));
     else if(res.errorCode == 101)
     $("#modal-mobile-no").parent().text(Joomla.JText._("BETTING_HIBERNATE_EXCEPTION"));
    else if(res.errorCode == 1012)
     $("#modal-mobile-no").parent().text(Joomla.JText._("REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER"));
    else if(res.errorCode == 307)
     $("#modal-mobile-no").parent().text(Joomla.JText._("ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG"));
    else if(res.errorCode == 317)
     $("#modal-mobile-no").parent().text(Joomla.JText._("PLAYER_TYPE_DOES_NOT_EXIST"));
    else if(res.errorCode == 1003)
    $("#modal-mobile-no").parent().text(Joomla.JText._("WRONG_VERIFICATION_CODE_PROVIDED"));
    else if(res.errorCode == 1005)
    $("#modal-mobile-no").parent().text(Joomla.JText._("PLAYER_MUST_BE_LOG_OUT_MSG"));
    else if(res.errorCode == 434)
     $("#modal-mobile-no").parent().text(Joomla.JText._("PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS"));
    else if(res.errorCode == 308)
     $("#modal-mobile-no").parent().text(Joomla.JText._("NO_PAYMENT_OPTIONS_AVAILABLE"));
    else
    $("#modal-mobile-no").parent().text(res.respMsg);
    //$("#resend-link-deposit").css('color', 'red');
    } else {
        $("#modal-mobile-no").parent().css('display', 'block');
        setTimeout(function(){$("#modal-mobile-no").parent().css('display','none');}, 3000);       
    }
}
 function showDefaultForWithdrawal(){
  $("#withdrawal-account-details-form").css('display','none');
  $("#withdrawal-otp-verification-form").css('display','none');
  $("#successfull-withdrawal-form").css('display','none');
  $("#withdrawal-amount-form").css('display','block');
  $(".withdrawal-title").text(Joomla.JText._("ADD_WITHDRAWAL_ACCOUNT"));
  $(".withdrawal-footer").html(""); 
}

function DisableWithdrawalBtn() {
        if(cashBalance < parseFloat(aarayAmount.CASH_PAYMENT.min))
           $("#main-div .btn-CASH_PAYMENT").addClass("disabled");
        else
           $("#main-div .btn-CASH_PAYMENT").removeClass("disabled");
       if(cashBalance < parseFloat(aarayAmount.MOBILE_MONEY.min))
                $("#main-div  .btn-MOBILE_MONEY").addClass("disabled");
        else
            $("#main-div .btn-MOBILE_MONEY").removeClass("disabled");
        }
$(window).load(function(){
    if( location.hash == "#withdrawal" ){
        $("[href='#withdrawal']").trigger("click");
    }
    if (location.hash == "#deposit") {
        $("[href='#deposit']").trigger("click");
    }
});
