<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$credit_card_id_images = Constants::CREDIT_CARD_ID_IMAGES;
$debit_card_id_images = Constants::DEBIT_CARD_ID_IMAGES;
$top_banks = Constants::NET_BANKING_TOP_BANKS;
$prepaid_wallet_images = Constants::PREPAID_WALLET_IMAGES;
$mobile_wallet_images = Constants::MOBILE_WALLET_IMAGES;

Html::addJs(JUri::base() . "templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "templates/shaper_helix3/css/bootstrap-datepicker.min.css");
$playerLoginResponse = Utilities::getPlayerLoginResponse();
?>
<style>
    div.modal.in .modal-dialog {
        margin: 70px auto;
    }
    .modal-backdrop {
        position: fixed;
    }
</style>
<div class="cashier_header">
    Select Amount
    <?php
    if (!((strtoupper($playerLoginResponse->playerStatus) == "FULL" || strtoupper($playerLoginResponse->playerStatus) == "ACTIVE") && $playerLoginResponse->emailVerified == "Y" && $playerLoginResponse->phoneVerified == "Y")) {
        ?>
        <span class="mobile_back_btn">
            <a href="<?php echo Redirection::CASHIER_INITIATE . "?rd=t"; ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</a>
            <a href="javascript:void(0);" onclick="window.close();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></a>
        </span>
        <?php
    } else {
        ?>
        <span class="mobile_back_btn">
            <a href="javascript:void(0);" onclick="window.close();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close1.png"></a>
        </span>
        <?php
    }
    ?>
</div>
<div class="amount_div">
    <?php $test = '10000.0'; ?>
    <label class="amount_tab" id="amount_10000_div" amount="10000">
        <div class="amout"><?php echo $this->CurrData['decSymbol']?><span>10,000</span></div>
        <div class="bonus_strip">
            <?php if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                ?>
                <?php echo $this->CurrData['decSymbol']?>
                <?php
                if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                    $test = '10000.0';
                    print_r($this->bonusMap->$test);
                }
                ?> <?php echo JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php
            } else {
                ?>
                <?php echo JText::_("NO_BT")." ".JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php }
            ?>
        </div>
        <input type="radio" name="amount" id="amount_10000" value="10000">
    </label>
    
    <?php $test = '1000.0'; ?>
    <label class="amount_tab" id="amount_1000_div" amount="1000">
        <div class="amout"><?php echo $this->CurrData['decSymbol']?><span>1,000</span></div>
        <div class="bonus_strip">
            <?php if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                ?>
                <?php echo $this->CurrData['decSymbol']?>
                <?php
                if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                    $test = '1000.0';
                    print_r($this->bonusMap->$test);
                }
                ?> <?php echo JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php
            } else {
                ?>
                <?php echo JText::_("NO_BT")." ".JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php }
            ?>
        </div>
        <input type="radio" name="amount" id="amount_1000" value="1000">
    </label>

    <?php $test = '500.0'; ?>
    <label class="amount_tab selected" id="amount_500_div" amount="500">
        <div class="amout"><?php echo $this->CurrData['decSymbol']?><span>500</span></div>
        <div class="bonus_strip">
            <?php if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                ?>
                <?php echo $this->CurrData['decSymbol']?>
                <?php
                if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                    $test = '500.0';
                    print_r($this->bonusMap->$test);
                }
                ?> <?php echo JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php
            } else {
                ?>
                <?php echo JText::_("NO_BT")." ".JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php }
            ?>
        </div>
        <input type="radio" name="amount" id="amount_500" value="500" checked>
    </label>

    <?php $test = '100.0'; ?>
    <label class="amount_tab" id="amount_100_div" amount="100">
        <div class="amout"><?php echo $this->CurrData['decSymbol']?><span>100</span></div>
        <div class="bonus_strip">
            <?php if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                ?>
                <?php echo $this->CurrData['decSymbol']?>
                <?php
                if (isset($this->bonusMap->$test) && $this->bonusMap->$test != 0) {
                    $test = '100.0';
                    print_r($this->bonusMap->$test);
                }
                ?> <?php echo JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php
            } else {
                ?>
               <?php echo JText::_("NO_BT")." ".JText::_("TRANSECTION_BONUS_DETAIL_BONUS");?>
                <?php }
            ?>
        </div>
        <input type="radio" name="amount" id="amount_100" value="100">
    </label>

    <label class="amount_tab other" id="amount_other_div">
        <div class="other_amount">
            <?php echo $this->CurrData['decSymbol']?>
            <input type="tel" id="other_amount" name="other_amount" maxlength="5">
        </div>
        <div class="bonus_strip"><?php echo JText::_("OTHER_AMOUNT");?></div>
        <input type="radio" name="amount" id="amount_other">
    </label>
    <label class="amount_tab bonus" id="amount_bonus_div">
        <input type="text" class="enter_promo" name="promoCode" id="promoCode" placeholder="<?php echo JText::_("BONUS_CODE");?>">
        <?php
        if (count($this->promoCodeList) > 0) {
            if ($playerLoginResponse->firstDepositDate == "" && Configuration::getDeviceType() == "PC") {
                ?>
                <div>
                    <a href="javascript:void(0);"><?php echo JText::_("CHECK_AVAIL_BONUS");?></a>
                    <?php
                    foreach ($this->promoCodeList as $promoCodeData) {
                        ?>
                        <p><?php echo JText::sprintf("INST_TO_USE_BONUS"
                        ,$promoCodeData->promoCode
                        ,$promoCodeData->bonusValue . ( ($promoCodeData->bonusValueType != "PERCENT") ? ' ' . $promoCodeData->bonusValueAs : '% ' . $promoCodeData->bonusValueAs)
                        ,'<a href="javascript:void(0);" onclick="selectPromoCode('. $promoCodeData->promoCode.');">'.JText::_("USE_CODE").'</a>')?></p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div>
                    <a data-toggle="modal" data-target=".bonus_details" href="javascript:void(0);"><?php echo JText::_("CHECK_AVAIL_BONUS");?></a>
                </div>
        <?php
    }
} else {
    ?>
            <div>
                <a class="non_clickable" href="javascript:void(0);" onclick="document.getElementById('promoCode').focus();"><?php echo JText::_("ENTER_COUPON");?></a>
            </div>
    <?php
}
?>
    </label>

        <?php
        if ($playerLoginResponse->firstDepositDate != "" || Configuration::getDeviceType() !== "PC") {
            ?>
        <div class="modal fade bonus_details">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close.png"></button>
                        <h4 class="modal-title"><?php echo JText::_("REGULAR_BONUS");?></h4>
                    </div>
                    <div class="modal-body">
                        <ul>
    <?php
    if (count($this->promoCodeList) > 0) {
        foreach ($this->promoCodeList as $promoCodeData) {
            ?>
                                    <li>
                                        <div class="group">
                                            <div class="left">
                                                <strong><?php echo $promoCodeData->bonusName; ?>:</strong> Deposit between <?php echo $this->CurrData['decSymbol']?><?php echo $promoCodeData->activityValueMin; ?> - <?php echo $this->CurrData['decSymbol']?><?php echo $promoCodeData->activityValueMax; ?> and Get <?php echo $promoCodeData->bonusValue . ( ($promoCodeData->bonusValueType != "PERCENT") ? ' ' . $promoCodeData->bonusValueAs : '% ' . $promoCodeData->bonusValueAs) ?>.
                                            </div>
                                            <div class="right"><a href="javascript:void(0);" onclick="selectPromoCode('<?php echo $promoCodeData->promoCode; ?>');">(Use Code)<br><strong><?php echo $promoCodeData->promoCode; ?></strong></a>
                                            </div>
                                        </div>
                                    </li>
            <?php
        }
    } else {
        ?>
                                <p class="text-center"><?php echo JText::_("NO_BONUS_AVAILABLE");?></p>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    <?php
}
?>

    <div class="error_strip">
        <span></span>
        <a href="javascript:void(0);" class="close" onclick="hideErrorStrip();">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close.png">
        </a>
    </div>
</div>

<div id="cashiertab">
<?php
if (count($this->paymentOptionsResponse) > 0) {
    ?>
        <ul class="resp-tabs-list">
        <?php
        foreach ($this->paymentOptionsResponse->payTypeMap as $paymentOptions) {
            ?>
                <li thisPaytypeId = <?php echo $paymentOptions->payTypeId; ?> ><?php echo $paymentOptions->payTypeDispCode; ?></li>
                <?php
            }
            ?>
        </ul>
            <?php }
        ?>


    <div class="resp-tabs-container">
    <?php
            
    foreach ($this->paymentOptionsResponse->payTypeMap as $paymentOptions) {
        $paymentOptions->subTypeMap = get_object_vars($paymentOptions->subTypeMap);
        ?>
            <div paytypeid="<?php echo $paymentOptions->payTypeId; ?>" paytypecode="<?php echo $paymentOptions->payTypeCode; ?>">
                <div class="card_content <?php echo strtolower(Constants::$paytypeCode_class[$paymentOptions->payTypeCode]); ?>">
                    <div class="content_inner">
            <?php
            if ($paymentOptions->payTypeCode == Constants::CREDIT_CARD_DEPOSIT) {
                ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="card_type">
                            <?php
                            $tmp_subTypeMapPortal = [];
                            foreach ($paymentOptions->subTypeMapPortal as $subTypeCode => $subTypeValue) {
                                $tmp_subTypeMapPortal[explode("#", $subTypeValue)[1]] = ["subTypeCode" => $subTypeCode, "subTypeValue" => explode("#", $subTypeValue)[0]];
                            }
                            ksort($tmp_subTypeMapPortal);
                            foreach ($tmp_subTypeMapPortal as $subTypeMap) {
                                ?>
                                    <label class="card_tab" id="<?php if (isset($credit_card_id_images[$subTypeMap['subTypeValue']])) {
                            echo $credit_card_id_images[$subTypeMap['subTypeValue']]["id"];
                        } ?>">
                                        <div class="card_icon">
                                            <img src="<?php if (isset($credit_card_id_images[$subTypeMap['subTypeValue']])) {
                            echo $credit_card_id_images[$subTypeMap['subTypeValue']]["src"];
                        } ?>">
                                        </div>
                                        <input type="radio" name="creditcard" id="card_<?php echo $subTypeMap['subTypeValue']; ?>" value="<?php echo $subTypeMap['subTypeCode']; ?>">
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if ($paymentOptions->payTypeCode == Constants::DEBIT_CARD_DEPOSIT) {
                            ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="card_type">
                                <?php
                                $tmp_subTypeMapPortal = [];
                                foreach ($paymentOptions->subTypeMapPortal as $subTypeCode => $subTypeValue) {
                                    $tmp_subTypeMapPortal[explode("#", $subTypeValue)[1]] = ["subTypeCode" => $subTypeCode, "subTypeValue" => explode("#", $subTypeValue)[0]];
                                }
                                ksort($tmp_subTypeMapPortal);
                                foreach ($tmp_subTypeMapPortal as $subTypeMap) {
                                    ?>
                                    <label class="card_tab" id="<?php if (isset($debit_card_id_images[$subTypeMap['subTypeValue']])) {
                            echo $debit_card_id_images[$subTypeMap['subTypeValue']]["id"];
                        } ?>">
                                        <div class="card_icon">
                                            <img src="<?php if (isset($debit_card_id_images[$subTypeMap['subTypeValue']])) {
                            echo $debit_card_id_images[$subTypeMap['subTypeValue']]["src"];
                        } ?>">
                                        </div>
                                        <input type="radio" name="creditcard" id="card_<?php echo $subTypeMap['subTypeValue']; ?>" value="<?php echo $subTypeMap['subTypeCode']; ?>">
                                    </label>
                                <?php
                            }
                            ?>
                            </div>
                            <?php
                        }
                        ?>

    <?php
    if ($paymentOptions->payTypeCode == Constants::NET_BANKING_DEPOSIT) {
        ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="banck_icon">
                                <ul id="top_bank_list">
                                    <?php
                                    $tmp_subTypeMap = [];
                                    foreach ($paymentOptions->subTypeMap as $k => $v) {
                                        $tmp_subTypeMap[intval($k)] = $v;
                                    }

                                    foreach ($top_banks as $bank => $img_ext) {
                                        if (isset($tmp_subTypeMap[$bank])) {
                                            ?>
                                            <li>
                                                <label>
                                                    <input type="radio" name="bank_logo" id="bank_<?php echo $tmp_subTypeMap[$bank]; ?>" value="<?php echo $bank; ?>">
                                                    <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/bank_<?php echo $bank . $img_ext ?>">
                                                </label>
                                            </li>
                <?php
            }
        }
        ?>
                                    <script>
                                        $(document).ready(function () {
                                            var net_banking_bank_list_max_height = 0;
                                            $($(".net_banking .banck_icon>ul#top_bank_list>li")).each(function () {
                                                if (parseInt($(this).css("height").substring(0, ($(this).css("height").length - 2))) > net_banking_bank_list_max_height) {
                                                    net_banking_bank_list_max_height = parseInt($(this).css("height").substring(0, ($(this).css("height").length - 2)));
                                                }
                                            });

                                            if (net_banking_bank_list_max_height > 0) {
                                                $(".net_banking .banck_icon>ul#top_bank_list>li").css('height', net_banking_bank_list_max_height);
                                            }
                                        });
                                    </script>
                                </ul>
                            </div>
        <?php asort($paymentOptions->subTypeMap); ?>
                            <div class="form-group">
                                <label><?php echo JText::_("ALL_BANKS");?></label>
                                <div class="select_box">
                                    <select class="custome_input" id="<?php echo $paymentOptions->payTypeCode; ?>_LIST" name="<?php echo $paymentOptions->payTypeCode; ?>_LIST">
                                        <option value="select"><?php echo JText::_("PLEASE_SELECT");?></option>
                                        <?php
                                        foreach ($paymentOptions->subTypeMap as $subTypeCode => $subTypeValue) {
                                            ?>
                                            <option value="<?php echo $subTypeCode; ?>"><?php echo $subTypeValue; ?></option>
            <?php
        }
        ?>
                                    </select>
                                    <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_LIST"></div>
                                </div>
                            </div>

                            <?php
                        }

                        if ($paymentOptions->payTypeCode == Constants::CHEQUE_TRANS_DEPOSIT) {
                            ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("CHEQUE_NO");?><span class="req_star">*</span> :</label>
                                        <input type="tel" placeholder="Cheque No" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_NO" id="<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_NO" maxlength="20"/>
                                        <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_NO"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("BANK_NAME");?><span class="req_star">*</span> :</label>
                                        <input type="text" placeholder="Bank Name" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME" id="<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME" maxlength="30"/>
                                        <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("LOYALTY_DATE");?><span class="req_star">*</span> :</label>
                                        <div class="input-group date" id="datepicker1">
                                            <input type="text" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_DATE" id="<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_DATE" tabindex="7" readonly="readonly">
                                            <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                                            <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_CHEQUE_DATE"></div>
                                        </div>
                                        <script>
                                            $(document).ready(function () {
                                                var d = new Date();
                                                var year = d.getFullYear();
                                                if ((d.getMonth() + 1) < 10)
                                                    var month = "0" + (d.getMonth() + 1);
                                                else
                                                    var month = d.getMonth() + 1;

                                                if (d.getDate() < 10)
                                                    var day = "0" + d.getDate();
                                                else
                                                    var day = d.getDate();

                                                var current = day + '/' + month + '/' + year;
                                                $('#datepicker1>input').val(current);

                                                var newDate = new Date();
                                                newDate.setMonth(newDate.getMonth() - 3);
                                                var d = new Date(newDate);
                                                $('#datepicker1').datepicker({
                                                    format: "dd/mm/yyyy",
                                                    autoclose: true,
                                                    startDate: d
        //                                        endDate: "0d"
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="note">
                                <?php echo JText::_("CHEQUE_LINE");?>
                            </div>
                            <?php
                        }

                        if ($paymentOptions->payTypeCode == Constants::WIRE_TRANS_DEPOSIT) {
                            ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("REFRENCE_NO");?><span class="req_star">*</span>  :</label>
                                        <input type="text" placeholder="<?php echo JText::_("REFRENCE_NO");?>" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_REFERENCE_NO" id="<?php echo $paymentOptions->payTypeCode; ?>_REFERENCE_NO"/>
                                        <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_REFERENCE_NO"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("CASHIER_FORM_DATE");?><span class="req_star">*</span>  :</label>
                                        <div class="input-group date" id="datepicker2">
                                            <input type="text" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_DATE" id="<?php echo $paymentOptions->payTypeCode; ?>_DATE" tabindex="7" readonly="readonly">
                                            <button class="btn_date input-group-addon" type="button" tabindex="8"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/calendar_icon.png" alt=""></button>
                                            <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_DATE"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("BANK_NO");?><span class="req_star">*</span>  :</label>
                                        <div class="select_box">
                                            <select class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME" id="<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME">
                                                <option value="select"><?php echo JText::_("PLEASE_SELECT");?></option>
                                                <?php foreach ($paymentOptions->providerMap as $providerMapCode => $providerMapValue) {
                                                    ?>
                                                    <option value="<?php echo $providerMapCode; ?>"><?php echo $providerMapValue; ?></option>
            <?php }
        ?>
                                            </select>
                                            <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_BANK_NAME"></div>
                                            <script>
                                                $(document).ready(function () {
                                                    var d = new Date();
                                                    var year = d.getFullYear();
                                                    if ((d.getMonth() + 1) < 10)
                                                        var month = "0" + (d.getMonth() + 1);
                                                    else
                                                        var month = d.getMonth() + 1;

                                                    if (d.getDate() < 10)
                                                        var day = "0" + d.getDate();
                                                    else
                                                        var day = d.getDate();

                                                    var current = day + '/' + month + '/' + year;
                                                    $('#datepicker2>input').val(current);

                                                    var newDate = new Date();
                                                    newDate.setMonth(newDate.getMonth() - 3);
                                                    var d = new Date(newDate);
                                                    $('#datepicker2').datepicker({
                                                        format: "dd/mm/yyyy",
                                                        autoclose: true,
                                                        startDate: d
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a data-toggle="modal" data-target=".bank_details" href="javascript:void(0);" class="view_details"><?php echo JText::_("VIEW_BANK_DETAILS_BTN");?></a>
                                <div class="modal fade bank_details">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/close.png"></button>
                                                <h4 class="modal-title"><?php echo JText::_("BANK_DET");?></h4>
                                                <div class="print_btn_mobile"><a href="javascript:void(0);" onclick="window.print();"><i class="fa fa-print"></i> <?php echo JText::_("CASHIER_PRINT");?></a></div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="bankdetailstab">
                                                    <div class="tabs">
                                                        <ul class="tabs-menu">
                                                            <li class="current"><a href="#tab-1"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/bankdetails_icon1.png"></a></li>
                                                            <li><a href="#tab-2"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/bankdetails_icon2.png"></a></li>
                                                        </ul>
                                                        <div class="print_btn"><a href="javascript:void(0);" onclick="window.print();"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/cashier/print_btn.png"></a></div>
                                                    </div>
                                                    <div class="tab">
                                                        <div id="tab-1" class="tab-content">
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo Jtext::_("CASHIER_BENE")?></strong> : </div>
                                                                <div class="col-md-9"><?php echo JText::_("BANK_NAME");?></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("ACC_NOO");?></strong> : </div>
                                                                <div class="col-md-9">04202320000382</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("BANK_NAME");?></strong> : </div>
                                                                <div class="col-md-9">HDFC BANK</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("IFSC_CODE");?></strong> : </div>
                                                                <div class="col-md-9">HDFC0000420</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("MICR_NO");?></strong> : </div>
                                                                <div class="col-md-9">737240002</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("BRANCH_ADDRESS");?></strong> : </div>
                                                                <div class="col-md-9">YAMA BUILDING, M G MARG, GANGTOK, SIKKIM 737101</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("OFFICE_ADDR");?></strong> : </div>
                                                                <div class="col-md-9">Sachar Gaming PVT LTD <br/>903-904, Krushal Commercial Complex, <br/>Above Shoppers Stop, M.G. Road, Chembur (W), Mumbai</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("PIN_CODE");?></strong> : </div>
                                                                <div class="col-md-9">400089</div>
                                                            </div>
                                                        </div>
                                                        <div id="tab-2" class="tab-content">
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("CASHIER_BENE");?></strong> : </div>
                                                                <div class="col-md-9">Sachar Gaming Pvt. Ltd.</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("ACC_NOO");?></strong> : </div>
                                                                <div class="col-md-9">121405000144</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("BANK_NO");?></strong> : </div>
                                                                <div class="col-md-9">ICICI BANK</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("IFSC_CODE");?></strong> : </div>
                                                                <div class="col-md-9">ICIC0001214</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("MICR_NO");?></strong> : </div>
                                                                <div class="col-md-9">400229130</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("BRANCH_ADDRESS");?></strong> : </div>
                                                                <div class="col-md-9">27, Aniruddha Building, Tilak Nagar,Mumbai - 400 089</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("OFFICE_ADDR");?></strong> : </div>
                                                                <div class="col-md-9">Sachar Gaming PVT LTD <br/>903-904, Krushal Commercial Complex, <br/>Above Shoppers Stop, M.G. Road, Chembur (W), Mumbai</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"><strong><?php echo JText::_("PIN_CODE");?></strong> : </div>
                                                                <div class="col-md-9">400089</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="payment_note"><?php echo JText::_("PAYMENT_NOTICE");?>
                                                    <a href="mailto:support@khelplayrummy.com">support@khelplayrummy.com</a></p>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                            </div>
                            <?php
                        }

                        if ($paymentOptions->payTypeCode == Constants::CASH_CARD_DEPOSIT) {
                            ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("SELECT_CARD");  ?><span class="req_star">*</span>  :</label>
                                        <div class="select_box">
                                            <select class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_CARD_LIST" id="<?php echo $paymentOptions->payTypeCode; ?>_CARD_LIST">
                                                <option value="select"><?php echo JText::_("PLEASE_SELECT");t?></option>
                                                <?php
                                                foreach ($paymentOptions->subTypeMap as $subTypeCode => $subTypeValue) {
                                                    ?>
                                                    <option value="<?php echo $subTypeCode; ?>"><?php echo $subTypeValue; ?></option>
            <?php
        }
        ?>
                                            </select>
                                            <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_CARD_LIST"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("PIN_NO");?><span class="req_star">*</span>  :</label>
                                        <input type="tel" placeholder="Pin No" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_PIN_NO" id="<?php echo $paymentOptions->payTypeCode; ?>_PIN_NO" pattern="^[0-9]*$"/>
                                        <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_PIN_NO"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label><?php echo JText::_("SERIAL_NO");?><span class="req_star">*</span>  :</label>
                                        <input type="tel" placeholder="<?php echo JText::_("SERIAL_NO");?>" class="custome_input" name="<?php echo $paymentOptions->payTypeCode; ?>_SERIAL_NO" id="<?php echo $paymentOptions->payTypeCode; ?>_SERIAL_NO" pattern="^[0-9]*$"/>
                                        <div class="error_tooltip manual_tooltip_error" id="error_<?php echo $paymentOptions->payTypeCode; ?>_SERIAL_NO"></div>
                                    </div>
                                </div>
                            </div>
        <?php
    }
    if ($paymentOptions->payTypeCode == Constants::PREPAID_WALLET_DEPOSIT) {
        ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="banck_icon">
                                <ul>
                                    <?php
                                    foreach ($paymentOptions->subTypeMap as $subTypeCode => $subTypeValue) {
                                        if (isset($prepaid_wallet_images[$subTypeCode])) {
                                            ?>
                                            <li><label><input type="radio" name="wallets" id="card_<?php echo $subTypeValue; ?>" value="<?php echo $subTypeCode; ?>"> <img src="<?php echo $prepaid_wallet_images[$subTypeCode]; ?>"></label></li>
                                    <?php
                                }
                            }
                            ?>
                                </ul>
                            </div>
        <?php
    }
  
    if ($paymentOptions->payTypeCode == Constants::PAYTM_WALLET_DEPOSIT) {
        ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="note1">
                                <?php echo JText::_("NOTICE_CASHIER");?>
                            </div>
                            <?php
                        }
                        ?>

                                <?php
                                if ($paymentOptions->payTypeCode == Constants::MOBILE_WALLET_DEPOSIT) {
                                    ?>
                            <div class="title_header"><?php echo $paymentOptions->payTypeDispCode; ?></div>
                            <div class="banck_icon">
                                <ul>

                                    <?php
                                    foreach ($paymentOptions->subTypeMap as $subTypeCode => $subTypeValue) {
                                        if (isset($mobile_wallet_images[$subTypeCode])) {
                                            ?>
                                            <li><label><input type="radio" name="wallets" id="card_<?php echo $subTypeValue; ?>" value="<?php echo $subTypeCode; ?>"> <img src="<?php echo $mobile_wallet_images[$subTypeCode]; ?>" height="37" ></label></li>

                                    <?php
                                }
                            }
                            ?>
                                </ul>
                            </div>
        <?php
    }
    ?>

                    </div>
                    <a href="javascript:void(0);" class="brown_bg add_cash" add_cash_button="true"><?php echo JText::_("BETTING_ADD_CASH");?></a>
                    <div class="clearfix"></div>
                </div>
            </div>
    <?php
}
?>
    </div>
</div>
<!--<div class="marquee"><marquee><strong>Important:</strong>• After making a Deposit, you should play 100% of the deposited amount before you can request a Withdrawal. Using code is compulsory for availing any bonus.</marquee></div>-->
<?php
if ($this->message) {
    ?>    
    <div class="marquee"><marquee><?php echo " " . $this->message . " "; ?></marquee></div>
<?php } ?>
<div class="cashier_footer">
    <div class="cashier_footer1">
        <span class="fleft"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/safe_secure.jpg"></span>
        <span class="fright">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/visa.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/paytm.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/ebs.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/techprocess.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/atom.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/payumoney.jpg">
            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/citrus.jpg">
        </span>
    </div>
    <div class="cashier_footer1 mobile">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/safe_secure.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/visa.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/paytm.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/ebs.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/techprocess.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/atom.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/payumoney.jpg">
        <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/citrus.jpg">
        </span>
    </div>
    <div class="cashier_footer2"><?php echo JText::_("ACCOUNT_TEXT");?> &nbsp;|&nbsp; <span><a href="#" onclick="openLiveChat();" ><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/chat_icon.png"> <?php echo JText::_("CHAT_BUTTON");?></a> &nbsp;|&nbsp; <a href="mailto:support@khelplayrummy.com"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/email_icon.png"> <?php echo JText::_("EMAIL_US");?></a></span></div>
</div>

<form id="payment-request-form" name="payment-request-form" style="display: none;" method="post" action="<?php echo JRoute::_('index.php?task=cashier.paymentInitiate'); ?>"></form>

<script>
    var CREDIT_CARD = '<?php echo Constants::CREDIT_CARD_DEPOSIT; ?>';
    var DEBIT_CARD = '<?php echo Constants::DEBIT_CARD_DEPOSIT; ?>';
    var NET_BANKING = '<?php echo Constants::NET_BANKING_DEPOSIT; ?>';
    var CHEQUE_TRANS = '<?php echo Constants::CHEQUE_TRANS_DEPOSIT; ?>';
    var WIRE_TRANS = '<?php echo Constants::WIRE_TRANS_DEPOSIT; ?>';
    var CASH_CARD = '<?php echo Constants::CASH_CARD_DEPOSIT; ?>';
    var PREPAID_WALLET = '<?php echo Constants::PREPAID_WALLET_DEPOSIT; ?>';
    var PAYTM_WALLET = '<?php echo Constants::PAYTM_WALLET_DEPOSIT; ?>';
    var FREECHARGE_WALLET = '<?php echo Constants::FREECHARGE_WALLET;?>';
    var CASH_PAYMENT = '<?php echo Constants::CASH_PAYMENT_DEPOSIT; ?>';
    var MOBILE_WALLET = '<?php echo Constants::MOBILE_WALLET_DEPOSIT; ?>';
    var UPI_PAYMENT = '<?php echo Constants::UPI_DEPOSIT; ?>';


    $(window).load(function () {
        if (window.opener != null)
            $(".zopim").hide();

<?php
if (isset($this->paymentOptionsResponse->lastDepInfoBean)) {
    ?>
            var lastPaymentTypeId = '<?php echo $this->paymentOptionsResponse->lastDepInfoBean->paymentTypeId; ?>';
            var lastPaymentSubTypeId = '<?php echo $this->paymentOptionsResponse->lastDepInfoBean->paymentSubTypeId; ?>';
            var lastAmount = '<?php echo $this->paymentOptionsResponse->lastDepInfoBean->amount; ?>';

            var lastpayTypeCode = '';

            if ($("[amount=" + lastAmount + "]").length > 0)
            {
                $("#amount_10000_div").removeClass("selected");
                $("#amount_5000_div").removeClass("selected");
                $("#amount_1000_div").removeClass("selected");
                $("#amount_500_div").removeClass("selected");
                $("#amount_100_div").removeClass("selected");
                $("#amount_50_div").removeClass("selected");
                $("#amount_other_div").removeClass("selected");
                $(".amount_tab").find("input[type='radio']").iCheck("uncheck");
                $("#amount_" + lastAmount + "_div").addClass("selected");
                $("#amount_" + lastAmount + "_div").iCheck("check");
                $(".amount_tab").addClass("no_bonus");
                $("#amount_10000_div").removeClass("no_bonus");

                $("#amount_" + lastAmount + "_div").trigger('ifClicked');
            } else
            {
                $("#other_amount").val(lastAmount);
                $("#amount_10000_div").removeClass("selected");
                $("#amount_5000_div").removeClass("selected");
                $("#amount_1000_div").removeClass("selected");
                $("#amount_500_div").removeClass("selected");
                $("#amount_100_div").removeClass("selected");
                $("#amount_50_div").removeClass("selected");
                $("#amount_other_div").removeClass("selected");
                $("#amount_other_div").addClass("selected");
                $(".amount_tab").addClass("no_bonus");

                $(".amount_tab").find("input[type='radio']").iCheck("uncheck");
                $(".amount_tab").removeClass("selected");

                $("#amount_other").iCheck("check");
                $("#amount_other_div").addClass("selected");
            }

            $("[thispaytypeid=" + lastPaymentTypeId + "]").trigger("click");
            $("[paytypeid=" + lastPaymentTypeId + "]").find($(":radio[value=" + lastPaymentSubTypeId + "]")).parent().addClass('checked');
            var Id = $("[paytypeid=" + lastPaymentTypeId + "]").find($("[value=" + lastPaymentSubTypeId + "]")).parent().parent().find('select').attr('id');

            $("#" + Id).val(lastPaymentSubTypeId);

    <?php
}
?>

    });

</script>

<?php
if (Session::getSessionVariable('isDepositProcessable') === true) {
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
    Session::unsetSessionVariable('isDepositProcessable');
    echo Session::getSessionVariable('isDepositProcessableMsg');
    Session::unsetSessionVariable('isDepositProcessableMsg');
    ?>
                    </p>
                </div>
                <div class="modal-footer text-center"  style="border: none; text-align: center">
                    <a href="<?php echo Redirection::CASHIER_INITIATE . "?rd=t"; ?>" class="btn btn-primary" ><?php echo JText::_("UPDATE_PROFILE_BUTTON");?></a>
                    <?php
                    if (Session::getSessionVariable('isDepositProcessableThird') === true) {
                        ?>
                        <a href="#" id="change-amount-close" class="btn btn-primary" ><?php echo JText::_("CHANGE_AMOUNT");?></a>
        <?php
        Session::unsetSessionVariable('isDepositProcessableThird');
    }
    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#alert-modal").modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
            $("#change-amount-close").on('click', function () {
                $('#alert-modal .close').click();
            });
        });
    </script>
    <?php
}
?>
