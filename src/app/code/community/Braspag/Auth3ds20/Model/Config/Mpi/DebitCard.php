<?php
class Braspag_Auth3ds20_Model_Config_Mpi_Debitcard extends Mage_Core_Model_Abstract
{
    /**
     * @param $storeId
     * @return bool
     */
    public function isMpiDebitCardActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/is_active', $storeId);
    }

    /**
     * @param $storeId
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyDebitCardEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mastercard_notify_only', $storeId);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getMpiDebitCardMdds($storeId = null)
    {
        return [
            'mdd1' => Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mdd1', $storeId),
            'mdd2' => Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mdd2', $storeId),
            'mdd3' => Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mdd3', $storeId),
            'mdd4' => Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mdd4', $storeId),
            'mdd5' => Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/mdd5', $storeId),
        ];
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnError($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/authorize_on_error', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnFailure($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/authorize_on_failure', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnUnenrolled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/authorize_on_unenrolled', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiDebitCardAuthorizedOnUnsupportedBrand($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/debitcard_transaction/authorize_on_unsupported_brand', $storeId);
    }
}