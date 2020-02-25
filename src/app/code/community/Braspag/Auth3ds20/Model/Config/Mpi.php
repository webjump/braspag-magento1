<?php
class Braspag_Auth3ds20_Model_Config_Mpi extends Mage_Core_Model_Abstract
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
            $endPoint = Mage::getStoreConfig('braspag_auth3ds20/mpi/config/sandbox', $storeId);
        } else {
            $endPoint = Mage::getStoreConfig('braspag_auth3ds20/mpi/config/production', $storeId);
        }

        return $endPoint;
    }
}