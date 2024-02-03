<?php

$playerLoginResponse = Utilities::getPlayerLoginResponse();
if (($playerLoginResponse->walletBean->withdrawableBal % 1) == 0) {
    $cashBalance = $playerLoginResponse->walletBean->cashBalance;
} else {
    $cashBalance = number_format((float) $playerLoginResponse->walletBean->cashBalance, 2);
}
$payTypeMap = json_encode($this->options);
$min_bal = Configuration::MINIMUM_BALANCE;
//echo '<pre>';
//print_r($this->withdrawalOptions->payTypeMap);die;
$bal = number_format((float) $playerLoginResponse->walletBean->withdrawableBal, 2);
$cancelWithdrawalURL = JRoute::_('index.php?task=withdrawal.cancelPendingWithdrawal');
$currency_code =  $playerLoginResponse->walletBean->currency;
$maxValueDeposit;
$minValueDeposit;
 $arrayAmount = array();
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::MY_WALLET_DEPOSIT; ?>']").parent().addClass('active');
</script>

<div class="myaccount_body_section" id="main-div">
    <div class="">
        <div class="entry-header has-post-format">
            <h2 itemprop="name"><?php echo JText::_('BETTING_MY_WALLET')?></h2>
        </div>
        <div class="heading">
            <ul id="url-tabs" class="tabNav">
                <li class="active" ><a href="#deposit"><?php echo JText::_('WITH_DEPOSIT')?></a></li>
                <li class="" ><a href="#withdrawal"><?php echo JText::_('BETTING_WITHDRAWAL')?></a></li>
            </ul>
        </div>

        <div class="walletContent withdrawal" div_id="withdrawal" style="display:none;">
            <div class="bs-container">
                <div class="withdrawal_table_data">

                    <table id="withdrawal-options" class="table" style="text-align:center;display: none">
                        <thead>
                        <tr>
                            <th style="display: table-cell; text-align:center;"><?php echo JText::_("COST")?></th>
                            <th style="display: table-cell; text-align:center;"><?php echo JText::_("MIN")?></th>
                            <th style="display: table-cell; text-align:center;"><?php echo JText::_("MAX")?></th>
                            <th data-hide="phone" style="display: table-cell; text-align:center;"><?php echo JText::_("ACTION")?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($this->withdrawalOptions->payTypeMap as $payType) {  
                            $arrayAmount[$payType->payTypeCode] = array(
                                'min' => $payType->minValue,
                                'max' => $payType->maxValue,
                            );
                            if ($payType->payTypeCode == Constants::CASH_PAYMENT_DEPOSIT)
                                $dataTarget = '#initiate_withdrawal';
                            else
                                $dataTarget = '#withdrawal_amount_popup';
                            
                            foreach($payType->subTypeMap as $key => $subTypeMap){
                            ?>
                            <tr style="display: table-row;">
                                <td style="display: table-cell;">0</td>
                                <td style="display: table-cell;"><?php echo Utilities::formatCurrency(number_format($payType->minValue,2),$currency_code)?></td>
                                <td style="display: table-cell;"><?php echo Utilities::formatCurrency(number_format($payType->maxValue,2),$currency_code)?></td>
                                <td style="display: table-cell;" >
                                 <a data-dismiss="modal" data-target="<?php echo $dataTarget;?>"  data-toggle="modal" paytype-id='<?php echo $payType->payTypeId;?>' paytype-code='<?php echo $payType->payTypeCode;?>'subtype-id="<?php echo $key?>" class="green_bg loginBtn btn  btnStyle1 btn-<?php echo $payType->payTypeCode; ?>"><?php echo JText::_("WITHDRAWAL")?></a>
                                  <?php //if($payType->payTypeCode == Constants::CASH_PAYMENT_DEPOSIT){ ?>
<!--                                    <input type ="hidden" id="OlapayTypeCodeNo" value="<?php echo $payType->payTypeCode?>">
                                    <input type ="hidden" id="OlapayTypeId" value="<?php echo $payType->payTypeId?>">
                                  <?php //}else {?>
                                  <input type ="hidden" id="withpayTypeCodeNo" value="<?php echo $payType->payTypeCode?>">
                                  <input type ="hidden" id="withpayTypeId" value="<?php echo $payType->payTypeId?>"> -->
                                  <?php //}?>
                                </td>
                            </tr>
                            <?php }}?>
                        </tbody>
                    </table>
                    <div class="withdrawal_table_transactions" id="withdrawal-div" style="display: none;">

                        <table id="withdrawal-table" class="table" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-page-navigation=".pagination" data-page-previous-text="prev" data-page-next-text="next">
                            <thead>
                            <tr>
                        <th data-toggle="true" class="footable-first-column text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_SNO")?></th>
                        <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_DT")?></th>
                        <th class="text-center" style="display: table-cell;"><?php echo JText::_("TRANSECTION_DETAIL_TABLE_TID")?></th>
                        <th class="text-center" data-hide="phone" style="display: table-cell;"><?php echo JText::_("BETTING_WITHDRAWAL")?></th>
