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
            <ul class="refer_friend_menu">
                <li class="active"><a href="<?php echo Redirection::WITHDRAWAL_PROCESS; ?>"><?php echo JText::_("WITHDRAWL_CASH");?></a></li>
              <?php
                if(!isset($this->fistDeposited)) {
                    ?>
                    <li><a href="<?php echo Redirection::WITHDRAWAL_REQUEST; ?>"><?php echo JText::_("VIEW_STATUS");?></a></li>
                    <?php
                }
                ?>
        	<li ><a href="<?php echo Redirection::CASHIER_HELP; ?>"><?php echo JText::_("CASHIER_HELP");?></a></li>
            </ul>
        </div>

        <div class="withdrawl_static_page">
            <?php if(isset($this->fistDeposited)) {
                ?>
                <div class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/withdraw_add_cash_icon.png"></div>
                <div class="text">
                    <span class="title"><?php echo JText::_("ADD_MONEY_WALET");?></span>
                    <p><?php echo JText::_("WITH_MSG_17");?></p>
                    <a href="javascript:void(0);" add_cash="true" class="brown_bg"><?php echo JText::_("BETTING_ADD_CASH");?></a>
                </div>
                <?php
            }
            else {
                ?>
                <div class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/withdraw_insuffi_cash_icon.png"></div>
                <div class="text insufficient_cash">
                    <span class="title"><?php echo JText::_("INSUFF_WITH_BAL");?></span>
                    <p><?php JText::_("WITH_LINE_1");?></p>
                    <p><?php echo JText::_("WITH_PLAY_MORE_LINE_2");?><a href="<?php echo Redirection::CASHIER_HELP; ?>"><?php echo JText::_("WITH_LINE_LINK_HERE")?></a>.</p>
                    <p><?php echo JText::_("WISIT_US_LINE_3");?><a href="mailto:support@khelplayrummy.com">support@khelplayrummy.com</a> <?php echo JText::_("BETTING_OR_USE");?> <a href="javascript:void(0);" onclick="openLiveChat();"><?php echo JText::_("LIVE_CHAT");?></a> <?php echo JText::_("CHAT_ONLINE")?></p>
                </div>
                <?php
            }?>
        </div>
    </div>
</div>
