<?php
class Webjump_BraspagPagador_Model_Source_Acquirer
{
	public function toOptionArray()
	{
		$options = array();
		foreach(Mage::getSingleton('webjump_braspag_pagador/config')->getAcquirers() AS $key => $value){
			$options[] = array(
			   'value' => $key,
			   'label' => $value,
			);
		}
		
		return $options;
	}
}