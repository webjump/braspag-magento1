<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Refund
{
    /**
     * @return int
     */
    public function getPaymentStatus()
    {
        return Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_REFUNDED;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     */
    public function execute($payment, $transactionDataPayment)
    {
        $amount = $payment->getAmountPaid();

        return Mage::getModel('braspag_pagador/payment_creditMemoManager')
            ->registerRefundNotification($payment, $amount, $transactionDataPayment, true);
    }
}
