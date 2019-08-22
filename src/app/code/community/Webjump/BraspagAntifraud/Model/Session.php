<?php

class Webjump_BraspagAntifraud_Model_Session extends Mage_Core_Model_Session
{
    public function __construct($data = array())
    {
        return parent::__construct(array('name' => 'braspag_antifraud'));
    }
    
    public function addPurchaseAttempt()
    {
    	$purchaseAttempts = (int)$this->getPurchaseAttempts() + 1;
    	$this->setPurchaseAttempts($purchaseAttempts);
    	return $this;
    }
}