<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section">
    <div class="">
        <div class="heading">
            <h2><?php echo JText::_("WITHDRAWL_CASH");?></h2>
        </div>

        <div class="withdrawl_static_page">
            <div class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/withdraw_incomplete_profile_icon.png"></div>
            <div class="text">
                <span class="title"><?php echo JText::_("WITH_PROFIL_INCOMPLETE");?></span>
                <p><?php echo JText::_("WITH_MSG_16");?> <!--<a href="javascript:void(0);" onclick="//openLiveChat();">--><?php echo JText::_("SUPPORT_TEAM");?></a></p>
                <a href="<?php echo Redirection::MYACC_PROFILE; ?>" class="btnStyle1"><?php echo JText::_("COMPLETE_PROFILE_TILTE");?></a>
            </div>
        </div>
    </div>
</div>