<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
list($msec,$sec) = explode(" ", microtime());
$msec = str_replace("0.","",$msec);
$loginToken = str_pad($sec.$msec,18,"0");
$loginToken = md5($loginToken);
$fp_link_href = '';
if($params->get('forgot_password') == 1 && $params->get('contenttype') == "static")
    $fp_link_href = trim(JFactory::getApplication()->getMenu()->getItem($menu_id)->route);
$modal_var = md5($loginToken);
$login_form_count = Session::getSessionVariable('LOGIN_WIDGET_COUNT');
if($login_form_count === false ){
    $login_form_count = 1;
}else {
    $login_form_count++;
}
$currentURl = JUri::current();
if (strpos($currentURl, '/register') !== false) {
    $loginurl = $submiturl;
}else{
     $loginurl = $currentURl;
}
Session::setSessionVariable('LOGIN_WIDGET_COUNT', $login_form_count);
$forgotModal = $params->get('forgotpasswordmodal');
//exit(json_encode($fp_link_href));
?>
<?php
if($params->get('enablePopup') == 1){
?>
    <div class="modal fade <?php echo $params->get('modalClass'); ?>" id="<?php echo $params->get('modalId'); ?>">
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

                            <!--Start Login Form Container-->

                            <div class="login-form" style="">
                                <h2 class="h2-title">Login</h2>
                                <form action="#" method="post" id="login-form-<?php echo $login_form_count;?>" class="loginForm" submit-type="ajax" validation-style="bottom" tooltip-mode="manual" error-callback="topLoginBox" >
                                    <div class="input-group-row">
                                        <div class="input-group-left"><span>+91</span></div>
                                        <input type="tel" class="<?php echo $params->get('mobiletagclass'); ?> mobile formControl change_maxlength input-control allow_only_nums" id="userName_email" autocomplete="off" prefix="prefix" name="userName_email" placeholder='<?php echo trim($params->get('v')) != "" ? trim($params->get('usernameplaceholder')) : "Mobile Number*" ?>' maxlength="10" >
                                        <div class="error_tooltip manual_tooltip_error" id="error_userName_email"></div>
                                    </div>


                                    <div class="input-group-row">
                                        <div class="input-group-left">
                                            <img src="images/pages/password-icon.png" alt="Password Icon">
                                        </div>
                                        <input type="password" id="password" name="password" maxlength="16" placeholder='<?php echo trim($params->get('passwordplaceholder')) != "" ? trim($params->get('passwordplaceholder')) : "Password " ?>' class="custome_input input-control" prefix="prefix">
                                        <button class="password-show-hide toggleTypeLogin">
                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                        </button>
                                        <div class="error_tooltip manual_tooltip_error" id="error_password"></div>
                                    </div>

                                    <div class="passwordError"></div>
                                    <div class="helping-text text-center" style="">
                                        <p class="text-end"><a data-bs-dismiss="modal" data-bs-target="#<?php echo $forgotModal; ?>" data-bs-toggle="modal" class="link forgotLink"><?php echo $params->get('forgotpasswordlabel');?></a></p>
                                        <button type="submit" class="proceed-button primary-gradient mt15 loginClass"><span><?php print_r((trim($params->get('submitlabel')) != "") ? trim($params->get('submitlabel')) : 'LOGIN'); ?></span></button>
                                    </div>
                                    <div class="new-account-link">
                                        <span>Not a member? <a href='/register' class="registeClass"><span>Register Now</span></a>
                                        </span>
                                    </div>

                                    <input type="hidden" name="loginToken" id="loginToken" value="<?php echo trim($loginToken);?>"/>
                                    <input type="hidden" name="login_ajaxCall" id="login_ajaxCall" value=""/>
                                    <input type="hidden" name="callFrom" id="callFrom" value="LANDINGPAGE"/>
                                    <?php if(isset($submiturl)) {?>
                                        <input type="hidden" id="submiturl" name="submiturl" value="<?php echo $loginurl; ?>"/>
                                    <?php } ?>

                                </form>
                            </div>
                            <!--End Login Form Container-->

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

<?php } ?>
<script>
    var login_action = <?php echo json_encode(JRoute::_('index.php/component/Betting/?task=authorisation.playerLogin')); ?>;
    var loginToken = $("#loginToken").val();
</script>

<?php /*
if($params->get('forgot_password') == 1 && $params->get('contenttype') == "widget") { ?>
	<div class="modal fade" id="<?php echo $modal_var?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
				</div>
				<div class="modal-body">
					<?php
					$modules = JModuleHelper::getModules($params->get('contenttype_widget'));
					foreach($modules as $module)
					{
						echo JModuleHelper::renderModule($module);
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php } */ ?>
<?php /* if($forgot_password == 1) {?>
	<a <?php if($params->get('contenttype') == "static") echo "href='".trim(JFactory::getApplication()->getMenu()->getItem($params->get('contenttype_static'))->route)."'"; else {echo 'data-toggle="modal" data-target="#'.$modal_var.'" href="#"';}?> class="<?php echo $params->get('forgotpasswordclass');?>"><?php echo $params->get('forgotpasswordlabel');?></a>
<?php } */ ?>
