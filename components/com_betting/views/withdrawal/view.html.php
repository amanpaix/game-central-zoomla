<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

class BettingViewWithdrawal extends JViewLegacy
{

	function display($tpl = null) 
	{

	 JText::script('WITHDRAWL_JS_NOTAPP_MSG');
	 JText::script('WITH_JS_AMOUNT_SEELCT');
	 JText::script('WITH_JS_GREATER_AMOUNT');
	 JText::script('WITH_JS_LIMIT_MSG');
	 JText::script('WITH_JS_ACCNO_REQ');
	 JText::script('WITH_ACCOUNT_FORMAT_ERROR');
	 JText::script('WITH_JS_ACCNO');
	 JText::script('WITH_JS_ACCNO_AC');
	 JText::script('WITH_JS_BANK_SELECT');
	 JText::script('WITH_JS_CITY_REQ');
	 JText::script('WITH_JS_PATTERN');
	 JText::script('WITH_JS_IFSC_REQ');
	 JText::script('WITH_SELECT_AMT');


	if(isset($this->withdrawal)){
            $model = $this->getModel();
              $this->message = $model->getBettingMessage(2);
        }

		parent::display($tpl);
	}
}
