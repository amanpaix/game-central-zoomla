<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>
<div class="profile_details change_password">
     <div class="entry-header has-post-format">
         <h2><?php echo JText::_('BETTING_CHANGE_PASSWORD');?></h2>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <form action="#" method="post" id="change-password-form" validation-style="bottom" submit-type="ajax" tooltip-mode="manual">
                <br/>
                <div class="form_item_holder reg_password">
                    <!-- <label><?php echo JText::_("BETTING_CHANGE_PASSWORD_OLD_TEXT");?><span class="req_star">*</span></label> -->
                    <input type="password" tabindex="1" class="custome_input" id="currentPassword" name="currentPassword" placeholder="<?php echo JText::_("BETTING_CURRENT_PASSWORD");?>" maxlength="16">
                    <div class="error_tooltip manual_tooltip_error" id="error_currentPassword"></div>
                    <div class="clear"></div>
                </div>
                <div class="form_item_holder reg_password">
                    <!-- <label><?php echo JText::_("BETTING_CHANGE_PASSWORD_NEW_TEXT");?><span class="req_star">*</span></label> -->
                    <input type="password" tabindex="2" class="custome_input" id="newPassword" name="newPassword" placeholder="<?php echo JText::_('BETTING_NEW_PASSWORD');?>" maxlength="16">
                    <div class="error_tooltip manual_tooltip_error" id="error_newPassword"></div>
                    <div class="clear"></div>
                </div>
                <div class="form_item_holder reg_password">
                    <!-- <label><?php echo JText::_("BETTING_CHANGE_PASSWORD_REENTER_NEW_TEXT");?><span class="req_star">*</span></label> -->
                    <input type="password" tabindex="2" class="custome_input" id="retypePassword" name="retypePassword" placeholder="<?php echo JText::_('WAVER_RETYPE_NEW_PASSWORD')?>" maxlength="16">
                    <div class="error_tooltip manual_tooltip_error" id="error_retypePassword"></div>
                    <div class="clear"></div>
                </div>
                <div class="form-group last button_holder">
                    <button type="button" id="cancel_change_password" class="btn btn_next btnStyle1" tabindex="14"><?php echo JText::_("BETTING_CANCEL"); ?></button>
                    <button class="btn btn_next" type="submit" id="submit" tabindex="14"><?php echo JText::_("BETTING_CHANGE_PASSWORD_BUTTON_TEXT");?></button>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
        </div>
    </div>
</div>
<?php
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/change-password.js?v=".Constants::JS_VER);
?>

<script>
    var change_password_action = <?php //echo json_encode(JRoute::_('index.php?task=account.changePassword')); ?>
    
      $(document).ready(function () {
      $("#cancel_change_password").click(function () {
      location.hash="";
    }); 
    });
    
</script>
