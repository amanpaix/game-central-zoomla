<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$playerInfo = Utilities::getPlayerLoginResponse();
$avatarImagesArr = Utilities::getAvatarImages($playerInfo->playerId, $playerInfo->avatarPath);
//$playerImage = $playerInfo->commonContentPath.$playerInfo->avatarPath."?v=".microtime();

//$playerArr = explode("StaticContent.war", $playerInfo->avatarPath);
//$playerImage = Configuration::AVATAR_DOMAIN . '/StaticContent.war' .$playerArr[1]."?v=".microtime();

if( str_contains($playerInfo->avatarPath,"edit-thumbnai.jpg") ){
    $playerImage = Configuration::AVATAR_DOMAIN . 'StaticContent.war/commonContent/trinity/playerImages/edit-thumbnai.jpg';
}
else
{
    $playerArr = explode("StaticContent.war", $playerInfo->avatarPath);
    $playerImage = Configuration::AVATAR_DOMAIN . '/StaticContent.war' .$playerArr[1]."?v=".microtime();
}

$avatars = '';
foreach($avatarImagesArr AS $imageName) {
    $avatars .= '<li><label><input type="radio" name="avtar" id="'.substr($imageName, strrpos($imageName, "/")+1).'"><span><img src="'.$imageName.'" ></span><span class="check"></span></label></li>';
}
?>
<div class="edit_avtar">
<div class="entry-header has-post-format">
    <h2><?php echo JText::_('BETTING_UPDATE_AVATAR');?></h2>
    </div>
    <form action="<?php echo JRoute::_('index.php?task=account.uploadPlayerAvatar'); ?>" method="post" enctype="multipart/form-data" name="player-avatar-form" id="player-avatar-form">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 selected_avtar">
                <div class="user_avtar">
                    <span class="helper">&nbsp;</span>
                    <img src="<?php echo $playerImage; ?>" class="preview_img">
                </div>

                <div class="upload_pic_actbtn" style="display: none;">
                    <div class="button_holder">
                        <a href="javascript:void(0);" style="display: none;" id="avatar_saving_btn" class="saving_btn"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/avatar/saving.gif"> <?php echo JText::_("BETTING_SAVINGS");?></a>
                        <div  style="display: none;" id="avatar_saved_btn" class="saved_btn"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/avatar/avatar_save_icon.png"> <?php echo JText::_("BETTING_SAVED");?></div>
                        <div class="divider col-md-12 col-xs-12 col-sm-12">&nbsp;</div>
                    </div>
                </div>
                <div class="upload_pic">
                    <br><?php echo JText::_("BETTING_EDIT_AVATAR_OWN_TITLE");?>
                    <div class="button_holder">
                        <button type="button" id="cancel_edit_avatar" class="btn btn_next btnStyle1" tabindex="14"><?php echo JText::_("BETTING_CANCEL"); ?></button>
                        <a href="javascript:void(0);" id="upload_btn" class="btn upload_btn"><i class="fa fa-check"></i> <?php echo JText::_("BETTING_EDIT_AVATAR_UPLOAD");?></a>
                        <a href="javascript:void(0);" style="display: none;" id="save_button" class="save_btn btn outlineBtn"><?php echo JText::_('SAVE');?></a>
                        <div class="clear"></div>
                    </div>
                </div>

                <input type="file" accept="image/*" id="user_avatar" name="user_avatar" style="display: none">
                <input type="hidden" name="selected_avatar" id="selected_avatar" value="">

<!--                <div class="upload_pic">-->
<!--                    <button style="display: none;" type="submit" class="save_btn done btnStyle1" id="save_button"><i class="fa fa-check"></i> Save</button>-->
<!--                    <a style="display: none;" id="avatar_saved_btn" href="javascript:;" class="saved_btn"><img src="/templates/shaper_helix3/images/my_account/avatar/avatar_save_icon.png"> Saved!</a>-->
<!--                    <a style="display: none;" id="avatar_saving_btn" href="javascript:;" class="saved_btn"><i class="fa fa-upload" aria-hidden="true"></i> Saving...</a>-->
<!---->
<!--                    <div class="divider col-md-12 col-xs-12 col-sm-12">&nbsp;</div>-->
<!--                    <br>Want to upload your own picture?<br><br>-->
<!--                    <a href="javascript:;" class="upload_btn" id="upload_btn"><i class="fa fa-check"></i> Upload</a>-->
<!--                    <input type="file" accept="image/*" id="user_avatar" name="user_avatar" style="display: none">-->
<!--                    <input type="hidden" name="selected_avatar" id="selected_avatar" value="">-->
<!--                    <div class="clear"></div>-->
<!--                </div>-->



            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 avtar_list">
                <ul id="avatar_list">
                    <?php echo $avatars; ?>
                </ul>
            </div>
        </div>
    </form>
</div>

<?php Html::addJs(JUri::base()."templates/shaper_helixultimate/js/core/upload-avatar.js?v=".Constants::JS_VER); ?>
<script>
  $(document).ready(function () {
  $("#cancel_edit_avatar").click(function () {
      location.href="/my-profile";
    }); 
    });
</script>
