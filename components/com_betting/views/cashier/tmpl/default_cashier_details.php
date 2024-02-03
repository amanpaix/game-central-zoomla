<?php
$playerLoginResponse = Utilities::getPlayerLoginResponse();
if (($playerLoginResponse->walletBean->withdrawableBal % 1) == 0) {
    $cashBalance = $playerLoginResponse->walletBean->cashBalance;
} else {
    $cashBalance = number_format((float) $playerLoginResponse->walletBean->cashBalance, 2);
}
//$payTypeMap = json_encode($this->options);
$min_bal = Configuration::MINIMUM_BALANCE;
// echo '<pre>';
// print_r($playerLoginResponse->walletBean);die;
$paymentAccounts = array();
if(array_key_exists('paymentAccounts',$this->options)){
$paymentAccounts = $this->options->paymentAccounts;
}
$cashBalance = Utilities::FormatCurrency(number_format((float)$playerLoginResponse->walletBean->cashBalance,2),$currencyCode);	
$bonusBalance = Utilities::FormatCurrency(number_format((float)$playerLoginResponse->walletBean->bonusBalance,2),$currencyCode);	
$withdrawalBalance = Utilities::FormatCurrency(number_format((float)$playerLoginResponse->walletBean->withdrawableBal,2),$currencyCode);
if(array_key_exists('paymentAccounts',$this->withdrawalOptions)){
$withdrawalAccounts = $this->withdrawalOptions->paymentAccounts;
} 
$bal = number_format((float) $playerLoginResponse->walletBean->withdrawableBal, 2);
$cancelWithdrawalURL = JRoute::_('index.php?task=withdrawal.cancelPendingWithdrawal');
$currency_code =  $playerLoginResponse->walletBean->currency;
$maxValueDeposit;
$minValueDeposit;
 $arrayAmount = array();
 $currencyMap = "";
 $depositURL = JRoute::_('index.php?task=cashier.requestCashierDeposit');
  foreach ($this->options->payTypeMap as $paymentOptions) {
     foreach ($paymentOptions->currencyMap as $currency){
      $currencyMap = $paymentOptions->currencyMap;
               
     }
 }
$currencyList = json_decode(json_encode($currencyMap), true);

 foreach ($this->withdrawalOptions->payTypeMap as $paymentOptions) {
     foreach ($paymentOptions->currencyMap as $currency){
      $withCurrencyMap = $paymentOptions->currencyMap;
               
     }
 }
$withCurrencyList = json_decode(json_encode($withCurrencyMap), true);
 $depositURL = JRoute::_('index.php?task=cashier.requestCashierDeposit');
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MY_WALLET_DEPOSIT; ?>']").parent().addClass('active');
</script>

<div class="myaccount_body_section" id="main-div">
<!--    <div class="">-->
        <div class="entry-header has-post-format">
            <h2 itemprop="name"><?php echo JText::_('BETTING_MY_WALLET')?></h2>
        </div>

        <div class="profileHeader">	
            <div class="userInfoGroup">	
                <div class="userInfo">	
                    <div class="player-balance">	
                        <div class="amountWrap">	
                            <div class="cash"><span class="label"><?php echo JText::_('CASH_BALANCE')?> : </span><span class="cash-balance"><?php echo $cashBalance;?></span><span class=""></span></div>	
                        <div class="bonus"><span class="label"><?php echo JText::_('BONUS_BALANCE')?> : </span><span class="bonus-balance"><?php echo $bonusBalance;?></span><span class=""></span></div>	
                        <div class="withdrawal"><span class="label"><?php echo JText::_('WITHDRAWABLE_BALANCE')?> : </span><span class="withdrawal-balance"><?php echo $withdrawalBalance;?></span><span class=""></span></div>	
                        </div>	
                    </div>	
                </div>	
            </div>	
        </div>

        <div class="heading">
            <ul id="url-tabs" class="tabNav">
                <li class="active" ><a href="#deposit"><?php echo JText::_('WITH_DEPOSIT')?></a></li>
                <li class="" ><a href="#withdrawal"><?php echo JText::_('BETTING_WITHDRAWAL')?></a></li>
            </ul>
        </div>

        <div class="walletContent withdrawal" div_id="withdrawal" style="display:none;">
            <!--            <div class="bs-container">
                            <div class="withdrawal_table_data">-->

