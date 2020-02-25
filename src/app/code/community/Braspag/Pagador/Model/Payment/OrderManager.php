<?php
class Braspag_Pagador_Model_Payment_OrderManager extends Mage_Core_Model_Abstract
{
    /**
     * @param $payment
     */
    public function setOrderStatusPaymentReview($payment)
    {
        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
        $message = $this->getHelper()->__("Possible Fraud Detected. Analysing.");

        $payment->getOrder()->setState($state, 'payment_review', $message)
            ->save();
    }

    /**
     * @param $payment
     */
    public function setOrderStatusCanceled($payment)
    {
        $payment->setIsFraudDetected(false);
        $order = $payment->getOrder();

        $order->cancel()->save();
        $state = Mage_Sales_Model_Order::STATE_CANCELED;
        $message = $this->getHelper()->__("Canceled After Fraud Detected.");

        $order->setState($state, 'canceled', $message)
            ->save();
    }

    /**
     * @param $payment
     */
    public function setOrderStatusFraud($payment)
    {
        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
        $message = $this->getHelper()->__("Anti Fraud - Fraud Detected.");

        $payment->getOrder()->setState($state, 'fraud', $message)
            ->save();
    }

    /**
     * @param $paymentResponse
     * @param $payment
     * @return mixed
     */
    public function cancel($payment, $paymentResponse)
    {
        $order = $payment->getOrder();

        $transactionId = $this->getHelper()->cleanTransactionId($payment->getLastTransId());

        $payment->setParentTransactionId($transactionId)
            ->setTransactionId($transactionId."-void")
            ->setIsTransactionClosed(1);

        $raw_details = [];
        foreach ($paymentResponse->getArrayCopy() as $r_key => $r_value) {
            $raw_details['payment_void_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->resetTransactionAdditionalInfo();
        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $payment->registerVoidNotification();

        $order->registerCancellation();

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($order);

        $transactionSave->save();
        $order->save();

        return $order;
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('braspag_pagador');
    }
}