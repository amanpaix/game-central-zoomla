<?php 
    $lang = explode("-", JFactory::getLanguage()->getTag())[0];
    ?>
    <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang?>" async defer></script>
<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

$playerInfo = Utilities::getPlayerLoginResponse();
?>

<div class="entry-header has-post-format">
    <div class="my-acc-title mb-5">
        <h1>Contact Us</h1>
        <p class="sub-title">We'd love to hear from you</p>
    </div>
</div>

<script src="templates/shaper_helix3/js/jquery.validate.min.js?v=<?php echo Constants::JS_VER ?>" type="text/javascript"></script>
<script src="templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js?v=<?php echo Constants::JS_VER ?>" type="text/javascript"></script>
<script>// <![CDATA[
    $(document).ready(function () {
        var location = window.location.href;
        var url = new URL(location); 
        if (location.indexOf("msg") > -1) {
        var msg = url.searchParams.get("msg");
        alert(msg);
         var uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        var clean_uri = uri.substring(0, uri.indexOf("?"));
        window.history.replaceState({}, document.title, clean_uri);
    }       
    }   
        var mobile_min_length = 10;
        var mobile_max_length = 10;
        var mobile_pattern = /^[0]{1}[6-8]{1}[0-9]{8}$/;
//        $.validator.addMethod("alphabets", function (value, element) {
//            $(element).val($(element).val().replace(/[^A-Za-z ]/g, ''));
//            return this.optional(element) || /^[a-zA-Z ]+$/.test($(element).val());
//        });
        $.validator.addMethod("alphabets_multispaces", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]{1}[a-zA-Z0-9 ]*$/.test(value);
        });
        $.validator.addMethod("email", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/.test(value);
        });        
        var error_callback = $("#contact-us-form").attr('error-callback');
        $("#contact-us-form").validate({
            submitHandler: function(form) {
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    alert(Joomla.JText._('BETTING_PLEASE_VERIFY_YOU_ARE_HUMAN'));
                     return false;
                }
                document.getElementById("contact-us-form").submit();
                removeToolTipErrorManual("all", $("#contact-us-form"));
            },
            showErrors: function (errorMap, errorList) {
                var style = 'bottom';
                if ($("#contact-us-form").attr('validation-style') ==
                        undefined) {
                    if ($("#contact-us-form").attr('submit-type') == "ajax") {
                        style = 'left';
                    }
                } else
                    style = $("#contact-us-form").attr('validation-style');
                if ($("#contact-us-form").attr('tooltip-mode') == "bootstrap") {
                    displayToolTip(this, errorMap, errorList, style, error_callback);
                } else if ($("#contact-us-form").attr('tooltip-mode') == "manual") {
                    displayToolTipManual(this, errorMap, errorList, style, error_callback);
                }
            }
        });
        if ($('#contact-us-form #contact_fname').length > 0) {
            $($('#contact-us-form #contact_fname')).rules('add', {
                required: true,
                rangelength: [2, 25],
                messages: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_FIRST_NAME'),
                    rangelength: Joomla.JText._('FIRST_NAME_SHOULD_BE_BETWEEN_CHARACTERS'),
                }
            });
        }
        if ($('#contact-us-form #contact_lname').length > 0) {
            $($('#contact-us-form #contact_lname')).rules('add', {
                required: true,
                rangelength: [2, 25],
                messages: {
                    required: Joomla.JText._('PLEASE_ENTER_YOUR_LAST_NAME'),
                    rangelength: Joomla.JText._('LAST_NAME_SHOULD_BE_BETWEEN_CHARACTERS'),
                }
            });
        }
        if ($('#contact-us-form #contact_email').length > 0) {
            $($('#contact-us-form #contact_email')).rules('add', {
                required: true,
                pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                email: true,
                rangelength: [3, 50],
                messages:
                        {
                            required: Joomla.JText._('PLEASE_ENTER_EMAIL'),
                            pattern: Joomla.JText._('FORM_JS_EMAIL_ADDRESS_IS_INVALID'),
                            email: Joomla.JText._('FORM_JS_EMAIL_ADDRESS_IS_INVALID'),
                            rangelength: Joomla.JText._('EMAIL_LENGTH_SHOULD_BE_BETWEEN_CHARACTERS'),

                        }
            });
        }
        if ($('#contact-us-form #contact_subject').length > 0) {
            $($('#contact-us-form #contact_subject')).rules('add',
                    {
                        required: true,
                        rangelength: [3, 50],
                        messages: {
                            required: Joomla.JText._('PLEASE_ENTER_YOUR_SUBJECT'),
                            rangelength: Joomla.JText._('SUBJECT_LENGTH_SHOULD_BE_BETWEEN_FIVE_TO_THREE_HUNDRED')
                        }
                    });
        }
        if ($('#contact-us-form #contact_message').length > 0) {
            $($('#contact-us-form #contact_message')).rules('add',
                {
                    required: true,
                    rangelength: [5, 300],
                    messages: {
                        required: Joomla.JText._('PLEASE_ENTER_YOUR_MESSAGE'),
                        rangelength: Joomla.JText._('MESSAGE_LENGTH_SHOULD_BE_BETWEEN_FIVE_TO_THREE_HUNDRED')
                    }
                });
            }
});
         $(document).on("keyup", "#contact_email", function (e) {
           var value = $(this).val();
           value = value.replace(/[^a-zA-Z0-9@._]/g, '');
           $(this).val(value);
            });
        $(document).on("keypress"  , ".checkSpace" , function (e){
          if(e.which == 32){
              return false;
         }   
        });        
    // ]]></script>
