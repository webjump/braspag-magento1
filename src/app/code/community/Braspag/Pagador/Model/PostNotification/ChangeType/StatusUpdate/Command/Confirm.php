<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm
{
    /**
     * @return int
     */
    public function getPaymentStatus()
    {
        return Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     * @throws Exception
     */
    public function execute($payment, $transactionDataPayment = null)
    {
        if (!Mage::getStoreConfig('braspag_pagador/status_update/autoinvoice')){
            throw new \Exception('Invoice creation is disabled.', 400);
        }

        $order = $payment->getOrder();

        $amountOrdered = floatval($order->getGrandTotal());
        $amountPaid = floatval($transactionDataPayment->getAmount()/100);

        $payment->setAdditionalInformation('payment_response', $transactionDataPayment->getArrayCopy())
            ->setAdditionalInformation('captured_total_paid', $amountPaid);

        if ($order->hasInvoices()) {
            throw new \Exception('Order already Invoiced.', 400);
        }

        if ($amountPaid < $amountOrdered) {
            throw new \Exception('Invalid Amount to invoice', 400);
        }

        if ($order->canUnhold()) {
            $order->unhold()->save();
        }

        return Mage::getModel('braspag_pagador/payment_invoiceManager')
            ->registerCaptureNotification($payment, $amountPaid, $transactionDataPayment, true);
    }
}
