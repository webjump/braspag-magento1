<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Braspag_Pagador_Model_Transaction_AbstractBuilder extends Mage_Core_Model_Abstract
{
    protected $buildName;
    protected $dataBuild;

    public function __construct()
    {
        $className = explode("_", get_class($this));
        $className = end($className);

        $this->setBuildName($className);
    }

    /**
     * @return mixed
     */
    public function getBuildName()
    {
        return $this->buildName;
    }

    /**
     * @param mixed $buildName
     */
    public function setBuildName($buildName)
    {
        $this->buildName = $buildName;
    }

    /**
     * @return mixed
     */
    public function getDataBuild()
    {
        return $this->dataBuild;
    }

    /**
     * @param mixed $dataBuild
     */
    public function setDataBuild($dataBuild)
    {
        $this->dataBuild = $dataBuild;
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagPagadorHelper()
    {
        return Mage::helper('braspag_pagador');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreHelper()
    {
        return Mage::helper('braspag_core');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getTransactionBuilderComposite()
    {
        return Mage::getModel('braspag_pagador/transaction_builder_composite');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getAddressAbbreviationHelper() {
        return Mage::helper('braspag_pagador/addressAbbreviation');
    }

    /**
     * @return Braspag_Lib_Core_Service_Manager
     */
    public function getServiceManager()
    {
        return new Braspag_Lib_Core_Service_Manager([]);
    }

    /**
     * @param $array
     * @return Varien_Object
     */
    public function arrayToObject($array)
    {
        $dataObject = new Varien_Object();
        $dataObject->addData($array);

        return $dataObject;
    }

    /**
     * @return Varien_Object
     */
    public function getNewVarienObject()
    {
        return new Varien_Object();
    }
}
