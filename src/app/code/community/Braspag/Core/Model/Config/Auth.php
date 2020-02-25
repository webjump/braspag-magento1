<?php
class Braspag_Core_Model_Config_Auth extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getEndPoint()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('braspag_core/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $endPoint = Mage::getStoreConfig('braspag_core/auth/config/sandbox', $storeId);
        } else {
            $endPoint = Mage::getStoreConfig('braspag_core/auth/config/production', $storeId);
        }

        return $endPoint;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientId($storeId = null)
    {
        $merchantId = Mage::getStoreConfig('braspag_core/auth/client_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid Client Id in production environment. Please check configuration.'));
        }

        return trim($merchantId);
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientSecret($storeId = null)
    {
        $merchantKey = Mage::getStoreConfig('braspag_core/auth/client_secret', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('braspag_core')
                ->__('Invalid Client Secret in production environment. Please check configuration.'));
        }

        return trim($merchantKey);
    }
}