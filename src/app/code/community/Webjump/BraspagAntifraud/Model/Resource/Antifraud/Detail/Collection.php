<?php

class Webjump_BraspagAntifraud_Model_Resource_Antifraud_Detail_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_antifraudField = 'antifraud_id';

    public function _construct()
    {
        $this->_init('webjump_braspag_antifraud/antifraud_detail');
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

    /**
     * Save all the entities in the collection
     *
     * @todo make batch save directly from collection
     */
    public function save()
    {
        foreach ($this->getItems() as $item) {
            $item->save();
        }

        return $this;
    }

    public function setAntifraudFilter($antifraud)
    {
        if ($antifraud instanceof Webjump_BraspagAntifraud_Model_Antifraud) {
            $antifraudId = $antifraud->getId();
            if ($antifraudId) {
                $this->addFieldToFilter($this->_antifraudField, $antifraudId);
            } else {
                $this->_totalRecords = 0;
                $this->_setIsLoaded(true);
            }
        } else {
            $this->addFieldToFilter($this->_antifraudField, $antifraud);
        }

        return $this;
    }

    public function addOrderIdFilter($orderId)
    {
        $this->addFieldToFilter('order_id', array('eq' => $orderId));
    }
}
