<?php

class Braspag_AntiFraud_Model_Config_Mdd extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return string
     */
    public function getMddFetchSelfShippingMethod($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/fetch_self_shipping_method', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddStoreCodeToFetchSelf($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/store_code_to_fetch_self', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddVerticalSegment($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/vertical_segment', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddStoreIdentity($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/store_identity', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddCategoryAttributeCode($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/category_attribute_code', $storeId);
    }
}