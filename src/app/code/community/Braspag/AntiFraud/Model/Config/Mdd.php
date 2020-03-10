<?php

class Braspag_AntiFraud_Model_Config_Mdd extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return string
     */
    public function getMddSalesChannel($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/sales_channel', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddMerchantCategory($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/merchant_category', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddMerchantSegment($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/merchant_segment', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddExtraData1($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/extra_data1', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddExtraData2($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/extra_data2', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddExtraData3($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/extra_data3', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddExtraData4($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/extra_data4', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMddExtraData5($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/mdd/extra_data5', $storeId);
    }
}