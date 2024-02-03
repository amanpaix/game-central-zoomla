<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$info = $this->options->redeemAccounts->chqAccInfo;
$bankRedAccMap = $this->options->redeemAccounts->bankRedAccMap;

$playerLoginResponse = Utilities::getPlayerLoginResponse();
$withdrawableBalance = explode(".", $playerLoginResponse->walletBean->withdrawableBal)[0];
$cashBalance = $playerLoginResponse->walletBean->cashBalance;

if((int)$cashBalance < (int)$withdrawableBalance) {
    $withdrawableBalance = $cashBalance;
    $withdrawableBalance = explode(".", $withdrawableBalance)[0];
}
$withdrawalAction = JRoute::_('index.php?task=withdrawal.withdrawalRequest');
?>
  
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
    var withdrawabalBalance = <?php echo $withdrawableBalance; ?>;
    var withdrawalAction = <?php echo json_encode($withdrawalAction); ?>;
</script>

<div class="myaccount_body_section">
    <div class="">
        <div class="heading">
            <h2><?php echo JText::_("WITHDRAWL_CASH");?></h2>
            <ul class="refer_friend_menu">
                <li class="active"><a href="<?php echo Redirection::WITHDRAWAL_PROCESS; ?>"><?php echo JText::_("WITHDRAWL_CASH");?></a></li>
                <li><a href="<?php echo Redirection::WITHDRAWAL_REQUEST; ?>"><?php echo JText::_("VIEW_STATUS");?></a></li>
        	<!-- <li><a href="<?php //echo Redirection::CASHIER_HELP; ?>"><?php //echo JText::_("CASHIER_HELP");?></a></li> -->
            </ul>
        </div>

        <div class="withdrawl_section">
            <div id="withdrawltab">
                <ul class="resp-tabs-list">
                    <?php
                    foreach($this->options->payTypeMap as $option) {
                        if($option->payTypeCode == Constants::WITHDRAWAL_CASH_TRANS && strtoupper(Utilities::getPlayerLoginResponse()->olaPlayer) == "YES") {
                            ?>
                            <li><span class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/cash_payment_icon.png"></span><span class="text"><?php echo $option->payTypeDispCode; ?></span></li>
                            <?php
                        }
                        else if($option->payTypeCode == Constants::WITHDRAWAL_CHEQUE_TRANS) {
                            ?>
                            <li><span class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/cheque_transfer_icon.png"></span><span class="text"><?php echo $option->payTypeDispCode; ?></span></li>
                            <?php
                        }
                        else if($option->payTypeCode == Constants::WITHDRAWAL_BANK_TRANS) {
                            ?>
                            <li><span class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/bank_transfer_icon.png"></span><span class="text"><?php echo $option->payTypeDispCode; ?></span></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <div class="resp-tabs-container">
                    <?php
                    foreach($this->options->payTypeMap as $option) {
                        if($option->payTypeCode == Constants::WITHDRAWAL_CASH_TRANS && strtoupper(Utilities::getPlayerLoginResponse()->olaPlayer) == "YES") {
                            ?>
                            <div payType="<?php echo $option->payTypeCode;?>">
                                 <!--<div class="marquee"><marquee><strong>Important:</strong>• After making a Deposit, you should play 100% of the deposited amount before you can request a Withdrawal. • Keep minimum Rs. 50 Cash Balance in your Account till we process your Withdrawal or else your Bonus Balance, if any, will expire. • Please check our Withdrawal Policies before you initiate a Withdrawal. </marquee></div>-->
			<?php
                                if($this->message){
                            ?>    
                                <div class="marquee"><marquee><?php echo " ".$this->message." "; ?></marquee></div>
                            <?php } ?>

                                <div class="title"><?php echo $option->payTypeDispCode; ?></div>
                                <div class="withdrawl_amount"><?php echo JText::_("WITHDRAWL_BAL");?> : <?php echo $this->CurrData['decSymbol'];?><strong><?php echo $withdrawableBalance; ?></strong></div>
                                <div class="row">
                                    <form action="<?php echo $withdrawalAction; ?>" name="cash-withdrawal-form" id="cash-withdrawal-form" method="POST">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_("ENTER_AMOUNT");?></label>
                                                        <input type="text" tabindex="1" class="custome_input" id="cashAmount" name="cashAmount" tabindex="1" maxlength="6">
                                                        <div class="error_tooltip manual_tooltip_error" id="error_cashAmount"></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group last">
                                                <button class="btnStyle1" id='cash-withdrawal-submit-btn' paytypecode="<?php echo $option->payTypeCode; ?>"><?php echo JText::_("SUBMIT");?></button>
                                                <input type="hidden" name="payTypeCode" id="payTypeCode" value="<?php echo $option->payTypeCode; ?>">
                                                <input type="hidden" name="payTypeId" id="payTypeId" value="<?php echo $option->payTypeId; ?>">
                                                <input type="hidden" name="payTypeName" id="payTypeName" value="<?php echo $option->payTypeDispCode; ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--Tab End-->
                            <?php
                        }
                        else if($option->payTypeCode == Constants::WITHDRAWAL_CHEQUE_TRANS) {
                            ?>
                            <div payType="<?php echo $option->payTypeCode;?>">
                                  <!--<div class="marquee"><marquee><strong>Important:</strong>• After making a Deposit, you should play 100% of the deposited amount before you can request a Withdrawal. • Keep minimum Rs. 50 Cash Balance in your Account till we process your Withdrawal or else your Bonus Balance, if any, will expire. • Please check our Withdrawal Policies before you initiate a Withdrawal. </marquee></div>-->
			<?php
                                if($this->message){
                            ?>    
                                <div class="marquee"><marquee><?php echo " ".$this->message." "; ?></marquee></div>
                            <?php } ?>
                                <div class="title"><?php echo $option->payTypeDispCode; ?></div>
                                <div class="withdrawl_amount"><?php echo JText::_("WITHDRAWL_BAL");?> : <?php echo $this->CurrData['decSymbol']?><strong><?php echo $withdrawableBalance; ?></strong></div>
                                <div class="row">
                                    <form action="<?php echo $withdrawalAction; ?>" name="cheque-withdrawal-form" id="cheque-withdrawal-form" method="POST">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_("ENTER_AMOUNT");?><span class="req_star">*</span></label>
                                                        <input type="tel" id="chequeAmount" name="chequeAmount"  tabindex="1" placeholder="<?php echo JText::_("ENTER_AMOUNT");?>" class="custome_input" maxlength="6">
                                                        <div class="error_tooltip manual_tooltip_error" id="error_chequeAmount"></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <div class="form-group mtop5">
                                                        <p><strong><?php echo JText::_("CHEQUE_FAVOUR_OF");?></strong>: <?php print_r($info->firstName." ".$info->lastName); ?><br><br>
                                                            <strong><?php echo JText::_("BETTING_FORM_ADDRESS");?></strong>: <br>
                                                            <?php
                                                            $address = '';
                                                            if(isset($info->houseNum) && !is_null($info->houseNum))
                                                                $address .= $info->houseNum.", ";

                                                            if(isset($info->addressLine1) && !is_null($info->addressLine1))
                                                                $address .= $info->addressLine1.", ";

                                                            if(isset($info->addressLine2) && !is_null($info->addressLine2))
                                                                $address .= $info->addressLine2.", ";

                                                            if(isset($info->city) && !is_null($info->city))
                                                                $address .= $info->city.", ";

                                                            if(isset($info->state) && !is_null($info->state))
                                                                $address .= $info->state.", ";

                                                            if(isset($info->country) && !is_null($info->country))
                                                                $address .= $info->country;

                                                            $address = trim($address, ",");
                                                            print_r($address);
                                                            ?>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group last">
                                                <button class="btnStyle1" style="padding:5px 10px;" id='cheque-withdrawal-submit-btn' paytypecode="<?php echo $option->payTypeCode; ?>"><?php echo JText::_("SUBMIT_BTN");?></button>
                                                <input type="hidden" name="payTypeCode" value="<?php echo $option->payTypeCode; ?>">
                                                <input type="hidden" name="payTypeId" value="<?php echo $option->payTypeId; ?>">
                                                <input type="hidden" name="isNewRedeemAcc" value="<?php if(is_null($info->redeemAccId)) echo 'Y'; else echo 'N';?>">
                                                <input type="hidden" name="payTypeName" id="payTypeName" value="<?php echo $option->payTypeDispCode; ?>">
                                                <?php
                                                if(isset($info->redeemAccId) && !is_null($info->redeemAccId)) {
                                                    ?>
                                                    <input type="hidden" name="redeemAccountId" value="<?php echo $info->redeemAccId; ?>">
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--Tab End-->
                            <?php
                        }
                        else if($option->payTypeCode == Constants::WITHDRAWAL_BANK_TRANS) {
                            $list = get_object_vars($option->subTypeMap);
                            asort($list);
                            ?>
                            <div payType="<?php echo $option->payTypeCode;?>">
                                 <!--<div class="marquee"><marquee><strong>Important:</strong>• After making a Deposit, you should play 100% of the deposited amount before you can request a Withdrawal. • Keep minimum Rs. 50 Cash Balance in your Account till we process your Withdrawal or else your Bonus Balance, if any, will expire. • Please check our Withdrawal Policies before you initiate a Withdrawal. </marquee></div>-->

			<?php
                                if($this->message){
                            ?>    
                                <div class="marquee"><marquee><?php echo " ".$this->message." "; ?></marquee></div>
                            <?php } ?>

                                <div class="title"><?php echo JText::_("WITH_BANK_TRANSFER");?></div>
                                <div class="withdrawl_amount"><?php echo JText::_("WITH_BAL");?> : <?php echo $this->CurrData['decSymbol'];?><strong><?php echo $withdrawableBalance; ?></strong></div>
                                <div class="row">
                                    <form action="<?php echo $withdrawalAction; ?>" name="bank-withdrawal-form" id="bank-withdrawal-form" method="POST">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_("ENTER_AMOUNT");?><span class="req_star">*</span></label>
                                                        <input type="tel" tabindex="1" placeholder="<?php echo JText::_("ENTER_AMOUNT");?>" class="custome_input" name="bankAmount" id="bankAmount" maxlength="6">
                                                        <div class="error_tooltip manual_tooltip_error" id="error_bankAmount"></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if(!is_null($bankRedAccMap)) {
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-xs-12" id="selectAccount-div">
                                                <div class="row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("REG_NO");?></label>
                                                            <div class="select_box">
                                                                <select class="custome_input" tabindex="10" name="selectAccount" id="selectAccount">
                                                                    <option value="select"><?php echo JText::_("SELECT_AMOUNT");?></option>
                                                                    <?php
                                                                    foreach($bankRedAccMap as $acc) {
                                                                        ?>
                                                                        <option value="<?php echo $acc->redeemAccId; ?>" acc_status="<?php echo $acc->status; ?>"><?php echo $acc->bankName."-".$acc->accNum; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="error_tooltip manual_tooltip_error" id="error_selectAccount"></div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12" id="add-new-div">
                                                <div class="form-group last">
                                                    <a href="javascript:void(0);" paytypecode="<?php echo $option->payTypeCode; ?>" id="submit-without-add" class="btnStyle1">Submit</a>
                                                    <div class="add_account_link">
                                                        <a href="javascript:void(0);" id="add-new-btn"> <i class="fa fa-plus" aria-hidden="true"></i> Add New Account</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" name="payTypeCode" value="<?php echo $option->payTypeCode; ?>">
                                        <input type="hidden" name="payTypeId" value="<?php echo $option->payTypeId; ?>">
                                        <input type="hidden" name="payTypeName" id="payTypeName" value="<?php echo $option->payTypeDispCode; ?>">
                                    </form>
                                    <div class="col-md-12 col-sm-12 col-xs-12 add_new_account" id="add_account" style="display: <?php if(!is_null($bankRedAccMap)) echo 'none'; else echo 'block';?>">
                                        <form action="<?php echo $withdrawalAction; ?>" method="post" name="add-new-form" id="add-new-form">
                                            <div class="divider col-md-12 col-xs-12 col-sm-12">&nbsp;</div>
                                            <div class="title"><?php echo JText::_("ADD_BANK_MSG");?><?php print_r($info->firstName." ".$info->lastName); ?></strong></p></div>
                                            <input type="hidden" name="accHolderName" value="<?php print_r($info->firstName." ".$info->lastName); ?>">
                                            <div class="profile_details">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("ACCNO");?><span class="req_star">*</span></label>
                                                            <input type="tel" tabindex="1" placeholder="<?php echo JText::_("ACCNO");?>" class="custome_input" name="accNo" id="accNo" maxlength="20">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_accNo"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("CNFRM_ACCNO");?><span class="req_star">*</span></label>
                                                            <input type="tel" tabindex="2" placeholder="<?php echo JText::_("ACCNO");?>" class="custome_input" name="retypeAccNo" id="retypeAccNo" maxlength="20">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_retypeAccNo"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mobile"><?php echo JText::_("ACCNO_TYPE")?><span class="req_star">*</span></label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" name="actype" value="SAVING" tabindex="3" checked> <span></span><?php echo JText::_("BETTING_SAVINGS");?>
                                                                </label>
                                                                <label>
                                                                    <input type="radio" name="actype" value="CURRENT" tabindex="4"> <span></span><?php echo JText::_("CURRENT_C");?>
                                                                </label>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("BANK_NO");?><span class="req_star">*</span></label>
                                                            <div class="select_box">
                                                                <select class="custome_input" tabindex="5" name="bankName" id="bankName">
                                                                    <option value="select"><?php echo JText::_("BANK_NAME");?></option>
                                                                    <?php
                                                                    foreach($list as $key => $value) {
                                                                        ?>
                                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="error_tooltip manual_tooltip_error" id="error_bankName"></div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("BRANCH_LOC");?></label>
                                                            <input type="text" tabindex="6" placeholder="<?php echo JText::_("BRANCh_LOC");?>" class="custome_input" name="branchLocation" id="branchLocation">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_branchLocation"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("BRANCH_CITY");?><span class="req_star">*</span></label>
                                                            <input type="text" tabindex="7" placeholder="<?php echo JText::_("BRANCH_CITY");?>" class="custome_input" name="branchCity" id="branchCity">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_branchCity"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("BRANCH_ADDRESS");?></label>
                                                            <textarea class="custome_input" style="height:131px;" tabindex="8" placeholder="<?php echo JText::_("BRANCH_ADDRESS");?>" rows="4" name="branchAddress" id="branchAddress"></textarea>
                                                            <div class="error_tooltip manual_tooltip_error" id="error_branchAddress"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("IFSC_CODE");?><span class="req_star">*</span></label>
                                                            <input type="text" tabindex="9" placeholder="IFSC Code" class="custome_input" name="ifsc" id="ifsc" maxlength="20">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_ifsc"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_("MICR_CODE");?></label>
                                                            <input type="tel" tabindex="10" placeholder="<?php echo JText::_("MICR_CODE");?>" class="custome_input" name="micr" id="micr" maxlength="20">
                                                            <div class="error_tooltip manual_tooltip_error" id="error_micr"></div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group last">
                                                            <a href="javascript:void(0);" type="submit" id="submit-with-add" style="padding:5px 10px;" class="btnStyle1"><?php echo JTExt::_("SUBMIT_BTN");?></a>
                                                            <?php if(!is_null($bankRedAccMap)) {
                                                                ?>
                                                                <a href="javascript:void(0);" id="cancel-add" class="cancel btnStyle2"><?php echo JText::_("CANCEL_BTN");?></a>
                                                                <?php
                                                            }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="payTypeCode" value="<?php echo $option->payTypeCode; ?>">
                                            <input type="hidden" name="payTypeId" value="<?php echo $option->payTypeId; ?>">
                                            <input type="hidden" name="payTypeName" id="payTypeName" value="<?php echo $option->payTypeDispCode; ?>">
                                        </form>
                                    </div>
                                </div>
                            </div><!--Tab End-->
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div><script>
    var CASH_PAYMENT = '<?php echo Constants::WITHDRAWAL_CASH_TRANS;?>';
    var CHEQUE_TRANS = '<?php echo Constants::WITHDRAWAL_CHEQUE_TRANS;?>';
    var BANK_TRANS = '<?php echo Constants::WITHDRAWAL_BANK_TRANS;?>';
    var REDEEMACCOUNTLIST = '<?php print_r(is_null($bankRedAccMap))?>';
</script>
<?php
Html::addJs(JUri::base()."/templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base()."/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base()."/templates/shaper_helix3/js/custom/withdrawal.js");
?>
