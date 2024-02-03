<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

$cashBalance = Utilities::getPlayerLoginResponse()->walletBean->cashBalance;
$withdrawBalance = Utilities::getPlayerLoginResponse()->walletBean->withdrawableBal;

if(strpos($cashBalance, ".") !== false) {
    $cashBalance = substr($cashBalance, 0, strpos($cashBalance, "."));
}

if($this->status == "SUCCESS") {
    ?>
    <script>
        if(window.opener != null) {
            opener.updateBalance(<?php echo $cashBalance; ?>);
            opener.updateWithdrawBalance(<?php echo $withdrawBalance; ?>);
        }

        $(document).ready(function () {
            $("#cashier-success-my-account").on('click', function () {
                if(window.opener != null) {
                    window.opener.location.href = "<?php echo $this->afterPaymentRedirectLink; ?>";
                    window.close();
                }
                else {
                    window.location.href = "<?php echo $this->afterPaymentRedirectLink; ?>";
                }
            });

            $("#cashier-success-home").on('click', function () {
                if(window.opener != null) {
                    window.opener.location.href = "<?php echo JUri::base(); ?>";
                    window.close();
                }
                else {
                    window.location.href = "<?php echo JUri::base(); ?>";
                }
            });
        });
    </script>

    <div class="cashier_success">
        <div class="print_receipt"><a href="javascript:void(0);" onclick="window.print();"><i class="fa fa-print"></i> <?php echo JText::_("CASHIER_PRINT");?></a></div>
        <div class="title"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo JText::_("SUCCESS");?>!</div>
        <div class="success_img"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/success_img.png"></div>
        <div class="success_text">
            <?php
            if(isset($this->type)) {
                ?>
                <div class="deposit_amount"><?php echo JText::_("CASHIER_SUCCESS");?></div>
                <div class="transction_id"><?php echo JText::_("REQUEST_ID");?><?php echo $this->offlineRequestId;?></div>
                <p> <?php echo $this->afterPaymentMessage ?> &nbsp;<?php echo JText::sprintf("CASHIER_SUCCESS_MSG"
                    ,'<a href="javascript:void(0);" id="cashier-success-my-account">'.JText::_("MY_ACCOUNT").'</a>'
                    ,'<a href="javascript:void(0);" onclick="window.print();">'.JText::_("PRINT_RECIEPT").'</a>'
                    ,'<a href="javascript:void(0);" onclick="window.print();">'.JText::_("PRINT_RECIEPT").'.</a>');?></p>
                <?php
            }
            else {
                ?>
                <div class="deposit_amount"><?php echo JText::_("CASHIER_SUCCESS_2");?><strong><?php echo $this->CurrData['decSymbol']?><?php echo $this->afterPaymentAmount; ?></strong></div>
                <div class="transction_id"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID");?>: <?php echo $this->afterPaymentTransactionId; ?></div>
                <p><?php echo JText::sprintf("CASHIER_RECIEPT_LINK"
                ,Utilities::getPlayerLoginResponse()->emailId
                ,'<a href="javascript:void(0);" id="cashier-success-my-account">'.JText::_("MY_ACCOUNT").'</a>'
                ,'<a href="javascript:void(0);" onclick="window.print();">'.JText::_("PRINT_RECIEPT").'</a>')?></p>
                <?php
            }
            ?>
        </div>
       <!-- <div class="success_btn"><a href="javascript:void(0);" onclick="window.close();" play_rummy="true"><?php echo JText::_("PLAY_NOW");?></a></div>-->
        <div class="success_btn_mobile">
            <?php if(Configuration::getOS() == Configuration::OS_ANDROID) { ?>
                <a href="http://m.onelink.me/39f13719"><img src="https://d7hf0c5vwwy8u.cloudfront.net/images/promotions/common/rummy-on-mobile/android-download-button.png"></a>
            <?php }
            else if(Configuration::getOS() == Configuration::OS_IOS) { ?>
                <a href="http://m.onelink.me/2579a81b"><img src="https://d7hf0c5vwwy8u.cloudfront.net/images/promotions/common/rummy-on-mobile/ios-download-button.png"></a>
            <?php } ?>
        </div>    
</div>
    <div class="cashier_footer">
        <div class="cashier_footer1">
            <span class="fleft"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/safe_secure.jpg"></span>
            <span class="fright">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/visa.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/atom.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/citrus.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/ebs.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/payumoney.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/techprocess.jpg">
    </span>
        </div>
        <div class="cashier_footer1 mobile">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/safe_secure.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/visa.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/atom.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/citrus.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/ebs.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/payumoney.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/techprocess.jpg">
            </span>
        </div>
        <div class="cashier_footer2"><?php echo JText::_("ACCOUNT_TEXT")?> | <span><a href="#"  onclick="openLiveChat();" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/chat_icon.png"> <?php echo JText::_("CHAT_BUTTON");?></a> | <a href="mailto:support@khelplayrummy.com" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/email_icon.png"> <?php echo JText::_("EMAIL_US");?></a></span></div>
    </div>
    <?php
}

