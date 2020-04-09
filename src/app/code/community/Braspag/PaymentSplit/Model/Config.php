<?php

class Braspag_PaymentSplit_Model_Config extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return mixed
     */
    public function getConfig($storeId = null)
    {
        $sandboxFlag = Mage::getSingleton('braspag_core/config_general')
            ->isTestEnvironmentEnabled($storeId);

        if ($sandboxFlag) {
            $config = Mage::getStoreConfig('braspag_splitpayment/api/config/sandbox', $storeId);
        } else {
            $config = Mage::getStoreConfig('braspag_splitpayment/api/config/production', $storeId);
        }

        return $config;
    }
}