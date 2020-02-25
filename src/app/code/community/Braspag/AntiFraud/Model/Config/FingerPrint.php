<?php

class Braspag_AntiFraud_Model_Config_FingerPrint extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintDomain($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/fingerprint/domain', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintOrgId($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/fingerprint/org_id', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintMerchantId($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/fingerprint/merchant_id', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintUseOrderId($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_antifraud/fingerprint/use_order_id_to_figerprint', $storeId);
    }
}