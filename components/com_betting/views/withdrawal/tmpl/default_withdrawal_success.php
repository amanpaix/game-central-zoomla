<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
</script>

<div class="myaccount_body_section">
    <div class="container">
        <div class="heading">
            <h2><?php echo JText::_("WITHDRAWL_CASH");?></h2>
        </div>

        <div class="withdrawl_static_page">
            <div class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/withdraw_success_icon.jpg"></div>
            <div class="text">
                <p>
                <?php 
                $time = $this->time; 
                 if(strpos($time, ".") !== false) { 
                     $time = substr($time, 0, (strpos($time, "."))); 
                }

                echo sprintf(JText::_("WITH_SUCCESS_MSG")
                    ,$this->withdrawalTxnId
                    ,$this->CurrData['decSysmbol'].$this->amount
                    ,$this->date ." ". $time);
                ?></p>
                <a href="<?php echo Redirection::WITHDRAWAL_REQUEST; ?>" class="brown_bg"><?php echo JText::_("VIEW_WITHDRAW");?></a>
            </div>
        </div>
    </div>
</div>

<?php
$payTypeName = Session::getSessionVariable('payTypeName');
$subTypeName = Session::getSessionVariable('subTypeName');
$player_info_mixpanel = Utilities::getPlayerLoginResponse();
$amount = $this->amount;
Session::unsetSessionVariable('payTypeName');
Session::unsetSessionVariable('subTypeName');
?>
<script>
    mixpanelWithdrawalReq(MIXPANEL_DOMAIN_NAME,
        '<?php echo $player_info_mixpanel->userName; ?>',
        '<?php echo $amount; ?>',
        '<?php echo $payTypeName; ?>',
        '<?php echo $subTypeName; ?>',
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
        '<?php echo Configuration::getDeviceType(); ?>',
        'Website',
        'Web',
        '<?php echo (isset($player_info_mixpanel->playerStatus) ? $player_info_mixpanel->playerStatus : ""); ?>',
        '<?php echo Configuration::getOS(); ?>');
</script>
