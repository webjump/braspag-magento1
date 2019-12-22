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
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Creditcard_Command_RefundCommand
    extends Webjump_BraspagPagador_Model_Pagador_RefundAbstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_refund_request_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getResponseValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_refund_response_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_refund_requestBuilder');
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager($this->getConfigData());
    }

    /**
     * @return mixed
     */
    protected function getConfigData()
    {
        return Mage::getModel('webjump_braspag_pagador/config')->getConfig();
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }
}
