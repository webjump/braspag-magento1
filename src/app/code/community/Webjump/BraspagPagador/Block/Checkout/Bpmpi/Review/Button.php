<?php
class Webjump_BraspagPagador_Block_Checkout_Bpmpi_Review_Button extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * @var Webjump_BraspagPagador_Model_Config_Mpi
     */
    protected $bpmpiConfig;

    public function __construct()
    {
        $this->bpmpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_bpmpi');
    }

    /**
     * @return bool
     */
    public function isBpmpiActive()
    {
        return (bool) ($this->bpmpiConfig->isMpiCreditCardActive() || $this->bpmpiConfig->isMpiDebitCardActive());
    }
}
