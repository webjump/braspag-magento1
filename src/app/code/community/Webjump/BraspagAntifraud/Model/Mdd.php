<?php

class Webjump_BraspagAntifraud_Model_Mdd extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('webjump_braspag_antifraud/mdd');
	}

    public function getOrderMddData($order)
    {
		if (is_numeric($order)) {
			$orderId = $order;
		} else if (!$order instanceof Mage_Sales_Model_Order || !$orderId = $order->getId()) {
			throw new Exception ('Mdd fail. Order was not found.');
		}
    	 
        $result = $this->getCollection()
        	->addFieldToSelect('additional_information')
            ->addFieldToFilter('order_id', $orderId)
            ->getFirstItem();
        
        return $result;
    }

    public function setOrder($order = null)
    {
        $hlp = Mage::helper('webjump_braspag_antifraud');

        if (!$order) {
            throw new Exception($hlp->__('Mdd fail. Order was not sent.'));
        }

        if (is_numeric($order)) {
            $order = Mage::getModel('sales/order')->load($order);
        }

        if (!$order instanceof Mage_Sales_Model_Order || !$orderId = $order->getId()) {
            throw new Exception($hlp->__('Mdd fail. Order was not found.'));
        }

        $this->_order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->_order;
    }

	public function setOrderMddData()
	{
		$order = $this->getOrder();

		$data  = array(	
			'order_id' => $order->getId(),
			'additional_information' => $this->prepareOrderAdditionalInformation(),
		);

		$this->addData($data);
		return $this;
	}
	
	protected function prepareOrderAdditionalInformation()
	{
		$order = $this->getOrder();
		$data = Mage::getSingleton('core/session')->getVisitorData();
		
		$mobileDetect = new Webjump_MobileDetect();
		$data['is_mobile'] = $mobileDetect->isMobile();
		$data['is_tablet'] = $mobileDetect->isTablet();
		$data['is_ios'] = $mobileDetect->isiOS();
		$data['is_androidos'] = $mobileDetect->isAndroidOS();
		$data['purchase_attempts'] = Mage::getSingleton('webjump_braspag_antifraud/session')->getPurchaseAttempts();
		return $data;
	} 
}