<?php

class Webjump_BraspagPagador_Helper_Mpi extends Mage_Core_Helper_Abstract
{
    /**
     * @var Webjump_BraspagPagador_Model_Config_Mpi
     */
    protected $mpiConfig;

    public function __construct()
    {
        $this->mpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_mpi');
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getAuthorizationHeader()
    {
        return base64_encode($this->mpiConfig->getClientId().":".$this->mpiConfig->getClientSecret());
    }

    /**
     * @param $total
     * @return float|int
     */
    public function convertReaisToCentavos($total)
    {
        return floatval($total) * 100;
    }
}
