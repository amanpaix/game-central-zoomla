<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? Configuration::DEVICE_TABLET : Configuration::DEVICE_MOBILE) : Configuration::DEVICE_PC);




?>
<script>
	<?php if($deviceType == Configuration::DEVICE_PC) {
		?>
		$(document).ready(function () {
//			window.resizeTo($("div.cashier_div").outerWidth(), $("div.cashier_div").outerWidth());
		});
		<?php
	}?>
</script>
<style>
	<?php if($deviceType == Configuration::DEVICE_PC) {
		?>
	/*body {*/
		/*overflow: hidden;*/
	/*}*/
	<?php
}?>
</style>
<div class="cashier_div <?php echo $deviceType; ?>" >
	<?php
	Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate.min.js");
	Html::addJs(JUri::base()."templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js");

	if(isset($this->player_detail)) {
		Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/cashier-player-detail.js");
		Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/email_mobile_verify.js");
		echo $this->loadTemplate('player_detail');
	}

	elseif(isset($this->select_amount)) {
		Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/cashier-select-amount.js");
		echo $this->loadTemplate('select_amount');
		$messageQueue = JFactory::getApplication()->getMessageQueue();
		if(count($messageQueue) > 0) {
			foreach ($messageQueue as $message) {
				if($message['type'] == 'danger') {
					?>
					<script>showErrorStrip("<?php echo $message['message']; ?>");</script>
					<?php
				}
			}
		}
	}

	elseif(isset($this->paymentGatewayRedirect)) {
		echo $this->loadTemplate('paymentgatewayredirect');
	}

	elseif(isset($this->after_payment)) {
		echo $this->loadTemplate('after_payment');
	}
	elseif(isset($this->after_payment_failed)) {
		echo $this->loadTemplate('after_payment_failed');
	}
        if(isset($this->cashier_details))
        echo $this->loadTemplate('cashier_details');
         if(isset($this->depositresponse))
        echo $this->loadTemplate('deposit_response');

	?>
</div>
