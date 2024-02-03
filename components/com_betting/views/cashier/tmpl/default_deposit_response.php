
<?php
defined('_JEXEC') or die('Restricted Access');

if(($this->status == 0) && (isset($this->status))) {
     //echo $this->status;die;
    $playerLoginResponse = Utilities::getPlayerLoginResponse();
    $firstname = $playerLoginResponse->firstName;
    $lastname = $playerLoginResponse->lastName;

    $name = $playerLoginResponse->userName;
    $cashBalance = number_format((float)$this->amount,2,'.','');

    ?>

    <div class="page-header">
        <h2><span><?php echo JText::_("CONGRATULATIONS")?></span></h2>
    </div>
    <div class="customMessageBoxWrap">
        <div class="row">
            <div class="col-md-4">
                <img src="/images/transactionSuccess.png" alt="Transaction Successful">
            </div>
            <div class="col-md-8">
<!--                <div class="title"><?php echo JText::_('WALLET_TOPUP_SUCCESSFULLY')?></div>
                <div class="desc"><?php echo JText::_('DEAR')?> <strong><?php echo $name ?></strong>,<br>
                    <?php echo JText::_("THANKS_FOR_TRANSACTING_MSG");?> <?php echo Constants::MYCURRENCYSYMBOL.number_format($this->amount,2); ?>
                    <div class="tranId"> <strong><?php echo JText::_("TRANSACTION_ID")?></strong> <?php echo $this->txnId; ?></div>-->
                   <div class="title"><?php echo $this->message;?></div>
                </div>
            </div>
        </div>
    </div>

    <?php
}
else
{
    ?>

    <div class="page-header">
        <h2><span><?php echo JText::_("OOPS")?></span></h2>
    </div>
    <div class="customMessageBoxWrap">
        <div class="row">
            <div class="col-md-4">
                <img src="/images/transactionFailed.png" alt="Transaction Failed">
            </div>
            <div class="col-md-8">
            <?php if($this->status == 310) {?>
                <div class="title"><?php echo 'Your payment has been failed.Please try again.'?></div>
            <?php }else { ?>
             <div class="title"><?php echo $this->message;?></div>
            <?php } ?>
             <!-- <div class="desc"><?php echo JText::_("UNFORTUNATELY_WE_CANNOT_COMPLETE_MSG")?> -->
<!--                    <div class="tranId"> Transaction Id: #005496523451</div>-->
                </div>
            </div>
        </div>
    </div>

 <?php
 }
?>





