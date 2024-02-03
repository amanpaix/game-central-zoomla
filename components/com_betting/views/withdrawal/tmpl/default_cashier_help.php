<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
</script>

<div class="myaccount_body_section">
    <div class="">
        <div class="heading">
            <h2 id="top"><?php echo JText::_("CASHIER_HELP");?></h2>
            <ul class="refer_friend_menu">
                <li><a href="<?php echo Redirection::WITHDRAWAL_PROCESS; ?>"><?php echo JText::_("WITHDRAWL_CASH");?></a></li>
                <li><a href="<?php echo Redirection::WITHDRAWAL_REQUEST; ?>"><?php echo JText::_("VIEW_STATUS");?></a></li>
                <li class="active"><a href="<?php echo Redirection::CASHIER_HELP; ?>"><?php echo JText::_("CASHIER_HELP");?></a></li>
            </ul>
        </div>
        <div class="promotion_page">
            <div class="promotion_right_div width100">
                <div class="static_page">
                    <ul class="hashtag_link">
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('real_money')"><?php echo JText::_("WITHDRAWL_REAL_CASH");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('rummy_winnings')"><?php echo JText::_("WITHDRAW_CASHING_MSG");?></a></li>
                    </ul>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("WITHDRAW_DEPOSIT");?></div>
                    <ul class="hashtag_link">
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('profile_completion')"><?php echo JText::_("WITHDRAWL_PROFILE_COMPLET_TITLE");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('mode_deposits')"><?php echo JText::_("WITHDRAWL_AVAIL");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('credit_card')"><?php echo JText::_("WITHDRAWL_CRADIT_CARD");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('debit_card')"><?php echo JText::_("DABIT_CARD");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('net_banking')"><?php echo JText::_("NET_BANKING");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('cheque_transfer')"><?php echo JText::_("CHEQUE_TRANSFER");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('wire_transfer')"><?php echo JText::_("WIRE_TRANSFER");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('khelplayrummy_card')"><?php echo JText::_("KHELPLAY_CARD");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('cash_card')"><?php echo JText::_("CASH_CARD");?></a></li>
                    </ul>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("WITHDRAWL_PRE");?></div>
                    <ul class="hashtag_link">
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('profile_completion_documents')"><?php echo JText::_("PROFILE_COMPLITION_MSG");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('id_address_proof')"><?php echo JText::_("ID_PROFF");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('id_proof')"><?php echo JText::_("ID_PRO");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('address_proof')"><?php echo JText::_("ADDRESS_PROFF");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('withdrawal_policies')"><?php echo JText::_("WITHDRAWL_POLICY");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('withdrawal_request')"><?php echo JText::_("HOW_TO_MAKE_REQUEST");?></a></li>
                    </ul>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("MODE_OF_WITH");?></div>
                    <ul class="hashtag_link">
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('mode_cheque_transfer')"><?php echo JText::_("CHEQUE_TRANSFER");?></a></li>
                        <li><a href="#" onclick="bookmarkscroll.scrollTo('mode_bank_transfer')"><?php echo JText::_("WITH_BANK_TRANSFER");?></a></li>
                    </ul>
                    <div class="divider">&nbsp;</div>
                    <div class="hashtag_link_title" id="real_money"><?php echo JText::_("WITHDRAWL_REAL_CASH");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                      <p><?php echo JText::_("WITHDRAWL_MSG");?></p></div>
                    <div class="promotion_table">
                        <table class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10">
                            <thead>
                            <tr>
                                <th data-toggle="true"><?php echo JText::_("WITH_TBL_METHOD");?></th>
                                <th><?php echo JText::_("WITH_DEPOSIT");?></th>
                                <th><?php echo JText::_("CASH_OUT");?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo JText::_("CHEQUE_TRANSFER");?></td>
                                <td><i class="fa fa-2x fa-check"></i></td>
                                <td><i class="fa fa-2x fa-check"></i></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_("ONLINE_FUND_TRANSFER");?></td>
                                <td><i class="fa fa-2x fa-check"></i></td>
                                <td><i class="fa fa-2x fa-check"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITHDRAWL_MESSAGE_1");?></p>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("WITHDRAW_DEPOSIT");?></div>
                    <div class="hashtag_link_title" id="profile_completion"><?php echo JText::_("WITHDRAWL_PROFILE_COMPLET_TITLE");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITHDRAWL_MESSAGE_2");?></p>
                    </div>
                    <div class="hashtag_link_title" id="mode_deposits"><?php echo JText::_("WITHDRAWL_AVAIL"); ?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITHDRAWL_KHELPLAY");?></p>
                    </div>
                    <div class="hashtag_link_title" id="credit_card"><?php echo JText::_("WITHDRAWL_CRADIT_CARD");?><a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITHDRAW_MESSAGE_3");?></p>
                    </div>
                    <div class="hashtag_link_title" id="debit_card"><?php echo JText::_("DABIT_CARD");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_DRAWL_MSG_4");?></p>
                    </div>
                    <div class="hashtag_link_title" id="net_banking"><?php echo JText::_("NET_BANKING");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_5");?></p>
                    </div>
                    <div class="hashtag_link_title" id="cheque_transfer"><?php echo JText::_("CHEQUE_TRANSFER");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_6");?></p>
                    </div>
                    <div class="hashtag_link_title" id="wire_transfer"><?php echo Jtext::_("WIRE_TRANSFER");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_7");?></p>
                    </div>
                    <div class="hashtag_link_title" id="khelplayrummy_card"<?php echo JText::_("KHELPLAY_CARD");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_8");?></p>
                    </div>
                    <div class="hashtag_link_title" id="cash_card"><?php echo JText::_("CASH_CARD");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_9");?></p>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("WITHDRAWL_PRE");?></div>
                    <div class="hashtag_link_title" id="profile_completion_documents"><?php echo JText::_("PROFILE_COMPLITION_MSG");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_10");?></p>
                    </div>
                    <div class="hashtag_link_title" id="id_address_proof"><?php echo JText::_("ID_PROFF");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <ul>
                            <li><?php echo JText::_("DRIVING_LISENCE");?></li>
                            <li><?php echo JText::_("BETTING_PASSWPORT");?></li>
                            <li><?php echo JText::_("AAADHAR_CARD");?></li>
                            <li><?php echo JText::_("VOTTER_ID");?></li>
                        </ul>
                        <p><?php echo JText::_("WITHDRAWL_MSG_11");?></p>
                    </div>
                    <div class="hashtag_link_title" id="id_proof"><?php echo JText::_("ID_PRO");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <ul>
                            <li><?php echo JText::_("PAN_CARD");?></li>
                        </ul>
                    </div>
                    <div class="hashtag_link_title" id="address_proof"><?php echo JText::_("ADDRESS_PROFF");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <ul>
                            <li><?php echo JText::_("ELECT_BILL");?></li>
                            <li><?php echo JText::_("TELI_BILL");?></li>
                        </ul>
                        <p><?php echo JText::_("WITH_MSG_12");?></p>
                    </div>
                    <div class="hashtag_link_title" id="withdrawal_policies"><?php echo JText::_("WITHDRAWL_POLICY");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITDRAWL_MSG_13");?></p>
                    </div>
                    <div class="hashtag_link_title" id="withdrawal_request"><?php echo JText::_("HOW_TO_MAKE_REQUEST");?><a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_14");?></p>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <div class="sub_title"><?php echo JText::_("MODE_OF_WITH");?></div>
                    <div class="hashtag_link_title" id="mode_cheque_transfer"><?php echo JText::_("CHEQUE_TRANSFER");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_13");?></p>
                    </div>
                    <div class="hashtag_link_title" id="mode_bank_transfer"><?php echo Jtext::_("WITH_BANK_TRANSFER");?> <a href="#" onclick="bookmarkscroll.scrollTo('top')" class="green_bg"><?php echo JText::_("WITH_TOP");?></a></div>
                    <div class="para_text">
                        <p><?php echo JText::_("WITH_MSG_15");?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash;
	    var $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        window.location.hash = target;
	    });
	});
});
</script>
