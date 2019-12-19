<?php
class Webjump_BraspagPagador_Block_Form_Debitcard extends Webjump_BraspagPagador_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/debitcard.phtml');
    }

	public function getDebitCardAvailableTypes()
	{
        if ($method = $this->getMethod()) {
	        $types = $method->getDebitCardAvailableTypes();
        }
        else{
			$types = array();
        }
        return $types;
	}	
}
