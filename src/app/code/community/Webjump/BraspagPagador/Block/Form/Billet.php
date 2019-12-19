<?php
class Webjump_BraspagPagador_Block_Form_Billet extends Webjump_BraspagPagador_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/billet.phtml');
    }

    public function getPaymentInstructions()
    {
        $method = $this->getMethod();
        return $method->getConfigData('payment_instructions');
    }

    public function getBilletType()
    {
        $method = $this->getMethod();
        return $method->getConfigData('billet_type');
    }
}
