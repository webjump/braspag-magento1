<?php
class Braspag_Pagador_Model_Payment_AbstractManager extends Mage_Core_Model_Abstract
{
    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('braspag_pagador');
    }

    protected function getBraspagCoreHelper()
    {
        return Mage::helper('braspag_core');
    }

    protected function getBraspagCoreConfigGeneral()
    {
        return Mage::getSingleton('braspag_core/config_general');
    }

    /**
     * @return Braspag_Lib_Core_Service_Manager
     */
    public function getServiceManager()
    {
        return new Braspag_Lib_Core_Service_Manager(Mage::getModel('braspag_pagador/config_apiQuery')->getConfig());
    }
}