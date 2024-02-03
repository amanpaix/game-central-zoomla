<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$profile_class = "";
$profile_css = "style='display:none;'";
$edit_avatar_class = "";
$edit_avatar_css = "style='display:none;'";
$change_password_class = "";
$change_password_css = "style='display:none;'";
$default_profile_class = '';
$default_profile_css = '';
 
if(isset($this->edit_avatar)) {
    $edit_avatar_class = "active";
    $edit_avatar_css = "'style='display:inline;'";
}
else if(isset($this->change_password)) {
    $change_password_class = "active";
    $change_password_css = "'style='display:inline;'";
}
else if(isset($this->default_profile)) {
    $profile_class = "active";
    $profile_css = "'style='display:inline;'";
}
else {
    $default_profile_class = "active";
    $default_profile_css = "'style='display:inline;'";
}

$playerInfo = Utilities::getPlayerLoginResponse();
$playerRamData = Utilities::getRamPlayerDataResponse();
$ramPlayerInfo = Utilities::getRamPlayerInfoResponse();

// echo '<pre>';
// print_r($playerRamData);
// die;

//$playerImage = $playerInfo->commonContentPath.$playerInfo->avatarPath."?v=".microtime();


if( str_contains($playerInfo->avatarPath,"edit-thumbnai.jpg") ){
    $playerImage = Configuration::AVATAR_DOMAIN . 'StaticContent.war/commonContent/trinity/playerImages/edit-thumbnai.jpg';
}
else
{
    $playerArr = explode("StaticContent.war", $playerInfo->avatarPath);
    $playerImage = Configuration::AVATAR_DOMAIN . '/StaticContent.war' .$playerArr[1]."?v=".microtime();
}

//exit(json_encode($playerImage));
if($playerInfo->gender === 'F'){
    $title = 'Ms.';
}else if($playerInfo->gender === 'M'){
    $title = 'Mr.';
}else {
   $title = ''; 
}
$flagEditProfile = true;
if($playerInfo->profileUpdate && $playerInfo->emailVerified=="Y" && $playerInfo->phoneVerified=="Y")
    $flagEditProfile = false;
$playerName = "Player";
if(!empty($playerInfo->firstName) && ($playerInfo->firstName != "null"))
    $playerName =  $playerInfo->firstName;

if( !empty($playerInfo->lastName) && ($playerInfo->lastName != "null") )
    $playerName .= " ".$playerInfo->lastName;

$avatarImagesArr = Utilities::getAvatarImages($playerInfo->playerId, $playerInfo->avatarPath);

$mobile =   $playerInfo->userName;
$ramPlayerInfo = Utilities::getRamPlayerInfoResponse();

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
$cashBalance = number_format((float)$playerInfo->walletBean->cashBal,2);
$bonusBalance = number_format((float)$playerInfo->walletBean->bonusBal,2);
$currencyInfo = Utilities::getCurrencyInfo();
$currencyCode = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];

?>

<?php

Html::addJs(JUri::base()."templates/shaper_helixultimate/js/jquery.validate.min.js?v=".Constants::JS_VER);
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js?v=".Constants::JS_VER);
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/jquery.auto-complete.min.js");
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base()."templates/shaper_helixultimate/css/jquery.auto-complete.css");
Html::addCss(JUri::base()."templates/shaper_helixultimate/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/core/upload-avatar.js");
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/core/email_mobile_verify.js");
Html::addJs(JUri::base()."templates/shaper_helixultimate/js/core/change-password.js");
?>

<p></p>
<!--Start My Profile Section-->
<section class="pb35" >
    <div class="container"><!--Page Title-->
        <div class="my-profile-title">
            <h1>My Profile</h1>
            <p class="sub-title">Key information to know user better</p>
        </div>
        <!--Page Title--></div>
</section>
<!--Start User Info Section-->
<section class="pb20">
    <div class="container">
        <div class="my-profile-details radius10"><!--User Image-->
            <div class="my-profile-user-avatar">
                <a href="/edit-avatar">
                    <img src="<?php echo $playerImage; ?>" class="user-avatar-img radius10" alt="User Avatar" />
                </a>

                <button class="edit-profile-btn" onclick="location.href='/edit-profile';">
                    <img src="images/pages/edit-profile.png" alt="Edit Profile" />
                </button>
            </div>
            <!--User Info-->
            <div class="my-profile-user-details"><!--User Name Title-->
                <div class="my-profile-user-info">
                    <h1><?php echo $title . " " . $playerName ?></h1>
                    <p>
                        <?php
                        if (isset($playerInfo->addressLine1)) {
                            echo $playerInfo->addressLine1;
                        } if (isset($playerInfo->addressLine2))
                            echo ' ' . $playerInfo->addressLine2;
                        ?>
                    </p>
                </div>
                <!--User Wallet Details Start-->
                <div class="my-profile-user-wallet"><!--User Balance-->
                    <div class="my-profile-user-balance">
                        <div class="my-profile-balance-icon"><img src="images/pages/balance-icon.png" alt="Balance Image" /></div>
                        <div class="my-profile-user-balance-info">
                            <h6>Balance</h6>
                            <label><?php echo Utilities::FormatCurrency($cashBalance,$currencyCode,$dispCurrency)?></label></div>
                    </div>
                    <!--User Bonus-->
                    <div class="my-profile-user-balance">
                        <div class="my-profile-balance-icon"><img src="images/pages/bonus-icon.png" alt="Bonus Image" /></div>
                        <div class="my-profile-user-balance-info">
                            <h6>Bonus</h6>
                            <label><?php echo Utilities::FormatCurrency($bonusBalance,$currencyCode,$dispCurrency)?></label></div>
                    </div>
                    <!--Add Balance-->
