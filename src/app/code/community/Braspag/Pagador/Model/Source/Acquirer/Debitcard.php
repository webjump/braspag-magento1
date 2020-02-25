<?php

class Braspag_Pagador_Model_Source_Acquirer_Debitcard extends Braspag_Pagador_Model_Source_Acquirer_Abstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getDeditcardConfig()
    {
        return Mage::getModel('braspag_pagador/config_transaction_debitCard');
    }

    protected function getPaymentMethods()
    {
        return $this->getDeditcardConfig()->getAcquirersDebitCardPaymentMethods();
    }
}