<?php
abstract class Webjump_BraspagAntifraud_Model_System_Config_Source_Abstract
{
	const CONFIG_METHOD = null;

    public function toOptionArray()
    {
		$_configMethod = static::CONFIG_METHOD;
		$_hlp = Mage::helper('webjump_braspag_antifraud');

    	if (!$_configMethod) {
    		return false;
    	}
    	
		$options = Mage::getSingleton('webjump_braspag_antifraud/config')->$_configMethod();
		if (!is_array($options)) {
			Mage::throwException(Mage::helper('webjump_braspag_antifraud')->__('Unexpected return in method %s of model webjump_braspag_pagador/config', $_configMethod));
		}

		$return = array();
		foreach($options AS $key => $value){
			$return[] = array(
			   'value' => $key,
			   'label' => $_hlp->__($value),
			);
		}
		
		return $return;

    }
}