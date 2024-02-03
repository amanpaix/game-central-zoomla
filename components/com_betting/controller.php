<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT . '/helpers/Includes.php';
jimport('joomla.application.component.controller');

class BettingController extends JControllerLegacy {

    function display($cachable = false, $urlparams = false) {
        $app = JFactory::getApplication();
        $input = $app->input;
        $controller = $input->getCmd('controller', '');
        $ua = Mobile_Detect::getBrowserDetails();
        if (strtolower($controller) != "browser-not-supported" && $ua['name'] == 'Internet Explorer' && (int) $ua['version'] <= Constants::IE_NS_VERSION) {
            Redirection::to(Redirection::BROWSER_NOT_SUPPORTED);
        }
        if (Session::getSessionVariable('fireLoginEvent') === true)
            Mixpanel::fireLoginEvent();
        if (Session::getSessionVariable('fireRegistrationEvent') === true)
            Mixpanel::fireRegistrationEvent();
        if (Utilities::getPlayerToken() !== false && $app->getTemplate('template')->id == Constants::TEMPLATE_ID && $controller !== "logout") {
            Utilities::fetchHeaderInfo();
        }
        Utilities::manageButtons();
        //Multi Currency 
        $sessionVar = Session::getSessionVariable("playerLoginResponse");
        // die(json_encode( $sessionVar));
        if (empty(Configuration::getCurrencyDetails()) || !empty($sessionVar->walletBean->currency)) {
            if (!empty($sessionVar->walletBean->currency)) {
                Configuration::setCurrencyDetails($sessionVar->walletBean->currency);
            } else {
                Configuration::setCurrencyDetails(Constants::DEFAULT_CURRENCY_CODE);
            }
        }


//        exit(json_encode(date("Y-m-d H:i:s")));
        $CURRENCY = Configuration::getCurrencyDetails();
        switch (strtolower($controller)) {
            case "login":
                break;
            case "register":
                break;
            case "after-register":
                break;
            case "forgot-password":
                break;
            case "verification-pending":
                if (Session::getSessionVariable('verificationPending') === false) {
                    Redirection::to(JUri::base());
                }
                $view = $this->getView('account', 'html');
                $view->verification_pending = "YES";
                $view->display();
                break;
            case "logout":
                Session::setSessionVariable('fromPage', $_SERVER['HTTP_REFERER']);
                Session::setSessionVariable('logout_playerInfo', Utilities::getPlayerLoginResponse());
                Utilities::playerLogout();
                Session::setSessionVariable('fromLogOut', true);
                Redirection::to(Redirection::LOGIN);
                break;
            case "my-account":
            case "my-profile":
                if (Session::sessionValidate()) {
                    Utilities::getPlayerProfile();
                    $view = $this->getView('account', 'html');
                    $view->account = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "edit-profile":
                if (Session::sessionValidate()) {
                    Utilities::getPlayerProfile();
                    $view = $this->getView('account', 'html');
                    $view->profile = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "edit-avatar":
                if (Session::sessionValidate()) {
                    Utilities::getPlayerProfile();
                    $view = $this->getView('account', 'html');
                    $view->edit_avatar = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "change-password":
                if (Session::sessionValidate()) {
                    Utilities::getPlayerProfile();
                    $view = $this->getView('account', 'html');
                    $view->account = "YES";
                    $view->change_password = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "transaction-details":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->transaction_details_new = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;

            case "balance":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->balance = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;

            case "pre-buy":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->pre_buy = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;


            case "evolution_games":
                if (Session::sessionValidate()) {

                    $request = JFactory::getApplication()->input;
                    $tableId = $request->getString('tableId', null);
                    $playMode = $request->getString('playMode', null);
                    $gameCategory = $request->getString('gameCategory', null);
                    $open = $request->getString('open', '');

                    $view = $this->getView('virtualsports', 'html');
                    $view->uaResp = Utilities::evloutionUserAuthentication($tableId,$playMode,$gameCategory);
                    $view->CurrData = $CURRENCY;
                    $view->openStyle = $open;
                    $view->evolutiongames = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;

            case "ticket-details":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->ticket_details = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;
            case "wallet-details":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->wallet_details = "YES";
                    $view->options = Utilities::paymentOptions("DEPOSIT");
                    $view->withdrawalOptions = Utilities::paymentOptions("WITHDRAWAL");
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;
            case "cashier-details":
                if (Session::sessionValidate()) {
                    $view = $this->getView('cashier', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->cashier_details = "YES";
                    $view->options = Utilities::paymentOptions("DEPOSIT");
                    $view->withdrawalOptions = Utilities::paymentOptions("WITHDRAWAL");
//                    echo '<pre>';
//                    print_r($view->options);die;
                    //$view->withdrawalOptions = Utilities::paymentOptions("WITHDRAWAL");
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;
            case "withdrawal-details":
            case "withdrawal-request":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->withdrawal_details = "YES";
                    $view->CurrData = $CURRENCY;
                    $view->header = "Withdrawal Requests";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "cashier-initiate":
                if (Session::sessionValidate()) {
                    Session::setSessionVariable('cashier_initiate', true);
                    $beforeCashierInitiate = Utilities::beforeCashierInitiate();
                    if ($beforeCashierInitiate === false) {
                        Utilities::getPlayerProfile();
                        $beforeCashierInitiate = Utilities::beforeCashierInitiate();
                        if ($beforeCashierInitiate === false) {
                            Redirection::to(Redirection::CASHIER_PLAYER_DETAIL);
                        } else {
                            Redirection::to($beforeCashierInitiate);
                        }
                    } else {
                        Redirection::to($beforeCashierInitiate);
                    }
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "player-detail":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable('cashier_initiate') === false) {
                        Redirection::to(Redirection::CASHIER_INITIATE);
                    }
                    Session::unsetSessionVariable('cashier_initiate');
                    $view = $this->getView('cashier', 'html');
                    $view->CurrData = $CURRENCY;
                    $view->player_detail = "YES";
                    $view->obj = $this;
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "select-amount":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable('cashier_initiate') === false) {
                        Redirection::to(Redirection::CASHIER_INITIATE);
                    }
                    Session::unsetSessionVariable('cashier_initiate');
                    Session::unsetSessionVariable('promoCode');
                    $fetchDepositBonus = Utilities::fetchDepositBonus();
                    $view = $this->getView('cashier', 'html');
                    $view->select_amount = "YES";
                    $view->CurrData = $CURRENCY;
                    $view->promoCodeList = $fetchDepositBonus->promoCodeList;
                    $view->bonusMap = $fetchDepositBonus->bonusMap;
                    $view->paymentOptionsResponse = Utilities::paymentOptions("DEPOSIT");
                    $view->setModel($this->getModel('Betting'), true);
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "before-payment":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable('before_payment') === false) {
                        Redirection::to(Redirection::CASHIER_INITIATE);
                    }
                    Session::unsetSessionVariable('before_payment');
                    $view = $this->getView('cashier', 'html');
                    $view->paymentGatewayRedirect = "YES";
                    $view->data = Session::getSessionVariable('depositRequest');
                    $view->CurrData = $CURRENCY;
                    $view->url = Session::getSessionVariable('url');
                    Session::unsetSessionVariable('depositRequest');
                    Session::unsetSessionVariable('url');
                    Session::setSessionVariable('depositTxn', true);
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case 'deposit-response':
//exit(json_encode($_REQUEST));
                   $file = 'infinit.txt';
                    $current = file_get_contents($file);
                    $current .= json_encode($_SERVER['REQUEST_METHOD']) . '  ' . json_encode($_REQUEST)."\n";
                    file_put_contents($file, $current);
                if (Session::sessionValidate()) {
//                    if (Session::getSessionVariable('depositTxn') === false) {
//                        Redirection::to(Redirection::MY_WALLET_DEPOSIT);
//                    }
                    
                    //$response = '{"responseUrl":"http://portal.infinitilotto.com/deposit-response","firstDeposit":false,"errorCode":309,"errorMsg":"Your payment has been failed.Please try again."}';
                   $responseBean = json_decode($_REQUEST['responseJson']);
                
                   //print_r($responseBean->errorCode);die;
                    $responseBean_txnId = $input->getString('responseBean_txnId');
                    $responseBean_status = $input->getString('responseBean_status');
                    $amount = $input->getString('responseBean_requestAmount');
                    $view = $this->getView('cashier', 'html');
                    $view->depositresponse = "YES";
                    $view->status = $responseBean->errorCode;
                    $view->message = $responseBean->respMsg;
                    $view->txnId = $responseBean_txnId;
                    $view->amount = $amount;
                    $view->display();
                    Session::unsetSessionVariable('depositTxn');
                    Session::unsetSessionVariable('playerDepositedAmount');
                    Session::unsetSessionVariable('type');
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "after-payment-success":
                if (Session::sessionValidate()) {
                    $type = Session::getSessionVariable('type');
//                    Session::unsetSessionVariable('type');
                    $view = $this->getView('cashier', 'html');
                    $view->after_payment = "YES";
                    if ($type == "OFFLINEDEPOSIT") {
                        $view->status = "SUCCESS";
//                        $view->type = $type;
                        $view->CurrData = $CURRENCY;
                        $view->redirectionUrl = Session::getSessionVariable('url');
                        $view->afterPaymentRedirect = Session::getSessionVariable('afterPaymentRedirect');
                        $view->afterPaymentMessage = Session::getSessionVariable('afterPaymentMessage');
                        $view->offlineRequestId = Session::getSessionVariable('offlineRequestId');
                        $view->afterPaymentRedirectLink = Redirection::MYACC_ACC;
                        Session::unsetSessionVariable('url');
                        Session::unsetSessionVariable('afterPaymentRedirect');
                        Session::unsetSessionVariable('afterPaymentMessage');
                        Session::unsetSessionVariable('offlineRequestId');
                    } else {
                        $request = $app->input;
                        $responseBean_responseMsg = $request->getString('responseBean_responseMsg', null);
                        if ($responseBean_responseMsg == null) {
                            Redirection::to(Redirection::MYACC_ACC);
                        }
                        $responseBean_responseMsg = $request->getString('responseBean_responseMsg', null);
                        if ($responseBean_responseMsg != "SUCCESS" && $view->status != "DONE") {
                            $view->status = "INVALID";
                        } else
                            $view->status = $responseBean_responseMsg;
                        if ($view->status == "SUCCESS" || $view->status == "DONE") {
                            Utilities::getPlayerBalance();
                            if (Utilities::getPlayerLoginResponse()->firstDepositDate == "") {
                                Utilities::updatePlayerLoginResponse(array(
                                    "firstDepositDate" => date("Y-m-d H:i:s")
                                ));
                            }
                            $amount = $request->getInt('responseBean_requestAmount', 0);
                            $view->redirectionUrl = Redirection::MYACC_ACC;
                            $view->afterPaymentRedirect = true;
                            $view->CurrData = $CURRENCY;
                            $view->afterPaymentMessage = 'Your account has been deposited with amount <span class="rupees-symbol">`</span> ' . $amount . ' successfully';
                            $view->afterPaymentAmount = $amount;
                            $view->afterPaymentTransactionId = $request->getString('responseBean_txnId', 'N.A.');
                            $view->afterPaymentRedirectLink = Redirection::MYACC_ACC;
                        }
                    }
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "after-payment-failed":
                if (Session::sessionValidate()) {
                    $type = Session::getSessionVariable('type');
                    Session::unsetSessionVariable('type');
                    $request = $app->input;
                    $amount = $request->getInt('responseBean_requestAmount', 0);
                    $view = $this->getView('cashier', 'html');
                    $view->status = "FAILED";
                    $view->CurrData = $CURRENCY;
                    $view->after_payment_failed = "YES";
                    $view->afterPaymentAmount = $amount;
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "withdrawal":
                if (Session::sessionValidate()) {
                    $response = Utilities::getPlayerProfile();
                    Utilities::updatePlayerLoginResponse(array(
                        "walletBean" => $response->playerInfoBean->walletBean
                    ));
                    $view = $this->getView('withdrawal', 'html');
                    $playerInfo = Utilities::getPlayerLoginResponse();
                    if ($response->profileUpdate == false) {
                        $view->incompleteProfile = "YES";
                    } else if ($response->fistDeposited == false) {
                        $view->insufficientCash = "YES";
                        $view->fistDeposited = "YES";
                    } else if (strtoupper($response->docUploadStatus) == "PENDING") {
                        $view->uploaddoc = "YES";
                    } else if (strtoupper($response->docUploadStatus) == "UPLOADED") {
                        $view->verificationpending = "YES";
                    } else if (strtoupper($response->docUploadStatus) == "VERIFIED") {
                        $playerLoginResponse = Utilities::getPlayerLoginResponse();
                        $withdrawableBalance = $playerLoginResponse->walletBean->withdrawableBal;
                        $cashBalance = $playerLoginResponse->walletBean->cashBalance;
                        if ((int) $cashBalance < (int) $withdrawableBalance) {
                            $withdrawableBalance = $cashBalance;
                        }
                        if ((int) $withdrawableBalance < Constants::WITHDRAWAL_MIN_LIMIT) {
                            $view->insufficientCash = "YES";
                            $view->CurrData = $CURRENCY;
                        } else {
                            $view->options = Utilities::paymentOptions("WITHDRAWAL");
                            $view->withdrawal = "YES";
                            $view->CurrData = $CURRENCY;
                            $view->setModel($this->getModel('Betting'), true);
                        }
                    }
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "withdrawal-success":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable('withdrawalAmount') === false) {
                        Redirection::to(Redirection::WITHDRAWAL_PROCESS);
                    }
                    $view = $this->getView('withdrawal', 'html');
                    $view->withdrawal_success = "YES";
                    $view->CurrData = $CURRENCY;
                    $view->amount = Session::getSessionVariable('withdrawalAmount');
                    Session::unsetSessionVariable('withdrawalAmount');
                    $view->date = Session::getSessionVariable('withdrawalDate');
                    Session::unsetSessionVariable('withdrawalDate');
                    $view->time = Session::getSessionVariable('withdrawalTime');
                    Session::unsetSessionVariable('withdrawalTime');
                    $view->withdrawalTxnId = Session::getSessionVariable('withdrawalTxnId');
                    Session::unsetSessionVariable('withdrawalTxnId');
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "cashier-help":
                if (Session::sessionValidate()) {
                    $view = $this->getView('withdrawal', 'html');
                    $view->cashierHelp = "YES";
                    $view->CurrData = $CURRENCY;
                    $view->header = "Cashier Help";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "inbox":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->inbox = "YES";
                    $view->isCommingSoon = false;
                    if (!$view->isCommingSoon) {
                        $messages = Utilities::playerInbox();
//                        exit(json_encode($messages));

                        if ($messages === false /* || $messages['content'] === false */) {
                            $view->nomessages = true;
                        } else {
                            $view->messages = $messages['response'];
                            $view->messageContent = $messages['content'];
                        }
                    }
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "loyalty":
                if (Session::sessionValidate()) {
                    $view = $this->getView('loyalty', 'html');
                    $view->loyalty = "YES";
                    $view->loyalPlayerDetail = Utilities::getLoyalPlayerDetail();
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "loyalty-redeem":
                if (Session::sessionValidate()) {
                    $view = $this->getView('loyalty', 'html');
                    $view->redeem_loyalty = "YES";
                    $view->loyalPlayerDetail = Utilities::getLoyalPlayerDetail();
                    $view->loyaltyRedeemDetails = Utilities::getLoyaltyRedeemPage();
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "loyalty-details":
                if (Session::sessionValidate()) {
                    $packetId = $input->getInt('id', 0);
                    if ($packetId == 0) {
                        Redirection::to(Redirection::LOYALTY);
                    }
                    $view = $this->getView('loyalty', 'html');
                    $view->loyalty_details = "YES";
                    $view->loyaltyDetailsPackets = Utilities::getLoyaltyStatementDetails($packetId);
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "refer-a-friend":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->refer_a_friend = "YES";
                    $view->CurrData = $CURRENCY;
                    $referralLink = Utilities::getReferralLink("DIRECTPLAYER");
                    if ($referralLink !== false)
                        $view->referralLink = $referralLink;
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "refer-a-friend-thank-you":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable("refer_a_friend")) {
                        Session::unsetSessionVariable("refer_a_friend");
                        $view = $this->getView('account', 'html');
                        $view->refer_a_friend_thank_you = "YES";
                        $view->CurrData = $CURRENCY;
                        $view->display();
                    } else {
                        Redirection::to(Redirection::REFER_A_FRIEND);
                    }
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "refer-a-friend-invite-list":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $contacts = json_decode($input->getString('api_response', ''), true);
                    if ($contacts !== '') {
                        $view->contacts_list = $contacts;
                    }
                    $view->refer_a_friend_invite_list = "YES";
                    $view->CurrData = $CURRENCY;
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "refer-friend-error":
                if (Session::sessionValidate()) {
                    $error_msg = $input->getString('api_error_msg', '');
                    $error_type = $input->getString('api_error_type', '');
                    $error_url = $input->getString('api_redirect_url', '');
                    Redirection::to($error_url, $error_type, $error_msg);
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "track-bonus":
                if (Session::sessionValidate()) {
                    $view = $this->getView('account', 'html');
                    $view->refer_a_friend = "YES";
                    $view->refer_a_friend_track_bonus = "YES";
                    $view->CurrData = $CURRENCY;
                    // $view->track_bonus_list = Utilities::trackBonus();
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "gmail-callback" :
                if (Session::sessionValidate()) {
                    if (($input->getString('code', '') != "")) {
                        Session::unsetSessionVariable("refer_a_friend_gmail");
                        
//                        $contacts = Api::gmailApiCallResponse();
                        $contacts2 = Api::gmailCallbackResponse();
                    //    exit(json_encode($contacts2));
                        if (json_encode(count($contacts2)) == 0) {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("error_data_form");
                                        var theField = doc.getElementById("api_error_type");
                                        theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                                        var theField2 = doc.getElementById("api_error_msg");
                                        theField2.value = "<?php echo Errors::REFER_A_FRIEND_NO_CONTACTS; ?>";
                                        var theField3 = doc.getElementById("api_redirect_url");
                                        theField3.value = '<?php echo Redirection::REFER_A_FRIEND; ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    }
                                });
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("contact_data_form");
                                        var theField = doc.getElementById("api_response");
                                        theField.value = '<?php echo json_encode($contacts2); ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::INVITE_FRIEND_THANK_YOU; ?>";
                                    }
                                });
                            </script>
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            $(document).ready(function () {
                                if (window.opener != null) {
                                    window.opener.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    window.close();
                                } else {
                                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                }
                            });
                        </script>
                        <?php
                    }
                } else {
                    ?>
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
                    <?php
                }
                break;
            case "facebook-callback" :
                if (Session::sessionValidate()) {
                    //$facebook_resp = '{"post_id":"129758340809534_129789387473096"}';
                    //$facebook_resp = '{"error_code":"4201","error_message":"User canceled the Dialog flow"}';
                    $error_msg = $input->getString('error_message', '');
                    if (isset($error_msg) && strlen($error_msg) > 0) {
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
                                    theField3.value = '<?php echo Redirection::REFER_A_FRIEND; ?>';
                                    theForm.submit();
                                    window.close();
                                } else {
                                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                }
                            });
                        </script>
                        <?php
                    } else {
                        // Utilities::sendFacebookResp($facebook_resp);
                        //Redirection::to(Redirection::INVITE_FRIEND_THANK_YOU);
                        ?>
                        <script>
                            $(document).ready(function () {
                                if (window.opener != null) {
                                    window.opener.location.href = "<?php echo Redirection::INVITE_FRIEND_THANK_YOU; ?>";
                                    window.close();
                                } else {
                                    window.location.href = "<?php echo Redirection::INVITE_FRIEND_THANK_YOU; ?>";
                                }
                            });
                        </script>
                        <?php
                    }
                } else {
                    ?>
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
                    <?php
                }
                break;
            case "outlook-callback" :
                if (Session::sessionValidate()) {
                    if (($input->getString('code', '') != "") && Session::getSessionVariable("refer_a_friend_outlook")) {
                        Session::unsetSessionVariable("refer_a_friend_outlook");
                        $contacts = Api::outlookApiCallResponse($input->getString('code', ''));
                        if (count($contacts) == 0) {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("error_data_form");
                                        var theField = doc.getElementById("api_error_type");
                                        theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                                        var theField2 = doc.getElementById("api_error_msg");
                                        theField2.value = "<?php echo Errors::REFER_A_FRIEND_NO_CONTACTS; ?>";
                                        var theField3 = doc.getElementById("api_redirect_url");
                                        theField3.value = '<?php echo Redirection::REFER_A_FRIEND; ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    }
                                });
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("contact_data_form");
                                        var theField = doc.getElementById("api_response");
                                        theField.value = '<?php echo json_encode($contacts); ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::INVITE_FRIEND_THANK_YOU; ?>";
                                    }
                                });
                            </script>
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            $(document).ready(function () {
                                if (window.opener != null) {
                                    window.opener.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    window.close();
                                } else {
                                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                }
                            });
                        </script>
                        <?php
                    }
                } else {
                    ?>
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
                    <?php
                }
                break;
            case "yho-callback" :
                echo 'rger';
                die;
                if (Session::sessionValidate()) {
                    if (($input->getString('oauth_verifier', '') != "") && Session::getSessionVariable("refer_a_friend_yahoo")) {
                        Session::unsetSessionVariable("refer_a_friend_yahoo");
                        $contacts = Api::yahooApiCallResponse($input->getString('oauth_verifier', ''));
                        if (count($contacts) == 0) {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("error_data_form");
                                        console.log(theForm);
                                        var theField = doc.getElementById("api_error_type");
                                        theField.value = '<?php echo Errors::TYPE_ERROR; ?>';
                                        var theField2 = doc.getElementById("api_error_msg");
                                        theField2.value = "<?php echo Errors::REFER_A_FRIEND_NO_CONTACTS; ?>";
                                        var theField3 = doc.getElementById("api_redirect_url");
                                        theField3.value = '<?php echo Redirection::REFER_A_FRIEND; ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    }
                                });
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    if (window.opener != null) {
                                        var doc = window.opener.document;
                                        var theForm = doc.getElementById("contact_data_form");
                                        var theField = doc.getElementById("api_response");
                                        theField.value = '<?php echo json_encode($contacts); ?>';
                                        theForm.submit();
                                        window.close();
                                    } else {
                                        window.location.href = "<?php echo Redirection::INVITE_FRIEND_THANK_YOU; ?>";
                                    }
                                });
                            </script>
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            $(document).ready(function () {
                                if (window.opener != null) {
                                    window.opener.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                    window.close();
                                } else {
                                    window.location.href = "<?php echo Redirection::REFER_A_FRIEND; ?>";
                                }
                            });
                        </script>
                        <?php
                    }
                } else {
                    ?>
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
                    <?php
                }
                break;
            case "reset-password-link" :
                Utilities::resetPasswordLink();
                break;
            case "play-rummy":
                if (Session::sessionValidate()) {
                    $isPlayerLoggedIn = Utilities::checkLogin(Utilities::getPlayerToken());
                    //exit(json_encode($isPlayerLoggedIn));
                    //$isPlayerLoggedIn = false;
                    if ($isPlayerLoggedIn !== false) {
                        $view = $this->getView('rummy', 'html');
                        $view->playRummy = "YES";
                        $view->display();
                    } else {
                        Session::sessionRemove();
                        Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                    }
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "play-html-rummy":
                if (Session::sessionValidate()) {
                    $view = $this->getView('rummy', 'html');
                    $view->playhtmlRummy = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "play-mobile-rummy":
                if (Session::sessionValidate()) {
                    $view = $this->getView('rummy', 'html');
                    $view->playmobileRummy = "YES";
                    $view->display();
                } else {
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "myaccount-page-rummy" :
                Redirection::to(Redirection::MYACC_ACC);
                break;
            case "promotion-page-rummy" :
                Redirection::to(Redirection::PROMOTIONS);
                break;
            case "refer-a-friend-page-rummy" :
                Redirection::to(Redirection::REFER_A_FRIEND);
                break;
            case "player-inbox-page-rummy" :
                Redirection::to(Redirection::MYACC_INBOX);
                break;
            case "account-activated" :
                if (Session::getSessionVariable('account_activated') === false)
                    Redirection::to(JUri::base());
                Session::unsetSessionVariable('account_activated');
                $view = $this->getView('account', 'html');
                $view->account_activated = "YES";
                $view->display();
                break;
            case "activation-page" :
                Utilities::emailVerify();
                break;
            case "fetch-email-data" :
                /* if (Session::sessionValidate()) { */
                $mod_id = $input->getString('id', '');
                Utilities::fetchInternalEmailData($mod_id);
                /* else {
                  Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                  } */
                break;
            case "download" :
                $file = "";
                $filePath = $input->getString('path', $file);
                if( empty($filePath) ){
                    exit;
                }
                $fileName = preg_split("/\//", $filePath);
                $fileName = $fileName[count($fileName)-1];
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename='. $fileName);
                header('Pragma: no-cache');
                readfile($filePath);
                exit;
                break;

            case 'contactus' :

                $view = $this->getView('account', 'html');
                $view->contactus = "YES";
                $view->display();
                break;

            case "keroninteractive":
                Session::setSessionVariable('LOTTERY_lastpage', Redirection::KERON_INTERACTIVE);
                $view = $this->getView('virtualsports', 'html');
                $view->keroninteractive = "YES";
                $view->display();
                break;
            case "goldenrace":
                $tokenData = Utilities::parseXmlResponse("http://asia-api.golden-race.net/api/?method=auth&uid=7299&hash=72aeb32fe0aebf2f6f67aba298bf442d&key=UepaR6UnUtNKZSrorRc");
                // exit(json_encode($tokenData));
                if ($tokenData->response->result == "success") {
                    $session_token = $tokenData->response->session_token;
                }
                //exit(json_encode($session_token));
                if (Session::sessionValidate()) {
                    $result = Utilities::goldenraceData();
                    if ($result == true) {
                        $view = $this->getView('virtualsports', 'html');
                        $view->goldenrace = "YES";
                        $view->token = $session_token;
                        $view->display();
                    } else {
                        $plr_reg_resp = Utilities::parseXmlResponse("http://asia-api.golden-race.net/api/?method=add_shadow_user&token=" . $session_token . "&extid=" . Utilities::getPlayerId() . "&passwd=" . Session::getSessionVariable('encPwd') . "&idtype=EXTID&currency=" . $CURRENCY['decSymbol']);
                        // exit(json_encode($plr_reg_resp));
                        if ($plr_reg_resp->response->result == "success") {
                            $setPlayerId = Utilities::setPlayerIdForGR(Utilities::getPlayerId());
                            $view = $this->getView('virtualsports', 'html');
                            $view->goldenrace = "YES";
                            $view->token = $session_token;
                            $view->display();
                        } else {
                            Utilities::setPlayerIdForGR(Utilities::getPlayerId());
                            Redirection::to(Redirection::LOGIN_NEW, Errors::TYPE_ERROR, "Player Not Registered!.");
                        }
                    }
                } else {
                    Redirection::to(Redirection::LOGIN_PAGE, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                }
                break;
            case "livegames":
                //Session::setSessionVariable('LOTTERY_lastpage', Redirection::SB_TECH);
                $view = $this->getView('virtualsports', 'html');
                $view->livegames = "YES";
                $request = JFactory::getApplication()->input;
                $tableId = $request->getString('tableId', null);
                if( !is_null($tableId) ){
                    $view->gameId = $tableId;
                }
                $view->display();
                break;

            case "betgames_lobby":
                //Session::setSessionVariable('LOTTERY_lastpage', Redirection::SB_TECH);
                $view = $this->getView('virtualsports', 'html');
                $view->betgames_lobby = "YES";
                $request = JFactory::getApplication()->input;
                $tableId = $request->getString('tableId', null);
                if( !is_null($tableId) ){
                    $view->gameId = $tableId;
                }
                $view->display();
                break;

            case "app-download" :
                header("Location: " . Constants::APP_DOWNLOAD_LINK);
                exit;
                $file = Constants::RUMMY_APH_ABS_PATH_APPSFX2X;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/vnd.android.package-archive');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    exit;
                }
                break;
            case "instantgames_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->drawgames = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('newige');
                $view->display();
                break;
            case "drawgames_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->drawgames = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('drawgames');
                $view->display();
                break;
            case "thailottery_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->thailottery = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('thailottery');
                $view->display();
                break;
            case "sixbyfortytwo_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->sixbyfortytwo = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('sixbyfortytwo');
                $view->display();
                break;
            case "twelvebytwentyfour_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->twelvebytwentyfour = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('twelvebytwentyfour');
                $view->display();
                break;
            case "view_ticket_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->viewticket = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('viewticket');
                $view->display();
                break;                
            case "sportsBETTING_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->sportsbetting = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('sportsbetting');
                $view->display();
                break;
            case "sportslottery_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->sportslottery = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('sportslottery');
                $view->display();
                break;
            case "bingo_iframe" :
                $view = $this->getView('iframegames', 'html');
                $model = $this->getModel('Betting');
                $view->bingo = "YES";
                $view->CurrData = $CURRENCY;
                $view->setModel($model, true);
                $view->setLayout('bingo');
                $view->display();
                break;

            /*             * *************Games Related End************** */
            case 'instantwingames' :

                Session::setSessionVariable('LOTTERY_lastpage', Redirection::INSTANT_WIN);
                $serviceData['IGE'] = Utilities::getServiceDetails(Constants::IGE);
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $mobile = false;
                $usrAgt = $_SERVER['HTTP_USER_AGENT'];
                $query->select("*")
                        ->from($db->quoteName('#__game_master'))
                        ->where($db->quoteName('published') . " = " . $db->quote(1))
                        ->order('ordering');
                $db->setQuery($query);
                $result = $db->loadObjectList();
                if (count($result) <= 0) {
                    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, Errors::NO_GAME_AVAILABLE_TO_PLAY);
                }
                $view = $this->getView('instantgames', 'html');
                $view->serviceData = $serviceData;
                $view->currency = $CURRENCY['curCode'];
                $view->recset = $result;
                $view->instantgamesnew = "YES";
//                $view->igepath = ServerUrl::IGEWEB;
//                $view->igepathhtml5 = ServerUrl::IGEWEB;
                $view->gameMode = 'buy';
                $view->display();

                break;
            case 'gameplay' :
                $jinput = JFactory::getApplication()->input;
                $request['token'] = $jinput->get('gamename', '', 'STR');
                if (Session::sessionValidate()) {
                    Redirection::to(rtrim(JUri::base(), '/') . base64_decode($request['token']));
                } elseif ($request['token'] == '') {
                    Redirection::to('/');
                } else {
                    Redirection::to(Redirection:: LOGIN_PATH . "?fromPage=" . $request['token']);
                }
                break;
            case 'updategamelist' :

//            $serviceData['IGE'] = Utilities::getServiceDetails(Constants::IGE);
//            $serviceData['SGE'] = Utilities::getServiceDetails(Constants::SGE);
                $response['IGE'] = ServerCommunication::sendCallToGameEngine(Configuration::IGE_PATH['IGE'] . Configuration::IGE_PATH['IGE-GAMELIST'], array(), false, false, false, true);
//                $response['SGE'] = ServerCommunication::sendCallToGameEngine(Configuration::IGE_PATH['SGE']."getGameListData.action?domainName=www.nationallottery.co.za&deviceName=PC&language=eng&currencyCode=ZAR", array(), false, false, false, true);
//                $response['IGE'] = json_decode('{"games":[{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/TreasureHunt_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/TreasureHunt_1024.png"},"orderId":14,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/treasurehuntTablet.png","windowHeight":450,"isHTML5":"Y","gamePrice":3,"isKeyboard":"N","gameCategory":"Scratch","gameWinUpto":"150000.0000","gameName":"Treasure Hunt","gameNumber":103,"gameVersion":"1.0","gameDescription":"This game lets you scratch and match any three symbols to win the prize!.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"33":3,"35":5,"310":10},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/TicTacToe_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/TicTacToe_1024.png"},"orderId":6,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/Tic_tac_300.png","windowHeight":450,"isHTML5":"Y","gamePrice":5,"isKeyboard":"N","gameCategory":"Scratch","gameWinUpto":"200000.0000","gameName":"Tic Tac Toe","gameNumber":104,"gameVersion":"1.0","gameDescription":"Pop the balloons and match 3 symbols in a line to win the prize.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"45":5,"410":10},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/MoneyBee_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/MoneyBee_1024.png"},"orderId":3,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/Money_bee_300.png","windowHeight":450,"isHTML5":"Y","gamePrice":3,"isKeyboard":"N","gameCategory":"Scratch","gameWinUpto":"75000.0000","gameName":"Money Bee","gameNumber":105,"gameVersion":"1.0","gameDescription":"Reveal the amount inside the flowers.Match any 3 amounts to win the prize.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"55":5,"53":3},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/LuckyJersey_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/LuckyJersey_1024.png"},"orderId":7,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/Lucky_jersey_300.png","windowHeight":450,"isHTML5":"Y","gamePrice":5,"isKeyboard":"N","gameCategory":"Scratch","gameWinUpto":"200000.0000","gameName":"Lucky Jersey","gameNumber":106,"gameVersion":"1.0","gameDescription":"Click and reveal the jersey numbers. Match any three to win!","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"610":10,"65":5},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/RoyalPoker_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/RoyalPoker_1024.png"},"orderId":21,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/RoyalPoker.png","windowHeight":450,"isHTML5":"Y","gamePrice":3,"isKeyboard":"N","gameCategory":"Scratch","gameName":"Royal Poker","gameNumber":116,"gameVersion":"1.0","gameDescription":"Get 3 strikers of identical colors in any horizontal,vertical or diagonal  line to win the prize.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"1":0.1},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/BallBash_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/BallBash_1024.png"},"orderId":13,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/ballbash300.png","windowHeight":450,"isHTML5":"Y","gamePrice":5,"isKeyboard":"N","gameCategory":"Scratch","gameName":"Ball Bash","gameNumber":120,"gameVersion":"1.0","gameDescription":"Get 3 strikers of identical colors in any horizontal,vertical or diagonal  line to win the prize.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"106":5,"701":3,"702":10},"isImageGeneration":"N","status":"OLD"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/Bingo_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/Bingo_1024.png"},"orderId":0,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/300_BINGO-01.png","windowHeight":450,"isHTML5":"Y","gamePrice":5,"isKeyboard":"N","gameCategory":"Scratch","gameName":"Bingo","gameNumber":121,"gameVersion":"1.0","gameDescription":"Get 3 strikers of identical colors in any horizontal,vertical or diagonal  line to win the prize.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"995":5},"isImageGeneration":"N"},{"loaderImage":{"2048":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/ChampRally_2048.png","1024":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/loader/ChampRally_1024.png"},"orderId":22,"imagePath":"http://192.168.132.59:8080/IGEContent/content/gamePlayContent/launcher/eng/adventurefort.png","windowHeight":450,"isHTML5":"Y","gamePrice":5,"isKeyboard":"N","gameCategory":"Scratch","gameName":"Champ Rally","gameNumber":122,"gameVersion":"1.0","gameDescription":"Click on the matches to select and click on bet to select your team. Click on play to enter the game.","currencyCode":"USD","windowWidth":600,"isFlash":"N","priceSchemes":{"107":10},"isImageGeneration":"N","status":"OLD"}],"errorCode":0}');
//                                 exit(json_encode($response));
                if (Validations::getErrorCode() != 0) {
                    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, Validations::getRespMsg());
                }
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('merchant_code') . ' LIKE ' . $db->quote('IGE')
            );
            $query->delete($db->quoteName('#__game_master'))
                    ->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
                $db->truncateTable('#__game_master');
                $orderId = 0;
                foreach ($response['IGE']->games as $value) {
                    $row = new stdClass();
                    $row->gameNumber = $value->gameNumber;
                    $row->gameName = $value->gameName;
                    $row->gameCategory = $value->gameCategory;
                    $row->currencyCode = $value->currencyCode;
                    $row->windowHeight = $value->windowHeight;
                    $row->windowWidth = $value->windowWidth;
                    $row->gameImageLocations = $value->imagePath;
                    $row->ordering = $value->orderId;
                    $orderId = ($row->ordering > $orderId) ? $row->ordering : $orderId;
                    $row->gamePrice = $value->gamePrice;
                    $row->gameDescription = htmlentities($value->gameDescription);
                    $row->published = 1;
                    $row->merchant_code = 'IGE';
                    $row->prizeSchemeIge = json_encode($value->priceSchemes);
                    $row->extraParams = json_encode(array(
                        "isHTML5" => $value->isHTML5,
                        "isKeyboard" => $value->isKeyboard,
                        "loaderImage" => $value->loaderImage,
                        "gameVersion" => $value->gameVersion,
                        "isFlash" => $value->isFlash,
                        "isImageGeneration" => $value->isImageGeneration,
                        "gameWinUpto" => $value->gameWinUpto,
                        "status" => $value->status
                    ));
                    $db->insertObject('#__game_master', $row);
                }
                Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_SUCCESS, Errors::GAME_DATA_UPDATED);
                break;
            case "htmlpage":
                $view = $this->getView('slot', 'html');
//                $view->page = "YES";
                $view->display();
                break;
            case "slotpage":
                $view = $this->getView('slot', 'html');
                $view->slotpage = "YES";
                $view->display();
                break;

