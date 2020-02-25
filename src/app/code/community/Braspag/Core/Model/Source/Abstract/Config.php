<?php
abstract class Braspag_Core_Model_Source_Abstract_Config
{
	const CONFIG_METHOD = null;
	const CONFIG_CLASS = 'braspag_core/config';

    public function toOptionArray()
    {
		$configMethod = static::CONFIG_METHOD;
		$configClass = static::CONFIG_CLASS;
		$_hlp = Mage::helper('braspag_core');

    	if (!$configMethod) {
    		return false;
    	}
    	
		$options = Mage::getSingleton($configClass)->$configMethod();

    	if(!is_array($options)){
			Mage::throwException(Mage::helper('braspag_core')->__('Unexpected return in method %s of model braspag_core/config', $configMethod));
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