<form action="/component/betting/?task=account.contact" method="post" id="contact-us-form" class="formStyle" submit-type="ajax" validation-style="left" tooltip-mode="manual" novalidate="novalidate">
<!--    <h3>--><?php //echo JText::_('BETTING_LOVE_TO_HEAR_FROM_YOU') ?><!--</h3>-->
    <p><?php echo JText::_('BETTING_WE_AEW_HERE_TO_HELP_AND_ANSWER_MSG') ?><br><h3>contacts us via phone <a href="tel:+9100000000">+9100000000</a></h3><br></p>
    <div class="formWrap contact-formWrap no-inputGroup border clearFix">
        <div class="lcPanel fl w55">
<!--            <div class="panelHead"><span>CONTACT US FORM</span></div>-->
            <fieldset>
                <div class="form_item_holder userName">
                    <!-- <label>First Name</label> -->
                    <div class="inputGroup">
                        <input type="text" name="fname" class="formControl dont_allow_nums checkSpace" maxlength="25" autocomplete="off" placeholder="<?php echo JText::_('BETTING_FORM_FIRST_NAME')?>" id="contact_fname" required="" value="<?php
                        if (isset($playerInfo->firstName) && $playerInfo->firstName != "null"  ) {
                            echo $playerInfo->firstName;
                        }
                        ?>" />
                        <div class="error_tooltip manual_tooltip_error" id="error_contact_fname" style="display: none;"></div>
                    </div>

                </div>
                <div class="form_item_holder userName">
                    <!-- <label>Last Name</label> -->
                    <div class="inputGroup"><input type="text" name="lname" class="formControl dont_allow_nums checkSpace" autocomplete="off" maxlength="25" placeholder="<?php echo JText::_('BETTING_YOUR_NAME')?>" id="contact_lname" required="" value="<?php
                        if ( isset($playerInfo->lastName) && $playerInfo->lastName != "null" ) {
                            echo  $playerInfo->lastName;
                        }
                        ?>"  />
                        <div class="error_tooltip manual_tooltip_error" id="error_contact_lname" style="display: none;"></div>
                    </div>

                </div>
                <div class="form_item_holder email">
                    <!-- <label>Your Email</label> -->
                    <div class="inputGroup"><input type="text" name="email" class="formControl" autocomplete="off" placeholder="<?php echo JText::_('BETTING_YOUT_EMAIL') ?>" maxlength="50" id="contact_email"  value="<?php
                        if (isset($playerInfo->emailId)) {
                            echo $playerInfo->emailId;
                        }
                        ?>" required="" />
                        <div class="error_tooltip manual_tooltip_error" id="error_contact_email" style="display: none;"></div>
                    </div>

                </div>

                     <div class="form_item_holder ribbon">
                    <!-- <label>Last Name</label> -->
                    <div class="inputGroup"><input type="text" name="subject" class="formControl dont_allow_nums" autocomplete="off" maxlength="50" placeholder="<?php echo JText::_('SUBJECT') ?>" id="contact_subject" value="<?php
//                        if ( isset($playerInfo->lastName) ) {
//                            echo  $playerInfo->lastName;
//                        }
                        ?>"  required="" />
                        <div class="error_tooltip manual_tooltip_error" id="error_contact_subject" style="display: none;"></div>
                    </div>

                </div>
                <div class="form-group" style="margin-top:20px;">
                    <!-- <label>Your Message</label> -->
                    <div class="inputGroup"><textarea rows="8" name="message" class="formControl no_special_chars" autocomplete="off" placeholder="<?php echo JText::_('BETTING_YOUE_MESSAGE')?>" id="contact_message"  maxlength="300" required="" style="min-height: 100px;"></textarea>
                        <div class="error_tooltip manual_tooltip_error" id="error_contact_message" style="display: none;"></div>
                    </div>

                </div>

                <div class="form-group" style="margin-top:20px;">
                <div class="g-recaptcha" style = "margin-top:20px;" data-sitekey="6LdwQQYkAAAAAFB3DOm898qxqi537z6KIMcLOsuP" id="enableRecapcha"></div>
                <div  class="error_tooltip manual_tooltip_error" id="error_enableRecapcha"></div>
                </div>    

                <div class="clearFix"><button type="submit" class="btn" style="min-width: 100px;"><?php echo JText::_('SUBMIT') ?></button></div>
            </fieldset>
        </div>

    </div>
    <input type="hidden" id="submiturl" name="submiturl" value="/after-registration" /></form>

