<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$fp_form_count = Session::getSessionVariable('FP_WIDGET_COUNT');
if ($fp_form_count === false) {
    $fp_form_count = 1;
} else {
    $fp_form_count++;
}
Session::setSessionVariable('FP_WIDGET_COUNT', $fp_form_count);
$mobile_min_length = Constants::MOBILE_MIN_LENGTH;
$mobile_max_length = Constants::MOBILE_MAX_LENGTH;
?>
<?php
    if ($params->get('enablePopup') == 1)
    {
        ?>
            <div class="modal fade <?php echo $params->get('modalClass'); ?>" id="<?php echo $params->get('modalId'); ?>">
                    <div class="modal-dialog modal-lg" style="">
                        <div class="modal-content">

                            <div class="modal-body"><div class="modal-box">
                                    <!--Left Side Banner-->
                                    <div class="modal-left-banner" style="background-image: url('images/site-misc/login-bg.jpg');">
                                        <img src="images/site-misc/gonzo-smobile.png">
                                    </div>
                                    <!--Right Side Banner-->
                                    <div class="modal-right-signup">
                                        <button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">×</button>

                                        <!--Start Forgot Password Form Container-->
                                        <div class="login-form" style="">
                                            <h2 class="h2-title">Forgot Password?</h2>
                                            <form action="#" method="post" id="forgot-password-form-<?php echo $fp_form_count; ?>" class="<?php echo $params->get('formtagclass'); ?>" submit-type="ajax" validation-style="<?php echo $params->get('validationstyle') ?>" home-forgot-modal="true" tooltip-mode="manual">
                                                <div class="input-group-row">
                                                    <div class="input-group-left"><span>+91</span></div>
                                                    <input type="tel" class="input-control <?php echo $params->get('emailtagclass'); ?> mobile allow_only_nums formControl change_maxlength" id="forgot_username" autocomplete="off" prefix="prefix" name="forgot_username" placeholder='<?php echo trim($params->get('v')) != "" ? trim($params->get('usernameplaceholder')) : "Mobile Number*" ?>' maxlength="<?php echo $mobile_max_length; ?>" pattern="<?php echo $mobile_pattern; ?>">
                                                    <div class="error_tooltip manual_tooltip_error" id="error_forgot_username"></div>
                                                </div>



                                                <div class="helping-text text-center" style="">
                                                    <button type="submit" class="<?php echo $params->get('submitbtnclass'); ?> proceed-button primary-gradient mt15"><?php print_r((trim($params->get('submitlabel')) != "") ? trim($params->get('submitlabel')) : 'Submit'); ?></button>
                                                </div>

                                                <input type="hidden" name="callFrom" id="callFrom" value="LANDINGPAGE"/>
                                            </form>
                                        </div>
                                        <!--End Forgot Password Form Container-->

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
            </div>
        <?php
    }

    if ($params->get('enablePopup') == 1)
    {
        ?>
            <div class="modal fade" id="home_forgot_thank">
                <div class="modal-dialog modal-lg" style="">
                    <div class="modal-content">

                        <div class="modal-body"><div class="modal-box">
                                <!--Left Side Banner-->
                                <div class="modal-left-banner" style="background-image: url('images/site-misc/login-bg.jpg');">
                                    <img src="images/site-misc/gonzo-smobile.png">
                                </div>
                                <!--Right Side Banner-->
                                <div class="modal-right-signup">
                                    <button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">×</button>

                                    <!--Start Forgot Password Form Container-->
                                    <div class="login-form" style="">
                                        <h2 class="h2-title">Forgot Password?</h2>
                                        <form action="#" method="post" id="forgot-confirm-form-<?php echo $fp_form_count; ?>" class="<?php echo $params->get('formtagclass'); ?>" submit-type="ajax" validation-style="<?php echo $params->get('validationstyle') ?>" home-forgot-modal="true" tooltip-mode="manual">

                                            <div class="input-group-row">
                                                <div class="input-group-left"><span></span></div>
                                                <input type="password" class="" maxlength="16" id="forgot_newpassword" name="forgot_newpassword" placeholder="<?php echo JText::_('BETTING_NEW_PASSWORD') ?>">
                                                <div class="error_tooltip manual_tooltip_error" id="error_forgot_newpassword"></div>
                                            </div>

                                            <div class="input-group-row">
                                                <div class="input-group-left"><span></span></div>
                                                <input type="password" class="" maxlength="16" id="forgot_confirmpassword" name="forgot_confirmpassword" placeholder="<?php echo JText::_('BETTING_CONFIRM_PASSWORD') ?>">
                                                <div class="error_tooltip manual_tooltip_error" id="error_forgot_confirmpassword"></div>
                                            </div>

                                            <div class="input-group-row">
                                                <div class="input-group-left"><span></span></div>
                                                <input type="tel" class="allow_only_nums" maxlength="4" id="forgot_otp" name="forgot_otp" placeholder="<?php echo JText::_('BETTING_OTP') ?> ">
                                                <div class="error_tooltip manual_tooltip_error" id="error_forgot_otp"></div>
                                            </div>

                                            <div class="helping-text text-center" style="">
                                                <button type="submit" class="<?php echo $params->get('submitbtnclass'); ?> btn"><?php print_r((trim($params->get('submitlabel')) != "") ? trim($params->get('submitlabel')) : 'Submit'); ?></button>
                                            </div>

                                        </form>
                                    </div>
                                    <!--End Forgot Password Form Container-->

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
    ?>

<script>
    var forgotpassword_action = <?php echo json_encode(JRoute::_('index.php/component/betting/?task=forgotpassword.forgotPassword')); ?>;
</script>


