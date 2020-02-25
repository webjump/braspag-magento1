<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Abort
{
    /**
     * @return int
     */
    public function getPaymentStatus()
    {
        return Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_ABORTED;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     */
    public function execute($payment, $transactionDataPayment)
    {
        return Mage::getModel('braspag_pagador/payment_orderManager')
            ->cancel($payment, $transactionDataPayment);
    }
}