<!--            <div class="my-wallet-head-info">
                <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,
                    ultrizcies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
            </div>-->
            <div class="my-wallet-list-wrap">
            <?php foreach($this->withdrawalOptions->payTypeMap as $payType) {
             $withdrawalBal[$payType->payTypeCode] = array(
                    'min' => $payType->minValue,
                    'max' => $payType->maxValue,
                );
                if(count($withdrawalAccounts) > 0){
                              $display = "style='display:flex;'";
                            }else{
                              $display = "style='display:none;'"; 
                            }
                foreach ($payType->subTypeMap as $key => $subTypeMap) {
                    if ($subTypeMap == 'Skrill Digital Wallet')
                        $imgSrc = 'skrill.png';
                    else
                        $imgSrc = $subTypeMap . '.png';
                ?>
                <div class="my-wallet-list">
                    <div class="my-wallet-list-item fig-wrap">
                        <div class="fig-blk"><img src="/images/payment-icons/<?php echo $imgSrc;?>" alt=""></div>
                    </div>
                    <div class="my-wallet-list-item cost-wrap">
                        <div class="cost-blk">
                            <div class="blk-label">Cost</div>
                            <div class="blk-value">
                                <div class="currency-wrap">
                                    <div class="cu-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-wallet-list-item min-wrap">
                        <div class="min-blk">
                            <div class="blk-label">Min</div>
                            <div class="blk-value">
                                <div class="currency-wrap">
                                    <div class="cu-value"><?php echo Utilities::formatCurrency(number_format($payType->minValue,2),$currency_code)?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-wallet-list-item max-wrap">
                        <div class="max-blk">
                            <div class="blk-label">Max</div>
                            <div class="blk-value">
                                <div class="currency-wrap">
                                    <div class="cu-value"><?php echo Utilities::formatCurrency(number_format($payType->maxValue,2),$currency_code)?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php if($payType->payTypeCode == Constants::CASH_PAYMENT_DEPOSIT) {?>
                <div class="my-wallet-list-item action-wrap">
            <div class="action-blk">
                <div class="txt">Please visit the nearest store for wallet top-up. Please keep the Username/Registered
                    Mobile Number, handy, to process the deposit.</div>
            </div>
        </div>
                <?php }else {?>
                    <div class="my-wallet-list-item action-wrap">
                        <div class="action-blk">
<!--                            <div class="blk-info-button">
                                <button class="btn btn-info">i</button>
                            </div>-->
                            <div class="blk-main-action">
                         <form name="cashier-withdrawal-form" id="cashier-withdrawal-form-<?php echo $key; ?>" action="#" method="post" submit-type="ajax" validation-style="left" home-forgot-modal="true" tooltip-mode="manual" novalidate="novalidate">  
                            <div class="section sec1 withdrawal_block" <?php echo $display?>>
                        <?php foreach($payType->payAccReqMap as $set => $payAccReqMap) {
                              if($payAccReqMap == 'YES'){?>
                                    <div class="field-wrap select-parent">
                                    <div class="form-group">
                                        <div class="label"></div>
                                        <select name="withdrawalAccounts" class="withdrawalAccounts" id="withdrawalAccounts-<?php echo $key;?>">
<!--                                        <option value="">Select Account</option>-->
                                        <?php foreach($withdrawalAccounts as $accounts) {
                                    if($key == $accounts->subTypeId) { ?>
                                <option value="<?php echo $accounts->paymentAccId?>"><?php echo $accounts->accNum;?></option>
                                    <?php }}?>   
                                        </select>
                                    <div id="error_withdrawalAccounts-<?php echo $key; ?>" class="manual_tooltip_error error_tooltip"></div>
                                </div>
                                    </div>
                        <?php } }?>
                                <?php foreach($payType->currencyMap as $val){
                         $currency = $val;
                          }?>
                                <?php if(count($withCurrencyList) == 1) {?>
                                    <div class="field-wrap input-parent">
                                    <div class="form-group">
                                        <div class="label">AMOUNT (in <?php echo $currency?>)</div>
                                        <input type="text" name="amount_withdrawal" id="amount_withdrawal-<?php echo $key?>" value="">
                                     <div id="error_amount_withdrawal-<?php echo $key; ?>" class="manual_tooltip_error error_tooltip"></div>
                                    </div>
                                    </div>
                                
                        <?php  echo '<input type="hidden" name="withCurrency" id="withCurrency-'.$key.'" value="'. $currency. '">';
?>
                                <?php }else { ?>
                                <div class="field-wrap input-parent">
                            <div class="label">AMOUNT</div>
                            <div class="input-group">
                                <div class="input-group-prepend open input-currency">
<!--                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">CFA</button>-->
<!--                                    <div class="dropdown-menu">-->
                                         <select name="withCurrency" id="withCurrency-<?php echo $payType->payTypeId?>" onchange="changeWithCurrency(this)">
                                    <?php foreach($withCurrencyList as $Currencykey => $currency){?>
                                            <option value="<?php echo $currency?>"><?php echo $currency?></option>
<!--                                        <a class="dropdown-item" href="#"><?php echo $currency?></a>-->
<!--                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" href="#"></a>-->
                                    <?php } ?>
                                        </select>
<!--                                    </div>-->
                                </div>
                                <input type="text" name="amount_withdrawal" id="amount_withdrawal-<?php echo $key?>" value="">
                            </div>
                        </div>
                                <?php } ?>
                                </div>
                                <div class="section sec2">
                                    <div class="buttonWrap">
                                        <button class="btn btnStyle1 btn-deposit withdrawal_btn" totalBalance="<?php echo $playerLoginResponse->walletBean->totalBalance ?>" paytype-id="<?php echo $payType->payTypeId?>" paymentype-code="<?php echo $payType->payTypeCode?>" subtype-id="<?php echo $key?>"<?php echo $display?>>Withdrawal</button>
                            <?php foreach($payType->payAccReqMap as $value => $payAccReq) {
                            if($payAccReq == 'YES') {?>
                            <button type="button"  class="btn btnOutline btn-addaccount withdrawal_add_new_account" data-toggle="modal" paytype-id="<?php echo $payType->payTypeId?>" curr="<?php echo $currency?>" paymentype-code="<?php echo $payType->payTypeCode?>" subtype-id="<?php echo $key?>"  data-target="#cashier_add_account_withdrawal_popup">Add New Account</button>
                             <?php } }?>
                                    </div>
                                </div>
                    <input type="hidden" id="withPaytypeId-<?php echo $key; ?>" name = "withPaytypeId" value="<?php echo $payType->payTypeId; ?>">
                    <input type="hidden" id="totalBalance-<?php echo $key; ?>" name = "totalBalance" value="<?php echo $playerLoginResponse->walletBean->withdrawableBal ?>">
                    <input type="hidden" id="withPayTypeCode-<?php echo $key; ?>" name = "withPayTypeCode" value="<?php echo $payType->payTypeCode?>">
                    <input type="hidden" id="withSubType-<?php echo $key; ?>" name = "withSubType" value="<?php echo $key?>">
                    <input type="hidden" id="with_payment_gateway-<?php echo $key; ?>" name = "with_payment_gateway" value="<?php echo $subTypeMap?>">
                         </form>
                            </div>
                        </div>
                    </div>
                <?php }  ?>
                </div>
            <?php } } ?>
            </div>
                    <div class="withdrawal_table_transactions" id="cashier-withdrawal-div" style="display: none;">

                        <table id="cashier-withdrawal-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next">
                            <thead>
                            <tr>
                        <th data-toggle="true" class="footable-first-column text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO")?></th>
                        <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT")?></th>
                        <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID")?></th>
                        <th class="text-center" data-hide="phone" style="display: table-cell;"><?php echo JText::_("BETTING_WITHDRAWAL")?></th>
