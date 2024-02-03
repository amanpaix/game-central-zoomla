<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

if(isset($this->account))
	echo $this->loadTemplate('account');

if(isset($this->transaction_details))
	echo $this->loadTemplate('transaction_details');

if(isset($this->transaction_details_new))
    echo $this->loadTemplate('transaction_details_new');

if(isset($this->ticket_details))
    echo $this->loadTemplate('ticket_details');

if(isset($this->wallet_details))
    echo $this->loadTemplate('wallet_details');

if(isset($this->withdrawal_details))
	echo $this->loadTemplate('withdrawal_details');

if(isset($this->bonus_details))
	echo $this->loadTemplate('bonus_details');

if(isset($this->email_popup))
    echo $this->loadTemplate('email_popup');

if(isset($this->mobile_popup))
    echo $this->loadTemplate('mobile_popup');

if(isset($this->inbox))
    echo $this->loadTemplate('inbox');

if(isset($this->refer_a_friend))
    echo $this->loadTemplate('refer_a_friend');

if(isset($this->refer_a_friend_invite_list))
    echo $this->loadTemplate('refer_a_friend_invite_list');

if(isset($this->refer_a_friend_thank_you))
    echo $this->loadTemplate('refer_a_friend_thank_you');

if(isset($this->verification_pending))
    echo $this->loadTemplate('verification_pending');

if(isset($this->account_activated))
    echo $this->loadTemplate('account_activated');


if (isset($this->contactus))
    echo $this->loadTemplate('contactus');


if(isset($this->profile))
    echo $this->loadTemplate('profile');

if(isset($this->edit_avatar))
    echo $this->loadTemplate('edit_avatar');

if(isset($this->balance))
    echo $this->loadTemplate('balance');

if(isset($this->pre_buy))
    echo $this->loadTemplate('pre_buy');
