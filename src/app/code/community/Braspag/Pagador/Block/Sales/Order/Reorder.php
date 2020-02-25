<?php
//class Braspag_Pagador_Block_Sales_Order_Reorder extends Braspag_Pagador_Block_Form
//{
//
//    protected function _construct()
//    {
//        parent::_construct();
//        $this->setTemplate('braspag/pagador/payment/form/payment/reorder.phtml');
//    }
//
//	public function getCreditCardAvailableTypes()
//	{
//        $types = array();
//
//        if ($method = $this->getCreditCardMethod()) {
//	        $types = $method->getCreditCardAvailableTypes();
//        }
//
//        return $types;
//	}
//
//    public function getAmount()
//    {
//        return Mage::registry('paymentTotalDue');
//    }
//
//    public function getInstallmentsSelectOptions()
//    {
//        $options = "";
//        foreach ($this->getInstallments() as $id => $value) {
//            $options .= Mage::helper('braspag_pagador')->__('<option value="%1$s">%1$sx %2$s without interest</option>', $id, $value);
//        }
//
//        return $options;
//    }
//
//    protected function getInstallments()
//    {
//        return Mage::getSingleton('braspag_pagador/pagador_creditcard_resource_authorize_installmentsBuilder')
//            ->calculate($this->getAmount());
//    }
//
//    public function isShowJustclickOption()
//    {
//        return Mage::getStoreConfig('payment/braspag_justclick/active');
//    }
//
//    protected function getCreditCardMethod()
//    {
//        return Mage::getSingleton('braspag_pagador/method_creditcard');
//    }
//
//    protected function getPaymentPending()
//    {
//        return Mage::getSingleton('braspag_pagador/session')->getPaymentsPending();
//    }
//
//    /**
//     * @deprecated
//     */
//    protected function getPaymentsPending()
//    {
//        return $this->getPaymentPending();
//    }
//
//    public function getOrderId()
//    {
//        return Mage::app()->getRequest()->getQuery('order', Mage::getSingleton('braspag_pagador/session')->getOrderId());
//    }
//
//    public function getBoletoType()
//    {
//        return Mage::getSingleton('braspag_pagador/method_boleto')->getBoletoType();
//    }
//}
