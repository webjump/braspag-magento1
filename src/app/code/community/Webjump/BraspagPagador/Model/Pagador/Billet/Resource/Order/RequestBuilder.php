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
class Webjump_BraspagPagador_Model_Pagador_Billet_Resource_Order_RequestBuilder
{
    const PAYMENT_TYPE = 'PAYMENT_TYPE';

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

        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
            ->setOrderId($order->getIncrementId())
            ->setOrderAmount(($amount == 0 ? $order->getGrandTotal() : $amount));

        $paymentData = Mage::getModel('webjump_braspag_pagador/pagador_billet_resource_order_paymentBuilder')
            ->build($payment, $amount, 'Boleto');

        $customerData = Mage::getModel('webjump_braspag_pagador/pagador_general_request_customerBuilder')
            ->build($payment, $amount);

        $dataRequest = array(
            'requestId' => $helper->generateGuid($order->getIncrementId()),
            'merchantId' => $merchantId,
            'merchantKey' => $merchantKey,
            'order' 	=> $dataOrder,
            'payment' 	=> $paymentData,
            'customer' 	=> $customerData
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Order\Request');
        $request->populate($dataRequest);

        return $request;
    }

    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager([]);
    }
}
