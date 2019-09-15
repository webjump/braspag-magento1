<?php

class Webjump_BraspagPagador_Model_Config_Antifraud extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return bool
     */
    public function isAntifraudActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('antifraud/general/active', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintDomain($storeId = null)
    {
        return (string) Mage::getStoreConfig('webjump_braspag_antifraud/fingerprint/domain', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintOrgId($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/fingerprint/org_id', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintMerchantId($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/fingerprint/merchant_id', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getFingerPrintUseOrderId($storeId = null)
    {
        return (bool) Mage::getStoreConfig('antifraud/fingerprint/use_order_id_to_figerprint', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsSequence($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/options/sequence', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsSequenceCriteria($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/options/sequence_criteria', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsCaptureOnLowRisk($storeId = null)
    {
        return (bool) Mage::getStoreConfig('antifraud/options/capture_on_low_risk', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function getOptionsVoidOnHighRisk($storeId = null)
    {
        return (bool) Mage::getStoreConfig('antifraud/options/void_on_high_risk', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddFetchSelfShippingMethod($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/mdd/fetch_self_shipping_method', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddStoreCodeToFetchSelf($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/mdd/store_code_to_fetch_self', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddVerticalSegment($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/mdd/vertical_segment', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddStoreIdentity($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/mdd/store_identity', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddCategoryAttributeCode($storeId = null)
    {
        return (string) Mage::getStoreConfig('antifraud/mdd/category_attribute_code', $storeId);
    }
}