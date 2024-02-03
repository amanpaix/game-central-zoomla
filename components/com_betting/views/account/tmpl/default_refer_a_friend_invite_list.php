<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
if(count($this->contacts_list) <= 0)
{
    Redirection::to(Redirection::REFER_A_FRIEND);
}
$inviteFriendURL = JRoute::_('index.php?task=referafriend.inviteFriend');
Html::addCss(JUri::base()."templates/shaper_helix3/css/jquery.scrollbar.css");
?>
<script>

    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::REFER_A_FRIEND; ?>']").parent().addClass('active');

</script>
<div class="myaccount_body_section">
    <div class="">
        <div class="entry-header has-post-format">
            <h2><?php echo JText::_("INVITE_FRIENDS_TITLE");?></h2>
        </div>

        <div class="refer_friend mtop20">
            <div class="transaction_table">
                <div class="top_section">
                    <p style="color:white;font-size:14px;"><?php sprintf(JText::_("INVITE_FRIENDS_TEXT"),count($this->contacts_list));?></p>
                    <div class="search_box">
                        <div class="input-group">
                            <input id="filter" type="text" class="custome_input" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </span>                            
                        </div>
                    </div>
                </div>
                <div class="scrollbar-outer">
                    <form name="invite-friend-form" id="invite-friend-form-email" action="<?php echo $inviteFriendURL; ?>" method="post" >
                    <table class="table" id="invite-list" data-filter="#filter" data-filter-text-only="true" data-page-size="<?php echo count($this->contacts_list);?>">
                        <thead>
                        <tr>
                            <th data-toggle="true"><label class="icheckbox_label"><input id="select-all" type="checkbox"><span class="chkIcon"></span><?php //echo JText::_("SELECT_ALL");?></label></th>
                            <th><?php echo JText::_("NAME");?></th>
                            <th data-hide="phone"><?php echo JText::_("EMAIL_ID");?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($this->contacts_list as $contacts) {
                            ?>
                            <tr>
                                <td><label class="icheckbox_label"><input type="checkbox"><span class="chkIcon"></span></label></td>
                                <td referName="name"><?php echo $contacts['name']; ?></td>
                                <td referEmail="email"><?php echo $contacts['email'];?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                        </form> 
                </div>
                <div class="action_btn">
                    <a class="disabled btn" tabindex="13" style="cursor: pointer;" id="inviteFriendNowEmail"  onclick="inviteFriendNowEmail();"><span><?php echo JText::_("INVITE_FRIEND_NOW")?></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
Html::addJs(JUri::base()."templates/shaper_helix3/js/bootstrap.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/custome.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/isotope.pkgd.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.scrollbar.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.sticky.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/main.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/placeholder.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/slick.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/smk-accordion.js");

Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/refer_a_friend.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/bootstrap.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/custome.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/isotope.pkgd.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.scrollbar.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.sticky.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/main.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/placeholder.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/slick.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/smk-accordion.js");
?>