<!--                        <th data-toggle="true"><?php echo JText::_('TRANSECTION_DETAIL_TABLE_TID')?></th>
                        <th><?php echo JText::_('TRANSECTION_DETAIL_TABLE_DT')?></th>
                        <th><?php echo JText::_('TRANSECTION_BONUS_DETAIL_AMT')?></th></th>-->
<!--                        <th class="text-center" style="display: table-cell;" data-hide="phone"><?php echo JText::_('OTP')?></th>-->
                        <th class="text-center" data-hide="phone" style="display: table-cell;"><?php echo JText::_('TRANSECTION_BONUS_DETAIL_STATUS')?>
<!--                        <th><?php echo JText::_('BETTING_CANCEL_REQUEST')?></th>-->
                                <!--                    <th class="text-center" data-hide="phone" class="footable-last-column" style="display: table-cell;">Balance</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="hide-if-no-paging">
                            <tr><td colspan="6"><div id="footer-pagination-div-withdrawal" class="pagination pagination-centered"></div></td></tr>
                            </tfoot>
                        </table>
                    </div>
<!--              </div>
            </div>-->
        </div>
        <div class="walletContent deposit" div_id="deposit">
<!--            <div class="my-wallet-head-info">
                <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,
                    ultrizcies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
            </div>-->


