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
 * Pagador Transaction Refund
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction_Refund extends Webjump_BraspagPagador_Model_Pagador_Transaction_Abstract
{
    const STATUS_CONFIRMED = 0;
    const STATUS_DENIED = 1;
    const STATUS_INVALID = 2;

    protected $order;

    /*
        O recurso de refund parcial não esta
        dentro do escopo até o momento.
        Por isso estou setando o valor padrão
        de resgate para 0, pois desta maneira
        a braspag entende que deve ser resgatado
        todo o valor da transação.
    */
    const DEFAULT_AMOUNT = 0;

    public function getRequest()
    {
        return array(
            'requestId' => $this->getRequestId($this->getOrder()->getId()),
            'version' => $this->getVersion(),
            'merchantId' => $this->getMerchantId(),
            'transactionDataCollection' => $this->getOrderTransactions(),
        );
    }

    public function getOrder()
    {
        if ($this->order) {
            return $this->order;
        }

        throw new Exception("Set the order first");
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;

        return $this;
    }


    public function processResponse($response)
    {
        if (!$this->isResponseValid($response)) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Server response error.'));
        }

        if (!$this->isOrderCanBeCanceledByResponse($response)) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Any transactions can not be canceled on braspag.'));
        }

        if (!$this->getParentTransactionId()) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Authorize Transaction is necessary to refund this transaction'));
        }

        $this->proccessOrderState($response);
        $this->proccesssOrderRefundTransaction($response);

        return $this;
        
    }

    protected function isResponseValid($response)
    {
        return ((!empty($response)) && (isset($response['success'])) && (isset($response['transactions'])));
    }

    protected function isOrderCanBeCanceledByResponse($response)
    {
        foreach ($response['transactions'] as $transaction) {
            if (!in_array($transaction['status'], array(self::STATUS_CONFIRMED, self::STATUS_INVALID))) {
                return false;
            }
        }

        return true;
    }

    protected function getParentTransactionId()
    {
        $payment = $this->getOrderPayment();
        return $payment->getParentTransactionId() ? $payment->getParentTransactionId() : $payment->getLastTransId();
    }

    protected function proccessOrderState($response)
    {
        $message = Mage::helper('sales')->__('Refunded authorization. Amount: %s.', $this->_formatPrice(
            $this->getAmountTotalByResponse($response)
        ));

        $this->getOrder()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, $message);
        $this->getOrder()->save();
    }

    protected function getAmountTotalByResponse($response)
    {
        $amount = array_sum(array_map(function($item) { 
            return $item['amount'];
        }, $response['transactions']));
        
        return $amount/100;
    }

    protected function proccesssOrderRefundTransaction($response)
    {
        $this->closeAuthorizationTransaction();
        $txnId = $response['correlationId'];

        Mage::getModel('sales/order_payment_transaction')
            ->setOrderPaymentObject($this->getOrderPayment())
            ->setOrder($this->getOrder())
            ->setTxnId($txnId)
            ->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_REFUND)
            ->setIsClosed(1)
            ->setAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $this->convertResponseToTransactiondetails($response))
            ->save();

        $this->getOrderPayment()->setTransactionId($txnId);
    }

    protected function convertResponseToTransactiondetails($response)
    {
       $transactionDetails = array();

       foreach ($response['transactions'] as $transacionKey => $transaction) {
            foreach ($transaction as $key => $data) {
                $transactionDetails['refund_' . $transacionKey . '_' . $key ] = $data;
            }
       }

       $response = array_merge($response, $transactionDetails);
       unset($response['transactions']);
       unset($response['errorReport']);

       return $response;
    }

    protected function closeAuthorizationTransaction()
    {
        $this->getOrderPayment()->getAuthorizationTransaction()
            ->setIsClosed(1)
            ->save();
    }

    protected function _formatPrice($amount, $currency = null)
    {
        return $this->getOrder()->getBaseCurrency()->formatTxt(
            $amount,
            $currency ? array('currency' => $currency) : array()
        );
    }

    protected function getOrderPayment()
    {
        return $this->getOrder()->getPayment();                    
    }

    protected function getOrderPaymentResponse()
    {
        return $this->getOrderPayment()->getAdditionalInformation('payment_response');
    }

    /**
     * @deprecated
     */
    protected function getOrderPaymentsResponse()
    {
        return $this->getOrderPaymentResponse();
    }
    
    protected function getOrderTransactions()
    {
        $transaction = [];

        if ($payment = $this->getOrderPaymentResponse()) {
            $transaction = array(
                'braspagTransactionId' => $payment['braspagTransactionId'],
                'amount' => self::DEFAULT_AMOUNT,
            );
        }

        return $transaction;
    }
}
