<?php
$lang = explode("-", JFactory::getLanguage()->getTag())[0];
$playerToken = Utilities::getPlayerToken();
$playerId = Utilities::getPlayerID();
$url = Configuration::SPORTS_BETTING_IFRAME;
$currencyInfo = Utilities::getCurrencyInfo();
$currency = $currencyInfo[0];
$dispCurrency = $currencyInfo[1];
?>

<style>
    .sb-floatFixBtnWrap {
        position: fixed;
        bottom: 0px;
        right: 0px;
        z-index: 100000000;
        width: 100%;
        border-top: 1px solid #c1c1c1;
        display: flex;
        align-items: center;
    }

    .sb-floatFixBtnWrap .sb-floatFixBtnInnerWrap {
        display: flex;
        flex: 1;
        background-color: #f5f5f5;
        max-width: 1000px;
        margin: auto;
    }

    .sb-floatFixBtnWrap .sb-input {
        padding: 2px 4px;
        align-self: stretch;
        background-color: #c1c1c1;
        position: relative;
    }

    .sb-floatFixBtnWrap .sb-input input {
        width: 70px;
        border-radius: 3px;
        border: 1px solid #c1c1c1;
        box-shadow: inset 1px 2px 4px rgba(0, 0, 0, 0.5);
        font-size: 11px;
        height: 30px;
        text-align: center;
        padding-right: 20px;
        background-color: #ffffff;
        -webkit-appearance: none;
        padding-left: 3px;
    }

    .sb-floatFixBtnWrap .sb-input input:focus {
        outline: none;
    }

    .sb-floatFixBtnWrap .sb-input button {
        position: absolute;
        right: 4px;
        width: 20px;
        top: 50%;
        height: 28px;
        transform: translateY(-50%);
        border: none;
        background-color: #fdd116;
        color: #ffffff;
        border-radius: 0 3px 3px 0;
        font-size: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sb-floatFixBtnWrap .sb-input button:after {
        width: 0;
        height: 0;
        content: '';
        display: block;
        border-left: 8px solid #012161;
        border-top: 4px solid transparent;
        border-bottom: 4px solid transparent;
    }

    .sb-floatFixBtnWrap .sb-betInfoWrap {
        flex: 1;
        border: none;
        display: flex;
        align-self: stretch;
    }

    .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo {
        flex: 1 1 auto;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }

    .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo .label {
        font-size: 0.8em;
        margin: 0 5px;
        line-height: 1;
        text-align: center;
        color: inherit;
        font-weight: 400;
    }

    .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo + .betInfo:before {
        content: '';
        height: 100%;
        width: 1px;
        top: 0;
        left: -1px;
        position: absolute;
        display: block;
        background-color: #c1c1c1;
    }

    .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo + .betInfo:after {
        content: '';
        height: 7px;
        width: 7px;
        top: 50%;
        left: -4px;
        position: absolute;
        margin-top: -4px;
        background-color: #c1c1c1;
        border-radius: 50%;
    }

    .sb-floatFixBtnWrap .sb-floatBtn {
        width: 90px;
        height: 36px;
        border-radius: 0;
        background-color: #d30e24;
        position: relative;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        letter-spacing: 0.04em;
    }

    @media only screen and (min-width: 1024px) {
        .sb-floatFixBtnWrap {
            background-color: #2b3a57;
            border: none;
        }

        .sb-floatFixBtnWrap .sb-floatFixBtnInnerWrap {
            background-color: transparent;
            color: #eef6ff;
        }

        .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo {
            flex-direction: row-reverse;
            font-size: 1.22em;
            font-weight: 500;
            margin: 2px 0;
        }

        .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo .txt {
            color: #fdd116;
        }

        .sb-floatFixBtnWrap .sb-betInfoWrap .betInfo + .betInfo:after {
            display: none;
        }

        .sb-floatFixBtnWrap .sb-input input {
            width: 120px;
            padding-left: 3px;
        }

        .sb-floatFixBtnWrap .sb-floatBtn {
            width: 200px;
        }
    }
</style>
<div class="sb-floatFixBtnWrap" id="sbsCart">
    <div class="sb-floatFixBtnInnerWrap">
        <!-- <div class="sb-input">
            <input type="number" placeholder="Event ID" />
            <button class="go_BTN">></button>
        </div> -->
        <div class="sb-betInfoWrap">
            <div class="betInfo">
                <div class="txt sb-badgeVal">0</div>
                <div class="label">No. Of Bets </div>
            </div>
            <div class="betInfo">
                <span class="currency">$</span>
                <div class="txt stake" >
                    0
                </div>
                <div class="label">Bet Value</div>
            </div>
        </div>
        <div class="sb-floatBtn" onclick="cartOpen();">
            BUY
        </div>
    </div>
</div>

<script type="text/javascript">
    var _ic = _ic || [];
    _ic.push(['server', '<?php echo $url; ?>']);
    _ic.push(['gametype', 'sbs']);
    _ic.push(['session_id', '<?php echo $playerToken; ?>']);
    _ic.push(['language', '<?php echo $lang; ?>']);
    _ic.push(['player_id', '<?php echo $playerId; ?>']);
    _ic.push(['iframe_div_id', 'lottogames_div_iframe']);
    _ic.push(['currency', '<?php echo $this->CurrData['curCode']; ?>']);
    _ic.push(['family', 'SPORTS_BOOK']);
    (function () {
        document.write('<' + 'script type="text/javascript" src="<?php echo $url; ?>assets/js/lottogames.js"><' + '/script>');
    })();

    $('body').addClass('iframeGamePlay');
    var cartStatus = false;

    function fetchMobile() {
        return document.getElementById('playerMobileNo').value;
    }

    function cartOpen(){
        cartStatus = !cartStatus;
        childCartOpen(cartStatus);
        document.getElementById('sbsCart').style.display = "none";
    }

    function postCartSize(data){
        $('#sbsCart .sb-badgeVal').text(data.cartInfo.size);
        $('#sbsCart .stake').text(data.cartInfo.stake);
        if(data.cartInfo.visibility && data.cartInfo.visibility != 'null'){
            document.getElementById('sbsCart').style.display = "block";
        }

    }
</script>
<input type="hidden" name="playerMobileNo" id="playerMobileNo" value="<?php echo Utilities::getPlayerLoginResponse()->mobileNo; ?>" />
<script type="text/javascript">LottoGames.frame(_ic);</script>