$("#add_cash_button").click(function() {
    removeToolTipError("all");
    var error_found = false;
    var params ='';
    $(".resp-tab-content").each(function () {
        if($(this).hasClass("resp-tab-content-active")) {
            var payTypeCode = $(this).attr('paytypecode');
            var payTypeId = $(this).attr('paytypeid');
            params += "payTypeCode="+payTypeCode+"&payTypeId="+payTypeId;

            if(payTypeCode == CREDIT_CARD || payTypeCode == DEBIT_CARD || payTypeCode == NET_BANKING || payTypeCode == PREPAID_WALLET) {
                if($("#"+payTypeCode+"_LIST").val() == "" || $("#"+payTypeCode+"_LIST").val() == 'select') {
                    showToolTipError(payTypeCode+"_LIST", "Please select an option from the list");
                    error_found = true;
                    return false;
                }
                params += "&subTypeId="+$("#"+payTypeCode+"_LIST").val();
            }
            if(payTypeCode == CHEQUE_TRANS) {
                var tmp_error_found = false;
                if($("#"+payTypeCode+"_CHEQUE_NO").val() == "") {
                    showToolTipError(payTypeCode+"_CHEQUE_NO", "Please enter cheque no.");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if(isNaN($("#"+payTypeCode+"_CHEQUE_NO").val())) {
                    showToolTipError(payTypeCode+"_CHEQUE_NO", "Cheque no. should be numeric");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if($("#"+payTypeCode+"_BANK_NAME").val() == "") {
                    showToolTipError(payTypeCode+"_BANK_NAME", "Please enter bank name.");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if($("#"+payTypeCode+"_BANK_NAME").val() != "" && new RegExp("^[a-zA-Z ]{2,30}[a-zA-Z]{1}$").test($("#"+payTypeCode+"_BANK_NAME").val()) == false) {
                    showToolTipError(payTypeCode+"_BANK_NAME", "Invalid Bank Name.");
                    error_found = true;
                    tmp_error_found = true;
                }

                if(tmp_error_found)
                    return false;
                params += "&chequeNo="+$("#"+payTypeCode+"_CHEQUE_NO").val()+"&bankName="+$("#"+payTypeCode+"_BANK_NAME").val()+"&chequeDate="+$("#"+payTypeCode+"_CHEQUE_DATE").val();
            }
            if(payTypeCode == WIRE_TRANS) {
                var tmp_error_found = false;
                if($("#"+payTypeCode+"_REFERENCE_NO").val() == "") {
                    showToolTipError(payTypeCode+"_REFERENCE_NO", "Please enter reference no.");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if(isNaN($("#"+payTypeCode+"_REFERENCE_NO").val())) {
                    showToolTipError(payTypeCode+"_REFERENCE_NO", "Reference no. should be numeric");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if($("#"+payTypeCode+"_BANK_NAME").val() == "" || $("#"+payTypeCode+"_BANK_NAME").val() == 'select') {
                    showToolTipError(payTypeCode+"_BANK_NAME", "Please select an option from the list");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }
                if(tmp_error_found)
                    return false;
                params += "&referenceNo="+$("#"+payTypeCode+"_REFERENCE_NO").val()+"&bankName="+$("#"+payTypeCode+"_BANK_NAME").val()+"&date="+$("#"+payTypeCode+"_DATE").val();
            }
            if(payTypeCode == CASH_CARD) {
                var tmp_error_found = false;
                if($("#"+payTypeCode+"_CARD_LIST").val() == "" || $("#"+payTypeCode+"_CARD_LIST").val() == 'select') {
                    showToolTipError(payTypeCode+"_CARD_LIST", "Please select an option from the list");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if($("#"+payTypeCode+"_PIN_NO").val() == "") {
                    showToolTipError(payTypeCode+"_PIN_NO", "Please enter pin no.");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }

                if($("#"+payTypeCode+"_SERIAL_NO").val() == "") {
                    showToolTipError(payTypeCode+"_SERIAL_NO", "Please enter serial no.");
                    error_found = true;
                    tmp_error_found = true;
                    //return false;
                }
                if(tmp_error_found)
                    return false;
                params += "&subTypeId="+$("#"+payTypeCode+"_CARD_LIST").val()+"&pinNo="+$("#"+payTypeCode+"_PIN_NO").val()+"&serialNo="+$("#"+payTypeCode+"_SERIAL_NO").val();
            }
            if(payTypeCode == CASH_PAYMENT) {
                params += "&amount="+$("#"+CASH_PAYMENT+"_AMOUNT").text().trim()+"&requestId="+$("#"+CASH_PAYMENT+"_AMOUNT").attr("request-id").trim();
            }
        }
    });

    if(error_found)
        return false;

    var form_fields = params.split("&");
    for(var i=0; i<form_fields.length; i++) {
        var temp = form_fields[i].split("=");
        $('#payment-request-form').append($('<input>', {
            type: "hidden",
            name: temp[0],
            value: temp[1]
        }));
    }

    $(this).attr('disabled', 'disabled');
    document.getElementById("payment-request-form").submit();
});

$(document).keypress(function(e) {
    if($("select").is(":focus") ==false && e.which == 13) {
        $("#add_cash_button").click();
    }
});


$(document).ready(function(){
    $("#"+CREDIT_CARD+"_LIST").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(CREDIT_CARD+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#"+DEBIT_CARD+"_LIST").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(CREDIT_CARD+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#"+NET_BANKING+"_LIST").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(NET_BANKING+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#"+WIRE_TRANS+"_BANK_NAME").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(WIRE_TRANS+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#"+CASH_CARD+"_CARD_LIST").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(CASH_CARD+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#"+PREPAID_WALLET+"_LIST").change(function(){
        removeToolTipError("all");
        if($(this).val() == 'select') {
            showToolTipError(PREPAID_WALLET+"_LIST", "Please select an option from the list");
            return false;
        }
    });

    $("#confirm_cash_payment_button").click(function () {
        $("#add_cash_button").click();
    });
});