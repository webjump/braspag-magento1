<?php
class Braspag_Core_Model_Config_General extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantId($storeId = null)
    {
        $merchantId = Mage::getStoreConfig('braspag_core/merchant/merchant_id', $storeId);

        if (empty($merchantId)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid merchant id. Please check configuration.'));
        }

        return $merchantId;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantKey($storeId = null)
    {
        $merchantKey = Mage::getStoreConfig('braspag_core/merchant/merchant_key', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid merchant key. Please check configuration.'));
        }

        return $merchantKey;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantName($storeId = null)
    {
        $merchantName = Mage::getStoreConfig('braspag_core/merchant/merchant_name', $storeId);
        if (empty($merchantName)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid merchant Name. Please check configuration.'));
        }

        return $merchantName;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getEstablishmentCode($storeId = null)
    {
        $establishmentCode = Mage::getStoreConfig('braspag_core/merchant/establishment_code', $storeId);
        if (empty($establishmentCode)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid Establishment Code. Please check configuration.'));
        }

        return $establishmentCode;
    }

    public function getMcc($storeId = null)
    {
        $mcc = Mage::getStoreConfig('braspag_core/merchant/mcc', $storeId);
        if (empty($mcc)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid MCC. Plean getTokense check configuration.'));
        }

        return $mcc;
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isTestEnvironmentEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_core/general/sandbox_flag', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isDebugEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_core/general/debug', $storeId);
    }
}