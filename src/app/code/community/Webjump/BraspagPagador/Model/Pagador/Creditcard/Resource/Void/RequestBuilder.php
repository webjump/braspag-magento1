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
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Creditcard_Resource_Void_RequestBuilder
{
    /**
     * @param $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $helper = Mage::helper('webjump_braspag_pagador');
        $generalConfigModel = Mage::getSingleton('webjump_braspag_pagador/config');

        $order = $payment->getOrder();

        $merchantId = $generalConfigModel->getMerchantId();
        $merchantKey = $generalConfigModel->getMerchantKey();

        $paymentTransactionId = $payment->getParentTransactionId() ? $payment->getParentTransactionId() : $payment->getTransactionId();

        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
            ->setOrderId($order->getIncrementId())
            ->setBraspagOrderId($paymentTransactionId)
            ->setOrderAmount(($amount == 0 ? $order->getGrandTotal() : $amount));

        $dataRequest = array(
            'requestId' => $helper->generateGuid($order->getIncrementId()),
            'merchantId' => $merchantId,
            'merchantKey' => $merchantKey,
            'order' 	=> $dataOrder
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Void\Request');
        $request->populate($dataRequest);

        return $request;

    }

    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager([]);
    }
}
