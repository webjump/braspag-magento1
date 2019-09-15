<?php
class Webjump_BraspagPagador_Model_Config_ApiQuery extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     */
    public function getEndPoint()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/pagadorquery/config/sandbox', $storeId);
        } else {
            $endPoint = Mage::getStoreConfig('webjump_braspag_pagador/pagadorquery/config/production', $storeId);
        }

        return $endPoint;
    }
}