<?php

class Webjump_BraspagPagador_Block_Pay_Info extends Webjump_BraspagPagador_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/info/pay.phtml');
    }

    public function getOrder()
    {
		$orderId = $this->getRequest()->getQuery('order', Mage::getSingleton('webjump_braspag_pagador/session')->getOrderId());
		return Mage::helper('webjump_braspag_pagador')->getOrderWithPendingPayment($orderId);
    }

    public function getMethod()
    {
    	return $this->getOrder()->getPayment()->getMethodInstance();
    }

    public function getInfo()
    {
    	return $this->getOrder()->getPayment();
    }

    public function getTotalAuthorizedPaid()
    {
    	$total = $this->getInfo()->getAdditionalInformation('authorized_total_paid');
    	return Mage::helper('core')->currency($total);
    }

    public function getTotalAuthorizedDue()
    {
    	$total = $this->getOrder()->getGrandTotal() - $this->getInfo()->getAdditionalInformation('authorized_total_paid');
    	return Mage::helper('core')->currency($total);
    }

    public function getGrandTotal()
    {
    	return Mage::helper('core')->currency($this->getOrder()->getGrandTotal());
    }
}