<div class="my-wallet-list-wrap">
    <?php 
   foreach($this->options->payTypeMap as $payType) {  
                            $depositBal[$payType->payTypeCode] = array(
                                'min' => $payType->minValue,
                                'max' => $payType->maxValue,
                            );
                            if(count($paymentAccounts) > 0){
                              $display = "style='display:flex;'";
                            }else{
                              $display = "style='display:none;'"; 
                            }
                            $i = 1;
                            foreach($payType->subTypeMap as $key => $subTypeMap){
                             if($subTypeMap == 'Skrill Digital Wallet')
                              $imgSrc = 'skrill.png';
                             else
                              $imgSrc = $subTypeMap. '.png';
                              ?>
    <div class="my-wallet-list">
        <div class="my-wallet-list-item fig-wrap">
            <div class="fig-blk"><img src="/images/payment-icons/<?php echo $imgSrc?>" alt=""></div>
        </div>
        <div class="my-wallet-list-item cost-wrap">
            <div class="cost-blk">
                <div class="blk-label">Cost</div>
                <div class="blk-value">
                    <div class="currency-wrap">
                        <div class="cu-value">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-wallet-list-item min-wrap">
            <div class="min-blk">
                <div class="blk-label">Min</div>
                <div class="blk-value">
                    <div class="currency-wrap">
                      <div class="cu-value"><?php echo Utilities::formatCurrency(number_format($payType->minValue,2),$currency_code)?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-wallet-list-item max-wrap">
            <div class="max-blk">
                <div class="blk-label">Max</div>
                <div class="blk-value">
                    <div class="currency-wrap">
                        <div class="cu-value"><?php echo Utilities::formatCurrency(number_format($payType->maxValue,2),$currency_code)?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($payType->payTypeCode == Constants::CASH_PAYMENT_DEPOSIT) { ?>
        <div class="my-wallet-list-item action-wrap">
            <div class="action-blk">
                <div class="txt">Please visit the nearest store for wallet top-up. Please keep the Username/Registered
                    Mobile Number, handy, to process the deposit.</div>
            </div>
        </div>
        <?php }else {?>
        <div class="my-wallet-list-item action-wrap">
            <div class="action-blk">
<!--                <div class="blk-info-button">
                    <button class="btn btn-info">i</button>
                </div>            -->
                <div class="blk-main-action">
                 <form name="cashier-deposit-form" id="cashier-deposit-form-<?php echo $payType->payTypeId; ?>" action="<?php echo $depositURL?>" method="post" >  
                    <div class="section sec1 select_block" <?php echo $display;?>>
                        <?php foreach($payType->payAccReqMap as $set => $payAccReqMap) {
                        if($payAccReqMap == 'YES'){?>
                        <div class="field-wrap select-parent">
                        <div class="form-group">
                            <div class="label"></div>
                            <select name="paymentAccount" id="paymentAccount-<?php echo $payType->payTypeId; ?>" class="paymentAccount">
<!--                             <option value="">Select Option</option>-->
                            <?php foreach($paymentAccounts as $accounts) {
                             if($key == $accounts->subTypeId) {?>
                                <option <?php echo strpos($accounts->accNum,$playerLoginResponse->mobileNo) != false ? selected : '' ?> value="<?php echo  $accounts->paymentAccId?>"><?php echo $accounts->accNum;?></option>
                             <?php }}?>   
                            </select>
                            <div id="error_paymentAccount-<?php echo $payType->payTypeId; ?>" class="manual_tooltip_error error_tooltip"></div>
                        </div>
                        </div>
        <?php } }?>
                        <?php if(count($currencyList) == 1) {?>
                         <?php foreach($payType->currencyMap as $val){
                         $currency = $val;
                          } ?>
                        <div class="field-wrap input-parent">
<!--                        <div class="form-group"> 
                            <div class="label">AMOUNT (in <?php echo $currency?>)</div>
                            <input type="text" name="deposit" id="deposit-<?php echo $payType->payTypeId; ?>" value="">
                             <div id="error_deposit-<?php echo $payType->payTypeId; ?>" class="manual_tooltip_error error_tooltip"></div>
                        </div>-->
                        <div class="input-group">
                                               <div class="label">AMOUNT in <?php echo $currency?></div>
                                               <div class="input-group-prepend input-amountdrop">
                                                   <select name="select_deposit" id="select_deposit-<?php echo $payType->payTypeId; ?>">
                                                    <option value=60>60</option>
                                                    <option value=90>90</option>
                                                    <option value=120>120</option>
                                                    <option value=150 selected>150</option>
                                                    <option value=other>other</option>
                                                   </select>
                                               </div>
                                               <input type="text" name="deposit_value" id="deposit_value-<?php echo $payType->payTypeId; ?>" value="" style="display: none;">
                                                <div id="error_deposit_value-<?php echo $payType->payTypeId; ?>" class="manual_tooltip_error error_tooltip"></div>
                                           </div>
                        </div>
                       
                        <?php  echo '<input type="hidden" name="currency" id="currency-'.$payType->payTypeId.'" value="'. $currency. '">';
?>
                        <?php  }else { ?>
                        <div class="field-wrap input-parent">
                            <div class="label">AMOUNT</div>
                            <div class="input-group">
                                <div class="input-group-prepend open input-currency">
<!--                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >CFA</button>-->
<!--                                    <div class="dropdown-menu" >-->
                                       <select name="currency" id="currency-<?php echo $payType->payTypeId?>" onchange="changeCurrency(this)">
                                    <?php foreach($currencyList as $Currencykey => $currency){?>
                                           <option value="<?php echo $currency?>"><?php echo $currency?></option>
<!--                                      <a class="dropdown-item" href="#"><?php echo $currency?></a>-->
<!--                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" href="#"></a>-->
                                    <?php } ?>
                                        </select>
<!--                                    </div>-->
                                </div>
                            <!--      <input type="text" name="deposit" id="deposit-<?php echo $payType->payTypeId; ?>" value=""> -->
                           <div class="input-group-prepend input-amountdrop">
                                                <select name="multi-currency-deposit-select" id="multi-currency-deposit-select-<?php echo $payType->payTypeId?>" aria-invalid="false">
                                                    <option value=60>60</option>
                                                    <option value=90>90</option>
                                                    <option value=120>120</option>
                                                    <option value=150 selected>150</option>
                                                    <option value=other>other</option>
                                                </select>
<!--                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">2000</button>-->
<!--                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">50</a>
                                                        <a class="dropdown-item" href="#">100</a>
                                                        <a class="dropdown-item" href="#">200</a><a class="dropdown-item" href="#">1000</a>
                                                        <a class="dropdown-item" href="#">other</a>
                                                    </div>-->
                                                </div>
                                                 <input type="text" name="multi-currency-deposit-value" id="multi-currency-deposit-value-<?php echo $payType->payTypeId; ?>" value="" style="display: none;">
                                                  <div id="error_multi-currency-deposit-value-<?php echo $payType->payTypeId; ?>" class="manual_tooltip_error error_tooltip"></div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <div class="section sec2">
                        <div class="buttonWrap">
                            <button type="submit" class="btn btnStyle1 btn-deposit" id="check_deposit-<?php echo $payType->payTypeId; ?>" paytype-id="<?php echo $payType->payTypeId?>" paymentype-code="<?php echo $payType->payTypeCode?>" subtype-id="<?php echo $key?>" <?php echo $display?>>Deposit</button>
                             <?php foreach($payType->payAccReqMap as $value => $payAccReq) {
                            if($payAccReq == 'YES') {?>
                            <button type="button" class="btn btnOutline btn-addaccount deposit_add_new_account" paytype-id="<?php echo $payType->payTypeId?>" curr= "<?php echo $currency?>" paymentype-code="<?php echo $payType->payTypeCode?>" subtype-id="<?php echo $key?>"  data-target="#cashier_deposit_popup">Add New Account</button>
                             <?php } }?>
                        </div>
                    </div>
                    <input type="hidden" id="paytypeId-<?php echo $payType->payTypeId; ?>" name = "paytypeId" value="<?php echo $payType->payTypeId; ?>">
                    <input type="hidden" id="payTypeCode-<?php echo $payType->payTypeId; ?>" name = "payTypeCode" value="<?php echo $payType->payTypeCode?>">
                    <input type="hidden" id="subType-<?php echo $payType->payTypeId; ?>" name = "subType" value="<?php echo $key?>">
                <input type="hidden" id="payment_gateway-<?php echo $payType->payTypeId; ?>" name = "payment_gateway" value="<?php echo $subTypeMap?>">
                    </form>
                </div>
<!--                <form name="cashier-deposit-form_one" id="cashier-deposit-form_one" action="<?php echo $depositURL; ?>" method="post" >
                    <input type ="hidden" name="account" id="account" >
                    <input type ="hidden" name="cashier_amount" id="cashier_amount" >
                   
                </form>-->
            </div>
        </div>
        <?php }?>
    </div>
   <?php $i++;} } ?>
</div>
    <div class="deposit_table_transactions" id="deposit-div" style="display: none;">
          <h4 style="text-align: left; margin-top: 50px; color: #0448a8;"><?php echo JText::_("PENDING_DEPOSIT_TRANSACTIONS")?></h4>
              <table id="deposit-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next">
                  <thead>
                  <tr>
                      <th data-toggle="true" class="footable-first-column text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO")?></th>
                      <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT")?></th>
                      <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID")?></th>
                      <th class="text-center" data-hide="phone" style="display: table-cell;"><?php echo JText::_("WITH_DEPOSIT")?></th>
                      <!--                    <th class="text-center" data-hide="phone" class="footable-last-column" style="display: table-cell;">Balance</th>-->
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot class="hide-if-no-paging">
                  <tr><td colspan="6"><div id="footer-pagination-div" class="pagination pagination-centered"></div></td></tr>
                  </tfoot>
              </table>
      </div>
