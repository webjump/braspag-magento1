<?php
class Webjump_BraspagPagador_Block_Form_Boleto extends Webjump_BraspagPagador_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/form/boleto.phtml');
    }

    public function getPaymentInstructions()
    {
        $method = $this->getMethod();
        return $method->getConfigData('payment_instructions');
    }

    public function getBoletoType()
    {
        $method = $this->getMethod();
        return $method->getConfigData('boleto_type');
    }
}
