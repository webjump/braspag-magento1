<?php

class Webjump_BraspagAntifraud_Model_Devicefingerprint extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/devicefingerprint');
	}

    public function getOrderSessionId($order)
    {
		if (is_numeric($order)) {
			$orderId = $order;
		} else if (!$order instanceof Mage_Sales_Model_Order || !$orderId = $order->getId()) {
			throw new Exception ('Device Finger Print fail. Order was not found.');
		}
    	 
        $result = $this->getCollection()
        	->addFieldToSelect('session_id')
            ->addFieldToFilter('order_id', $orderId)
            ->getFirstItem();
        
        return $result->getSessionId();
    }

	public function saveOrderSessionId($order)
	{
		Mage::helper('webjump_braspag_antifraud')->debug('DeviceFingerPrint->saveOrderSessionId - ' . $order->getIncrementId());

		$data  = array(	
			'order_id' 		=> $order->getId(),
			'session_id'	=> Mage::helper('webjump_braspag_antifraud/devicefingerprint')->getSessionId(),
		);

		try {
			$this->setData($data);
			$this->save();
		} catch (Exception $e){
			throw new Exception ($e->getMessage());
		}
	}
}