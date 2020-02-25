<?php
class Braspag_Pagador_Model_Config_ApiQuery extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getConfig()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('braspag_core/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $config = Mage::getStoreConfig('braspag_pagador/api_query/config/sandbox', $storeId);
        } else {
            $config = Mage::getStoreConfig('braspag_pagador/api_query/config/production', $storeId);
        }

        return $config;
    }
}