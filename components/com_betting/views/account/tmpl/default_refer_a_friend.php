<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$inviteFriendURL = JRoute::_('index.php?task=referafriend.inviteFriend');
$gmailReferURL = JRoute::_('index.php?task=referafriend.getClient');
$facebookReferURL = JRoute::_('index.php?task=referafriend.facebookRefer&referThrough=FACEBOOK');
$yahooReferURL = JRoute::_('index.php?task=referafriend.yahooRefer');
$twitterReferURL = JRoute::_('index.php?task=referafriend.twitterRefer&referThrough=TWITTER');
$outlookReferURL = JRoute::_('index.php?task=referafriend.outlookRefer');
$playerInfo = Utilities::getPlayerLoginResponse();
$refer_now_class = "";
$refer_now_css = "style='display:none;'";
$track_bonus_class = "";
$track_bonus_css = "style='display:none;'";
$client_name = Constants::CLIENT_NAME;
if(isset($this->refer_a_friend_track_bonus)) {
    $track_bonus_class = "active";
    $track_bonus_css = 'style="display:block;"';
}
else {
    $refer_now_class = "active";
    $refer_now_css = 'style="display:block;"';
}
//var_dump(($this->referralLink));
?>
    <script>
        $("div.myaccount_topsection").find("a[href='<?php echo Redirection::REFER_A_FRIEND; ?>']").parent().addClass('active');
    </script>

    <div class="myaccount_body_section" id="main-div">
        <div class="">
            <div class="entry-header has-post-format">
                <h2 itemprop="name"><?php echo JText::_('REFER_NOW'); ?></h2>
            </div>
            <div class="heading">
                <ul id="url-tabs" class="refer_friend_menu tabNav">
                    <li class="<?php echo $refer_now_class; ?>"><a href="#refer-now"><?php echo JText::_("REFER_NOW")?></a></li>
                    <li class="<?php echo $track_bonus_class; ?>"><a href="#track-status"><?php echo JText::_("TRACK_STATUS");?></a></li>
                </ul>
            </div>


            <div div_id="track-status" <?php echo $track_bonus_css; ?>>
                <?php
                echo $this->loadTemplate('refer_a_friend_track_bonus');
                ?>
            </div>


            <div class="refer_friend" div_id="refer-now" <?php echo $refer_now_css; ?>>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center mbottom15 contentheading"><?php echo JText::_("REFER_OPTION_MESSSAGE")?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 refer_option">
                        <ul>
		                    <li class="link-gmail"><a href="#" onclick='openInPopuop("<?php echo $gmailReferURL; ?>");' ><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-gmail"></use></svg><span>Gmail</span></a></li>               
                                    <li class="link-facebook"><a href="#" onclick='openInPopuop("<?php echo $facebookReferURL; ?>");' ><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-facebook"></use></svg><span><?php echo JText::_('BETTING_FACEBOOK')?></span></a></li>
                                    <li class="link-twitter"><a href="#" onclick='openInPopuop("<?php echo $twitterReferURL; ?>");'><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-twitter"></use></svg><span><?php echo JText::_('BETTING_TWITTER')?></span></a></li>
