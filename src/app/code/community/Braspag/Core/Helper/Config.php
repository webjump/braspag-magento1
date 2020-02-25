<?php

/**
 * Data Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Core_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * @param $configPath
     * @param null $storeId
     * @return bool|mixed
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getDefaultConfigPath($configPath, $storeId = null)
    {
        if (empty($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        if (!$configStr = Mage::getStoreConfig($configPath, $storeId)) {
            return false;
        }

        return $configStr;
    }

    /**
     * @param $configPath
     * @param null $storeId
     * @return bool|false|Mage_Core_Model_Abstract
     * @throws Mage_Core_Model_Store_Exception
     */
	public function getDefaultConfigClassModel($configPath, $storeId = null)
    {
        $configPath = $configPath."/class";

        if (empty($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        if (!$configClassModelStr = Mage::getStoreConfig($configPath, $storeId)) {
            return false;
        }

        $configClassModelStr = trim($configClassModelStr);

        if (!$configClassInstance = Mage::getModel($configClassModelStr)) {
            return false;
        }

        return $configClassInstance;
    }

    /**
     * @param $configPath
     * @param null $storeId
     * @return array|false|Mage_Core_Model_Abstract
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getDefaultConfigClassComposite($configPath, $storeId = null)
    {
        $configPath = $configPath."/composite";

        if (empty($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        if (!$configClassModelStr = Mage::getStoreConfig($configPath, $storeId)) {
            return [];
        }

        if (!is_array($configClassModelStr)) {
            return [];
        }

        $configClassModelItems = [];
        foreach ($configClassModelStr as $configClassModelStrItem) {

            if (!isset($configClassModelStrItem['class'])) {
                continue;
            }

            $configClassModelStrItemClass = trim($configClassModelStrItem['class']);

            if (!class_exists($configClassModelStrItemClass)) {

                var_dump($configClassModelStrItemClass);
                exit('class not exists');
                continue;
            }

            $configClassInstance = Mage::getModel($configClassModelStrItemClass);

            $configClassModelItems[] = $configClassInstance;
        }

        return $configClassModelItems;
    }
}
