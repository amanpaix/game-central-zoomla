<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class BettingViewCashier extends JViewLegacy
{
	function display($tpl = null) 
	{
	if(isset($this->select_amount)){
            $model = $this->getModel();
              $this->message = $model->getBettingMessage(1);
        }

		parent::display($tpl);
                JText::script('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER');
		JText::script('PLEASE_ENTER_AMOUNT_TO_DEPOSIT');
		JText::script('PLEASE_SELECT_ACCOUNT_NUMBER');
		JText::script('BETTING_PLAESE_ENTER_OTP');
		JText::script('WITH_SELECT_AMT');
		JText::script('PENDING_DEPOSIT_TRANSACTIONS');
		JText::script('PLEASE_ENTER_VALID_ACCOUNT_NUMBER');
		JText::script('PLEASE_ENTER_VALID_OTP');
		JText::script('PLEASE_ENTER_AMOUNT_TO_WITHDRAW');
		JText::script('ADD_DEPOSIT_ACCOUNT');
		JText::script('ADD_WITHDRAWAL_ACCOUNT');
		JText::script('ADD_REDEEM_ACCOUNT');
		JText::script('NO_CODE_RECEIVED');
                JText::script('PLEASE_ENTER_YOUR_NAME');
		JText::script('REQUEST_AGAIN');
		JText::script('RESEND_OTP');
		JText::script('OTP_CODE_HAS_BEEN_SENT');
		JText::script('VERIFY_ACCOUNT');
		JText::script('PLAYER_NOT_LOGGED_IN');
		JText::script('REDEEM_ACCOUNT_IS_INACTIVE_FOT_THIS_PLAYER');
		JText::script('ACCOUNT_HAS_ALREADY_BEEN_CREATED_MSG');
		JText::script('WRONG_VERIFICATION_CODE_PROVIDED');
		JText::script('PLAYER_MUST_BE_LOG_OUT_MSG');
		JText::script('PLAYER_WITHDRAWAL_AMOUNT_LIMIT_EXCEEDS');
		JText::script('NO_PAYMENT_OPTIONS_AVAILABLE');
		JText::script('PLAYER_TYPE_DOES_NOT_EXIST');
		JText::script('BETTING_DEVICE_TYPE_NOT_SUPPLIED');
		JText::script('BETTING_USER_AGENT_TYPE_NOT_SUPPLIED');
		JText::script('REDEEM_ACCOUNT_NOT_EXIST');
		JText::script('REDEEM_ACCOUNT_DOES_NOT_EXIST_FOR_THIS_PLAYER');
		JText::script('MOMO_MTN_IS_INACTIVE');
		JText::script('INVALID_CURRENCY');
                JText::script('BETTING_PLEASE_SELECT_BETWEEN');
		JText::script('BETTING_TO');
		JText::script('YOUR_PAYMENT_HAS_BEEN_FAILED');
		JText::script('ACCOUNT_ADDED_SUCCESSFULLY');
		JText::script('BETTING_DEPOSIT_AMOUNT');
                JText::script('BETTING_PLEASE_ENTER_AMOUNT_TO_BE_WITHDRAWN');
                JText::script('BETTING_WITHDRAWABLE_AMOUNT_SHOULD_BE_NUMERIC');
                JText::script('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED');
                JText::script('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE');
                JText::script('BETTING_PLAYER_AMOUNT_LIMIT_EXCEEDS');
	}
}
