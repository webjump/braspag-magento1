<?php
class Webjump_BraspagPagador_Block_Form_Dc extends Webjump_BraspagPagador_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/dc.phtml');
    }

	public function getDcAvailableTypes()
	{
        if ($method = $this->getMethod()) {
	        $types = $method->getDcAvailableTypes();
        }
        else{
			$types = array();
        }
        return $types;
	}	
}
