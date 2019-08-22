<?php

class Webjump_BraspagAntifraud_Model_Resource_Devicefingerprint extends Mage_Core_Model_Resource_Db_Abstract
{
	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/devicefingerprint', 'devicefingerprint_id');
	}
}