</div>
 <form id="deposit-request-form" name="deposit-request-form" style="display: none;" method="POST" action="<?php echo $depositURL; ?>"></form>
 <form id="withdrawal-request-form" name="deposit-request-form" style="display: none;" action="#" method="post" submit-type="ajax" validation-style="left" home-forgot-modal="true" tooltip-mode="manual" novalidate="novalidate"></form>
</div>

<!--        </div>
    </div>
</div>-->
<?php
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/bootstrap-datepicker.min.js");
Html::addCss(JUri::base() . "/templates/shaper_helix3/css/bootstrap-datepicker.min.css");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/placeholder.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.scrollbar.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/isotope.pkgd.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/custome.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.tools.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/jquery.themepunch.revolution.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/revolution.extension.slideanims.min.js");
Html::addJs(JUri::base() . "/templates/shaper_helix3/js/revolution.extension.navigation.min.js");

?>
<script>
function changeCurrency(select){
    let currencySelected = select.value;
      $(select).parents('.my-wallet-list').find('.cu-value').each(function (index) {
       if(index > 0){
       var selectedText = $(this).text().split(' ');
    selectedText[0] = currencySelected;
    $(this).html(selectedText.join(' '))
}
});
}

function changeWithCurrency(select){
    let currencySelected = select.value;
    $(select).parents('.my-wallet-list').find('.cu-value').each(function (index) {
    if(index > 0){
    var selectedText = $(this).text().split(' ');
    selectedText[0] = currencySelected;
    $(this).val(selectedText.join())
    }
    });
    }
    var mobileNo = '<?php echo $playerLoginResponse->mobileNo; ?>';
    var fromDate = '<?php echo Constants::WITHDRAWAL_START_DATE; ?>';
    var toDate = '<?php echo date("Y-m-d"); ?>';
    var withdrawalBal = '<?php echo json_encode($withdrawalBal); ?>';
    var depositBal = '<?php echo json_encode($depositBal); ?>';
    withdrawalBal = $.parseJSON(withdrawalBal);
    depositBal = $.parseJSON(depositBal);
    console.log(depositBal);
    var offset = 1;
    var limit = 100;
    var currencyList = '<?php echo json_encode($currencyList)?>';
    currencyList = $.parseJSON(currencyList);
    var withCurrencyList = '<?php echo json_encode($withCurrencyList)?>';
     withCurrencyList = $.parseJSON(withCurrencyList);
    var cancelWithdrawalURL = '<?php echo $cancelWithdrawalURL; ?>';
    var decSymbol = '<?php echo $currency_code; ?>';
    var withdrawabalBalance = parseFloat('<?php echo $playerLoginResponse->walletBean->withdrawableBal; ?>');
    var cashBalance = parseFloat('<?php echo $cashBalance ?>');    
</script>
<?php Html::addJs(JUri::base() . "templates/shaper_helix3/js/custom/wallet.js?v=".Constants::JS_VER);?>
