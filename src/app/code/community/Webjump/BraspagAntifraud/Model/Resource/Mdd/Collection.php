<?php

class Webjump_BraspagAntifraud_Model_Resource_Mdd_Collection extends Mage_Core_Model_Resource_Db_Collection_ABstract
{
	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/mdd');
	}

    /**
     * Unserialize additional_information in each item
     */
    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $this->getResource()->unserializeFields($item);
        }

        return parent::_afterLoad();
    }
}