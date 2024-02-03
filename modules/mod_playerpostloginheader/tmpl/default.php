<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';

$playerLoginResponse = Utilities::getPlayerLoginResponse();

//$playerImage = Configuration::AVATAR_DOMAIN . '/' .$playerLoginResponse->avatarPath."?v=".microtime();
// edit-thumbnai.jpg

if( str_contains($playerLoginResponse->avatarPath,"edit-thumbnai.jpg") ){
    $playerImage = Configuration::AVATAR_DOMAIN . 'StaticContent.war/commonContent/trinity/playerImages/edit-thumbnai.jpg';
}
else
{
    $playerArr = explode("StaticContent.war", $playerLoginResponse->avatarPath);
    $playerImage = Configuration::AVATAR_DOMAIN . '/StaticContent.war' .$playerArr[1]."?v=".microtime();
}

//exit(json_encode($playerImage));
//exit(json_encode(explode(".", $playerLoginResponse->walletBean->cashBal)[0]));
$cashBalance = explode(".", $playerLoginResponse->walletBean->cashBal)[0];


$bonusBalance = Session::getSessionVariable("bonusBarReceived") - Session::getSessionVariable("bonusBarRedeemed");
$bonusBalance = explode(".", $bonusBalance)[0];

$practiceBalance = explode(".", $playerLoginResponse->walletBean->practiceBalance)[0];

$loyaltyTotalPointsOld = Session::getSessionVariable("loyaltyCurrentTierEarning");
$loyaltyTotalPoints = explode(".", $loyaltyTotalPointsOld)[0];

$loyaltyCurTierMaintanancePoints = Session::getSessionVariable("loyaltyCurrentTierMaintanancePoints");
$loyaltyCurTierMaintanancePoints = explode(".", $loyaltyCurTierMaintanancePoints)[0];
$loyaltyCurTierMaintanancePointsShowLine = false;

//exit(json_encode($playerLoginResponse));


  //getting currency array from constant using session variable
//Multi Currency 
         $sessionVar=Session::getSessionVariable("playerLoginResponse");
        if(empty(Configuration::getCurrencyDetails()) || !empty($sessionVar->walletBean->currency))
        {
            if(!empty($sessionVar->walletBean->currency)){
                Configuration::setCurrencyDetails($sessionVar->walletBean->currency);
            }else {
                Configuration::setCurrencyDetails(Constants::DEFAULT_CURRENCY_CODE);
            }
        }

        $CurrData=Configuration::getCurrencyDetails();
        $cashBalance = number_format((float)$playerLoginResponse->walletBean->cashBal,2);
        $currencyInfo = Utilities::getCurrencyInfo();
        $currencyCode = $currencyInfo[0];
        $dispCurrency = $currencyInfo[1];
//        $dispCurrency = "$";



//        exit(json_encode(Utilities::getPlayerLoginResponse()));
        $isUnreadInbox = (int) (Utilities::getPlayerLoginResponse()->unreadMsgCount > 0) ? "unread" : "";
        $profilePercent = "0";

            if( Session::sessionValidate() ){
?>
<!--                <div class="userInfoWrap">-->
<!--                    <div class="username"> --><?php //echo $playerLoginResponse->userName ?><!-- </div>-->
<!--                    <div class="amountWrap"><span class="cash-balance">--><?php //echo Utilities::FormatCurrency($cashBalance,$currencyCode,$dispCurrency)?><!--</span><span class=""></span></div>-->
<!--                </div>-->

                <div class="top-user-wallet">
                    <div class="user-balance">
                        <div class="balance-icon"><img data-src="images/pages/balance-icon.png" alt="Balance Image" class=" lazyloaded" src="images/pages/balance-icon.png"></div>
                        <div class="balance-info">
                            <h6>Balance</h6>
                            <label class="cash-balance"><?php echo Utilities::FormatCurrency($cashBalance,$currencyCode,$dispCurrency)?></label></div>
                            <button class="refresh-balance" id="refresh_bal" type="button"><i class="icon-refresh"></i></button>
                    </div>
                    <button style="visibility: hidden" class="primary-gradient user-add-balance" add_cash_button>Deposit</button>
                </div>
<!--                <div class="notification-info --><?php //echo $isUnreadInbox ?><!--">-->
<!--                    <a href="/notifications"><img src="images/pages/bell.png"></a>-->
<!--                </div>-->
                <div class="top-user-pic" style="background: conic-gradient(rgb(152, 221, 75) <?php echo $profilePercent?>%, rgb(41, 39, 92) <?php echo $profilePercent?>%);">
                    <div class="user-pic-wrap">
                        <img src="<?php echo $playerImage; ?>">
                    </div>
                </div>


<?php
            }
            else{
?>
<div class="btnWrap "><a data-dismiss="modal" data-bs-target="#home_login" data-bs-toggle="modal" class="green_bg loginBtn btn"><?php echo JText::_('BETTING_LOGIN_SIGNUP')?></a></div>
<?php
            }
?>



