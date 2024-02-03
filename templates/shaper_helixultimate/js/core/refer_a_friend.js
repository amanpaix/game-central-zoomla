var $ = jQuery.noConflict();
var minManulRow = 1;
var maxManulRow = 5;
var referalList = [];
var referalListEmail = [];
var withdraw_form_id;
var error_callback_cp = {};
$(document).ready(function () {
//    $("#copy_link").on('click', function() {
//        var my_share_link = document.querySelector('#my_share_link');
//        my_share_link.select();
//        document.execCommand('copy');
//    });
    
    $(".my_share_link_b").on('click', function() {
        var my_share_link = document.querySelector('.my_share_link_i');
        my_share_link.select();
        document.execCommand('copy');
        //$(my_share_link).next().text('text copied');
        //setTimeout(function(){$(my_share_link).next().text('');}, 2000);
    });

 $(".referr_code_b").on('click', function() {
        var reffer_code = document.querySelector('.referr_code_i');
        reffer_code .select();
        document.execCommand('copy');
    });
    
        $($("form[id^='email_verification-form']")).each(function () {
            withdraw_form_id = $(this).attr('id');
            error_callback_cp["email_verification-form"] = $("#email_verification-form").attr('error-callback');
            $(this).validate({
                showErrors: function (errorMap, errorList) {
                    if ($("#email_verification-form").attr('validation-style') == undefined) {
                    if ($("#email_verification-form").attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                } else
                       style = $("#email_verification-form").attr('validation-style');
                    displayToolTipManual(this, errorMap, errorList, style, error_callback_cp["email_verification-form"]);
                },

                rules: {
                    email: {
                        required: true,
                        pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
//                        notGreater: true,
//                range: [min_limit, max_limit]
                    }
                },

                messages: {
                    email: {
                        required: Joomla.JText._('FORM_JS_PLEASE_ENTER')+" "+Joomla.JText._('BETTING_FORM_EMAIL_ADDR'),
                        pattern: Joomla.JText._('INVALID_FRIENDS_EMAIL'),
//                        notGreater: 'Entered amount should be less than your withdrawable balance.',
                        //range: "Amount should be between "+min_limit+" to "+max_limit+"."
                    }
                },

                submitHandler: function () {
                    //document.getElementById('cash-withdrawal-form').submit();
                    $("#btn").attr('disabled', 'disabled');
                    //$("#email_verification").modal('hide');
                    if ($("#email_verification-form").attr('submit-type') != 'ajax') {
                        document.getElementById('amount').submit();
                    } else {
                        var params = 'email=' + $("#" + withdraw_form_id + " #email").val();
                        startAjax("/component/Betting/?task=account.updatePlayerProfile", params, getReferFriendResponse, "#" + withdraw_form_id);
                    }

                }
            });
        });   
    $(".add_friend").on('click', function() {
        var row_shown = false;
        var shown_rows = 0;
        var last_shown_row_id;
		
		if(!validateReferList())
            return false;
		
        $($("[row-id]")).each(function () {
            if($(this).css('display') == 'none' && row_shown == false) {
                $(this).css('display', '');
                row_shown = true;
            }
            if($(this).css('display') != 'none') {
                last_shown_row_id = $(this).attr("row-id");
                shown_rows++;
                $('[remove-row='+$(this).attr("row-id")+']').css('visibility','');
            }
        });

        $('[add-row]').css('visibility','hidden');
        $('[add-row='+last_shown_row_id+']').css('visibility',"");
		
		if(shown_rows == 5)
        {
            $('[add-row=5]').css('visibility',"hidden");
        }
    });

    $("[remove-row]").on('click', function() {
        var thisRowId = $(this).attr("remove-row");
        var shown_row = 0;
        var last_shown_row_id;
        $("[row-id="+thisRowId+"]").css('display','none');
        resetThisRowData(thisRowId);
        $($("[row-id]")).each(function () {
            if($(this).css('display') != 'none') {
                shown_row++;
                last_shown_row_id = $(this).attr('row-id');
            }
        });

        if(shown_row <= 0) {
            $('[row-id=1]').css('display',"");
            $('[remove-row=1').css('visibility','hidden');
        }
        if(shown_row == 1) {
            $('[remove-row='+last_shown_row_id+']').css('visibility',"hidden");
        }
        $('[add-row]').css('visibility','hidden');
        $('[add-row='+last_shown_row_id+']').css('visibility',"");
    });

   $("#filter").on('keyup', function () {
       if($("#filter").val().length != 0)
       {

       }
       else
       {
           var isAllChecked = true;
		   var cnt=0;
           $.each($("#track-bonus-list tbody input[type='checkbox'], #invite-list tbody input[type='checkbox']"), function( index, value ) {
               if(!$(this).parent().hasClass("checked")) {
                   isAllChecked = false;
                   $("#select-all").iCheck("uncheck");
                   return false;
               }
			   cnt++;
           });
		   if(cnt > 0)
		   {
			   if(isAllChecked)
               $("#select-all").iCheck("check");
				else
               $("#select-all").iCheck("uncheck");
		   }
       }
    });
	
    $("#select-all").on('ifClicked', function (e) {
        if($(this).parent().hasClass("checked")) {
            $("input[type='checkbox']").iCheck("uncheck");
            $("#send-reminder").addClass("disabled");
            $("#inviteFriendNowEmail").addClass("disabled");
        }
        else {
            if($("#filter").val().length != 0)
            {
                // var isAllCheckedIL = true;
                 $.each($("#invite-list tbody tr, #track-bonus-list tbody tr"), function( index, value ) {
                     if($(this).css('display') != "none") {
                         $(this).find("input[type='checkbox']").iCheck("check");
                     }
                 });
                // var isAllCheckedTB = true;
                $("#select-all").iCheck("check") ;
                $("#send-reminder").removeClass("disabled");
                $("#inviteFriendNowEmail").removeClass("disabled");
                e.originalEvent.preventDefault();
                //$("input[type='checkbox']").iCheck("check") ;
            }
            else
            {
                $("input[type='checkbox']").iCheck("check");
            }
            $("#send-reminder").removeClass("disabled");
            $("#inviteFriendNowEmail").removeClass("disabled");
        }
    });


    $("#invite-list tbody input[type='checkbox']").on('ifClicked', function () {
        if($(this).parent().hasClass("checked")) {
            $(this).iCheck("uncheck")
        }
        else {
            $(this).iCheck("check")
        }
        var isAllChecked = true;
        var isAtlestOneChecked = false;
        $.each($("#invite-list tbody input[type='checkbox']"), function( index, value ) {
            if(!$(this).parent().hasClass("checked")) {
                isAllChecked = false;
            }
            else
                isAtlestOneChecked = true;
        });

        if(isAllChecked)
            $("#select-all").iCheck("check");
        else
            $("#select-all").iCheck("uncheck");

	if(isAtlestOneChecked)
            $("#inviteFriendNowEmail").removeClass("disabled");
        else
            $("#inviteFriendNowEmail").addClass("disabled");
    });

    $("#track-bonus-list tbody input[type='checkbox']").live('ifClicked', function () {
        if($(this).parent().hasClass("checked")) {
            $(this).iCheck("uncheck")
        }
        else {
            $(this).iCheck("check")
        }
        var isAllChecked = true;
        var isAtlestOneChecked = false;
        $.each($("#track-bonus-list tbody input[type='checkbox']"), function( index, value ) {
            if(!$(this).parent().hasClass("checked")) {
                isAllChecked = false;
            }
            else
		isAtlestOneChecked = true;
        });

        if(isAllChecked)
            $("#select-all").iCheck("check");
        else
            $("#select-all").iCheck("uncheck");

    		if(isAtlestOneChecked)
        $("#send-reminder").removeClass("disabled");
        else
            $("#send-reminder").addClass("disabled");
    });

    $("[href='#track-status']").on('click', function () {
        if($(this).parent().hasClass("active") == false)
		{
					    if($("#system-message-container").text().trim().length > 0)
                startAjax("/component/Betting/index.php?task=referafriend.trackBonus", "", getTrackBonusResponse, "nottoshow");
            else
			startAjax("/component/Betting/index.php?task=referafriend.trackBonus", "", getTrackBonusResponse, "null");
		}
            
    });

    $("#send-reminder").on('click', function () {
        if($(this).hasClass('disabled'))
	{
			
	}
        else
        {
        var reminderList = [];
        $($("#track-bonus-list tbody input[type='checkbox']")).each(function () {
            if($(this).parent().hasClass("checked")) {
                var arr = {};
                arr["userName"] = $(this).attr("user-name");
                arr["emailId"] = $(this).attr("email-id");
                arr["mobileNo"] = $(this).attr("mobile-no");
                reminderList.push(arr);
            }
        });

        if(reminderList.length == 0) {
            error_message(Joomla.JText._('INVITE_JS_SELECT_ATLEST_ONE_CHECK_ERROR'), undefined);
            return false;
        }

        $("#send-reminder-form #reminderList").val(JSON.stringify(reminderList));
        document.getElementById("send-reminder-form").submit();
        }
    });

    $('[href="#refer-now"]').on('click', function () {
        $("#no_invitations").css('display', 'none');
        $("#list_of_invitations").css('display', 'none');
		clearSystemMessage();
    });
    
    $(document).on("keyup", ".validate_email", function (e) {
        var value = $(this).val();
        value = value.replace(/[^a-zA-Z0-9@._]/g, '');
        $(this).val(value);

    });
});


function resetThisRowData(rowId) {
    $("[row-id="+rowId+"] input").val("");
    $($("[row-id="+rowId+"] input")).each(function () {
        removeToolTipErrorManual("", $(this));
    });
}

function inviteFriendNow(type)
{
    removeToolTipError("all");
    var referalList = [];
    var form = "";
    if(type == "EMAIL") {
        if(!validateReferList())
            return false;
        var thisRowId=0;
        $($("[row-id]")).each(function () {
            if($(this).css('display') != 'none') {
                var arr = {};
                thisRowId = $(this).attr('row-id');
                arr["firstName"] = $("#name-"+thisRowId).val().trim();
                arr["lastName"] = "";
                arr["emailId"] = $("#email-"+thisRowId).val().trim();
                arr["mobileNo"] = "";
                referalList.push(arr);
            }
        });
        form = "invite-friend-form";
    }
    else {

        var row_count_check = 1;
        var filledArr  = [];
        $($("form#invite-friend-mobile-form div.form-group .row")).each(function () {

            var name = $(this).find("input#fname_"+row_count_check).val();
            var mobile = $(this).find("input#mobile_"+row_count_check).val();

            if(name.trim().length > 0 || mobile.trim().length > 0)
            {
                filledArr.push(row_count_check);
            }
            row_count_check++;
        });

        if(filledArr.length == 0 ) {

            validateName("fname_1", "", "manual");
            validateMobile("mobile_1", "", "manual");
            return false;
        }
        else
        {
            var div_filled_found = false;
            var row_count = 1;
            filledArr.forEach(function(id)
            {
                var name = $("#fname_" + id).val();
                var mobile = $("#mobile_" + id).val();

                var name_valid = false;
                var mobile_valid = false;
                if (div_filled_found == false) {
                    name_valid = validateName("fname_" + id, name, "manual");
                    mobile_valid = validateMobile("mobile_" + id, mobile, "manual");
                }
                else {
                    if (name.trim() != "")
                        name_valid = validateName("fname_" + id, name, "manual");
                    if (mobile.trim() != "")
                        mobile_valid = validateMobile("mobile_" + id, mobile, "manual");
                }

                if (name_valid == false || mobile_valid == false)
                    return false;

                var arr = {};
                arr["firstName"] = name;
                arr["lastName"] = "";
                arr["emailId"] = "";
                arr["mobileNo"] = mobile;
                referalList.push(arr);
                row_count++;
                div_filled_found = true;

            });

            if (div_filled_found == false)
                return false;
        }
        form = "invite-friend-mobile-form";
    }

    $('#'+form).append($('<input>', {
        type: "hidden",
        name: "referalList",
        value: JSON.stringify(referalList)
    }));

    $('#'+form).append($('<input>', {
        type: "hidden",
        name: "referType",
        value: "manualRefer"
    }));

    $('#'+form).append($('<input>', {
        type: "hidden",
        name: "inviteMode",
        value: type
    }));

    document.getElementById(form).submit();
}

function validateReferList() {
    var thisRowId=0;
    var isErrorName=false;
    var isErrorEmail=false;
    $($("[row-id]")).each(function () {
        if($(this).css('display') != 'none') {
            thisRowId = $(this).attr('row-id');
            var name = $("#name-"+thisRowId).val().trim();
            var email = $("#email-"+thisRowId).val().trim();
            if(!validateName("name-"+thisRowId,name, "manual")){
                isErrorName = true;
            }
            if(!validateEmail("email-"+thisRowId,email, "manual")){
                isErrorEmail = true;
            }
            
//            validateName("name-"+thisRowId,name, "manual") ? isErrorName = false : isErrorName = true;
//            validateEmail("email-"+thisRowId,email, "manual") ? isErrorEmail = false : isErrorEmail = true;
			
//			if(isErrorName || isErrorEmail)
//            {
//                return false;
//            }
        }
    });

    if(isErrorName || isErrorEmail)
    {
        return false;
    }

    return true;
}

function validateName(id,name, errType)
{

    if(name.length == 0){
         if(errType == "manual")
            showToolTipErrorManual(id, Joomla.JText._('INVITE_JS_FRIENDS_NAME'), "bottom", $("#"+id), undefined);
        else 
            showToolTipErrorManual(id, Joomla.JText._('INVITE_JS_FRIENDS_NAME'), "bottom", undefined);
            return false;
        }
     else if((name.length < 2) || (name.length > 25)){
       if(errType == "manual")
       showToolTipErrorManual(id, Joomla.JText._('FRIENDS_NAME_SHOULD_BE_BETWEEN_CHARACTERS'), "bottom", $("#"+id), undefined);
       else 
        showToolTipErrorManual(id, Joomla.JText._('FRIENDS_NAME_SHOULD_BE_BETWEEN_CHARACTERS'), "bottom", undefined);
       return false;      
       }

    var myRegEx  = /^[-\w\s]+$/;
    if(!myRegEx.test(name)) {
        if(errType == "manual")
            showToolTipErrorManual(id, Joomla.JText._('INVITE_JS_FRIEND_INVALID_NAME'), "bottom", $("#"+id), undefined);
        else
            showToolTipError(id, Joomla.JText._('INVITE_JS_FRIEND_INVALID_NAME'), "bottom", undefined);
        return false;
    }
    
	removeToolTipErrorManual("", $("#"+id));
    return true;
}

function validateEmail(id,email, errType)
{
    if(email.length == 0) {
        if(errType == "manual")
            showToolTipErrorManual(id, Joomla.JText._('INVITE_JS_FRIEND_EMAIL_MSG'), "bottom", $("#"+id), undefined);
        else
            showToolTipError(id, Joomla.JText._('INVITE_JS_FRIEND_EMAIL_MSG'), "bottom", undefined);
        return false;
    } else if((email.length == 0 < 3) || (email.length > 50)){
       if(errType == "manual") 
        showToolTipErrorManual(id, Joomla.JText._('EMAIL_ID_SHOULD_BE_IN_RANGE'), "bottom", $("#"+id), undefined);
       else
        showToolTipErrorManual(id, Joomla.JText._('EMAIL_ID_SHOULD_BE_IN_RANGE'), "bottom", undefined); 
        return false;
    }

    var myRegEx  = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!myRegEx.test(email)) {
        if(errType == "manual")
            showToolTipErrorManual(id, Joomla.JText._('INVITE_JS_INVALID_EMAIL_MSG'), "bottom", $("#"+id), undefined);
        else
            showToolTipError(id, Joomla.JText._('INVITE_JS_INVALID_EMAIL_MSG'), "bottom", undefined);
        return false;
    }

	removeToolTipErrorManual("", $("#"+id));
    return true;
}

