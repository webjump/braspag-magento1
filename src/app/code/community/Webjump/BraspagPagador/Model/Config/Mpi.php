<?php
class Webjump_BraspagPagador_Model_Config_Mpi extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     */
    public function getEndPoint()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/mpi/config/sandbox', $storeId);
        } else {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/mpi/config/production', $storeId);
        }

        return $endPoint;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientId($storeId = null)
    {
        $merchantId = Mage::getStoreConfig('mpi/access_token_generation/client_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid Client Id in production environment. Please check configuration.'));
        }

        return trim($merchantId);
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientSecret($storeId = null)
    {
        $merchantKey = Mage::getStoreConfig('mpi/access_token_generation/client_secret', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid Client Secret in production environment. Please check configuration.'));
        }

        return trim($merchantKey);
    }
}