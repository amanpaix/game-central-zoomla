<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
Html::addCss(JUri::base()."templates/shaper_helix3/css/jquery.scrollbar.css");
?>
<div id="no_invitations" style="display: none;">
    <div class="withdrawl_static_page text-center">
        <div class="icon" style="display: inline-block;"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/refer_friend/refer_friend_invite_friends_icon.png"></div>
        <div class="text ptop40">
            <p></p>
            <a href="<?php echo Redirection::REFER_A_FRIEND; ?>" class="btn" style="padding: 8px 25px;"><?php echo JText::_("BETTING_INVITE_NOW");?></a>
        </div>
    </div>
</div>
<div id="list_of_invitations" style="display: none;">
    <div class="refer_friend mtop20">
        <div class="transaction_table">
            <div class="top_section">
                <p style="margin-top: 25px; margin-bottom: 25px; text-align: center;"><?php echo JText::_("REFER_INVITE_REMIND_MSG");?></p>
                <div class="search_box">
                    <div class="input-group">
                        <input id="filter" type="text" class="custome_input no_special_chars" placeholder="<?php echo JText::_('SEARCH')?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </span>                        
                    </div>
                </div>
            </div>
            <div class="scrollbar-outer">
                <form action="<?php echo JRoute::_('index.php?task=referafriend.sendReminder');?>" method="post" name="send-reminder-form" id="send-reminder-form">
                    <table class="table" data-filter="#filter" data-filter-text-only="true" id="track-bonus-list">
                        <thead>
                        <tr>
                            <th data-toggle="true"><label class="icheckbox_label"><input type="checkbox" id="select-all"><span class="chkIcon"></span><!--<?php echo JText::_("SELECT_ALL");?></label>--></th>
                            <th><?php echo JText::_("REFER_USER_NAME");?></th>
                            <th><?php echo JText::_("REFER_DATE");?></th>
                            <th data-hide="phone"><?php echo JText::_("REGISTERED");?></th>
                            <th data-hide="phone"><?php echo JText::_("BETTING_ADD_CASH");?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <input type="hidden" name="reminderList" id="reminderList" />
                </form>
            </div>
            <div class="action_btn">
                <a class="btn disabled" tabindex="13" href="javascript:void(0);" id="send-reminder"><span><?php echo JText::_("SEND_REMINDER");?></span></a>
            </div>
        </div>
    </div>
</div>
