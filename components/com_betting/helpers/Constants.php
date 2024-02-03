<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class Constants {

    const JS_VER = '1.3';
    const CSS_VER = '1.0';
    const HTML5_VER = '1.9';

    const GAME_LIST_LIMIT = "35";
    const AMAZON_S3_KEYID = "";
    const AMAZON_S3_SECRETKEY = "";
    const AMAZON_S3_DISTRIBUTIONID = "";
    const PROJECT_NAME = "LUKKYWIN";
    const WITHDRAWAL_START_DATE = "2020-02-13";

    const BONUS_MODULE = true;
    const HIDE_ZERO_DECIMAL = false;
    const DRAW_TIME = "8:30 PM";

    const APP_DOWNLOAD_LINK = "";
    const RUMMY_APH_ABS_PATH = '';
    const RUMMY_APH_ABS_PATH_APPSFX2X = '';
    const RUMMY_SPECIAL_PLAYER_TESTING_ARRAY = array(207195, 413420, 369244, 413381, 381611, 199745, 201976, 400908, 192950, 228746, 201874, 280863, 235120, 337586, 371035, 391724, 303857, 267159, 155876, 202556, 44138);
    const MIXPANEL_ID = '';
    const COUNTRY_CODE = "KE";
    const COUNTRY_NAME = "India";
    const CURRENCY_ID = 15;
    const AJAX_FLAG_RELOAD = "RELOAD";
    const AJAX_FLAG_SESSION_EXPIRE = "EXPIRE";
    const AJAX_FLAG_ALREADY_LOGGED_IN = "ALREADY";
    // Constants related to Avatar
    const AVATAR_PATH = "/images/bet2winasia/avatar/";
    const AVATAR_PATH_ABS_COMMON = JPATH_BASE . self::AVATAR_PATH . "common/";
    const AVATAR_PATH_REL_COMMON = Redirection::BASE . self::AVATAR_PATH . "common/";
    const AVATAR_PATH_ABS_PLAYER = JPATH_BASE . self::AVATAR_PATH . "player/";
    const AVATAR_PATH_REL_PLAYER = Redirection::BASE . self::AVATAR_PATH . "player/";
    const AVATAR_DEFAULT_IMG_NAME = "edit-thumbnail.jpg";
    const AVATAR_PATH_ABS_DEFAULT = JPATH_BASE . self::AVATAR_PATH . "default/";
    const AVATAR_PATH_REL_DEFAULT = Redirection::BASE . self::AVATAR_PATH . "default/";
    const PLAYER_DOCUMENT_PATH = JPATH_BASE . "/images/kpr/player-documents/";
    const MAX_ROW_LIMIT = 50;
    const TXNTYPE_ALL = "ALL";
    const TXNTYPE_PLR_DEPOSIT = "PLR_DEPOSIT";
    const TXNTYPE_PLR_WITHDRAWAL = "PLR_WITHDRAWAL";
    const TXNTYPE_PLR_WAGER = "PLR_WAGER";
    const TXNTYPE_PLR_WAGER_REFUND = "PLR_WAGER_REFUND";
    const TXNTYPE_PLR_WINNING = "PLR_WINNING";
    const TXNTYPE_PLR_HIGH_WINNING = "PLR_HIGH_WINNING";
    const TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL = "PLR_DEPOSIT_AGAINST_CANCEL";
    const TXNTYPE_PLR_BONUS_TRANSFER = "PLR_BONUS_TRANSFER";
    const TXNTYPE_BO_CORRECTION = "BO_CORRECTION";
    const TXNTYPE_BONUS_DETAILS = "BONUS_DETAILS";
    const TXNTYPE_TICKET_DETAILS = "ticket";
    const CLIENT_NAME = 'Sabanzuri';
    const MY_LANGUAGE_MAP = array( "en-GB" => "en" , "th-TH" => "th" ,"mm-MM" => 'mm');

    public static $txnTypes_TransactionDetails = array('EN' => array(
        self::TXNTYPE_ALL => "Ledger",
        self::TXNTYPE_PLR_DEPOSIT => "Deposit",
        self::TXNTYPE_PLR_WITHDRAWAL => "Withdrawal",
        self::TXNTYPE_PLR_WAGER => "Wager",
        self::TXNTYPE_PLR_WAGER_REFUND => "Wager Refund",
        self::TXNTYPE_PLR_WINNING => "Winning",
        self::TXNTYPE_PLR_HIGH_WINNING => "High Winning",
        self::TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL => "Withdrawal Cancel",
//        self::TXNTYPE_PLR_BONUS_TRANSFER => "Bonus to Cash",
//        self::TXNTYPE_BO_CORRECTION => "Payment Correction",
//        self::TXNTYPE_BONUS_DETAILS => "Bonus Details",
//        self::TXNTYPE_TICKET_DETAILS => "Ticket Details"
    ), "TH" => array(
        self::TXNTYPE_ALL => "บัญชีแยกประเภท",
        self::TXNTYPE_PLR_DEPOSIT => "เงินฝาก",
        self::TXNTYPE_PLR_WITHDRAWAL => "การถอนตัว",
        self::TXNTYPE_PLR_WAGER => "เดิมพัน",
        self::TXNTYPE_PLR_WAGER_REFUND => "เดิมพันคืนเงิน",
        self::TXNTYPE_PLR_WINNING => "การชนะ",
        self::TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL => "ถอนการยกเลิก",
    ),"MM" => array(
        self::TXNTYPE_ALL => "Ledger",
        self::TXNTYPE_PLR_DEPOSIT => "အပ်ငွေ",
        self::TXNTYPE_PLR_WITHDRAWAL => "ထုတ်ယူခြင်း",
        self::TXNTYPE_PLR_WAGER => "ပန်းချီဆရာ",
        self::TXNTYPE_PLR_WAGER_REFUND => "ပန်းချီဆရာ ပြန်အမ်းငွေ",
        self::TXNTYPE_PLR_WINNING => "အနိုင်ရ",
        self::TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL => "ထုတ်ယူပယ်ဖျက်ပါ",
     ),"FR" => array(
        self::TXNTYPE_ALL => "registre",
        self::TXNTYPE_PLR_DEPOSIT => "Dépôt",
        self::TXNTYPE_PLR_WITHDRAWAL => "Retrait",
        self::TXNTYPE_PLR_WAGER => "Parier",
        self::TXNTYPE_PLR_WAGER_REFUND => "Remboursement de paris",
        self::TXNTYPE_PLR_WINNING => "Gagnante",
        self::TXNTYPE_PLR_DEPOSIT_AGAINST_CANCEL => "Retrait Annuler",
    ));
    public static $txnTypes_PaymentOptions = array(
        "DEPOSIT" => "Deposit",
        "WITHDRAWAL" => "Withdrawal"
    );
    public static $withdrawlDoc_singleID = array(
        "PASSPORT" => 'Passport (Photo, Address Proof, Front and Back Colour Scan)',
        "AADHAR_CARD" => 'Aadhar (Photo, Address Proof, Front and Back Colour Scan)',
        "VOTER_ID" => 'Voters Identity (Photo, Address Proof, Front and Back Colour Scan)',
        "DRIVING_LICENSE" => 'Permanent Driving License (Photo, Address Proof, Front and Back Colour Scan)',
    );
    public static $withdrawalDoc_multipleID = array(
        "PAN_CARD" => "Pan Card (Colour Scan)",
    );
    public static $withdrawalDoc_multipleAddress = array(
        "E_BILL" => "Electricity Bill (With your name and colour scan)",
        "T_BILL" => 'Telephone Bill (With your name and colour scan)',
    );

    const STATE_LIST = array(
        "MM" => array('MM-01' => 'Sagaing', 'MM-02' => 'Bago', 'MM-03' => 'Magway', 'MM-04' => 'Mandalay', 'MM-05' => 'Tanintharyi', 'MM-06' => 'Yangon', 'MM-07' => 'Ayeyarwady', 'MM-11' => 'Kachin', 'MM-12' => 'Kayah', 'MM-13' => 'Kayin', 'MM-14' => 'Chin', 'MM-15' => 'Mon', 'MM-16' => 'Rakhine', 'MM-17' => 'Shan'),
        );
    const CURRENT_REGISTRATION_TYPE = "MINI";
    // Constants related to deposit
    const CREDIT_CARD_DEPOSIT = "CREDIT_CARD";
    const DEBIT_CARD_DEPOSIT = "DEBIT_CARD";
    const NET_BANKING_DEPOSIT = "NET_BANKING";
    const CHEQUE_TRANS_DEPOSIT = "CHEQUE_TRANS";
    const WIRE_TRANS_DEPOSIT = "WIRE_TRANS";
    const CASH_CARD_DEPOSIT = "CASH_CARD";
    const PREPAID_WALLET_DEPOSIT = "PREPAID_WALLET";
    const CASH_PAYMENT_DEPOSIT = "CASH_PAYMENT";
    const MOBILE_MONEY = "MOBILE_MONEY";
    const CASH_COLLECTION_DEPOSIT = "CASH_COLLECTION";
    const BANK_TRANS_DEPOSIT = "BANK_TRANS";
    const PAYTM_WALLET_DEPOSIT = "PAYTM_WALLET";
    const FREECHARGE_WALLET = "FREECHARGE_WALLET";
    const MOBILE_WALLET_DEPOSIT = "MOBILE_WALLET";
    const UPI_DEPOSIT = "UPI_PAYMENT";
    const MOBIKWIK_DEPOSIT = "MOBIKWIK_DEPOSIT";
    public static $paytypeCode_images = array(
        self::CREDIT_CARD_DEPOSIT => "icon1",
        self::DEBIT_CARD_DEPOSIT => "icon2",
        self::NET_BANKING_DEPOSIT => "icon3",
        self::CASH_PAYMENT_DEPOSIT => "icon7",
        self::CASH_COLLECTION_DEPOSIT => "",
        self::CHEQUE_TRANS_DEPOSIT => "icon4",
        self::BANK_TRANS_DEPOSIT => "",
        self::WIRE_TRANS_DEPOSIT => "icon5",
        self::CASH_CARD_DEPOSIT => "icon6",
        self::PREPAID_WALLET_DEPOSIT => "icon7",
        self::PAYTM_WALLET_DEPOSIT => "icon8",
    );
    public static $paytypeCode_class = array(
        self::CREDIT_CARD_DEPOSIT => self::CREDIT_CARD_DEPOSIT,
        self::DEBIT_CARD_DEPOSIT => self::DEBIT_CARD_DEPOSIT,
        self::NET_BANKING_DEPOSIT => self::NET_BANKING_DEPOSIT,
        self::CASH_PAYMENT_DEPOSIT => self::CASH_PAYMENT_DEPOSIT,
        self::CASH_COLLECTION_DEPOSIT => self::CASH_COLLECTION_DEPOSIT,
        self::CHEQUE_TRANS_DEPOSIT => "cheque_transfer",
        self::BANK_TRANS_DEPOSIT => self::BANK_TRANS_DEPOSIT,
        self::WIRE_TRANS_DEPOSIT => "wire_transfer",
        self::CASH_CARD_DEPOSIT => "cash_card",
        self::PREPAID_WALLET_DEPOSIT => "wallets",
        self::PAYTM_WALLET_DEPOSIT => self::PAYTM_WALLET_DEPOSIT,
        self::MOBILE_WALLET_DEPOSIT => "wallets"
    );
    const ONLINE_DEPOSIT_MODES = array(
        self::CREDIT_CARD_DEPOSIT, self::DEBIT_CARD_DEPOSIT, self::NET_BANKING_DEPOSIT, self::PREPAID_WALLET_DEPOSIT,
        self::PAYTM_WALLET_DEPOSIT, self::MOBILE_WALLET_DEPOSIT
    );
    const OFFLINE_DEPOSIT_MODES = array(
        self::CHEQUE_TRANS_DEPOSIT, self::WIRE_TRANS_DEPOSIT, self::CASH_CARD_DEPOSIT, self::CASH_PAYMENT_DEPOSIT
    );
    const CREDIT_CARD_ID_IMAGES = array(
        "VISA_CARD" => array(
            "id" => "Credit_visa_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/visa_cardicon.png"
        ),
        "MASTER_CARD" => array(
            "id" => "Credit_mastercard_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/mastercard_cardicon.png"
        ),
        "MAESTRO_CARD" => array(
            "id" => "Credit_maestro_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/maestro_cardicon.png"
        ),
        "AMEX" => array(
            "id" => "Credit_americanexpress_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/americanexpress_cardicon.png"
        ),
        "RUPAY" => array(
            "id" => "Credit_rupay_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/rupay_cardicon.png"
        )
    );
    const DEBIT_CARD_ID_IMAGES = array(
        "VISA_CARD" => array(
            "id" => "Debit_visa_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/visa_cardicon.png"
        ),
        "MASTER_CARD" => array(
            "id" => "Debit_mastercard_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/mastercard_cardicon.png"
        ),
        "MAESTRO_CARD" => array(
            "id" => "Debit_maestro_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/maestro_cardicon.png"
        ),
        "AMEX" => array(
            "id" => "Debit_americanexpress_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/americanexpress_cardicon.png"
        ),
        "RUPAY" => array(
            "id" => "Debit_rupay_div",
            "src" => Redirection::BASE . "/templates/shaper_helix3/images/cashier/rupay_cardicon.png"
        )
    );
    const PREPAID_WALLET_IMAGES = array(
        220 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/wallets_paycash.jpg",
        233 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/wallets_mobikwik.jpg",
        231 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/wallets_icash.jpg",
        229 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/wallets_itzcash.jpg"
    );
    const MOBILE_WALLET_IMAGES = array(
        242 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/freecharge.jpg",
        243 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/olamoney.jpg",
        244 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/jiomoney.jpg",
        245 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/airtelmoney.jpg",
        246 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/mobikwik.jpg",
        247 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/oxigen.jpg",
        248 => Redirection::BASE . "/templates/shaper_helix3/images/cashier/mrupee.gif"
    );
    const NET_BANKING_TOP_BANKS = array(
        "30" => ".jpg",
        "21" => ".jpg",
        "20" => ".jpg",
        "8" => ".jpg",
        "34" => ".jpg",
        "211" => ".jpg",
        "28" => ".jpg",
        "23" => ".jpg",
        "22" => ".jpg",
    );
    // Variables for implementation of player-wise content
    const PLAYER_WISE_MODULES = array(
         'mod_popup'
    );
    const ALLOWED_POSITION_PLAYERWISE_CONTENT = array(
        'popupnew'
    );
    const CLASS_NAME_SLIDE_DIVS = 'player-wise-div';
    // Inbox constants
    const INBOX_LIMIT = 51;
    // Withdrawal constants
    const WITHDRAWAL_CASH_TRANS = 'CASH_PAYMENT';
    const WITHDRAWAL_CHEQUE_TRANS = 'CHEQUE_TRANS';
    const WITHDRAWAL_BANK_TRANS = 'BANK_TRANS';
    public static $paymentTypeCodes_Withdrawal = array(
        self::WITHDRAWAL_CASH_TRANS => "Cash withdrawal",
        self::WITHDRAWAL_CHEQUE_TRANS => "Cheque withdrawal",
        self::WITHDRAWAL_BANK_TRANS => "Bank Transfer withdrawal"
    );
    // Loyalty Constants
    public static $loyalty_club_images = array(
        "bronze" => "bronze.png",
        "silver" => "silver.png",
        "gold" => "gold.png",
        "diamond" => "diamond.png",
        "platinum" => "platinum.png",
    );
    const LOYALTY_MERCHANDISE_IMAGES_PATH = Redirection::BASE . "/images/kpr/loyalty/";
    const TEMP_AUTH_ENABLED = false;
    public static $temp_auth_passwords = array(
        'skilrock@123', "123123123"
    );
    /**** Mesaage constants *****/
    const PLAYER_NAME_KEY = "{playerName}";
    const PLAYER_ID_KEY = "{playerId}";
    const PLAYER_USERNAME_KEY = "{playerUsername}";
    const PLAYER_MOBILE_KEY = "{playerMobile}";
    const PLAYER_EMAIL_KEY = "{playerEmail}";
    const PLAYER_WITHDRAWBAL_KEY = "{withdrawableBal}";
    const PLAYER_BONUSBAL_KEY = "{bonusBal}";
    const PLAYER_TOATALBAL_KEY = "{totalBal}";
    const PLAYER_CASHBAL_KEY = "{cashBal}";
    const IGE = "IGE";
    const SGE = "SGE";
    const VERSION = "4.0";
    const REQUEST_CHANNEL = "portal";
    const DATEFORMAT = "d M, Y G:i";
    const MYCURRENCYCODE = "KES";
    const MYCURRENCYSYMBOL = "KES";
    const CODEVERSION = "2.0";
    public static $serviceDetails = array(
        'country' => 'KENYA',
        'currencySymbol' => 'KES',
        self::IGE => array(
            'secureCode' => '12345678',
            'domainName' => Configuration::DOMAIN_NAME,
            'lang' => 'english',
            'merchantKey' => "4",
        ),
        self::SGE => array(
            'domainId' => 1,
            'serviceRootUrl' => 'http://ala-new.winBetting.com/SGE/',
            'serviceCode' => self::SGE,
            'merchantCode' => 2,
            'serviceName' => 'Slot',
            'secureKey' => '25d55ad283aa400af464c76d713c07ad',
            'domainName' => "tbg.lottoBetting.com",
            'merchantKey' => "2",
            'secureCode' => '12345678',
            'lang' => 'eng'
        )
    );
    const DEFAULT_CURRENCY_CODE = "INR";
    public static $currencyMap = array(
        'KES' => array(
            'id' => 15,
            'decSymbol' => 'KES ',
            'curCode' => 'KES',
            'hexSymbol' => 'KES ',
            'entity' => 'KES',
            'enable' => 1
        ),
        'CFA' => array(
            'id' => 11,
            'decSymbol' => 'CFA ',
            'curCode' => 'CFA',
            'hexSymbol' => 'CFA ',
            'entity' => 'CFA ',
            'enable' => 0
        ),
        'USD' => array(
            'id' => 10,
            'curCode' => 'USD',
            'decSymbol' => '&#036;',
            'hexSymbol' => '&#x24;',
            'entity' => '$',
            'enable' => 0
        ),
        'JPY' => array(
            'id' => 9,
            'curCode' => 'JPY',
            'decSymbol' => '&#165;',
            'hexSymbol' => '&#xa5;',
            'entity' => '�',
            'enable' => 0
        ),
        'INR' => array(
            'id' => 8,
            'curCode' => 'INR',
            'decSymbol' => '&#8377;',
            'hexSymbol' => '&#x20B9;',
            'entity' => '?',
            'enable' => 0
        ),
        'HKD' => array(
            'id' => 7,
            'curCode' => 'HKD',
            'decSymbol' => 'HK&#036;',
            'hexSymbol' => 'HK&#x24;',
            'entity' => 'HK$',
            'enable' => 0
        ),
        'GBP' => array(
            'id' => 6,
            'curCode' => 'GBP',
            'decSymbol' => '&#163;',
            'hexSymbol' => '&#xa3;',
            'entity' => '�',
            'enable' => 0
        ),
        'EUR' => array(
            'id' => 5,
            'curCode' => 'EUR',
            'decSymbol' => '&#8364;',
            'hexSymbol' => '&#x20AC;',
            'entity' => '�',
            'enable' => 0
        ),
        'CNY' => array(
            'id' => 4,
            'curCode' => 'CNY',
            'decSymbol' => '?',
            'hexSymbol' => '?',
            'entity' => '?',
            'enable' => 0
        ),
        'CHF' => array(
            'id' => 3,
            'curCode' => 'CHF',
            'decSymbol' => 'Fr. ',
            'hexSymbol' => 'Fr. ',
            'entity' => 'Fr. ',
            'enable' => 0
        ),
        'CAD' => array(
            'id' => 2,
            'curCode' => 'CAD',
            'decSymbol' => 'C&#036;',
            'hexSymbol' => 'C&#x24;',
            'entity' => 'C$',
            'enable' => 0
        ),
        'AUD' => array(
            'id' => 1,
            'curCode' => 'AUD',
            'decSymbol' => 'A&#036;',
            'hexSymbol' => 'A&#x24;',
            'entity' => 'A$',
            'enable' => 0
        ),
        'THB' => array(
            'id' => 13,
            'curCode' => 'THB',
            'decSymbol' => '&#3647;',
            'hexSymbol' => '&#x0E3F;',
            'entity' => '฿',
            'enable' => 0
        ),
    );
    /***********************Betting Configuration**************************/
    const WITHDRAWAL_MIN_LIMIT = 200;
    const WITHDRAWAL_MAX_LIMIT = 10000;
    const MOBILE_MIN_LENGTH = 10;
    const MOBILE_MAX_LENGTH = 10;
    const MOBILE_PATTERN = (self::MOBILE_MIN_LENGTH == self::MOBILE_MAX_LENGTH) ? "^[6,7,8,9]{1}[0-9]{9}$" : "^[7,1]{1}[0-9]{8}$";
    const IE_NS_VERSION = 8;
    const TEMPLATE_ID = 13;
    const MOBILE_COUNTRY_CODE = '+91';
    const IGE_LOGIN_TOKEN = 'IGELOGIN';
    const DEFAULT_TIMEZONE = 'Asia/Kolkata';
    const FETCHALL_SKIP_ROUTE = array(
        "ugadi-special-offer-mar17",
        "super-welcome-bonus-offer-mar17",
        "rummy-legends-20-26-mar17",
    );
    const MAIL_FROM = "info@lukkywin.com";
    // Refer A Friend Constants
    const GMAIL_APP_NAME = "LukkyWIn";
    const GOOGLE_CLIENT_ID = '628695675587-ihogkrodct1o949s0neerctjp74okila.apps.googleusercontent.com';
    const GOOGLE_CLIENT_SECRET = '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';
    const GOOGLE_MAX_RESULTS = 1000;
    const OUTLOOK_CLIENT_ID = '00000000401C010A';
    const OUTLOOK_CLIENT_SECRET = 'ogXPaZuYpPqgWY9kHwmqHmb';
    const FACEBOOK_APP_ID = "389256989733447";
    const YAHOO_OAUTH_CONSUMER_KEY = "dj0yJmk9V29OdVhNcmVXVFprJmQ9WVdrOVZsaEVTWEZSTmpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PWFm";
    const YAHOO_OAUTH_CONSUMER_SECRET = "ecf0f2f8ce1ac048693c5a2c127c795e714b5e05";
    const TWITTER_HASHTAG = 'CamWinLotto';
    const TWITTER_REFER_TEXT = " Love Lottery Games. Register on www.sabanzurilotto.com for an ecstatic experience.";
    
    const BETGAMES_DATA = '[
  {
    "lobby_cat": "Betgames",
    "tableId": "1",
    "name": "Lucky 7",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
  {
    "lobby_cat": "Betgames",
    "tableId": "3",
    "name": "Lucky 5",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "5",
    "name": "Bet On Poker",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "6",
    "name": "Bet On Baccarat",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "7",
    "name": "Wheel Of Fortune",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "8",
    "name": "War Of Bets",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "9",
    "name": "Lucky 6",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "10",
    "name": "Dice Duel",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "11",
    "name": "Speedy 7",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "12",
    "name": "6+ Poker",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "13",
    "name": "Andar Bahar",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "16",
    "name": "Classic Wheel",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "18",
    "name": "Satta Matka",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "17",
    "name": "Football Grid",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "19",
    "name": "T-Basket",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  },
    {
    "lobby_cat": "Betgames",
    "tableId": "20",
    "name": "T-Kick",
    "gameType": "betgames",
    "gameProvider": "betgames",
    "gameVertical": "betgames",
    "language": "en",
    "gameTypeUnified": "betgames",
    "videoSnapshot": "",
    "img_src": "",
    "priority": "6"
  }
]';

}