function inviteFriendNowEmail() {
      	var param = document.getElementById("inviteFriendNowEmail");
	if(param.classList.contains('disabled'))
	{
		
	}
        else
        {
    referalListEmail = [];
    $($("#invite-list tbody tr")).each(function () {
        if($(this).find("td input").parent().hasClass("checked"))
        {
            var arr = {};
            arr["firstName"] = $(this).find("[referName=name]").text().trim();
            arr["lastName"] = "";
            arr["emailId"] = $(this).find("[referEmail=email]").text().trim();
            arr["mobileNo"] = "";
            referalListEmail.push(arr);
        }
    });

    $('#invite-friend-form-email').append($('<input>', {
        type: "hidden",
        name: "referalList",
        value: JSON.stringify(referalListEmail)
    }));

    $('#invite-friend-form-email').append($('<input>', {
        type: "hidden",
        name: "referType",
        value: "mailRefer"
    }));

    $('#invite-friend-form-email').append($('<input>', {
        type: "hidden",
        name: "inviteMode",
        value: "EMAIL"
    }));

    document.getElementById("invite-friend-form-email").submit();
      } 
}

function getTrackBonusResponse(result)
{
    $('#track-bonus-list tbody > tr').remove();
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);

    if(res.errorCode != 0) {
        $('[href="#refer-now"]').trigger('click');
        error_message(res.respMsg, undefined);
        return false;
    }
    $("input[type='checkbox']").iCheck("uncheck");
    $("#no_invitations").css('display', 'none');
    $("#list_of_invitations").css('display', 'none');
    if(res.plrTrackBonusList == undefined || res.plrTrackBonusList.length == 0) {
        $("#no_invitations").css('display', 'block');
        $("#list_of_invitations").css('display', 'none');
        return false;
    }

    $('#track-bonus-list').attr('data-page-size',res.plrTrackBonusList.length);

    for(var i = 0; i < res.plrTrackBonusList.length; i++) {
        var footable = $('#track-bonus-list').data('footable');

        var tr = "<tr><td>";

        var username_td = "";
        if(!(res.plrTrackBonusList[i].register == true && res.plrTrackBonusList[i].depositor == true)) {
            var username_td = "";
            if(res.plrTrackBonusList[i].userName != "null" && res.plrTrackBonusList[i].userName != null && res.plrTrackBonusList[i].userName.trim().length > 0 ) {
                username_td = res.plrTrackBonusList[i].userName;
            }
            else if(res.plrTrackBonusList[i].emailId != "null" && res.plrTrackBonusList[i].emailId != null && res.plrTrackBonusList[i].emailId.trim().length > 0 ) {
                username_td = res.plrTrackBonusList[i].emailId;
            }
            else if(res.plrTrackBonusList[i].mobileNum != "null" && res.plrTrackBonusList[i].mobileNum != null && res.plrTrackBonusList[i].mobileNum.trim().length > 0) {
                username_td = res.plrTrackBonusList[i].mobileNum;
            }

            if(username_td == "")
                continue;

            tr += '<label class="icheckbox_label"><input type="checkbox" user-name="'+res.plrTrackBonusList[i].userName+'" email-id="'+res.plrTrackBonusList[i].emailId+'" mobile-no="'+res.plrTrackBonusList[i].mobileNum+'"><span class="chkIcon"></span></label>';
        }
        else
        {
            username_td = "";
            if(res.plrTrackBonusList[i].userName != "null" && res.plrTrackBonusList[i].userName != null && res.plrTrackBonusList[i].userName.trim().length > 0 ) {
                username_td = res.plrTrackBonusList[i].userName;
            }
            else if(res.plrTrackBonusList[i].emailId != "null" && res.plrTrackBonusList[i].emailId != null && res.plrTrackBonusList[i].emailId.trim().length > 0 ) {
                username_td = res.plrTrackBonusList[i].emailId;
            }
            /*else if(res.plrTrackBonusList[i].mobileNum != "null" && res.plrTrackBonusList[i].mobileNum != null && res.plrTrackBonusList[i].mobileNum.trim().length > 0) {
             username_td = res.plrTrackBonusList[i].mobileNum;
             }*/ 
        }
        tr += "</td><td>"+username_td+"</td>";

        var referralDate = res.plrTrackBonusList[i].referralDate.toString().split(" ")[0];
        var referralTime = res.plrTrackBonusList[i].referralDate.toString().split(" ")[1];

        var finalDate = new Date(referralDate.split("/")[2], (parseInt(referralDate.split("/")[1])-1), referralDate.split("/")[0], referralTime.split(":")[0], referralTime.split(":")[1], referralTime.split(":")[2]);
        tr += '<td>'+finalDate.getDate()+' '+finalDate.getMonthName()+', '+finalDate.getFullYear()+' '+( (finalDate.getHours().toString().length == 1) ? "0"+finalDate.getHours() : finalDate.getHours())+':'+( (finalDate.getMinutes().toString().length == 1) ? "0"+finalDate.getMinutes() : finalDate.getMinutes() )+'</td>';

        res.plrTrackBonusList[i].register = (res.plrTrackBonusList[i].register == true) ? "register" : "remove";
        res.plrTrackBonusList[i].depositor = (res.plrTrackBonusList[i].depositor == true) ? "register" : "remove";

        tr += '<td><img src="'+base_href+'/templates/shaper_helix3/images/my_account/refer_friend/'+res.plrTrackBonusList[i].register+'_friend.png"></td><td><img src="'+base_href+'/templates/shaper_helix3/images/my_account/refer_friend/'+res.plrTrackBonusList[i].depositor+'_friend.png"></td></tr>';

        footable.appendRow(tr);
    }
    $('input').not("#select-all").iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%'
    });

    $("#no_invitations").css('display', 'none');
    $("#list_of_invitations").css('display', 'block');
    $('#track-bonus-list').trigger('footable_redraw');
    $('#track-bonus-list').trigger('footable_initialize');
	
	if($('#track-bonus-list tbody > tr').length == 0) {
        $("#no_invitations").css('display', 'block');
        $("#list_of_invitations").css('display', 'none');
        return false;
    }
}

 function getReferFriendResponse(result){
         if (validateSession(result) == false)
            return false;
           var res = $.parseJSON(result);
           if(res.errorCode == 0) {
              $("#email_verification").modal('hide');
              jQuery("#system-message-container").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+Joomla.JText._('BETTING_EMAIL_UPDATED_SUCCESSFULLY')+'</div></div>');
          }
          else if(res.errorCode == 502){
              showToolTipErrorManual(withdraw_form_id + ' #email', Joomla.JText._('EMAIL_ID_ALREAD_EXIST'), "bottom", $("#email"), error_callback_cp["email_verification-form"]);
              //jQuery("#system-message-container").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading"></h4><div class="alert-message">'+Joomla.JText._('EMAIL_ID_ALREAD_EXIST')+'</div></div>');
              //$('#email_verification').modal('show');
               //$('#email_verification').modal({backdrop: 'static', keyboard: false});
          }
          else if(res.errorCode == 102){
              showToolTipErrorManual(withdraw_form_id + ' #email', Joomla.JText._('BETTING_SOME_INTERNAL_ERROR'), "bottom", $("#email"), error_callback_cp["email_verification-form"]);
          }
          else{
               showToolTipErrorManual(withdraw_form_id + ' #email', res.respMsg, "bottom", $("#email"), error_callback_cp["email_verification-form"]);
          }
              
               //window.location.href = '/refer-a-friend';
    }
  function validateReferOnFocus(type) {
   var thisRowId=0;
    var isErrorName=false;
    var isErrorEmail=false;
    $($("[row-id]")).each(function () {
        if($(this).css('display') != 'none') {
            thisRowId = $(this).attr('row-id');
            var name = $("#name-"+thisRowId).val().trim();
            var email = $("#email-"+thisRowId).val().trim();
            if(type == 'Name'){
            if(!validateName("name-"+thisRowId,name, "manual")){
                isErrorName = true;
            }
            }else if(type == 'Email'){
            if(!validateEmail("email-"+thisRowId,email, "manual")){
                isErrorEmail = true;
            }
        }
            
//            validateName("name-"+thisRowId,name, "manual") ? isErrorName = false : isErrorName = true;
//            validateEmail("email-"+thisRowId,email, "manual") ? isErrorEmail = false : isErrorEmail = true;
			
//			if(isErrorName || isErrorEmail)
//            {
//                return false;
//            }
        }
    });

    if(isErrorName || isErrorEmail)
    {
        return false;
    }

    return true;  
     
   }
