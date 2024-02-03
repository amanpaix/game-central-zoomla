<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
//exit("T");
?>
<section class="mid_section" id="my_account">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xs-12 after_register ptop50">
                <div class="row">
                    <div class="myaccount_body_section">
                        <div class="withdrawl_static_page">
                            <div class="icon"><img src="images/common/after_email_icon.png" /></div>
                            <div class="text"><span class="title"><?php echo JText::_('BETTING_ACCOUNT_VERIFIED_SUCCESS_TITLE')?></span>
                                <p><?php echo JText::_("BETTING_ACCOUNT_VERIFIED_SUCCESS_MESSAGE");?></p>
                                <a class="green_bg btnStyle1" play_rummy="true" href="#"><?php echo JText::_("BETTING_PLAY_RUMMY_TEXT");?></a>   
                                <a class="brown_bg btnStyle1" add_cash="true" href="#"><?php echo JText::_("BETTING_DEPOSIT_NOW_TEXT");?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
