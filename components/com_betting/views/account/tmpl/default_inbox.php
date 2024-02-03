<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
//Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/bootstrap-datepicker.min.js");
//Html::addCss(JUri::base() . "/templates/shaper_helixultimate/css/bootstrap-datepicker.min.css");
//Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate.min.js");
//Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/placeholder.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.scrollbar.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/isotope.pkgd.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/custome.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.themepunch.tools.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/jquery.themepunch.revolution.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helixultimate/js/icheck.js");

if($this->isCommingSoon)
{
    ?>
    <script>
        $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MYACC_INBOX; ?>']").parent().addClass('active');
    </script>
    <div class="myaccount_body_section">
        <div class="container">
            <div class="entry-header has-post-format">
                <div class="my-acc-title mb-5">
                    <h1 >Notifications</h1>
                    <p class="sub-title">It helps with reminders, communication or other timely information</p>
                </div>
            </div>

            <div style="float:left; width:100%; text-align:center; font-size:28px;"><?php echo JText::_('COMMING_SOON');?></div>

        </div>
    </div>
    <?php
}
else
{
    $unreadMsgCount = Utilities::getPlayerLoginResponse()->unreadMsgCount;
    $content_details = array();
    if(isset($this->messageContent) && $this->messageContent !== false && count($this->messageContent) !== 0) {
        foreach ($this->messageContent as $msgContent) {
            $content_details[$msgContent['id']] = array('content' => json_decode($msgContent['params'])->content, 'css_style' => json_decode($msgContent['params'])->css_style);
        }
    }

    ?>
    <script>
        $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MYACC_INBOX; ?>']").parent().addClass('active');
        document.title = document.title + "<?php if($unreadMsgCount != 0){echo " (".$unreadMsgCount.")";} ?>";
        var print_limit_reached = true;
        var INBOX_LIMIT = <?php echo Constants::INBOX_LIMIT; ?>;
        var INBOX_ACTIVITY = '/component/betting/?task=account.inboxActivity';
        var PLAYER_INBOX = '/component/betting/?task=account.playerInbox';
    </script>

    <div class="myaccount_body_section">
        <div class="entry-header has-post-format">
            <div class="my-acc-title mb-5">
                <h1 class="inbox_count">Notifications</h1>
                <p class="sub-title">It helps with reminders, communication or other timely information</p>
            </div>

            <?php
            if( !((isset($this->messages->data) && count($this->messages->data) == 0) || isset($this->nomessages) === true)) {
                ?>
                <div class="search_mail">
                    <div class="search_box">
                        <div class="input-group">
                            <input type="text" class="custome_input no_special_chars" placeholder="<?php echo JText::_('SEARCH')?>" id="filter">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="mail_actionbtn">
                        <a href="javascript:void(0);" class="maroon_bg" id="delete_main"><?php echo JText::_('BETTING_DELETE')?></a>
                    </div>
                    <div class="mail_actionbtn_inner" style="display: none;">
                        <a href="javascript:void(0);" class="back_btn btn"><?php echo JText::_('LOYALTY_BACK')?></a>
                        <a href="javascript:void(0);" class="maroon_bg" delete_msg=""><?php echo JText::_('BETTING_DELETE')?></a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="inbox" id="main_inbox">
            <div id="filter-not-found" style="display: none; ">
                <div class="mail_item"><?php echo JText::_("NO_MESSAGE_FOUND")?></div>
            </div>
            <table class="table" id="inbox-table-footable" data-filtering="true" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next">
                <tbody>
                <?php
                if( (isset($this->messages->data) && count($this->messages->data) == 0) || isset($this->nomessages) === true) {
                    ?>
                    <tr>
                        <td colspan="3" class="text-center" style="border: none;height: 100px;font-size: 13px;">
                            <div class="mail_item"><?php echo JText::_("NO_MESSAGE_FOUND")?></div>
                        </td>
                    </tr>
                    <?php
                }
                else {
                    $i = 0;
                    foreach($this->messages->data as $message) {
                         $date = $message->mailSentDate;
                         $date = explode(' ', $date);
                          
                         $newDate = str_replace('/', '-', $date[0]);
                         $newDate = date("M d, Y", strtotime($newDate));
            if($i >= 50) {
                            echo '<script>print_limit_reached=false;</script>';
                            break;
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="mail_item <?php if(strtoupper($message->status) == 'UNREAD') echo 'unread';?>">
                                    <div class="mail_checkbox">
                                        <label>
                                            <input type="checkbox" name="mail_select" select-one-msg-id="<?php echo $message->inboxId; ?>">
                                            <span class="chkIcon"></span>
                                        </label>
                                    </div>
                                    <a href="javascript:void(0);" module-id-parent="<?php echo $message->content_id; ?>" msg-id-parent="<?php echo $message->inboxId; ?>">
                                    <div class="mail_shortinfo">
                                        <div class="mail_title">
                                            
                                                <?php echo $message->subject; ?>
                                            
                                            <span class="mail_date"><?php echo $newDate . ' ' . $date[1]; ?></span>
                                        </div>
                                        <!--<div class="mail_info">The weather department has predicted a thunderstorm of Cash Prizes tonight! Jump in to that thunderstorm on KhelPlay Rummy tonight at 9:00 PM to grab your share!</div>-->
                                    </div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
                </tbody>
                <tfoot class="hide-if-no-paging">
                <?php if( isset($this->messages->data) && count($this->messages->data)  > 10 ) { ?>
                <tr><td colspan="7"><div class="pagination pagination-centered" id="footer-pagination-div"></div></td></tr>
                <?php } ?>
                </tfoot>
            </table>

            <div id="main-content-div">
                <?php
                if(isset($this->messages->data)) {
                    foreach ($this->messages->data as $msg) {
                         $date = $msg->mailSentDate;
                         $date = explode(' ', $date);

                         $newDate = str_replace('/', '-', $date[0]);
                         $newDate = date("M d, Y", strtotime($newDate));
                        ?>
                        <div class="inbox_inner" style="display: none" module-id-child="<?php echo $msg->content_id;?>" msg-id-child="<?php echo $msg->inboxId;?>">
                            <div class="mail_heading"><?php echo $msg->subject; ?> <span class="mail_date"><?php echo $newDate . ' ' . $date[1]; ?></span></div>
                            <div class="inbox_mailer">
                                <?php

//                                    print_r("<style>".$content_details[$msg->content_id]['css_style']."</style>");
//                                    print_r($content_details[$msg->content_id]['content']);
                                        print_r($msg->contentId);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>
    </div>

    <?php
    Html::addJs(JUri::base()."templates/shaper_helixultimate/js/core/inbox.js?v=".Constants::JS_VER);
    if(isset($this->messages->data)) {
        ?>
        <script>
            if(print_limit_reached == true) {
                lastPageNo = 5;
            }
            else {
                lastPageNo = <?php print_r($i/10); ?>;
            }
        </script>
        <?php
    }
    ?>
<?php  if( (isset($this->messages->data) && count($this->messages->data) == 0) || isset($this->nomessages) === true) { ?>
     <script>
   $(document).ready(function () {
   $('#inbox-table-footable > tfoot').css('display', 'none');   
     });
     </script>
   <?php  }  ?>
    <?php

}

?>





<div class="modal fade" id="add_account_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-box"><!--Right Side Banner-->
                    <div class="modal-right-signup"><button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">×</button>
                        <div class="login-form">
                            <h2 class="h2-title">Add New Account</h2>
                            <form action="#" method="post" id="cashier-add-account-form" class="" submit-type="ajax" validation-style="left" home-forgot-modal="true" tooltip-mode="manual" novalidate="novalidate" style="display: block;"><input type="hidden" name="AddAccFor" value="" id="addAccFor" />
                                <div class="form-group">
<!--                                    <label>Enter Account Holder Name</label>-->
                                    <div class="form_item_holder userName">
                                        <input type="text" class="custome_input alphabets_only" placeholder="Enter Account Holder Name*" name="accHolderName" id="accHolderName" maxlength="25" />
                                        <div class="error_tooltip manual_tooltip_error" id="error_accHolderName" style="display: none;"></div>
                                    </div>
                                </div>

<!--                                <div class="form_item_holder mobile no-icon">-->
<!--                                    <label for="accNum">Enter Account Number</label>-->
<!--                                    <div class="inputGroup">-->
<!--                                        <div class="input-group"><span class="input-group-addon">+254</span>-->
<!--                                            <input type="tel" class="mobile allow_only_nums formControl" id="accNum" autocomplete="off" prefix="prefix" name="accNum" maxlength="9" pattern="^[7,1]{1}[0-9]{8}$" /></div>-->
<!--                                        <div class="error_tooltip manual_tooltip_error" id="error_accNum" style="display: none;"></div>-->
<!--                                    </div>-->
<!--                                </div>-->

                                <div class="input-group-row">
                                    <div class="input-group-left"><span>+254</span></div>
                                    <input type="tel" class="mobile formControl change_maxlength input-control allow_only_nums" id="accNum" autocomplete="off" prefix="prefix" name="accNum" placeholder="Enter Account Number*" maxlength="9" aria-required="true">
                                </div>
                                <div class="error_tooltip manual_tooltip_error" id="error_accNum" style="display: none;"></div>


                                <input type="hidden" id="accSubTypeId" value="" /> <input type="hidden" id="accPaymentTypeCode" value="" /> <input type="hidden" id="accPayTypeId" value="" /> <input type="hidden" id="accCurrency" value="" />
                                <div class="button_holder">
                                    <p><button type="submit" class=" btn btnStyle2">Submit</button></p>
                                </div>
                            </form>
                            <form action="#" method="post" id="cashier-otp-verification-form" class="" submit-type="ajax" validation-style="left" home-forgot-modal="true" tooltip-mode="manual" novalidate="novalidate" style="display: none;">
                                <div class="form-group">
                                    <p><sup>We have sent you a code to phone number <strong><span id="cashier-deposit-mobile"></span></strong></sup></p>
                                    <sup><label>Please confirm the code below</label></sup>
                                    <div class="form_item_holder chat"><sup><input type="text" class="custome_input allow_only_nums" id="account_otp" name="account_otp" maxlength="4" aria-required="true" /></sup>
                                        <div class="error_tooltip manual_tooltip_error" id="error_account_otp" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="button_holder"><sup><button type="submit" class=" btn btnStyle2">Submit</button></sup></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
