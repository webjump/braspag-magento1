<?php
//
//class Braspag_Pagador_Block_Pay_Info extends Braspag_Pagador_Block_Info
//{
//    protected function _construct()
//    {
//        parent::_construct();
//        $this->setTemplate('braspag/pagador/payment/info/pay.phtml');
//    }
//
//    public function getOrder()
//    {
//		$orderId = $this->getRequest()->getQuery('order', Mage::getSingleton('braspag_pagador/session')->getOrderId());
//		return Mage::helper('braspag_pagador')->getOrderWithPendingPayment($orderId);
//    }
//
//    public function getMethod()
//    {
//    	return $this->getOrder()->getPayment()->getMethodInstance();
//    }
//
//    public function getInfo()
//    {
//    	return $this->getOrder()->getPayment();
//    }
//
//    public function getTotalAuthorizedPaid()
//    {
//    	$total = $this->getInfo()->getAdditionalInformation('authorized_total_paid');
//    	return Mage::helper('core')->currency($total);
//    }
//
//    public function getTotalAuthorizedDue()
//    {
//    	$total = $this->getOrder()->getGrandTotal() - $this->getInfo()->getAdditionalInformation('authorized_total_paid');
//    	return Mage::helper('core')->currency($total);
//    }
//
//    public function getGrandTotal()
//    {
//    	return Mage::helper('core')->currency($this->getOrder()->getGrandTotal());
//    }
//}