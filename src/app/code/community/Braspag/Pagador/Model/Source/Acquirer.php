<?php
class Braspag_Pagador_Model_Source_Acquirer
{
	public function toOptionArray()
	{
		$options = array();
		foreach(Mage::getSingleton('braspag_pagador/config')->getAcquirers() AS $key => $value){
			$options[] = array(
			   'value' => $key,
			   'label' => $value,
			);
		}
		
		return $options;
	}
}