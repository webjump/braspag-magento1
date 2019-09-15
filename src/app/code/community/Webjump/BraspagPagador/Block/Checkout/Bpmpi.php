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
    public function isBpmpiCcEnabled()
    {
        return (bool) $this->mpiConfig->isMpiCcActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiDcEnabled()
    {
        return (bool) $this->mpiConfig->isMpiDcActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyCcEnabled()
    {
        return (bool) $this->mpiConfig->isBpmpiMcNotifyOnlyCcEnabled();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyDcEnabled()
    {
        return (bool) $this->mpiConfig->isBpmpiMcNotifyOnlyDcEnabled();
    }
}
