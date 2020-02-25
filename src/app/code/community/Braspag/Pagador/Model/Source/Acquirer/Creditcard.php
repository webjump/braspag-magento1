<?php

class Braspag_Pagador_Model_Source_Acquirer_Creditcard
    extends Braspag_Pagador_Model_Source_Acquirer_Abstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getCreditcardConfig()
    {
        return Mage::getModel('braspag_pagador/config_transaction_creditCard');
    }

    protected function getPaymentMethods()
    {
        return $this->getCreditcardConfig()->getAcquirersCreditCardPaymentMethods();
    }
}