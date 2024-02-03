<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::REFER_A_FRIEND; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section" id="thankyou-div">
    <div class="">
        <div class="heading page-header">
            <h1><?php echo JText::_("INVITE_THANK_YOU");?></h1>
        </div>

        <div class="withdrawl_static_page text-center">
            <div class="icon" style="display: inline-block;"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/refer_friend/refer_friend_email_sent_icon.png"></div>
            <div class="text">
                <?php sprintf(JText::_("INVITE_SUCCESS_MESSAGE"),Redirection::REFER_A_FRIEND_TRACK_BONUS);?>
                <a href="<?php echo Redirection::REFER_A_FRIEND; ?>"  class="btn"  style="padding: 8px 25px;"><?php echo JText::_("INVITE_MORE_FRIEND");?></a>
            </div>
        </div>
    </div>
</div>