<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$playerInfo = Utilities::getPlayerLoginResponse();
$playerRamData = Utilities::getRamPlayerDataResponse();

$ramPlayerInfo = Utilities::getRamPlayerInfoResponse();
$flagEditProfile = true;
if ($playerInfo->profileUpdate && $playerInfo->emailVerified == "Y" && $playerInfo->phoneVerified == "Y")
    $flagEditProfile = false;
$playerName = "Player";
if (!empty($playerInfo->firstName))
    $playerName = $playerInfo->firstName . " " . $playerInfo->lastName;
$avatarImagesArr = Utilities::getAvatarImages($playerInfo->playerId, $playerInfo->avatarPath);

$playerImage = $playerInfo->commonContentPath . $playerInfo->avatarPath;
//$stateDetailsURL = '/component/Betting/?task=authorisation.getStateList';
if (strlen($playerInfo->mobileNo) == 8)
    $mobile = $playerInfo->userName;
else
    $mobile = $playerInfo->userName;



?>

<?php
Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/jquery.auto-complete.min.js");
Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "templates/shaper_helixultimate/css/jquery.auto-complete.css");
Html::addCss(JUri::base() . "templates/shaper_helixultimate/css/bootstrap-datepicker.min.css");
?>

<script>
    var page_type = 'PROFILE';</script>


<div class="profile_details">
    <div class="my-profile-title">
        <h1>Update Profile</h1>
        <p class="sub-title">Key information to know user better</p>
    </div>
    <div class="row">
        <div class="col-md-12">
        <form name="player-profile-form" id="player-profile-form" action="<?php echo JRoute::_('index.php?task=ram.updatePlayerProfile'); ?>" method="post" validation-style="bottom" tooltip-mode="manual">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group last gender">
                        <label for="mobile"><?php echo JText::_("BETTING_FORM_GENDER"); ?><span class="req_star">*</span></label>
                        <div class="checkbox">
                            <label> <input type="radio" name="gender" checked tabindex="11" value="M" <?php
                                if (isset($playerInfo->gender) && $playerInfo->gender == "M")
                                    echo 'checked';
                                ?> id="gender_m"/>                                
                                <span><?php echo JText::_("MALE"); ?></span></label>
                            <label> <input type="radio" name="gender" tabindex="12" value="F" <?php
                                if (isset($playerInfo->gender) && $playerInfo->gender == "F")
                                    echo 'checked';
                                ?> id="gender_f"/><span><?php echo JText::_("FEMALE"); ?></span></label>
                                
                        </div>
                        <div class="error_tooltip manual_tooltip_error" id="error_gender_m"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_FIRST_NAME"); ?><span class="req_star"></span></label>
                        <div class="form_item_holder userName">
                        <input type="text" class="custome_input <?php
                        ?> dont_allow_nums" tabindex="1" id="fname" name="fname" oninput="checkEmoji()" value="<?php
                               if (isset($playerInfo->firstName) && $playerInfo->firstName !== "null" ) {
                                   echo $playerInfo->firstName;
                               }
                               ?>" maxlength="25" <?php
                               ?>/>
                          <div class="error_tooltip manual_tooltip_error" id="error_fname"></div>
                        </div>
                      
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_LAST_NAME"); ?><span class="req_star"></span></label>
                        <div class="form_item_holder userName">
                        <input type="text" class="custome_input <?php
                        ?> dont_allow_nums" tabindex="2" id="lname" name="lname" value="<?php
                               if (isset($playerInfo->lastName) && $playerInfo->lastName !== "null" ) {
                                   echo $playerInfo->lastName;
                               }
                               ?>" maxlength="25" <?php
                               ?>/>
                        <div class="error_tooltip manual_tooltip_error" id="error_lname"></div>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4 dob">
                    <label><?php echo JText::_("BETTING_FORM_DOB"); ?><span class="req_star">*</span></label>
                    <div class="form_item_holder date">     
                    <div class="input-group date" id="datepicker">
                        <input type="text" class="custome_input <?php
