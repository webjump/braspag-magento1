<?php

class Braspag_AntiFraud_Model_Config_Options extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsSequence($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/options/sequence', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsSequenceCriteria($storeId = null)
    {
        return (string) Mage::getStoreConfig('braspag_antifraud/options/sequence_criteria', $storeId);
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getOptionsCaptureOnLowRisk($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_antifraud/options/capture_on_low_risk', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function getOptionsVoidOnHighRisk($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_antifraud/options/void_on_high_risk', $storeId);
    }
}