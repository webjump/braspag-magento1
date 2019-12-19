<?php

/**
 * Class Webjump_BraspagPagador_Block_Checkout_Bpmpi
 */
class Webjump_BraspagPagador_Block_Checkout_Bpmpi extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * @var Webjump_BraspagPagador_Model_Config
     */
    protected $generalConfig;

    /**
     * @var Webjump_BraspagPagador_Model_Config_Mpi
     */
    protected $mpiConfig;

    /**
     * Webjump_BraspagPagador_Block_Checkout_Bpmpi constructor.
     */
    public function __construct()
    {
        $this->generalConfig = Mage::getSingleton('webjump_braspag_pagador/config');
        $this->mpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_mpi');
    }

    /**
     * @return bool
     */
    public function isTestEnvironmentEnabled()
    {
        return (bool) $this->generalConfig->isTestEnvironmentEnabled();

    }

    /**
     * @return bool
     */
    public function isBpmpiCreditCardEnabled()
    {
        return (bool) $this->mpiConfig->isMpiCreditCardActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiDebitCardEnabled()
    {
        return (bool) $this->mpiConfig->isMpiDebitCardActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyCreditCardEnabled()
    {
        return (bool) $this->mpiConfig->isBpmpiMcNotifyOnlyCreditCardEnabled();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyDebitCardEnabled()
    {
        return (bool) $this->mpiConfig->isBpmpiMcNotifyOnlyDebitCardEnabled();
    }
}
