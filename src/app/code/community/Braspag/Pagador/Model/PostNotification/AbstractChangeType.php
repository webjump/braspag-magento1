<?php

class Braspag_Pagador_Model_PostNotification_AbstractChangeType extends Mage_Core_Model_Abstract
{
    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('braspag_pagador');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getTransactionManager()
    {
        return Mage::getModel('braspag_pagador/payment_transactionManager');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getPaymentManager()
    {
        return Mage::getModel('braspag_pagador/payment_paymentManager');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getBraspagCoreConfigGeneral()
    {
        return Mage::getSingleton('braspag_core/config_general');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getBraspagCoreHelper()
    {
        return Mage::helper('braspag_core');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }
}
