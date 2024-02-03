<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';
$playerLoginResponse = Utilities::getPlayerLoginResponse();

if(isset($playerLoginResponse->emailVerified) && $playerLoginResponse->emailVerified == "N") {
    ?>
<!--    <div class="notification_bar">-->
<!--        <div class="container">-->
<!--            <img src="--><?php //echo Redirection::BASE; ?><!--/templates/shaper_helix3/images/my_account/notification_icon.png" /> Your e-mail is not verified yet.-->
<!--            <a href="--><?php //echo Redirection::MYACC_PROFILE?><!--" verify-now="EMAIL">Verify Now</a>-->
<!--        </div>-->
<!--    </div>-->
    <?php
}

else if(isset($playerLoginResponse->phoneVerified) && $playerLoginResponse->phoneVerified == "N") {
    ?>
<!--    <div class="notification_bar">-->
<!--        <div class="container">-->
<!--            <img src="--><?php //echo Redirection::BASE; ?><!--/templates/shaper_helix3/images/my_account/notification_icon.png" /> Your mobile is not verified yet.-->
<!--            <a href="--><?php //echo Redirection::MYACC_PROFILE?><!--" verify-now="MOBILE">Verify Now</a>-->
<!--        </div>-->
<!--    </div>-->

    <?php
}

if(isset($playerLoginResponse->emailVerified) && $playerLoginResponse->emailVerified == "N")
{
    ?>
    <button class="btnStyle1" type="button" id="emailVerify-btn" style="display: none;"></button>
    <!--email_verify Model-->
    <div class="modal fade" id="email_verify">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"><?php echo JText::_('EMAIL_VERIFICATION'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group text-center" style="padding-top:15px;">
                        <?php echo JText::_('VERIFICATION_LINK'); ?> <span id="modal-email"><?php echo Utilities::getPlayerLoginResponse()->emailId; ?></span><br><br>
                        <?php echo JText::_('VERIFY_EMAIL'); ?>, <br><?php echo JText::_('ACCESS_LINK'); ?>.
                    </div>
                    <div class="form-group text-center"  style="padding-bottom:30px;">
                        <p id="resend-link-show" style="display: none;" class="send_msg">We have sent the verification link again</p>
                        <a class="" href="javascript:void(0);" id="emailVerificationModal-resend-link"><?php echo JText::_('RESEND_LINK'); ?></a>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--email_verify Model-->
    <?php
}
if(isset($playerLoginResponse->phoneVerified) && $playerLoginResponse->phoneVerified == "N")
{
    ?>
    <button class="btnStyle1" type="button" id="mobileVerify-btn" style="display: none;"></button>

    <?php
    if($_SERVER['REQUEST_URI'] == Redirection::AFTER_REGISTRATION) {
        ?>
        <input type="tel" class="custome_input  allow_only_nums" tabindex="4" id="mobile" name="mobile"
               value="<?php echo $playerLoginResponse->mobileNo ?>" pattern="^[7-9]{1}[0-9]{9}$" maxlength="10" style="display:none;">
        <?php
    }
    ?>

    <!--mobile_verify Model-->
    <div class="modal fade" id="mobile_verify">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"><?php echo JText::_('MOBILE_VERIFICATION'); ?></h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group text-center">
                        <?php echo JText::_('VERIFICATION_CODE'); ?> <br><span id="modal-mobileNo"><?php echo Utilities::getPlayerLoginResponse()->mobileNo; ?></span> <br>
    <small><?php echo JText::_('VERIFICATION_RECEIVED'); ?></small>
    <div class="clear"></div>
    </div>
    <div class="divider">&nbsp;</div>
    <div class="form-group text-center">
        <?php echo JText::_('VERIFY_MOBILE'); ?><br><br>
        <div class="input-group">
            <input type="tel" class="custome_input" placeholder="<?php echo JText::_('ENTER_CODE'); ?>" tabindex="4" id="otpcode" name="otpcode" maxlength="4">
            <button class="btnStyle1" type="button" id="continue-btn"><?php echo JText::_('CONTINUE'); ?></button>
            <div class="error_tooltip manual_tooltip_error" id="error_otpcode"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="form-group text-center">
        <p id="resend-link-show-mobile" style="display: none;" class="send_msg">We have sent the verification code again</p>
        <a href="javascript:void(0);" id="mobileVerificationModal-resend-link"><?php echo JText::_('RESEND_CODE'); ?></a>
    </div>
    </div>

    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--mobile_verify Model-->
    <?php
}
?>

<script>
    $(document).ready(function () {
        $("[verify-now]").on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            if($(this).attr("verify-now") == "EMAIL") {
                if($("#emailVerify-btn").length > 0 )
                    $("#emailVerify-btn").trigger("click");
                else
                    window.location = $(this).attr("href");
            }
            else if($(this).attr("verify-now") == "MOBILE") {
                if($("#mobileVerify-btn").length > 0 )
                    $("#mobileVerify-btn").trigger("click");
                else
                    window.location = $(this).attr("href");
            }
        });
    });
</script>

<?php
Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/email_mobile_verify.js");
?>
