<?php

/**
 * Mpi Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
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
