<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

if(isset($this->withdrawal))
    echo $this->loadTemplate('withdrawal');
elseif(isset($this->withdrawal_success))
    echo $this->loadTemplate('withdrawal_success');
elseif(isset($this->uploaddoc))
	echo $this->loadTemplate('uploaddoc');
elseif(isset($this->incompleteProfile))
    echo $this->loadTemplate('incomplete_profile');
elseif(isset($this->insufficientCash))
    echo $this->loadTemplate('insufficient_cash');
elseif(isset($this->verificationpending))
    echo $this->loadTemplate('verificationpending');
elseif(isset($this->withdrawalRequest))
    echo $this->loadTemplate('withdrawal_request');
elseif(isset($this->cashierHelp))
    echo $this->loadTemplate('cashier_help');
