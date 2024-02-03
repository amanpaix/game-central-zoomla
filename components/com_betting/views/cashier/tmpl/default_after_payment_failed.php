<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>

<div class="cashier_failed">
 <div class="title_filed"><?php echo JText::_("CASHIER_MODULE_PROBLEM");?> <span class="rupees-symbol">`</span><?php echo number_format($this->afterPaymentAmount); ?>? </div>
    <div class="sub_text"><?php echo JText::_("CASHIER_REQUEST_MSG");?></div>
    <div class="success_text">
        <div class="row">
            <div class="col-md-6">
                <p>
                    <strong><?php echo JText::_("CASHIER_SUFFICIENT")?></strong><br>
                    <?php echo JText::_("CASHIER_WE_ADWICE");?>
                </p>
            </div>
            <div class="col-md-6">
                <p><?php echo JText::_("OTP_CASHIER");?></p>
            </div>
            <div class="col-md-6">
                <p>
                   <?php echo JText::_("CASHIER_CVV");?></p>
            </div>
            <div class="col-md-6">
                <p><?php echo JText::_("CARD_ENTRY_EXPIRY")?></p>
            </div>
        </div>
    </div>
    <div class="success_btn"><a href="<?php echo Redirection::CASHIER_INITIATE; ?>"><?php echo JText::_("TRY_AGAIN");?></a></div>
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
    <div class="cashier_footer2"><?php echo JText::_("ACCOUNT_TEXT");?> | <span><a href="#" onclick="openLiveChat();" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/chat_icon.png"> <?php echo JText::_("CHAT_BUTTON");?></a> | <a href="mailto:support@khelplayrummy.com"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/email_icon.png"><?php echo JText::_("EMAIL_US");?></a></span></div>
</div>

<?php
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