<?php
class Webjump_BraspagPagador_Block_Form_Creditcard extends Webjump_BraspagPagador_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/creditcard.phtml');
    }

    public function getCreditCardAvailableTypes()
    {
        if ($method = $this->getMethod()) {
            $types = $method->getCreditCardAvailableTypes();
        } else {
            $types = array();
        }

        return $types;
    }

    public function getInstallments()
    {
        if ($method = $this->getMethod()) {
            return $method->getInstallments();
        } else {
            return false;
        }
    }

    public function isShowJustclickOption()
    {
        return Mage::getStoreConfig('payment/webjump_braspag_justclick/active');
    }
}