<!--                            <li class="link-yahoo"><a href="#" onclick='openInPopuop("<?php //echo $yahooReferURL; ?>");'><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-yahoo"></use></svg><span>Yahoo</span></a></li>-->
<!--                            <li class="link-outlook"><a href="#" onclick='openInPopuop("<?php //echo $outlookReferURL; ?>");'><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-outlook"></use></svg><span>Outlook</span></a></li>-->
<!--                                    <li class="link-sms"><a href="#" data-toggle="modal" data-target="#refer_friend_sms"><svg class="svgicon"><use xlink:href="/images/misc/refer-spritesheet.svg#icon-sms"></use></svg><span><?php echo JText::_('BETTING_SMS')?></span></a></li>-->
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="divider col-md-12 col-xs-12 col-sm-12">&nbsp;</div>

                <?php
                if(isset($this->referralLink))
                {
                    ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 refer_link">

                        <span class="text-center mbottom15 contentheading"><?php echo JText::_("SHARE_LINK_TEXT");?></span>
                        <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 refer_code">
                            <div class="form-group">
                                <label for="email"><?php echo JText::_("REFER_CODE");?>:</label>
                                <div class="form_item_holder code formItemBtnGroup">
                                    <input type="text" placeholder="Share link" value="<?php echo Utilities::getPlayerLoginResponse()->referFriendCode; ?>" readonly class="custome_input referr_code_i">
                                    <button type="button" class="btn referr_code_b"><?php echo JText::_("SHARE_COPY");?></button>
                                </div>
                                <div id="referr_code"></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 referral_link">
                            <div class="form-group">
                                <label for="email"><?php echo JText::_("REFER_LINK");?>:</label>
                                <div class="form_item_holder link  formItemBtnGroup">
                                    <input type="text"  placeholder="Share link" value="<?php echo $this->referralLink.'?data='.Utilities::getPlayerLoginResponse()->referFriendCode; ?>" readonly class="custome_input my_share_link_i">
                                    <button type="button" class="btn my_share_link_b" ><?php echo JText::_("SHARE_COPY");?></button>
                                </div>
                                <div id="my_share_link"></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                            </div>
<div class="clear"></div>

                    </div>
                    <div class="divider col-md-12 col-xs-12 col-sm-12">&nbsp;</div>
                    <?php
                }
                ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 refer_link_manual">
                        <span class="text-center mbottom15 contentheading"><?php echo JText::_("REFER_ADD_FRIEND_MANUALLY")?></span>

                        <form name="invite-friend-form" id="invite-friend-form" action="<?php echo $inviteFriendURL; ?>" method="post" >
                        <fieldset>
                            <?php
                            for($i=1;$i<=5;$i++) {
                                ?>
                                <div class="row" row-id="<?php echo $i; ?>" <?php echo ($i==1)?'':'style="display:none;"';?>>
                                    <div class="col-md-5 col-sm-5 col-xs-9">
                                        <div class="form_item_holder userName">
                                            <label for="email"><?php echo JText::_("REFER_FRIENDS_NAME");?><span class="req_star">*</span></label>
                                            <div class="input-group">
                                                <input type="text" onfocusout="validateReferOnFocus('Name')" placeholder="<?php echo JText::_("REFER_FRIENDS_NAME");?>" class="custome_input dont_allow_nums" maxlength="25" id="name-<?php echo $i; ?>">
                                                <div class="error" id="error_name-<?php echo $i; ?>"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-9">
                                        <div class="form_item_holder email">
                                            <label for="email"><?php echo JText::_("BETTING_FORM_EMAIL_ADDR");?><span class="req_star">*</span></label>
                                            <div class="input-group">
                                                <input type="text" onfocusout="validateReferOnFocus('Email')" placeholder="<?php echo JText::_("FRIENDS_EMAIL_ADDR");?>" class="custome_input validate_email" maxlength="50" id="email-<?php echo $i; ?>">
                                                <div class="error" id="error_email-<?php echo $i; ?>"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-3 nopadding">
                                <span>
                                    <a href="javascript:void(0);" add-row="<?php echo $i; ?>" <?php echo ($i==1)?'':'style="visibility:hidden;"';?> class="add_friend btn btnStyle1">
                                        +<!-- <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/refer_friend/add_friend.png"> -->
                                    </a>
                                    <a href="javascript:void(0);" id="removeRow" <?php echo ($i==1)?'style="visibility:hidden;"':'';?>  remove-row="<?php echo $i; ?>" class="remove_friend btn btnStyle1">
                                        -<!-- <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/refer_friend/remove_friend.png"> -->
                                    </a>
                                </span>
                                    </div>

                                </div>
                                <?php
                            }
                            ?>
                            </fieldset>
                        </form>
                        <div class="row last">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <a class="btn" tabindex="13" id="invite-friend-btn" onclick="inviteFriendNow('EMAIL');" style="cursor: pointer;"><span><?php echo JText::_("INVITE_FRIEND_NOW");?></span></a>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center"><br><br>
                                <?php echo JText::sprintf( 'REFER_TEXT', $client_name );?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="modal fade" id="refer_friend_sms">-->
