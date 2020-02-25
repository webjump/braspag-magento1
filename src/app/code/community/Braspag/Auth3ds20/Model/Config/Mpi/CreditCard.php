<?php
class Braspag_Auth3ds20_Model_Config_Mpi_CreditCard extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return bool
     */
    public function isMpiCreditCardActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/is_active', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyCreditCardEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mastercard_notify_only', $storeId);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getMpiCreditCardMdds($storeId = null)
    {
        return [
            'mdd1' => Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mdd1', $storeId),
            'mdd2' => Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mdd2', $storeId),
            'mdd3' => Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mdd3', $storeId),
            'mdd4' => Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mdd4', $storeId),
            'mdd5' => Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/mdd5', $storeId),
        ];
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnError($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/authorize_on_error', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnFailure($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/authorize_on_failure', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isBpmpiCreditCardAuthorizedOnUnenrolled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_auth3ds20/creditcard_transaction/authorize_on_unenrolled', $storeId);
    }
}