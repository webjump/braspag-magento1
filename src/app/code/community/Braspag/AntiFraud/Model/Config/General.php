<?php

class Braspag_AntiFraud_Model_Config_General extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return bool
     */
    public function isAntifraudActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_antifraud/general/active', $storeId);
    }
}