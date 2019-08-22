<?php
class Webjump_BraspagPagador_Model_Generalservice extends Mage_Core_Model_Abstract
{
    protected $lastRequest;

    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

    protected function getConfig()
    {
        $storeId = $this->getStoreId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $config = Mage::getStoreConfig('webjump_braspag_pagador/generalservice/config/sandbox', $storeId);
        } else {
            $config = Mage::getStoreConfig('webjump_braspag_pagador/generalservice/config/production', $storeId);
        }

        return $config;
    }

    protected function getGeneralservice()
    {
        return new Webjump_BrasPag_Generalservice($this->getConfig());
    }

    protected function setLastRequest($data)
    {
        $this->lastRequest = $data;

        return $this;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function encrypt(array $data)
    {
        try {
            $helper = $this->getHelper();
            $generalService = $this->getGeneralservice();
            $generalService->setData($data);
            $return = $generalService->encrypt();
            $this->setLastRequest($generalService->getLastRequest());

            return $return;
        } catch (Exception $e) {
            Mage::logException(new Exception(
                sprintf('Error encrypt: %1$s (code %2$s)', $e->getMessage(), $e->getCode())
            ));
            Mage::throwException($helper->__('Error while encrypting your data.'));
        }
    }

    public function decrypt(array $data)
    {
        try {
            $helper = $this->getHelper();
            $generalService = $this->getGeneralservice();
            $generalService->setData($data);
            $return = $generalService->decrypt();
            $this->setLastRequest($generalService->getLastRequest());

            return $return;
        } catch (Exception $e) {
            Mage::logException(new Exception(
                sprintf('Error decrypt: %1$s (code %2$s)', $e->getMessage(), $e->getCode())
            ));
            Mage::throwException($helper->__('Error while decrypting your data.'));
        }
    }
}
