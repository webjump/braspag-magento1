<?php
class Webjump_BraspagPagador_Block_Form_Justclick extends Webjump_BraspagPagador_Block_Form_Creditcard
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/justclick.phtml');
    }

    public function getAvailableCards()
    {
        return Mage::getModel('webjump_braspag_pagador/justclick_card')->loadCardsByCustomer(Mage::helper('customer')->getCustomer());
    }

    public function isShowCardVerifyCode()
    {
        $method = $this->getMethod();
        return $method->getConfigData('cvv_required');
    }
}
