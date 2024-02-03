<!--mobile_verify Model-->
<div class="modal fade" id="mobile_verify">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></button>
                <h4 class="modal-title"><?php echo JText::_("BETTING_ACCOUNT_MOBILE_VERIFY_TITLE");?></h4>
            </div>
            <div class="modal-body">
                <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/mobile_verify.jpg" width="100%" alt="">
                <div class="form-group text-center">
                    <?php sprintf(JText::_("BETTING_MOBILE_VERIFY_TEXT"),Utilities::getPlayerLoginResponse()->mobileNo);?>
                    <div class="clear"></div>
                </div>
                <div class="divider">&nbsp;</div>
                <div class="form-group text-center">
                    <?php echo JText::_("BETTING_MOBILE_ENTER_VERIFY_TEXT")?><br><br>
                        <input type="tel" class="custome_input" placeholder="Enter Verification Code" tabindex="4" id="otpcode" name="otpcode" maxlength="4">
                        <button class="btn_do_verify" type="button" id="continue-btn">continue</button>
                        <div class="error_tooltip manual_tooltip_error" id="error_otpcode"></div>
                </div>
                <div class="form-group text-center">
                    <p id="resend-link-show-mobile" style="display: none;" class="send_msg"><?php echo JText::_("BETTING_VERIFY_CODE_AGAIN");?></p>
                    <a href="javascript:void(0);" id="mobileVerificationModal-resend-link"><?php echo JText::_("BETTING_VERIFY_CODE_AGAIN_LINK");?></a>
                </div>
            </div>
            <div class="modal-footer">
                <p class="text-center footer_desktop"><?php echo JText::_("NEED_HELP");?> &nbsp;&nbsp;&nbsp;<a href="mailto:support@KhelplayRummy.com"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/popup_mail.png" alt="" width="18" height="13"></a> &nbsp;| &nbsp;<a href="javascript:void(0);" onclick="openLiveChat();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/popup_chat.png" alt="" width="20" height="17"></a> &nbsp;|&nbsp; <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/popup_call.png" alt="" width="15" height="17"> &nbsp;022-2525 9555</p>
                <div class="footer_mobile"> <strong><?php echo JText::_("NEED_HELP");?></strong> <a href="javascript:void(0);" onclick="openLiveChat();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/home/chat_icon.png" alt=""><span><?php echo JText::_("LIVE_SUPPORT");?></span></a> <a href="mailto:support@KhelplayRummy.com"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/home/mail_icon.png" alt=""><span><?php echo JText::_("EMAIL_US");?></span></a> <a href="tel:02225259555"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/home/call_icon.png" alt=""><span>022-25259555</span></a> </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--mobile_verify Model-->
