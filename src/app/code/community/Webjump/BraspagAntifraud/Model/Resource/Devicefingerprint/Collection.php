<?php

class Webjump_BraspagAntifraud_Model_Resource_Devicefingerprint_Collection extends Mage_Core_Model_Resource_Db_Collection_ABstract
{
	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/devicefingerprint');
	}
}