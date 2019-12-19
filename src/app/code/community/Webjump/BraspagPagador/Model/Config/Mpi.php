<?php
class Webjump_BraspagPagador_Model_Config_Mpi extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     */
    public function getEndPoint()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/mpi/config/sandbox', $storeId);
        } else {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/mpi/config/production', $storeId);
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
        $merchantId = Mage::getStoreConfig('mpi/access_token_generation/client_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
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
        $merchantKey = Mage::getStoreConfig('mpi/access_token_generation/client_secret', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid Client Secret in production environment. Please check configuration.'));
        }

        return trim($merchantKey);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isMpiCreditCardActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/creditcard_transation/mpi_is_active', $storeId);
    }

    /**
     * @param $storeId
     * @return bool
     */
    public function isMpiDebitCardActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/debitcard_transation/mpi_is_active', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyCreditCardEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/creditcard_transation/mpi_mastercard_notify_only', $storeId);
    }

    /**
     * @param $storeId
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyDebitCardEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/debitcard_transation/mpi_mastercard_notify_only', $storeId);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getMpiCreditCardMdds($storeId = null)
    {
        return [
            'mdd1' => Mage::getStoreConfig('pagador/webjump_braspag_cc/mpi_mdd1', $storeId),
            'mdd2' => Mage::getStoreConfig('pagador/webjump_braspag_cc/mpi_mdd2', $storeId),
            'mdd3' => Mage::getStoreConfig('pagador/webjump_braspag_cc/mpi_mdd3', $storeId),
            'mdd4' => Mage::getStoreConfig('pagador/webjump_braspag_cc/mpi_mdd4', $storeId),
            'mdd5' => Mage::getStoreConfig('pagador/webjump_braspag_cc/mpi_mdd5', $storeId),
        ];
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getMpiDebitCardMdds($storeId = null)
    {
        return [
            'mdd1' => Mage::getStoreConfig('pagador/webjump_braspag_dc/mpi_mdd1', $storeId),
            'mdd2' => Mage::getStoreConfig('pagador/webjump_braspag_dc/mpi_mdd2', $storeId),
            'mdd3' => Mage::getStoreConfig('pagador/webjump_braspag_dc/mpi_mdd3', $storeId),
            'mdd4' => Mage::getStoreConfig('pagador/webjump_braspag_dc/mpi_mdd4', $storeId),
            'mdd5' => Mage::getStoreConfig('pagador/webjump_braspag_dc/mpi_mdd5', $storeId),
        ];
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnError($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/creditcard_transation/mpi_authorize_on_error', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnError($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/debitcard_transation/mpi_authorize_on_error', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnFailure($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/creditcard_transation/mpi_authorize_on_failure', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnFailure($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/debitcard_transation/mpi_authorize_on_failure', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnUnenrolled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/creditcard_transation/mpi_authorize_on_unenrolled', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnUnenrolled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('mpi/debitcard_transation/mpi_authorize_on_unenrolled', $storeId);
    }
}