<!--        <div class="modal-dialog modal-sm">-->
<!--            <div class="modal-content">-->
<!--               -->
<!--                <div class="modal-header">-->
<!--                    <button type="button" style="top: 8px;right: 8px;" class="close" data-dismiss="modal" aria-label="Close"></button>-->
<!--                    <h4 class="modal-title">--><?php //echo JText::_("SMS_POPUP");?><!--</h4>-->
<!--                </div>-->
<!--                <div class="modal-body">-->
<!--                    <form name="invite-friend-mobile-form" id="invite-friend-mobile-form" action="--><?php //echo $inviteFriendURL; ?><!--" method="post">-->
<!--                        --><?php
//                        for( $i=1 ; $i<=5 ; $i++) {
//                            ?>
<!--                            <div class="form-group">-->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--                                        <div class="form_item_holder userName">-->
<!--                                            <input type="text" class="custome_input dont_allow_nums" autocomplete="off" id="fname_--><?php //echo $i;?><!--" placeholder="Enter Name" tabindex="--><?php //echo $i;?><!--">-->
<!--                                            <div class="error" id="error_fname_--><?php //echo $i;?><!--"></div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--                                        <div class="form_item_holder mobile">-->
<!--                                            <input type="text" class="custome_input allow_only_nums" autocomplete="off" id="mobile_--><?php //echo $i;?><!--" placeholder="Enter Mobile No." tabindex="--><?php //echo $i;?><!--" maxlength="10">-->
<!--                                            <div class="error" id="error_mobile_--><?php //echo $i;?><!--"></div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            --><?php
//                        }
//                        ?>
<!--                        <div class="form-group text-center col-md-12 col-sm-12 col-xs-12">-->
<!--                            <br><a href="javascript:void(0);" class="btn" onclick="inviteFriendNow('MOBILE');">INVITE THEM NOW</a>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div> /.modal-content -->
<!--        </div>< /.modal-dialog -->
<!--    </div>-->
    <form id="contact_data_form" action="<?php echo Redirection::REFER_A_FRIEND_INVITE_LIST; ?>" method="post">
        <input type="hidden" name="api_response" id="api_response" value=""/>
    </form> 
    <form id="error_data_form" action="<?php echo Redirection::REFER_FRIEND_ERROR_PAGE; ?>" method="post">
        <input type="hidden" name="api_error_type" id="api_error_type" value=""/>
        <input type="hidden" name="api_error_msg" id="api_error_msg" value=""/>
        <input type="hidden" name="api_redirect_url" id="api_redirect_url" value=""/>
    </form> 
    <script type="text/javascript">
        var inviteFriendURL = <?php echo json_encode($inviteFriendURL); ?>;
        $(document).ready(function () {
            $("ul#url-tabs>li").on('click', function () {
                $("ul#url-tabs>li").removeClass("active");
                $(this).addClass("active");
                $("[div_id]").css("display", "none");
                $("[div_id='"+$(this).children().attr("href").replace("#", "")+"']").css("display", "block");
                $("div.heading>h2").html($(this).children().html());
            });
            var url_hash = window.location.hash;
            if(url_hash != "") {
                $("a[href='"+url_hash+"']").trigger("click");
            }
            
            var email = "<?php echo $playerInfo->emailId;?>";
            if(email){
               //location.href = "/refer-a-friend"; 
            }else{
               $('#email_verification').modal('show');
               $('#email_verification').modal({backdrop: 'static', keyboard: false});
            }
        });

        function openInPopuop(url)
        {
            try {
                var left = (screen.width/2)-(840/2);
                var top = (screen.height/2)-(450/2);
             window.open(url, "RaferaFriendWindow", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=no, copyhistory=no, width=840, height=450, top='+top+', left='+left);
            }
            catch(e) {
                $("#loadingImage").css("display","none");
            }
        }
        
        $(document).on("keyup", "#email", function (e) {
        var value = $(this).val();
        value = value.replace(/[^a-zA-Z0-9@._]/g, '');
        $(this).val(value);

});

    </script>

<?php
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "/templates/shaper_helix3/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/placeholder.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.scrollbar.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/isotope.pkgd.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/custome.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.tools.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.revolution.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/icheck.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/refer_a_friend.js?v=".Constants::JS_VER);
?>
