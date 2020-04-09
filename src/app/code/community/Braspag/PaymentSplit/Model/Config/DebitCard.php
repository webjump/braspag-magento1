<?php

class Braspag_PaymentSplit_Model_Config_DebitCard extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/is_active', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function getSplitType($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/transaction_type', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isTransactionPostSendRequestAutomatically($storeId = null)
    {
        return (bool) Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/transaction_post_send_request_automatically', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function getTransactionPostSendRequestAutomaticallyAfterXDays($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/transaction_post_send_request_automatically_after_x_days', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDefaultMdr($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/default_mdr', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDefaultFee($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/default_fee', $storeId);
    }
}