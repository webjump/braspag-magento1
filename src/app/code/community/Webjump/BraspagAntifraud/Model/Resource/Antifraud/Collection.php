<?php

class Webjump_BraspagAntifraud_Model_Resource_Antifraud_Collection extends Mage_Core_Model_Resource_Db_Collection_ABstract
{
	protected $_orderField = 'order_id';

	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/antifraud');
	}

	public function setOrderFilter($order)
    {
        if ($order instanceof Mage_Sales_Model_Order) {
            $orderId = $order->getId();
            if ($orderId) {
                $this->addFieldToFilter($this->_orderField, $orderId);
            } else {
                $this->_totalRecords = 0;
                $this->_setIsLoaded(true);
            }
        } else {
            $this->addFieldToFilter($this->_orderField, $order);
        }
        return $this;
    }
}