//                        if (!$flagEditProfile) {
//                            echo 'after_save';
//                        }
                        ?>" tabindex="7" id="dob" name="dob" readonly="readonly" required="true" value="<?php
                                if (isset($playerInfo->dob) && $playerInfo->dob !== "null" ) {
                                    echo $playerInfo->dob;
                                }
                                ?>"/>
                        <button class="btn_date input-group-addon" type="button" tabindex="8" <?php //if (!$flagEditProfile) echo 'disabled="disabled"'; ?>><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/calendar_icon.png" alt="" /></button>
                        <a class="input-group-addon btn_date" href="javascript:void(0);"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                         <div class="error_tooltip manual_tooltip_error" id="error_dob"></div>
                        </div>
                       
                    </div>
                </div>
                 <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for="email"><?php echo JText::_("BETTING_FORM_EMAIL_ADDR"); ?><?php //if ($flagEditProfile) { ?><span class="req_star">*</span><?php //} ?></label>
                        <div class="form_item_holder email <?php
                        if ($ramPlayerInfo->emailVerified == 'N')
                            echo 'do_verify';
                        else
                            echo 'verify';
                        ?>" id="email_div">
                            <input type="text" class="custome_input <?php
                            ?>" tabindex="9" id="email"  name="email" value="<?php echo $playerRamData->emailId; ?>" <?php if ($ramPlayerInfo->emailVerified == 'Y') echo 'readonly="readonly"'; ?> autocomplete="off"  maxlength="50" <?php
                                   ?>/>
                            <div class="error_tooltip manual_tooltip_error" id="error_email"></div>
                            <?php if( isset($playerRamData->emailId) && strlen($playerRamData->emailId) > 0 ) { ?>
                            <button class="btn_do_verify btn" type="button" id ="emailVerify-btn" verify-now="EMAIL"><?php echo JText::_("VERIFY"); ?></button>
                            <span class="btn_verify"><i></i> <?php echo JText::_("VERIFIED"); ?></span>
                            <?php }
                            else{
                             ?>
                                <button class="btn_do_verify btn disabled"  type="button" disabled="disabled"><?php echo JText::_("VERIFY"); ?></button>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for="mobile"><?php echo JText::_("BETTING_FORM_MOBILE"); ?><?php //if ($flagEditProfile) { ?><span class="req_star">*</span><?php //} ?></label>
                        <div class="form_item_holder mobile <?php
                        if ($playerInfo->phoneVerified == 'N')
                            echo 'do_verify';
                        else
                            echo 'verify';
                        ?>" id="mobile_div">
                            <input type="tel" class="custome_input <?php
                            ?> allow_only_nums" tabindex="10" id="mobile" name="mobile" value="<?php echo $mobile; ?>" <?php if ($playerInfo->phoneVerified == 'Y') echo 'readonly="readonly"'; ?> maxlength="10" <?php
                                   ?>/>
                            <div class="error_tooltip manual_tooltip_error" id="error_mobile"></div>
                            <?php //if( isset($mobile) && strlen($mobile) > 0 ) { ?>
<!--                            <button class="btn_do_verify btn" type="button" id="mobileVerify-btn" verify-now="MOBILE" ><?php echo JText::_("VERIFY"); ?></button>
                            <span class="btn_verify"><i></i> <?php echo JText::_("VERIFIED"); ?></span>-->
                            <?php// }
//                            else{
//                                ?>
                                <!--<button class="btn_do_verify btn disabled" type="button" id="mobileVerify-btn" disabled="disabled" >//<?php echo JText::_("VERIFY"); ?></button>-->
                                <?php
//                            }?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-8">
                    <div class="form-group">
                        <label><?php echo JText::_("BETTING_FORM_ADDRESS"); ?><?php// if ($flagEditProfile) { ?><span class="req_star">*</span><?php //} ?></label>
                        <textarea class="custome_input <?php
                        ?>" style="height: 60px;resize: none;" tabindex="3" autocomplete="off" id="address" name="address" rows="4" oninput = "checkAddressLength()"<?php
                                  ?>><?php
                                      if (isset($playerInfo->addressLine1)) {
                                          echo $playerInfo->addressLine1;
                                      } if (isset($playerInfo->addressLine2))
                                          echo ' ' . $playerInfo->addressLine2;
                                      ?></textarea>
                        <div class="error_tooltip manual_tooltip_error" id="error_address"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group city_country">
                        <label>Country*</label>
                        <div class="form_item_holder location">
                            <input id="country" type="text" class="custome_input" name="country" value="<?php echo Constants::COUNTRY_NAME ?>" disabled="">
                        </div>
                        <div class="clear"></div>
                    <!--                    <div class="form-group">
                                            <label><?php echo JText::_("BETTING_FORM_STATE"); ?><?php if ($flagEditProfile) { ?><span class="req_star">*</span><?php } ?></label>
                    <?php
                    if ($flagEditProfile) {
                        ?>
                                                                <div class="select_box">
                                                                    <select class="custome_input" tabindex="5" id="state" name="state">
                                                                        <option value="select"><?php echo JText::_("BETTING_FORM_STATE_SELECT"); ?></option>
                        <?php
                        $player_stateCode = 'select';
                        $state_list = Constants::STATE_LIST['MM'];
                        asort($state_list);
                        foreach ($state_list as $stateCode => $stateName) {
                            if (isset($playerInfo->state)) {
                                if ($stateName == $playerInfo->state) {
                                    $player_stateCode = $stateCode;
                                }
                            }
                            ?>
                                                                                            <option value="<?php echo $stateCode ?>"><?php echo $stateName ?></option>
                            <?php
                        }
                        ?>
                                                                        <script><?php echo "$('#state').val('" . $player_stateCode . "');"; ?></script>
                                                                    </select>
                                                                    <div class="error_tooltip manual_tooltip_error" id="error_state"></div>
                                                                </div>
                        <?php
                    } else {
                        ?>
                                                                <input type="text" tabindex="10" class="custome_input <?php
                        if (!$flagEditProfile) {
                            echo 'after_save';
                        }
                        ?>" value="<?php echo $playerInfo->state; ?>" <?php
                        if (!$flagEditProfile) {
                            echo 'readonly="readonly"';
                        }
                        ?>>
                        <?php
                    }
                    ?>
                                            <div class="clear"></div>
                                </div>-->
                </div>
            </div>
            <!--            <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label><?php echo JText::_("BETTING_FORM_PINCODE"); ?><?php if ($flagEditProfile) { ?><span class="req_star">*</span><?php } ?></label>
                                    <input type="tel" class="custome_input <?php
            if (!$flagEditProfile) {
                echo 'after_save';
            }
            ?> allow_only_nums" tabindex="6" id="pincode" name="pincode" value="<?php
            if (isset($playerInfo->pinCode)) {
                echo $playerInfo->pinCode;
            }
            ?>" maxlength="6" <?php
            if (!$flagEditProfile) {
                echo 'readonly="readonly"';
            }
            ?>/>
                                    <div class="error_tooltip manual_tooltip_error" id="error_pincode"></div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label><?php echo JText::_("BETTING_FORM_DOB"); ?><?php if ($flagEditProfile) { ?><span class="req_star">*</span><?php } ?></label>
                                    <div class="input-group date" id="datepicker">
                                        <input type="text" class="custome_input <?php
            if (!$flagEditProfile) {
                echo 'after_save';
            }
            ?>" tabindex="7" id="dob" name="dob" value="<?php
            if (isset($playerInfo->dob)) {
                echo $playerInfo->dob;
            }
            ?>"/>
                                        <button class="btn_date input-group-addon" type="button" tabindex="8" <?php if (!$flagEditProfile) echo 'disabled="disabled"'; ?>><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/common/calendar_icon.png" alt="" /></button>
                                        <a class="input-group-addon btn_date" href="javascript:;"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <div class="error_tooltip manual_tooltip_error" id="error_dob"></div>
                                    </div>-->
                <script>
                    //var fetchCityList = <?php //echo json_encode(JRoute::_('index.php?task=account.fetchCityList'));    ?>;
                    $(document).ready(function () {
                        var currentDate = new Date();
                        currentDate.setYear(currentDate.getFullYear() - 18);
                        var d = new Date(currentDate);
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
    <?php
    if (!isset($playerInfo->dob) || (isset($playerInfo->dob) && $playerInfo->dob == "")) {
        ?>
        //                            $('#dob').val(current);
        <?php
    }
    ?>

                        $('#datepicker').datepicker({
                            format: "dd/mm/yyyy",
                            autoclose: true,
                            startDate: '01/01/1900',
                            endDate: d,
                            orientation: 'top',
                            todayHighlight: true
                        }).on('change', function () {
                            removeToolTipErrorManual('dob', $("#dob"));
                        });
    //                        $('#dob').datepicker({
    //                            format: "dd/mm/yyyy",
    //                            autoclose: true,
    //                            startDate: '01/01/1900',
    //                            endDate: "0d",
    //                            orientation: 'top',
    //                            todayHighlight: true
    //                        })
                    });
                </script>
            <!--                        <div class="clear"></div>
                                </div>
                            </div>
                        </div>-->
            

            
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group last button_holder text-center">
    
                            <!--                                <a href="default"><button type="button"  id = 'back' class="brown_bg btn_next btnStyle1" tabindex="14"><?php // echo JText::_("BETTING_CANCEL");   ?></button></a>-->
                            <button type="button" id="cancel_edit_profile" class="btn btn_next btnStyle1" tabindex="14"><?php echo JText::_("BETTING_CANCEL"); ?></button>
                            <button type="submit" class="btn btn_next" tabindex="13"><?php echo JText::_("SAVE"); ?></button>
                            <div class="clear"></div>
                        </div>
                </div>
            </div>
                <?php echo JHtml::_( 'form.token' ); ?>
        </form>
        </div>
    </div>
</div>

<?php
    Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/jquery.validate.min.js");
    Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/jquery.validate2.additional-methods.min.js");
    Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/core/cashier-player-detail.js?v=".Constants::JS_VER);
    Html::addJs(JUri::base() . "templates/shaper_helixultimate/js/core/email_mobile_verify.js?v=".Constants::JS_VER);
//    Html::addJs(JUri::base() . "templates/shaper_helix3/js/custom/email_mobile_verify.js");
    Html::addJs(JUri::base()."templates/shaper_helixultimate/js/icheck.js");
?>
<script>
    $(document).ready(function () {
        //$('#email_verification').on('click', function () {
        //    // $("#email_verify").modal('show');
        //    $("#mobile_verify .modal-title").html('Email Verification');
        //    $("#mobile_verify small").html('(Verification code received on your email is valid for 30 minutes.)');
        //    $("#mobile_verify .modal-body .form-group.text-center").eq(1).find('p').first().html('To verify your email, enter the verification code below.');
        //    $("#mobile_verify").modal('show');
        //    // $("#resend-link-show").hide();
        //    $('#modal-email').text("<?php //echo $playerInfo->emailId ?>//");
        //    $("#resend-link-show-mobile").hide();
        //    sendVerificationCode('/component/Betting/?task=account.sendVerificationCode', "email", "email_verify", "", "#resend-link-show");
        //});
        // $('#mobile_verification').on('click', function () {
        //     $("#mobile_verify .modal-title").html('Mobile Verification');
        //     $("#mobile_verify small").html('(Verification code received on your mobile is valid for 30 minutes.)');
        //     $("#mobile_verify .modal-body .form-group.text-center").eq(1).find('p').first().html('To verify your mobile, enter the verification code below.');
        //    $("#mobile_verify").modal('show');
        //    $("#resend-link-show-mobile").hide();
        //    $('#modal-mobileNo').text("<?php //echo $playerInfo->mobileNo ?>//");
        //    sendVerificationCode('/component/Betting/?task=account.sendVerificationCode', "mobile", "mobile_verify", "", "#resend-link-show-mobile");
        //});
        
     $("#cancel_edit_profile").click(function () {
      location.hash="";
    }); 
    $('#cancel_edit_profile').click(function() {
            location.reload();
        }); 

//       function processStateList(res){
//           var stats = $.parseJSON(res);
//           $('#state option').remove();
//           
//   
//                
//                for (const [key, value] of Object.entries(stats)) {
//                            var temp = '<option value="'+ key +'" >'+ value +'</option>';
//                            $('#state').append(temp);  
//                }
//
//            }

//       function getStateList(code){
//           var params = "stateCode="+code;
//             startAjax('<?php //echo $stateDetailsURL     ?>', params, processStateList, 'null');       
//       }

//    $("#country").change(function () {
//        var country = this.value;
//        getStateList(country);
//    });   

//   });
         $('#player-profile-form').find(':input').each(function (i, elem) {
            $(this).val();
            $(this).data("previous-value", $(this).val());
        });
        $('#cancel_edit_profile').click(function () {
            restore();
        });
    });

    function restore() {
       $('#player-profile-form').find(':input').each(function (i, elem) {
            $(this).val($(this).data("previous-value"));
        });
   }

</script>
