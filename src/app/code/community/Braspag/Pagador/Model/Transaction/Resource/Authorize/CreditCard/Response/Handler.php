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
class Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Response_Handler
extends Braspag_Pagador_Model_Transaction_AbstractHandler
{
    /**
     * @param $payment
     * @param $response
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function handle($payment, $response)
    {
        $paymentDataResponse = $response->getPayment();

        if ($paymentDataResponse->getStatus() == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING) {
            $payment->setIsTransactionPending(true);
        }

        if ($paymentDataResponse && (!$paymentDataResponse->getAmount())) {
            \Mage::throwException('The payment was unauthorized.');
        }

        $payment->setTransactionParentId($response->getOrder()->getBraspagOrderId())
            ->setTransactionId($response->getOrder()->getBraspagOrderId())
            ->setIsTransactionClosed(0);

        $handlers = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/authorize/credit_card/response/handler');

        $handlerComposite = Mage::getModel('braspag_pagador/transaction_handler_composite');
        foreach ($handlers as $handler) {
            $handlerComposite->addHandle($handler);
        }

        $handlerComposite->processAll($payment, $response);

        if ($paymentDataResponse->getAmount() == 0 && !$payment->getIsTransactionPending()) {
            \Mage::throwException('The payment was unauthorized.');
        }

        return $this;
    }
}
