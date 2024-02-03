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
            <div class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/withdraw_doc_ver_pending_icon.png"></div>
            <div class="text insufficient_cash">
                <span class="title"><?php echo JText::_("DOC_VERIFY_PENDING");?></span>
                <p><?php echo JText::_("WITHDRAWL_VERIFY_PENDING_MSG");?><a href="javascript:void(0);" onclick="openLiveChat();"><?php echo JText::_("SUPPORT_TEAM");?></a></p>
            </div>
        </div>
    </div>
</div>