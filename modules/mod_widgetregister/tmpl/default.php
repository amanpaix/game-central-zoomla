<?php if($params->get('enableRecapcha') == 1) {
 $lang = explode("-", JFactory::getLanguage()->getTag())[0];
 ?>
<script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang?>" async defer></script>
<?php  
}
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$reg_form_count = Session::getSessionVariable('REG_WIDGET_COUNT');
JHtml::_('script', 'system/core.js', false, true);
if ($reg_form_count === false) {
    $reg_form_count = 1;
} else {
    $reg_form_count++;
}
$mobile_min_length = Constants::MOBILE_MIN_LENGTH;
$mobile_max_length = Constants::MOBILE_MAX_LENGTH;
$mobile_pattern = Constants::MOBILE_PATTERN;
$passwordPattern = true;
if($passwordPattern){
    $password_pattern = '^[0-9]{5}$';
}
$password_length = 16;
Session::setSessionVariable('REG_WIDGET_COUNT', $reg_form_count);
$currencyList = Utilities::getCurrencyList();
foreach ($currencyList as $key => $value) {
    if( $value['enable'] ){
        $currencyListEnabled[$key] =  $value;
    }
}
//$currencyList = Constants::$currencyMap;
?>


    <div class="register-form-wrap">

        <div class="register-form" style="">
            <h2 class="h2-title">Register</h2>
            <form action="#" method="post" id="registration-form-<?php echo $reg_form_count; ?>" class="<?php echo $params->get('formtagclass'); ?> formStyle" submit-type="ajax" validation-style="<?php echo $params->get('validationstyle') ?>" tooltip-mode="manual" novalidate="novalidate" >
                <div class="input-group-row">
                    <div class="input-group-left"><span>+91</span></div>
                    <input type="tel" class="<?php echo $params->get('mobiletagclass'); ?> mobile allow_only_nums formControl input-control" id="mobile" autocomplete="off" prefix="prefix" name="mobile" placeholder='<?php echo trim($params->get('mobileplaceholder')) != "" ? trim($params->get('mobileplaceholder')) : "Mobile No. " ?>' maxlength="<?php echo $mobile_max_length; ?>" pattern="<?php echo $mobile_pattern; ?>">
                    <div class="error_tooltip manual_tooltip_error " style="display: none;" id="error_mobile"></div>
                </div>

                <div class="input-group-row">
                    <div class="input-group-left"><img src="/images/pages/password-icon.png" alt="Password Icon"></div>
                    <input type="text" class="username formControl input-control" id="disp_name" autocomplete="off" prefix="prefix" name="disp_name" placeholder='Display Name' maxlength="10" >
                    <div class="error_tooltip manual_tooltip_error " style="display: none;" id="error_disp_name"></div>
                </div>

                <div class="input-group-row">
                    <div class="input-group-left"><img src="/images/pages/password-icon.png" alt="Password Icon"></div>
                    <input type="password" class="<?php echo $params->get('passwordtagclass'); ?> formControl input-control" id="reg_password" autocomplete="off"  name="reg_password" placeholder='Password'  maxlength="<?php echo $password_length; ?>">
                    <div class="error_tooltip manual_tooltip_error " style="display: none;" id="error_reg_password"></div>
                </div>

                <div class="input-group-row">
                    <div class="input-group-left">
                        <img src="/images/pages/password-icon.png" alt="Confirm Password Icon">
                    </div>
                    <input type="password" class="<?php echo $params->get('confirmpasswordtagclass'); ?> formControl input-control" id="confirm_password" autocomplete="off"  name="confirm_password" placeholder='Confirm Password' maxlength="<?php echo $password_length; ?>">
                    <button class="password-show-hide toggleTypeReg">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                    <div class="error_tooltip manual_tooltip_error" id="error_confirm_password"></div>
                </div>

                <div class="g-recaptcha-wrap mb-4">
                    <?php if ($params->get('enableRecapcha') == 1) { ?>

                    <div  class="error_tooltip manual_tooltip_error" id="error_enableRecapcha"></div>
                    <div class="g-recaptcha" style = "margin-top:20px;" data-sitekey="6LdwQQYkAAAAAFB3DOm898qxqi537z6KIMcLOsuP">
                    </div>
                    <?php } ?>
                </div>
                <div class="registration-helping-text">
                    <p>By creating an Account and playing any Games, you confirm that you are above 18 years of age and that you have read, understood and agree to be fully bound by the <a href="javascript:void(0);">terms and conditions.</a></p>
                    <button type="submit" id="submit-btn" class="proceed-button primary-gradient mt15 <?php echo $params->get('submitbtnclass'); ?>"><?php print_r((trim($params->get('submitlabel')) != "") ? trim($params->get('submitlabel')) : 'Join Now'); ?></button>
                </div>
                <div class="new-account-link">
                    <span>Have an account? <a data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#home_login">Login</a></span>
                </div>
                    <input type="hidden" name="registrationType" id="registrationType" value="<?php echo ($params->get('registrationType') == 1) ? 'FULL' : 'MINI'; ?>"/>
                    <?php if (isset($submiturl)) { ?>
                        <input type="hidden" class="submiturl" name="submiturl" value="/"/>
                        <input type="hidden" name="otp_enable" id="otp_enable" value="<?php echo $otp_enable; ?>"/>
                    <?php } ?>
            </form>
        </div>


