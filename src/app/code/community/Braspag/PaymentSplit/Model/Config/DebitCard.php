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

    /**
     * @param null $storeId
     * @return bool
     */
    public function isActiveStoreParticipateOnSales($storeId = null)
    {
        return (bool) Mage::getStoreConfig(
            'braspag_paymentsplit/debitcard_transaction/is_active_store_participate_on_sales', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDefaultStorePercentageOnSales($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/default_store_percentage_on_sales', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDefaultStoreAmountValueOnSales($storeId = null)
    {
        return Mage::getStoreConfig('braspag_paymentsplit/debitcard_transaction/default_store_amount_value_on_sales', $storeId);
    }

}