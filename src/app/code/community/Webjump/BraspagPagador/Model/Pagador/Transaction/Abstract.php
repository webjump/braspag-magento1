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
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction Void Abstract
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BraspagPagador_Model_Pagador_Transaction_Abstract
{
    protected function getRequestId($increment)
    {
        return $this->getConfig()->generateGuid($increment);
    }

    protected function getMerchantId()
    {
        return $this->getConfig()->getMerchantId();
    }

    protected function getMerchantKey()
    {
        return $this->getConfig()->getMerchantKey();
    }
    
    protected function getConfig()
    {
        return Mage::getSingleton('webjump_braspag_pagador/config');
    }
}