</div>

    <?php
    if( $otp_enable ){
        ?>
        <div class="modal fade" id="home_register_popup-otp">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-box">
                            <!--Left Side Banner-->
                            <div class="modal-left-banner" style="background-image: url('images/site-misc/login-bg.jpg');">
                                <img src="images/site-misc/gonzo-smobile.png">
                            </div>
                            <!--Right Side Banner-->
                            <div class="modal-right-signup">
                                <button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">×</button>

                                <!--Start Registration OTP Form Container-->

                                <div class="verify-form">
                                    <h2 class="h2-title">Verify OTP</h2>
                                    <form action="#" method="post" id="otp-registration-form-<?php echo $reg_form_count; ?>" class="formStyle  loginForm" submit-type="ajax" validation-style="left" tooltip-mode="manual" novalidate="novalidate">
                                        <div class="verify-helping-text">
                                            <p>We have sent the code verification to your mobile number</p>
                                            <span class="number-span">+91 - <span class="sent_mobile"></span>
<!--                                                <button class="number-edit-icon"><i class="fa fa-fw fa-pencil"></i></button>-->
                                            </span>
                                        </div>
                                        <div class="verify-input-group">
                                            <input type="text" maxlength1="1" class="allow_only_nums input-control verify-input" data-offset="1" name="otp_confirm_1" pattern="\d*" id="otp_confirm_1" autocomplete="off">
                                            <input type="text" maxlength1="1" class="allow_only_nums input-control verify-input"  data-offset="2" name="otp_confirm_2" pattern="\d*" id="otp_confirm_2" autocomplete="off">
                                            <input type="text" maxlength1="1" class="allow_only_nums input-control verify-input"  data-offset="3" name="otp_confirm_3" pattern="\d*" id="otp_confirm_3" autocomplete="off">
                                            <input type="text" maxlength1="1" class="allow_only_nums input-control verify-input"  data-offset="4" name="otp_confirm_4" pattern="\d*" id="otp_confirm_4" autocomplete="off">
                                        </div>
                                        <div class="verify-input-group-error" style="display: none;font-size: 12px;color: red;">

                                        </div>

                                        <div class="verify-button">
                                            <button type="submit" class="btnDefault btn" id="otp-submit" onclick="/*verifyOtp();*/">Verify Me</button>
                                        </div>
                                        <div class="msg sent-msg text-center mt-2" id="resendMsg" style="font-size: 12px;color: green;"></div>
                                        <div class="resendOtpLink text-right mt-2" onclick="resendRegOtp();">
                                            <a href="#">Resend OTP Code </a>
                                        </div>

                                    </form>
                                <!--End Registration OTP Form Container-->

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
    var registration_action = <?php echo json_encode(JRoute::_('index.php/component/Betting/?task=registration.playerRegistration')); ?>;
    var is3FieldReg = <?php echo trim($params->get('threeFieldReg')) ?>;
    mobile_min_length = <?php echo $mobile_min_length; ?>;
    mobile_max_length = <?php echo $mobile_max_length; ?>;
    mobile_pattern = /<?php echo $mobile_pattern; ?>/;
    if (is3FieldReg) {
        $($("#reg_password").parent().detach()).insertAfter($("#email").parent());
        var $inputs = $('#email');
        $inputs.on('keyup keypress blur', function () {
            $('#userName').val($(this).val());
        });
    }
</script>
