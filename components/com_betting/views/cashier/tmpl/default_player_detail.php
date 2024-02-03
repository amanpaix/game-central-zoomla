<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$playerInfo = Utilities::getPlayerLoginResponse();

Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.auto-complete.min.js");
Html::addJs(JUri::base()."templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base()."templates/shaper_helix3/css/jquery.auto-complete.css");
Html::addCss(JUri::base()."templates/shaper_helix3/css/bootstrap-datepicker.min.css");
?>
<style>
    div.modal.in .modal-dialog {
        margin: 70px auto;
    }
</style>

<div class="cashier_header">
    <?php echo JText::_("COMPLETE_PROFILE_TILTE");?>
    <span class="mobile_back_btn">
        <?php if(Session::getSessionVariable('showBackButton'))
        {
        ?>
        <a href="<?php echo Redirection::CASHIER_SELECT_AMOUNT; ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</a>
        <?php
        }
        ?>
        <a href="javascript:void(0);" onclick="window.close();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></a>
    </span>
</div>
<div class="cashier_content">
    <div class="row">
        <form name="player-profile-form" id="player-profile-form" action="<?php echo JRoute::_('index.php?task=cashier.playerDetailUpdate'); ?>" method="post" validation-style="bottom" tooltip-mode="manual">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_FIRST_NAME");?><span class="req_star">*</span></label>
                        <input type="text" class="custome_input" tabindex="1" id="fname" name="fname" value="<?php if(isset($playerInfo->firstName)) {echo $playerInfo->firstName;} ?>" maxlength="25"/>
                        <div class="error_tooltip manual_tooltip_error" id="error_fname"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_LAST_NAME");?><span class="req_star">*</span></label>
                        <input type="text" class="custome_input" tabindex="2" id="lname" name="lname" value="<?php if(isset($playerInfo->lastName)){echo $playerInfo->lastName;} ?>" maxlength="25"/>
                        <div class="error_tooltip manal_tooltip_error" id="error_lname"></div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_ADDRESS");?><span class="req_star">*</span></label>
                        <textarea class="custome_input" style="height: 122px;resize: none;" tabindex="3" id="address" name="address" rows="4" maxlength="197"><?php if(isset($playerInfo->addressLine1)) { echo $playerInfo->addressLine1;} if(isset($playerInfo->addressLine2)) echo ' '.$playerInfo->addressLine2;?></textarea>
                        <div class="error_tooltip manual_tooltip_error" id="error_address"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group city_country">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 city">
                                <label><?php echo JText::_("BETTING_FORM_CITY");?><span class="req_star">*</span></label>
                                <input type="text" class="custome_input" tabindex="4" id="city" name="city" value="<?php if(isset($playerInfo->city)) {echo $playerInfo->city; }?>"/>
                                <div class="error_tooltip manual_tooltip_error" id="error_city"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?php echo JText::_("BETTING_COUNTRY");?></label>
                                <span class="static_value">India</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_STATE");?><span class="req_star">*</span></label>
                        <div class="select_box">
                            <select class="custome_input" tabindex="5" id="state" name="state">
                                <option value="select"><?php echo JText::_("BETTING_FORM_STATE_SELECT");?></option>
                                <<?php
                                $player_stateCode = 'select';
                                $state_list =  Constants::STATE_LIST;
                                asort($state_list);
                                foreach ($state_list as $stateCode => $stateName) {
                                    if(isset($playerInfo->state)) {
                                        if($stateName == $playerInfo->state) {
                                            $player_stateCode = $stateCode;
                                        }
                                    }
                                    ?>
                                    <option value="<?php echo $stateCode?>"><?php echo $stateName?></option>
                                    <?php
                                }
                                ?>
                                <script><?php echo "$('#state').val('".$player_stateCode."');";?></script>
                            </select>
                            <div class="error_tooltip manual_tooltip_error" id="error_state"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_PINCODE");?><span class="req_star">*</span></label>
                        <input type="tel" class="allow_only_nums custome_input" tabindex="6" id="pincode" name="pincode" value="<?php if(isset($playerInfo->pinCode)) {echo $playerInfo->pinCode;}?>" maxlength="6"/>
                        <div class="error_tooltip manual_tooltip_error" id="error_pincode"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_DOB");?><span class="req_star">*</span></label>
                        <div class="input-group date" id="datepicker">
                            <input type="text" class="custome_input" tabindex="7" id="dob" name="dob" value="<?php if(isset($playerInfo->dob)) { echo $playerInfo->dob;}?>" readonly="readonly"/>
                            <button class="btn_date input-group-addon" type="button" tabindex="8" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/calendar_icon.png" alt="" /></button>
                            <div class="error_tooltip manual_tooltip_error" id="error_dob"></div>
                        </div>
                        <script>
                            var fetchCityList = <?php echo json_encode(JRoute::_('index.php?task=account.fetchCityList'));?>;
                            $(document).ready(function () {
                                var currentDate = new Date();
                                currentDate.setYear(currentDate.getFullYear() - 18);
                                var d = new Date(currentDate);

                                var year = d.getFullYear();
                                if((d.getMonth()+1) < 10)
                                    var month = "0" + (d.getMonth()+1);
                                else
                                    var month = d.getMonth()+1;

                                if(d.getDate() < 10)
                                    var day = "0" + d.getDate();
                                else
                                    var day = d.getDate();

                                var current = day + '/' + month + '/' + year;
                                <?php
                                if(!isset($playerInfo->dob) || (isset($playerInfo->dob) && $playerInfo->dob == "")) {
                                ?>
                                $('#dob').val(current);
                                <?php
                                }
                                ?>

                                $('#datepicker').datepicker({
                                    format: "dd/mm/yyyy",
                                    autoclose: true,
                                    startDate: '01/01/1900',
                                    endDate: d
                                });
                            });
                        </script>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="email"><?php echo JText::_("BETTING_FORM_EMAIL_ADDR");?><span class="req_star">*</span></label>
                        <div class="<?php if($playerInfo->emailVerified == 'N') echo 'do_verify'; else echo 'verify';?>" id="email_div">
                            <input type="email" class="custome_input" tabindex="9" id="email" name="email" value="<?php echo $playerInfo->emailId;?>" disabled="disabled" maxlength="50"/>
                            <button class="btn_do_verify" type="button" id="emailVerify-btn"><?php echo JText::_("VERIFY");?></button>
                            <span class="btn_verify"><i></i> <?php echo JText::_("VERIFIED");?></span>
                            <div class="error_tooltip manual_tooltip_error" id="error_email"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="mobile"><?php echo JText::_("BETTING_FORM_MOBILE");?><span class="req_star">*</span></label>
                        <div class="<?php if($playerInfo->phoneVerified == 'N') echo 'do_verify'; else echo 'verify';?>" id="mobile_div">
                            <input type="tel" class="allow_only_nums custome_input" tabindex="10" id="mobile" name="mobile" value="<?php echo $playerInfo->mobileNo;?>" <?php if($playerInfo->phoneVerified == 'Y') echo 'disabled="disabled"';?> pattern="^[7-9]{1}[0-9]{9}$" maxlength="10"/>
                            <button class="btn_do_verify" type="button" id="mobileVerify-btn"><?php echo JText::_("VERIFY");?></button>
                            <span class="btn_verify"><i></i> <?php echo JText::_("VERIFIED");?></span>
                            <div class="error_tooltip manual_tooltip_error" id="error_mobile"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group last">
                        <label for="mobile"><?php echo JText::_("BETTING_FORM_GENDER");?><span class="req_star">*</span></label>
                        <div class="checkbox">
                            <label> <input type="radio" name="gender" checked="checked" tabindex="11" value="M" <?php if(isset($playerInfo->gender) && $playerInfo->gender == "M") echo 'checked'; ?> id="gender_m"/> Male </label>
                            <label> <input type="radio" name="gender" tabindex="12" value="F" <?php if(isset($playerInfo->gender) && $playerInfo->gender == "F") echo 'checked'; ?> id="gender_f"/> Female </label>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group last">
                        <button class="brown_bg btn_next" type="submit" tabindex="13"><?php echo JText::_("BETTING_NEXT");?></button>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="marquee"><marquee><?php echo JText::_("ACCOUNT_WITH");?> </marquee></div>
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
    <div class="cashier_footer2"><?php echo JText::_("ACCOUNT_TEXT");?>&nbsp;|&nbsp; <span><a href="#" onclick="openLiveChat();" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/chat_icon.png"> <?php echo JText::_("CHAT_BUTTON");?></a> &nbsp;|&nbsp; <a href="mailto:support@khelplayrummy.com"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/email_icon.png"> <?php echo JText::_("EMAIL_US");?></a></span></div>
</div>
<?php
if($playerInfo->emailVerified == "N" || $playerInfo->phoneVerified == "N") {
    $view = $this->obj->getView('account', 'html');
    if($playerInfo->emailVerified == "N") {
        $view->email_popup = "YES";
    }
    if($playerInfo->phoneVerified == "N") {
        $view->mobile_popup = "YES";
    }
    $view->display();
}

if(Session::getSessionVariable('dontShowDefaultError') === true) {
    Session::unsetSessionVariable('dontShowDefaultError');
    ?>
    <script>
        var ask_for_validation = true;
    </script>
    <?php
}
else {
    ?>
    <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border: none;background: none !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        <?php
                        if(Session::getSessionVariable('isDepositProcessable') === true) {
                            Session::unsetSessionVariable('isDepositProcessable');
                            echo Session::getSessionVariable('isDepositProcessableMsg');
                            Session::unsetSessionVariable('isDepositProcessableMsg');
                            ?>
                            <script>
                                var ask_for_validation = true;
                            </script>
                        <?php
                        }
                        else {
                        ?>
                            <script>
                                var ask_for_validation = false;
                            </script>
		                            <?php echo sprintf(JText::_("ACCOUNT_MSG"), $this->CurrData['decSymbol'], $this->CurrData['decSymbol']);?>

                            <!--Since your account has not been updated. Please complete your profile now.-->
                            <?php
                        }
                        ?>
                    </p>
                </div>
                <div class="modal-footer text-center"  style="border: none; text-align: center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<style>
    .modal-backdrop {
        position: fixed;
    }
</style>
