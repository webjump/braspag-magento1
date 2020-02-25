<?php
class Braspag_Auth3ds20_Model_Config_Mpi_Auth extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientId($storeId = null)
    {
        $clientId = Mage::getStoreConfig('braspag_auth3ds20/auth/client_id', $storeId);
        if (empty($clientId)) {
            throw new Exception(Mage::helper('braspag_auth3ds20')
                ->__('Invalid Client Id. Please check configuration.'));
        }

        return trim($clientId);
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getClientSecret($storeId = null)
    {
        $clientSecret = Mage::getStoreConfig('braspag_auth3ds20/auth/client_secret', $storeId);
        if (empty($clientSecret)) {
            throw new Exception(Mage::helper('braspag_auth3ds20')
                ->__('Invalid Client Secret in production environment. Please check configuration.'));
        }

        return trim($clientSecret);
    }
}