<?php
class Webjump_BraspagPagador_Block_Form_Cc extends Webjump_BraspagPagador_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/cc.phtml');
    }

    public function getCcAvailableTypes()
    {
        if ($method = $this->getMethod()) {
            $types = $method->getCcAvailableTypes();
        } else {
            $types = array();
        }

        return $types;
    }

    public function isInstallmentsEnabled()
    {
        if ($method = $this->getMethod()) {
            return $method->isInstallmentsEnabled();
        } else {
            return false;
        }
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