            case "bingo-lobby":
               // if (Session::sessionValidate()) {

                    $view = $this->getView('virtualsports', 'html');
                    $view->bingolobby = "YES";
                    $view->display();
               // } else
               //     Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;

            case "play-bingo":
                if (Session::sessionValidate()) {

                    $request = JFactory::getApplication()->input;
                    $roomId = $request->getString('room', '0');
//                    exit(json_encode($roomId));
                    $announcer = $request->getString('announcer', null);
                    $caller = $request->getString('caller', null);
                    $skin = $request->getString('skin', null);

                    if( is_null($roomId) ){
                        Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                    }



                    $view = $this->getView('virtualsports', 'html');

                    $view->roomId = $roomId;
                    $view->announcer = $announcer;
                    $view->caller = $caller;
                    $view->skin = $skin;

                    $view->bingoplay = "YES";
                    $view->display();
                } else
                    Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::PLEASE_LOGIN_FIRST);
                break;

            case "slot":
                Session::setSessionVariable('LOTTERY_lastpage', Redirection::SLOT);
                $serviceData = Utilities::getServiceDetails(Constants::SGE);
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $mobile = false;
                $usrAgt = $_SERVER['HTTP_USER_AGENT'];
                /* if(stripos ($usrAgt, "iPad")!==false || stripos ($usrAgt, "iPod")!==false || stripos ($usrAgt, "iPhone")!==false ||  stripos ($usrAgt, "Android")!==false){
                  $query
                  ->select("*")
                  ->from($db->quoteName('#__game_master'))
                  ->where("isImageGeneration='Y'")
                  ->order('gameCategory');

                  $mobile=true;
                  }
                  else { */
                $query
                        ->select("*")
                        ->from($db->quoteName('#__slot_game_master'))
                        ->order('gameCategory');
                //}
                $db->setQuery($query);
                $result = $db->loadObjectList();
                //exit(json_encode($result));
                if (count($result) <= 0) {
                    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, Errors::NO_GAME_AVAILABLE_TO_PLAY);
                }
                $view = $this->getView('slot', 'html');
                $view->serviceData = $serviceData;
                $view->CurrData = $CURRENCY;
                // $view->currency= Constants::MYCURRENCYCODE;
                $view->recset = $result;
                $view->slotnew = "YES";
                $serviceRootUrl = str_replace("192.168.4.36", "41.190.38.202", $serviceData['serviceRootUrl']);
                $view->igepath = $serviceRootUrl;
                if ($mobile)
                    $view->igepath = ServerUrl::IGEWEB;
                $view->igepathhtml5 = ServerUrl::IGEWEB;
                $view->display();
                break;
            case 'slot_updategamelistmobile' :
                $serviceData = Utilities::getServiceDetails(Constants::SGE);
                $serviceRootUrl = str_replace("192.168.4.36", "192.168.4.39", $serviceData['serviceRootUrl']);
                $response = ServerCommunication::sendCallToGameEngine(
                                $serviceRootUrl . ServerUrl::SLOT_GET_GAME_LIST . "?domainId=" . $serviceData['domainId'] . "&deviceName=MOBILE&merchantKey=" . $serviceData['merchantKey'] . "&secureKey=" . $serviceData['merchantKey'] . "&domainName=" . $serviceData['domainName'], array(), false, false, false, true
                );
                if (Validations::getErrorCode() != 0) {
                    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, Validations::getRespMsg());
                }
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                foreach ($response->games as $value) {
                    $row = new stdClass();
                    $row->gameNumber = $value->gameNumber;
                    $row->gameName = $value->gameName;
                    $row->gameCategory = $value->gameCategory;
                    $row->currencyCode = $value->currencyCode;
                    $row->windowHeight = $value->windowHeight;
                    $row->windowWidth = $value->windowWidth;
                    $row->gameImageLocations = $value->gameImageLocations->imagePath;
                    $row->gameDescription = mysql_real_escape_string($value->gameDescription);
                    $row->deviceName = "MOBILE";
                    $db->insertObject('#__slot_game_master', $row);
                }
                Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_SUCCESS, Errors::GAME_DATA_UPDATED);
                break;
            case 'slot_updategamelist' :
                $serviceData = Utilities::getServiceDetails(Constants::SGE);
                $serviceRootUrl = str_replace("192.168.4.36", "192.168.4.39", $serviceData['serviceRootUrl']);
                /* $response = ServerCommunication::sendCallToGameEngine(
                  "http://ala-new.winBetting.com/SGEOLD/getGameListData.action?deviceName=PC&merchantKey=2&secureKey=25d55ad283aa400af464c76d713c07ad&domainName=tbg.lottoBetting.com",
                  array(), false, false, false, true
                  ); */
                $response = ServerCommunication::sendCallToGameEngine("http://ala-new.winBetting.com/SGE/getGameListData.action?deviceName=TAB&merchantKey=2&secureKey=25d55ad283aa400af464c76d713c07ad&domainName=tbg.lottoBetting.com&language=English&currencyCode=USD&isMobile=N&isFlash=Y&isHtml=Y&istablet=Y", array(), false, false, false, true);
                if (Validations::getErrorCode() != 0) {
                    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, Validations::getRespMsg());
                }
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                //$db->setQuery("truncate table ".$db->quoteName('#__slot_game_master'));
                //$db->setQuery("DELETE FROM ".$db->quoteName('#__slot_game_master')". WHERE thirdPartyURL is null;");
                $db->loadResult();
                foreach ($response->games as $value) {
                    $row = new stdClass();
                    $row->gameNumber = $value->gameNumber;
                    $row->gameName = $value->gameName;
                    $row->gameCategory = $value->gameCategory;
                    $row->currencyCode = $value->currencyCode;
                    $row->windowHeight = $value->windowHeight;
                    $row->windowWidth = $value->windowWidth;
                    $row->gameImageLocations = $value->gameImageLocations->imagePath;
                    //$row->gameDescription = mysql_real_escape_string($value->gameDescription);
                    $row->gameDescription = htmlentities($value->gameDescription);
                    $row->deviceName = "PC";
                    $db->insertObject('#__slot_game_master', $row);
                }
                Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_SUCCESS, Errors::GAME_DATA_UPDATED);
                break;

            case 'momo-response' :
                exit(json_encode($_REQUEST));
                break;
            case 'server-response' :
                exit(json_encode($_SERVER));
                break;
        }
    }

    /**
     * Don't remove this. Highly important.
     */
    public function specialCheck($controller) {
        $app = JFactory::getApplication();
        $ua = Mobile_Detect::getBrowserDetails();
        if (strtolower($controller) != "browser-not-supported" && $ua['name'] == 'Internet Explorer' && (int) $ua['version'] <= Constants::IE_NS_VERSION) {
            Redirection::to(Redirection::BROWSER_NOT_SUPPORTED);
        }
        if (Session::getSessionVariable('fireLoginEvent') === true)
            Mixpanel::fireLoginEvent();
        if (Session::getSessionVariable('fireRegistrationEvent') === true)
            Mixpanel::fireRegistrationEvent();
        if (Utilities::getPlayerToken() !== false && $app->getTemplate('template')->id == Constants::TEMPLATE_ID && $controller !== "logout") {
            Utilities::fetchHeaderInfo();
        }
        /* if(strpos($_SERVER['REQUEST_URI'], "fixed//") !== false)
          {
          $_SERVER['REQUEST_URI'] = str_replace("fixed//", "", $_SERVER['REQUEST_URI']);
          Redirection::to($_SERVER['REQUEST_URI']);
          } */
        $_SERVER['REQUEST_URI'] = str_replace("fixed//", "fixed/", $_SERVER['REQUEST_URI']);
        if ($controller == "fixed" && strpos($_SERVER['REQUEST_URI'], "fixed/mailerlink") !== false) {
            Redirection::to(str_replace("/fixed/mailerlink", "", $_SERVER['REQUEST_URI']));
        }
        if ($controller == "fixed" && (strpos($_SERVER['REQUEST_URI'], "fixed/index.php") !== false)) {
            Redirection::to(JUri::base());
        }
        Utilities::manageButtons();
        switch (strtolower($controller)) {
            case "home":
                $doc = JDocumentHtml::getInstance();
                $doc->addCustomTag('<meta name="google-site-verification" content="GfxIau3ReI41cTHwL3LB8XE_8u_2LO6fM2DQi_cnEsU" />');
                break;
            case "after-registration":
                if (Session::sessionValidate()) {
                    if (Session::getSessionVariable('after_registration') === false) {
                        Redirection::to(JUri::base());
                    }
                    Session::unsetSessionVariable('after_registration');
                } else {
                    Redirection::to(Redirection::LOGIN);
                }
                break;
            case "forgot-password-success":
                if (!isset($_SESSION['forgot_emailid']))
                    Redirection::to(JUri::base());
                $doc = JFactory::getDocument();
                $doc->addScriptDeclaration("
                        jQuery(document).ready(function($) {
                            $('span#forgot_emailid').html('" . $_SESSION['forgot_emailid'] . "');
                        });
                    ");
                unset($_SESSION['forgot_emailid']);
                break;
            case "activation-link-expired" :
                if (Session::getSessionVariable('activation-link-expired') === false) {
                    Redirection::to(JUri::base());
                }
                Session::unsetSessionVariable('activation-link-expired');
                break;
            case "forgot-password-link-expired" :
                if (Session::getSessionVariable('forgot-password-link-expired') === false)
                    Redirection::to(JUri::base());
                Session::unsetSessionVariable('forgot-password-link-expired');
                break;
            case "password-changed" :
                if (Session::getSessionVariable('passwordChanged') === false)
                    Redirection::to(JUri::base());
                Utilities::generateLoginToken();
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/custom/login.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate.min.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/MD5.min.js");
                Session::unsetSessionVariable('passwordChanged');
                break;
            case "password-reset" :
                if (Session::getSessionVariable('passwordReset') === false)
                    Redirection::to(JUri::base());
                Utilities::generateLoginToken();
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/custom/login.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate.min.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
                Html::addJs(JUri::base() . "templates/shaper_helix3/js/MD5.min.js");
                Session::unsetSessionVariable('passwordReset');
                break;
        }
    }

}
