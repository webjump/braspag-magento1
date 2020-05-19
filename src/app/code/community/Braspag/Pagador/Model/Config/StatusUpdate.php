<?php
class Braspag_Pagador_Model_Config_StatusUpdate extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return mixed
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getOrderStatusPaid($storeId = null)
    {
        if (empty($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        return Mage::getStoreConfig('braspag_pagador/status_update/order_status_paid', $storeId);
    }
}