<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

?>
    <div class="single_page">
        <div class="single_page_inner">
            <div class="header_content_div">
                <div class="container">
                    <div class="row">
                        <div class="logo"><a href="<?php echo Configuration::DOMAIN ?>"><img src="templates/shaper_helix3/images/common/logo.png" /></a></div>
                    </div>
                </div>
            </div>
            <form action="#" method="post" id="verify-player-form" submit-type="ajax" validation-style="bottom" tooltip-mode="manual" error-callback="rp_inputGroupElement">
            <div class="body_content_div">
                <div class="container">
                    <div class="row">
                        <div class="body_content_div_inner">
                            <div class="login_form">
                                <div class="login_mobile_banner">
                                    <a href="/mobile-rummy-app" ><img src="/images/promotions/common/rummy-on-mobile/form-page-mobile-1024x273.jpg" /></a></div>
                                <div class="login_form_inner">
                                    <div class="page-header"><?php echo JText::_("PLAYER_VERIFICATION");?></div>
                                    <div class="form-group">
                                        <p class="mbottom15"><?php echo JText::_("VERIFICATION_PENDING");?></p>
                                    </div>
                                    <div class="input-group"><span class="input-group-addon password_icon" id="password"><img src="templates/shaper_helix3/images/common/password_icon.png" alt="" width="22" height="22" /></span> <input type="password" class="custome_input" placeholder="Verification Code" aria-describedby="password" id="verificationcode" name="verificationcode" required />
                                        <div class="error_tooltip manual_tooltip_error" id="error_verificationcode" ></div>
                                    </div>
                                    <div class="form-group"><input type="button" class="brown_bg" value="Submit" id="verificationsubmit-btn" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="footer_content_div">
                    <div class="container">
                        <div class="row">
                            <p><?php echo JText::_("NEED_HELP");?>   <a href="mailto:support@KhelplayRummy.com" class="popup_mail"> </a>   |   <a href="#" class="popup_chat" onclick="openLiveChat();"> </a>   |   <a href="tel:02225259555" class="popup_call">  022-2525 9555</a></p>
                        </div>
                    </div>
                </div>
            <input type="hidden" name="verifyUserName" id="verifyUserName" value="<?php print_r(Session::getSessionVariable('verificationPendingUserName')); ?>">
</form>
        </div>
    </div>
<?php
Html::addJs(JUri::base()."/templates/shaper_helix3/js/jquery.validate.min.js");
?>
<script>

    $('#verificationcode').keypress(function (e) {
        if (e.which == 13) {
            $('#verificationsubmit-btn').trigger("click");
            return false;
        }
    });

    $("#verify-player-form").validate({
        showErrors: function(errorMap, errorList) {
            displayToolTipManual(this, errorMap, errorList, "bottom", undefined);
        },
        rules: {
            verificationCode: {
                required: true
            }
        },
        messages: {
            verificationCode: {
                required: Joomla.JText._('PLEASE_ENTER_VERIFICATION_CODE')
            }
        }
    });

    $("#verificationsubmit-btn").click(function (e) {
        if($('#verificationcode').val().trim().length <= 0)
        {
            showToolTipErrorManual('verificationcode', Joomla.JText._('PLEASE_ENTER_VERIFICATION_CODE'), "bottom", $("#verificationcode"), undefined);
            $("#verificationcode").parent().addClass('error');
            return false;
        }
        removeToolTipErrorManual("all");
        $("#verificationcode").parent().removeClass('error');
        var param = "verificationCode="+$("#verificationcode").val()+"&verificationType=LOGIN&verifyUserName="+$("#verifyUserName").val();
        startAjax(<?php echo json_encode(JRoute::_('index.php?task=account.verifyPlayer'))?>,param, getResponseVerifyPlayer, "#verify-player-form")
    });

    function getResponseVerifyPlayer(result)
    {
        if(validateSession(result) == false)
            return false;

        var res = JSON.parse(result);
        if(res.errorCode != 0) {
            showToolTipErrorManual('verificationcode', res.respMsg, "bottom", $("#verificationcode"), undefined);
            $("#verificationcode").parent().addClass('error');
            return false;
        }
        removeToolTipErrorManual("all");
        $("#verificationcode").parent().removeClass('error');
        location.href = res.path;
    }

</script>