$player_info_mixpanel = Utilities::getPlayerLoginResponse();
$request = JFactory::getApplication()->input;

$responseBean_netAmount = $request->getString('responseBean_netAmount', '');
$responseBean_paymentType = $request->getString('responseBean_paymentType', '');
$responseBean_paySubType = $request->getString('responseBean_paySubType', '');

$responseBean_bonusType = $request->getString('responseBean_bonusType', '');
$responseBean_bonusAmount = $request->getString('responseBean_bonusAmount', '');
$responseBean_responseMsg = $request->getString('responseBean_responseMsg', '');
?>
    <script>
	$(window).load(function(){
        if(window.opener != null)
            $(".zopim").hide();
    });
	
        mixpanelDepositResponse(MIXPANEL_DOMAIN_NAME,
            '<?php echo $player_info_mixpanel->userName; ?>',
            '<?php echo $responseBean_netAmount; ?>',
            '<?php echo $responseBean_paymentType; ?>',
            '<?php echo $responseBean_paySubType; ?>',
            '<?php echo $responseBean_responseMsg; ?>',
            'bonusApplied',
            '<?php echo $responseBean_bonusType; ?>',
            '<?php echo $responseBean_bonusAmount; ?>',
            '<?php echo $player_info_mixpanel->playerId; ?>',
            '<?php echo $player_info_mixpanel->emailId; ?>',
            '<?php echo $player_info_mixpanel->mobileNo; ?>',
            '<?php echo (isset($player_info_mixpanel->firstName) ? $player_info_mixpanel->firstName : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->lastName) ? $player_info_mixpanel->lastName : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->gender) ? $player_info_mixpanel->gender : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->dob) ? $player_info_mixpanel->dob : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->state) ? $player_info_mixpanel->state : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->city) ? $player_info_mixpanel->city : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->country) ? $player_info_mixpanel->country : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->pinCode) ? $player_info_mixpanel->pinCode : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->registrationIp) ? $player_info_mixpanel->registrationIp : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->regDevice) ? $player_info_mixpanel->regDevice : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->registrationDate) ? $player_info_mixpanel->registrationDate : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->playerStatus) ? $player_info_mixpanel->playerStatus : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->walletBean->cashBalance) ? $player_info_mixpanel->walletBean->cashBalance : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->walletBean->practiceBalance) ? $player_info_mixpanel->walletBean->practiceBalance : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->referSource) ? $player_info_mixpanel->referSource : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->campaignName) ? $player_info_mixpanel->campaignName : ""); ?>',
            '<?php echo (isset($player_info_mixpanel->firstDepositDate) ? $player_info_mixpanel->firstDepositDate : ""); ?>',
            'curDate',
            '<?php echo Configuration::getDeviceType(); ?>',
            'Website',
            'Web',
            '<?php echo Configuration::getOS(); ?>');
    </script>
<?php
?>