<!--                        <th data-toggle="true"><?php echo JText::_('TRANSECTION_DETAIL_TABLE_TID')?></th>
                        <th><?php echo JText::_('TRANSECTION_DETAIL_TABLE_DT')?></th>
                        <th><?php echo JText::_('TRANSECTION_BONUS_DETAIL_AMT')?></th></th>-->
                        <th class="text-center" style="display: table-cell;" data-hide="phone"><?php echo JText::_('OTP')?></th>
                        <!--<th data-hide="phone"><?php echo JText::_('TRANSECTION_BONUS_DETAIL_STATUS')?>-->
                        <th><?php echo JText::_('BETTING_CANCEL_REQUEST')?></th>
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
                </div>
            </div>
        </div>
        <div class="walletContent deposit" div_id="deposit">
   <div class="bs-container">
   <div class="deposit_table_data">
      <table id="deposit-options" class="table" style="text-align:center; display: none">
         <thead>
            <tr>
               <th style="display: table-cell; text-align:center;"><?php echo JText::_("COST")?></th>
                <th style="display: table-cell; text-align:center;"><?php echo JText::_("MIN")?></th>
                <th style="display: table-cell; text-align:center;"><?php echo JText::_("MAX")?></th>
                <th data-hide="phone" style="display: table-cell; text-align:center;"><?php echo JText::_("ACTION")?></th>
            </tr>
         </thead>
         <tbody>
              <?php $i=0; ?>
              <?php foreach($this->options->payTypeMap as  $payType) {
                  if($payType->payTypeCode == Constants::MOBILE_MONEY){
                     $maxValueDeposit =  $payType->maxValue;
                     $minValueDeposit =  $payType->minValue;
                  }
                 
                  if( $payType->payTypeCode == Constants::CASH_PAYMENT_DEPOSIT )
                      continue;
                   foreach($payType->subTypeMap as $key => $subTypeMap){
                       
                  ?>
            <tr style="display: table-row;">
               <td style="display: table-cell;">0</td>
               <td style="display: table-cell;"><?php echo Utilities::formatCurrency(number_format($payType->minValue,2),$currency_code)?></td>
               <td style="display: table-cell;"><?php echo Utilities::formatCurrency(number_format($payType->maxValue,2),$currency_code)?></td>
               <td style="display: table-cell;" >

                   <a data-dismiss="modal" data-target="#deposit_amount_popup"  data-toggle="modal" paytype-id="<?php echo $payType->payTypeId?>" paytype-code="<?php echo  $payType->payTypeCode?>" subtype-id="<?php echo $key?>" class="green_bg loginBtn btn  btnStyle1 <?php echo $payType->payTypeCode; ?>_depositBtn"><?php echo JText::_("DEPOSIT")?></a>
<!--                  <input type ="hidden" id="payTypeCodeNo" value="<?php echo $payType->payTypeCode?>">
                  <input type ="hidden" id="payTypeId" value="<?php echo $payType->payTypeId?>">-->
               </td>
            </tr>
             <?php
                   }
                  $i++;
                }
             ?>
         </tbody>
      </table>
      <div class="deposit_table_transactions" id="deposit-div" style="display: none;">

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
</div>
        </div>
    </div>
</div>


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
    var fromDate = '<?php echo Constants::WITHDRAWAL_START_DATE; ?>';
    var toDate = '<?php echo date("d/m/Y"); ?>';
    var maxValueDeposit = parseFloat('<?php echo $maxValueDeposit; ?>');
    var minValueDeposit = parseFloat('<?php echo $minValueDeposit; ?>');
    var aarayAmount = '<?php echo json_encode($arrayAmount); ?>';
    aarayAmount = $.parseJSON(aarayAmount);
    var offset = 0;
    var limit = 100;
    var cancelWithdrawalURL = '<?php echo $cancelWithdrawalURL; ?>';
    var decSymbol = '<?php echo $currency_code; ?>';
    var withdrawabalBalance = parseFloat('<?php echo $playerLoginResponse->walletBean->withdrawableBal; ?>');
    var cashBalance = parseFloat('<?php echo $cashBalance ?>');
    var depositOptionsLen = $("#deposit-options tbody tr").length;
    if( depositOptionsLen > 0 ){
        $("#deposit-options").show();
    }

    var withdrawalOptionsLen = $("#withdrawal-options tbody tr").length;
    if( withdrawalOptionsLen > 0 ){
        $("#withdrawal-options").show();
    }

</script>

<?php Html::addJs(JUri::base()."/templates/shaper_helix3/js/custom/withdrawal.js?v=".Constants::JS_VER); ?>
