<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_BETTING_COMPONENT . '/helpers/Api.php';

class BettingControllerreferafriend extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    function inviteFriend() {
        if (Session::sessionValidate()) {
            Session::setSessionVariable('refer_a_friend', true);
            $request = JFactory::getApplication()->input;
            $referType = $request->getString('referType', '');
            $inviteMode = $request->getString('inviteMode', '');
            if ($referType == "socialRefer") {
                $referThrough = $request->getString('referThrough', '');
                $response = ServerCommunication::sendCall(ServerUrl::REFER_A_FRIEND, array(
                            "referThrough" => $referThrough,
                            "referType" => $referType,
                            "inviteMode" => $inviteMode
                ));
            } else {
                $referalList_Arr = [];
                $referalList = $request->get('referalList', '', 'RAW');
                $referalList = json_decode($referalList);
                $referList = Array();
                //array_push($referList, $referalList[0]);
                foreach ($referalList as $item) {
                    $i = 0;
                    foreach ($referList as $item1) {
                        if ($item->firstName === $item1->firstName && $item->lastName === $item1->lastName && $item->emailId === $item1->emailId && $item->mobileNo === $item1->mobileNo) {
                            $i++;
                        }
                    }
                    if ($i == 0) {
                        array_push($referList, $item);
                    }
                }
                $referalList = $referList;
                foreach ($referalList as $item) {
                    array_push($referalList_Arr, [
                        "firstName" => $item->firstName,
                        "lastName" => $item->lastName,
                        "emailId" => $item->emailId,
                        "mobileNo" => $item->mobileNo
                    ]);
                }
                $response = ServerCommunication::sendCall(ServerUrl::REFER_A_FRIEND, array(
                            "referalList" => $referalList,
                            "referType" => $referType,
                            "inviteMode" => $inviteMode
                ));
            }
            if (Validations::getErrorCode() == 0) {
                Redirection::to(Redirection::INVITE_FRIEND_THANK_YOU);
            }
            Redirection::to(Redirection::REFER_A_FRIEND,Errors::TYPE_ERROR, $response->respMsg);
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    //rough work
    function getClient(){

        require_once JPATH_BETTING_COMPONENT . '/library/google-client/vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Sabanzuri');
        $client->setScopes("https://www.googleapis.com/auth/contacts.other.readonly");
        $client->setAuthConfig(JPATH_BETTING_COMPONENT . '/library/google-client/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $authUrl = $client->createAuthUrl();
        Redirection::to($authUrl);

    }
    //rough wrok
    function gmailRefer() {
        if (Session::sessionValidate() && Utilities::checkLogin(Utilities::getPlayerToken())) {

            require_once JPATH_BETTING_COMPONENT . '/library/google-api-php-client-2.0.2/vendor/autoload.php';
            Session::setSessionVariable('refer_a_friend_gmail', true);
            $client = new Google_Client();

            $client->setApplicationName(Constants::GMAIL_APP_NAME);

            $client->setClientid(Constants::GOOGLE_CLIENT_ID);
            $client->setClientSecret(Constants::GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri(Configuration::GOOGLE_CALLBACK);
            $client->setAccessType('online');
            $client->setApprovalPrompt('force');
            $client->setScopes('https://www.google.com/m8/feeds');
            $googleImportUrl = $client->createAuthUrl();
            Redirection::to($googleImportUrl);
        } else {  ?>
     <script>
      $(document).ready(function () {
                 if (window.opener != null) {
                     var doc = window.opener.document;
                     var theForm = doc.getElementById("error_data_form");
                     var theField = doc.getElementById("api_error_type");
                     theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                     var theField2 = doc.getElementById("api_error_msg");
                     theField2.value = "<?php echo $error_msg; ?>";
                     var theField3 = doc.getElementById("api_redirect_url");
                     theField3.value = '<?php// echo Redirection::REFER_A_FRIEND; ?>';
                     theForm.submit();
                    //window.opener.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";                                                     
                    window.close();
                  } else {
                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                     }
              });
     </script>
<?php 
   //Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function facebookRefer() {
        if (Session::sessionValidate() && Utilities::checkLogin(Utilities::getPlayerToken())) {
            $request = JFactory::getApplication()->input;
            $referThrough = $request->getString('referThrough', '');
            if (Validations::getErrorCode() == 0) {
                Session::setSessionVariable('refer_a_friend', true);
                //Utilities::getReferralLink($referThrough);
                $share_url = JUri::base();
                if (Validations::getErrorCode() === 0)
                    $share_url = Validations::getRespMsg();
                $facebook_url = "https://www.facebook.com/dialog/feed?app_id=" . Constants::FACEBOOK_APP_ID . "&display=popup&link=" . $share_url . "&redirect_uri=" . Configuration::FACEBOOK_CALLBACK;
                Redirection::to($facebook_url);
            }
            Redirection::to(Redirection::REFER_A_FRIEND, Errors::TYPE_ERROR, Errors::INVALID_ERROR_CODE);
        }
        else {
    ?>
        <script>
             $(document).ready(function () {
                 if (window.opener != null) {
                     var doc = window.opener.document;
                     var theForm = doc.getElementById("error_data_form");
                     var theField = doc.getElementById("api_error_type");
                     theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                     var theField2 = doc.getElementById("api_error_msg");
                     theField2.value = "<?php echo $error_msg; ?>";
                     var theField3 = doc.getElementById("api_redirect_url");
                     theField3.value = '<?php// echo Redirection::REFER_A_FRIEND; ?>';
                     theForm.submit();
                    //window.opener.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";                                                     
                    window.close();
                  } else {
                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                           }
              });
          </script>
    <?php
//            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function yahooRefer() {
        if (Session::sessionValidate()) {
            require_once( JPATH_BETTING_COMPONENT . '/library/Yahoo/globals.php');
            require_once(JPATH_BETTING_COMPONENT . '/library/Yahoo/oauth_helper.php');
            $retarr = get_request_token(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, Configuration::YAHOO_CALLBACK, false, true, true);
            if (!empty($retarr)) {
                list($info, $headers, $body, $body_parsed) = $retarr;
                if (!empty($body)) {
                    $_SESSION['request_token'] = $body_parsed['oauth_token'];
                    $_SESSION['request_token_secret'] = $body_parsed['oauth_token_secret'];
                    $_SESSION['oauth_verifier'] = $body_parsed['oauth_token'];
                    $url = urldecode($body_parsed['xoauth_request_auth_url']);
                    Session::setSessionVariable('refer_a_friend_yahoo', true);
                    Redirection::to($url);
                }
            }
            Redirection::to(Redirection::REFER_A_FRIEND, Errors::TYPE_ERROR, Errors::REFER_A_FRIEND_INVALID_REQUEST_TOKEN);
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function twitterRefer() {
        if (Session::sessionValidate() && Utilities::checkLogin(Utilities::getPlayerToken())) {
            $request = JFactory::getApplication()->input;
            $referThrough = $request->getString('referThrough', '');
            $text = Constants::TWITTER_REFER_TEXT;
            Utilities::getReferralLink($referThrough);
            $url = JUri::base();
            if (Validations::getErrorCode() === 0)
                $url = Validations::getRespMsg();
            /* $scheme = strpos($url, "https://");
              if($scheme == false)
              $url = "https://".$url; */
            $hashtag = Constants::TWITTER_HASHTAG;
            $twitter_url = "https://twitter.com/share?text=" . $text . "&url=" . $url . "&hashtags=" . $hashtag;
            Redirection::to($twitter_url);
        }
        else { ?>
       <script>
        $(document).ready(function () {
            if (window.opener != null) {
                 var doc = window.opener.document;
                 var theForm = doc.getElementById("error_data_form");
                 var theField = doc.getElementById("api_error_type");
                 theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                 var theField2 = doc.getElementById("api_error_msg");
                 theField2.value = "<?php echo Errors::PLEASE_LOGIN_FIRST; ?>";
                 var theField3 = doc.getElementById("api_redirect_url");
                 theField3.value = '<?php echo Redirection::LOGIN; ?>';
                 theForm.submit();
                 window.close();
                 } else {
                   window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                     }
              }); 
       </script>
     <?php  // Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED); }
    } 
    }

    function outlookRefer() {
        if (Session::sessionValidate()) {
            Session::setSessionVariable('refer_a_friend_outlook', true);
            $urls_ = 'https://login.live.com/oauth20_authorize.srf?client_id=' . Constants::OUTLOOK_CLIENT_ID . '&scope=wl.signin%20wl.basic%20wl.emails%20wl.contacts_emails%20wl.contacts_create&response_type=code&redirect_uri=' . Configuration::OUTLOOK_CALLBACK;
            Redirection::to($urls_);
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function sendReminder() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $reminderList = $request->getString('reminderList', '');
            $reminderList_Arr = [];
            $reminderList = json_decode($reminderList);
            foreach ($reminderList as $item) {
                $reminderList_sub_Arr = [];
                if ($item->userName != "null" && $item->userName != "undefined")
                    $reminderList_sub_Arr["userName"] = $item->userName;
                if ($item->emailId != "null" && $item->emailId != "undefined")
                    $reminderList_sub_Arr["emailId"] = $item->emailId;
                if ($item->mobileNo != "null" && $item->mobileNo != "undefined")
                    $reminderList_sub_Arr["mobileNo"] = $item->mobileNo;
                array_push($reminderList_Arr, $reminderList_sub_Arr);
            }
            $response = ServerCommunication::sendCall(ServerUrl::REFER_A_FRIEND_REMINDER, array(
                "notificationDataBean" => $reminderList_Arr
            ));
            if (Validations::getErrorCode() == 0) {
                Redirection::to(Redirection::REFER_A_FRIEND_TRACK_BONUS, Errors::TYPE_SUCCESS, Errors::REFER_A_FRIEND_REMINDER_SENT);
            }
            Redirection::to(Redirection::REFER_A_FRIEND_TRACK_BONUS, Errors::TYPE_ERROR, Validations::getRespMsg());
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function trackBonus() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            Redirection::ajaxSendDataToView(Utilities::trackBonus(Validations::$isAjax));
        } else {
            if (Validations::$isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

}
