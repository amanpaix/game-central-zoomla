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
Session::setSessionVariable('LOGIN_WIDGET_COUNT', $login_form_count);
//exit(json_encode($fp_link_href));
?>

<form action="#" method="post" id="login-form-<?php echo $login_form_count;?>" class="<?php echo $params->get('formtagclass'); ?> loginForm" submit-type="ajax" validation-style="<?php echo $params->get('validationstyle') ?>" tooltip-mode="bootstrap"  >
    <fieldset>
        
        <?php if(isset($formLegend) && $formLegend != ''){ ?>
        <legend class="formLegend"><span><?php echo $params->get('formlegend'); ?></span></legend>
        <?php } ?>
        
	<div class="form_item_holder userId">
		<?php if(trim($params->get('usernamelabel')) != "") {?>
			<label for="userName_email"><?php echo $params->get('usernamelabel');?></label>
		<?php } ?>
		<input type="text" id="userName_email" name="userName_email" placeholder='<?php echo trim($params->get('usernameplaceholder')) != "" ? trim($params->get('usernameplaceholder')) : "Username/Email " ?>'>
                <div class="usernameError"></div>
	</div>
        
	<div class="form_item_holder password">
		<?php if(trim($params->get('passwordlabel')) != "") {?>
			<label for="password"><?php echo $params->get('passwordlabel');?></label>
		<?php } ?>
		<input type="password" id="password" name="password" placeholder='<?php echo trim($params->get('passwordplaceholder')) != "" ? trim($params->get('passwordplaceholder')) : "Password " ?>'>
                <div class="passwordError"></div>
        </div>
        
	<div class="button_holder">
            <button type="submit" class="loginClass"><span><?php print_r((trim($params->get('submitlabel')) != "") ? trim($params->get('submitlabel')) : 'Log-in'); ?></span></button>
	</div>   
        <input type="hidden" name="loginToken" id="loginToken" value="<?php echo trim($loginToken);?>"/>
	<input type="hidden" name="login_ajaxCall" id="login_ajaxCall" value=""/>
	<input type="hidden" name="callFrom" id="callFrom" value="LANDINGPAGE"/>
	<?php if(isset($submiturl)) {?>
		<input type="hidden" id="submiturl" name="submiturl" value="<?php echo $submiturl; ?>"/>
	<?php } ?>
        <?php if($enableRegister == 1)
            {
                if ( $registerType )
                {
                    $registermodal = $params->get('registermodal');
                    ?>
                    <a data-dismiss="modal" data-target="#<?php echo $registermodal; ?>" data-toggle="modal" class="green_bg"><?php echo $params->get('registerlabel');?></a>
                    <?php
                }
                else
                {
                    ?>
                    <a href='<?php echo $registerurl; ?>' class="registeClass"><span><?php echo $params->get('registerlabel');?></span></a>
                    <?php
                }
                    ?>
        <?php
            }
        ?>
        <?php if($forgot_password == 1){ ?>
                <a href='<?php echo $forgoturl; ?>' class="forgotpasswordclass"><span><?php echo $params->get('forgotpasswordlabel');?></span></a>
                <div class="forgotPassworddesc"><?php echo $params->get('forgotpassworddesc'); ?></div>
        <?php } ?>   
        <div class="genericError"></div>
    </fieldset>
</form>
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
