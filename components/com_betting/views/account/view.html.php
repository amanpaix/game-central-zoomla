<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class BettingViewAccount extends JViewLegacy
{
	function display($tpl = null) 
	{

		JText::script('REFER_NOW');
		JText::script('LOGIN_ERROR');
		JText::script('BETTING_JS_CHANGE_PASSWORD_REQUIRED');
		JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_REQUIRED');
		JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_MINLENGTH');
		JText::script('BETTING_JS_CHANGE_PASSWORD_NEW_MAXLENGTH');
		JText::script('BETTING_JS_CHANGE_PASSWORD_RETYPE_REQUIRED');
		JText::script('BETTING_JS_CHANGE_PASSWORD_RETYPE_EQUAL');
		JText::script('BETTING_PASSWORD_SUCEESSFULLY_CHANGED');

		//uploadAvatar.js
		JText::script('BETTING_JS_CHANGE_AVATAR_FORMAT_ERROR');
		JText::script('BETTING_JS_CHANGE_AVATAR_SIZE_ERROR');
		JText::script('BETTING_JS_CHANGE_PASSWORD_FORMAT_ERROR');

		//inbox.js
		JText::script('NO_MESSAGE_FOUND');
		JText::script('BETTING_JS_CHECK_MSG_ERROR');

		//cashier details,js
		JText::script('FORM_JS_CHECK_DATE_OF_BIRTH');
		JText::script('FORM_JS_PLEASE_ENTER');
		JText::script('BETTING_FORM_FIRST_NAME');
		JText::script('BETTING_FORM_LAST_NAME');
		JText::script('BETTING_FORM_EMAIL_ADDR');
		JText::script('BETTING_FORM_MOBILE');
		JText::script('BETTING_FORM_DOB');
		JText::script('BETTING_FORM_ADDRESS');
		JText::script('BETTING_FORM_CITY');
		JText::script('BETTING_FORM_PINCODE');
		JText::script('FORM_JS_PLEASE_SELECT_GENDER');
		JText::script('FORM_JS_EMAIL_ADDRESS_IS_INVALID');
		JText::script('BETTING_ADDRESS_CAN_CONTAIN_CHARACTERS');
		JText::script('BETTING_PLASE_ENTER_ATLEAST_TWO_CHARACTERS');
		JText::script('EMAIL_ID_ALREAD_EXIST');
		
		//email_mobile_verify
		JText::script('FORM_JS_ENTER_CODE_MSG');
		JText::script('BETTING_PLEASE_ENTER_TEN_DIGIT_NUMBER');
		JText::script('FORM_JS_ENTER_CODE_SIZE_ERROR');
		JText::script('FORM_JS_ENTER_CODE_TYPE_ERROR');
		JText::script('FORM_MOBILE_ERROR_LENGTH');
		JText::script('FORM_MOBILE_TYPE_ERROR');
		JText::script('FORM_MOBILE_INVALID_ERROR');
		JText::script('BETTING_CANCEL_REQUEST');
		JText::script('BETTING_INVALID_ALIAS_NAME');
		JText::script('BETTING_HIBERNATE_EXCEPTION');
		JText::script('BETTING_PLEASE_ENTER_VALID_CODE_EMAIL');
		JText::script('BETTING_PLEASE_ENTER_VALID_CODE_MOBILE');
		JText::script('BETTING_HIBERNATE_EXCEPTION_MSG');
		JText::script('BETTING_ERROR_IN_COMMUNICATION_MODULE');
		JText::script('BETTING_OTP_CODE_IS_NOT_VALID');
		JText::script('BETTING_VERIFICATION_CODE_EXPIRED');
		JText::script('BETTING_VERIFICAION_ERROR');
		JText::script('BETTING_VERIFICATION_CODE_IS_ALREADY_USED_TO_VERIFY');
		JText::script('BETTING_INVALID_VERIFICATION_CODE');
		JText::script('BETTING_PLAYER_ALREADY_EXIST');

		//Rerer a Friend 
		JText::script('INVITE_JS_FRIENDS_NAME');
		JText::script('INVITE_JS_FRIEND_INVALID_NAME');
		JText::script('INVITE_JS_FRIEND_EMAIL_MSG');
		JText::script('INVITE_JS_INVALID_EMAIL_MSG');
		JText::script('INVITE_JS_SELECT_ATLEST_ONE_CHECK_ERROR');
		JText::script('FRIENDS_NAME_SHOULD_BE_BETWEEN_CHARACTERS');
                JText::script('EMAIL_ID_SHOULD_BE_IN_RANGE');
                JText::script('INVALID_FRIENDS_EMAIL');
		
		
		JText::script('TRANSECTION_JS_NO_TICKET');
		JText::script('TRANSECTION_TICKET_DETAIL_FEOM_BLANK_ERROR');
		JText::script('TRANSECTION_TICKET_DETAIL_TO_BLANK_ERROR');
                JText::script('TRANSECTION_TICKET_DETAIL_TRAN_TYPR');
                JText::script('BETTING_CLOSING_BALANCE');
                JText::script('TRANSECTION_BONUS_DETAIL_AMT');

		JText::script('TRANSECTION_JS_TOTAL_DEPOSIT');
		JText::script('TRANSECTION_JS_TOTAL_WT');
		JText::script('TRANSECTION_JS_TOTAL_WAGER');
		JText::script('TRANSECTION_JS_TOTAL_PERIOD');
		JText::script('TRANSECTION_JS_TOTAL_WINNING_P');
		JText::script('TRANSECTION_JS_TOTAL_WITHDRAWL_C');

		JText::script('TRANSECTION_JS_TOTAL_BONUS_TRANSFER');
		JText::script('TRANSECTION_JS_TOTAL_PAYMENT_CORRECTION');
		JText::script('PLEASE_ENTER_VERIFICATION_CODE');

			
		JText::script('BETTING_CHANGE_PASSWORD_JS_REQUIRED_ERROR');
		JText::script('BETTING_CHANGE_PASSWORD_JS_FORMAT');
		JText::script('WITHDRAWL_NO_DETAIL');
		JText::script('SOMTHING_WRONG_MSG');
		JText::script('BETTING_ERROR');
		JText::script('FORM_JS_FIRST_NAME');
		JText::script('FORM_JS_MAXIMUM_CHARACTERS_ALLOWED');
		JText::script('FORM_JS_EMAIL_ID_CAN_CONTAIN');
		JText::script('BETTING_ONLY_NUMBERS_ARE_ALLOWED');
		JText::script('BETTING_MOBILE_NO_SHOULD_BE_OF_TEN_DIGITS');
		JText::script('BETTING_PLEASE_SELECT_BETWEEN');
		JText::script('BETTING_TO');
		JText::script('BETTING_NO_TRANSACTION_DETAILS_MSG');
		JText::script('PLEASE_ENTER_A_VALID_MOBILE_NO');
		JText::script('BETTING_EMAIL_UPDATED_SUCCESSFULLY');
		
		//Wallet Details
                JText::script('BETTING_PLEASE_ENTER_AMOUNT_TO_BE_WITHDRAWN');
                JText::script('BETTING_WITHDRAWABLE_AMOUNT_SHOULD_BE_NUMERIC');
                JText::script('BETTING_ENTERED_AMOUNT_SHOULD_BE_MORE_THAN_REQIRED');
                JText::script('BETTING_AMOUNT_SHOULD_BE_UPTO_TWO_DECIMAL_PLACE');
                JText::script('BETTING_PLAYER_AMOUNT_LIMIT_EXCEEDS');
                JText::script('BETTING_INVALID_REQUEST');
                JText::script('BETTING_INVALID_AMOUNT');
                JText::script('BETTING_INSUFFICIENT_PLAYER_BALANCE');
                JText::script('BETTING_SOME_INTERNAL_ERROR');
                JText::script('BETTING_REDEEM_AMOUNT');
                JText::script('BETTING_OLD_INCORRECT_PASSWORD');
                JText::script('BETTING_NEW_PASSWORD_CANT_BE_FROM_LAST');
                JText::script('BETTING_CURRENT_AND_NEW_PASSWORD_CANT_BE_SAME');
                JText::script('WAVER_PLEASE_PROVIDE_VALID');
                JText::script('BETTING_SELECT_THE_GENDER');
                JText::script('FORM_JS_LAST_NAME');
                JText::script('BETTING_WITHDRAWAL_INITIATE_MSG');

                JText::script('LEDGER_PLR_WAGER');
                JText::script('LEDGER_PLR_WAGER_REFUND');
                JText::script('LEDGER_PLR_CREDIT_CUSTOMER');
                JText::script('LEDGER_PLR_DEBITED_CUSTOMER');
                JText::script('FRIENDS_EMAIL_ADDRESS_SHOULD_BE_IN_RANGE');
                JText::script('PLEASE_ENTER_YOUR_NAME');
		JText::script('PLEASE_ENTER_YOUR_ACCOUNT_NUMBER');
		JText::script('PLEASE_ENTER_AMOUNT_TO_DEPOSIT');
		JText::script('PLEASE_SELECT_ACCOUNT_NUMBER');
		JText::script('BETTING_PLAESE_ENTER_OTP');
		JText::script('PLEASE_ENTER_VALID_ACCOUNT_NUMBER');
		JText::script('PLEASE_ENTER_VALID_OTP');
		JText::script('PLEASE_ENTER_AMOUNT_TO_WITHDRAW');
		JText::script('ADD_DEPOSIT_ACCOUNT');
		JText::script('ADD_WITHDRAWAL_ACCOUNT');
		JText::script('ADD_REDEEM_ACCOUNT');
		JText::script('NO_CODE_RECEIVED');
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
		JText::script('YOUR_PAYMENT_HAS_BEEN_FAILED');
		JText::script('ACCOUNT_ADDED_SUCCESSFULLY');
		JText::script('BETTING_DEPOSIT_AMOUNT');
                //Contact Us
                JText::script('PLEASE_ENTER_YOUR_FIRST_NAME');
                JText::script('BETTING_PLEASE_VERIFY_YOU_ARE_HUMAN');
                JText::script('FIRST_NAME_SHOULD_BE_BETWEEN_CHARACTERS');
                JText::script('FIRST_NAME_CAN_ONLY_CONTAIN_ALPHABETS');
                JText::script('PLEASE_ENTER_YOUR_LAST_NAME');
                JText::script('LAST_NAME_SHOULD_BE_BETWEEN_CHARACTERS');
                JText::script('LAST_NAME_CAN_ONLY_CONTAIN_ALPHABETS');
                JText::script('PLEASE_ENTER_EMAIL');
                JText::script('EMAIL_LENGTH_SHOULD_BE_BETWEEN_CHARACTERS');
                JText::script('FORM_JS_EMAIL_ADDRESS_IS_INVALID');
                JText::script('PLEASE_ENTER_YOUR_MESSAGE');
                JText::script('MESSAGE_LENGTH_SHOULD_BE_BETWEEN_FIVE_TO_THREE_HUNDRED');
                JText::script('PLEASE_ENTER_YOUR_SUBJECT');
				JText::script('SUBJECT_LENGTH_SHOULD_BE_BETWEEN_FIVE_TO_THREE_HUNDRED');
				JText::script('TRANSECTION_DETAIL_TABLE_TID');
				JText::script('TRANSECTION_DETAIL_TABLE_TTIME');

		parent::display($tpl);
	}
}
