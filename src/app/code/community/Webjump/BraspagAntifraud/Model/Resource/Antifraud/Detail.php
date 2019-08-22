<?php
class Webjump_BraspagAntifraud_Model_Resource_Antifraud_Detail extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_serializableFields   = array(
        'additional_information' => array(null, array())
    );

	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/antifraud_detail', 'antifraud_detail_id');
	}
}