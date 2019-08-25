<?php
class Webjump_BraspagPagador_Block_Form_Payment_Reorder extends Webjump_BraspagPagador_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/payment/reorder.phtml');
    }

	public function getCcAvailableTypes()
	{
        $types = array();

        if ($method = $this->getCreditCardMethod()) {
	        $types = $method->getCcAvailableTypes();
        }

        return $types;
	}	
	
    public function getMethodCode()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_multi_cccc')->getCode();
    }

//	public function isInstallmentsEnabled()
//	{
//        return (!empty(Mage::getStoreConfig('payment/webjump_braspag_cc/installments_plan')));
//	}

    public function getAmount()
    {
        return Mage::registry('paymentTotalDue');
    }

    public function getInstallmentsSelectOptions()
    {
        foreach ($this->getInstallments() as $id => $value) {
            $options .= Mage::helper('webjump_braspag_pagador')->__('<option value="%1$s">%1$sx %2$s without interest</option>', $id, $value);
        }

        return $options;
    }

    protected function getInstallments()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments')
            ->caculate($this->getAmount());
    }

    public function isShowJustclickOption()
    {
        return Mage::getStoreConfig('payment/webjump_braspag_justclick/active');
    }

    protected function getCreditCardMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc');
    }

    protected function getPaymentPending()
    {
        return Mage::getSingleton('webjump_braspag_pagador/session')->getPaymentsPending();
    }

    /**
     * @deprecated
     */
    protected function getPaymentsPending()
    {
        return $this->getPaymentPending();
    }

    public function getOrderId()
    {
        return Mage::app()->getRequest()->getQuery('order', Mage::getSingleton('webjump_braspag_pagador/session')->getOrderId());
    }

    public function getBoletoType()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_boleto')->getBoletoType();
    }
}