<!--                    <button class="primary-gradient my-profile-user-add-balance" add_cash_button>+</button>-->
                </div>
                <!--User Wallet Details End--></div>
        </div>
    </div>
</section>
<!--Start User Contact Section-->
<section>
    <div class="container">
        <div class="my-profile-user-contact radius10"><!--Row Start-->
            <div class="my-profile-user-contact-row">
                <div class="user-contact-info">
                    <div class="user-contact-icon mr12"><img src="images/pages/phone-icon.png" alt="Phone Icon" /></div>
                    <div class="user-contact-number">
                        <h6>Mobile Number</h6>
                        <label><span class="mr15">+91 <?php echo $playerRamData->mobileNo ?></span>
                            <?php
                            if( $ramPlayerInfo->mobileVerified === "Y" ) {
                                ?>
                                <span class="badge success radius4">Verified</span>
                                <?php
                            }
                            else
                            {
                                ?>
                                <span class="badge warning radius4">Not Verified</span>
                                <?php
                            }
                            ?>

                        </label></div>
                </div>
<!--                <div class="my-profile-user-links">-->
<!--                    <a href="#" class="primary-button ml15">Change</a>-->
<!--                </div>-->
            </div>
            <!--Row End--> <!--Row Start-->
            <div class="my-profile-user-contact-row" >
                <div class="user-contact-info">
                    <div class="user-contact-icon mr12"><img src="images/pages/email-icon.png" alt="Phone Icon" /></div>
                    <div class="user-contact-number">
                        <h6>Email Address</h6>
                        <?php
                        if( $playerRamData->emailId == null || strlen($playerRamData->emailId) == 0 ){
                            ?>
                                <label><span class="mr15"></span><span class="badge warning radius4"></span></label></div>
                            <?php
                        }
                        else
                        {
                            if( $ramPlayerInfo->emailVerified === "Y" ){
                                ?>
                                <label><span class="mr15"><?php echo $playerRamData->emailId ?></span><span class="badge success radius4">Verified</span></label></div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <label><span class="mr15"><?php echo $playerRamData->emailId ?></span><span class="badge warning radius4">Not Verified</span></label></div>
                                <?php
                            }
                        }
                        ?>

                </div>
        <?php
            if( $playerRamData->emailId == null || strlen($playerRamData->emailId) == 0 ){
                ?>
                    <div class="my-profile-user-links"><a href="#" class="primary-button ml15" profile_email="ADD">Add</a></div>
                <?php
            }
            else
            {
                    if( $ramPlayerInfo->emailVerified === "Y" ){
                        ?>
                        <div class="my-profile-user-links"><a href="#" class="primary-button ml15" change_email="<?php echo $playerRamData->emailId ?>" profile_email="CHANGE" >Change</a></div>
                        <?php
                    }
                    else
                    {
                        ?>
                        <div class="my-profile-user-links"><a href="#" class="primary-button ml15" change_email="<?php echo $playerRamData->emailId ?>" profile_email="VERIFY"  >Send Link</a><a href="#" class="primary-button ml15" change_email="<?php echo $playerRamData->emailId ?>" profile_email="CHANGE">Change</a></div>
                        <?php
                    }
                ?>
                <?php
            }
        ?>
            </div>
            <!--Row End--> <!--Row Start-->
            <div class="my-profile-user-contact-row">
                <div class="user-contact-info">
                    <div class="user-contact-icon mr12"><img src="images/pages/password-icon.png" alt="Phone Icon" /></div>
                    <div class="user-contact-number">
                        <h6>Password</h6>
                        <label>********</label></div>
                </div>
                <div class="my-profile-user-links">
                    <a data-dismiss="modal" data-bs-target="#reset_password_modal" data-bs-toggle="modal" class="primary-button ml15">Change</a>
                </div>
            </div>
            <!--Row End--></div>
    </div>
</section>
<!--End User Contact Section-->



































