<?php

class Braspag_Antifraud_Model_Config extends Mage_Core_Model_Abstract
{
    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getGeneralConfig()
    {
        return Mage::getSingleton('braspag_antifraud/config_general');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getFingerPrintConfig()
    {
        return Mage::getSingleton('braspag_antifraud/config_fingerPrint');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getMddConfig()
    {
        return Mage::getSingleton('braspag_antifraud/config_mdd');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getOptionsConfig()
    {
        return Mage::getSingleton('braspag_antifraud/config_options');
    }
}