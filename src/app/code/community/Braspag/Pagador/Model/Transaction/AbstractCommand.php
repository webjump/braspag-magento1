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
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Braspag_Pagador_Model_Transaction_AbstractCommand extends Mage_Core_Model_Abstract
{
    public function getBraspagPagadorHelper()
    {
        return Mage::helper('braspag_pagador');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }

    /**
     * @param $payment
     * @param null $amount
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function execute($payment, $amount = null)
    {
        try {
            $request = $this->getRequestBuilder()->build($payment, $amount);

            $this->getRequestValidator()->validate($payment, $request);

            $requestTransaction = $this->getRequestHandler()->handle($payment, $request);

            $this->getResponseValidator()->validate($requestTransaction->getResponse());

            $this->getResponseHandler()->handle($payment, $requestTransaction->getResponse());

        } catch (\Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $requestTransaction;
    }
}