<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Refund_CreditCard
    extends Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Refund
{
    /**
     * @return int
     */
    public function getPaymentMethodCode()
    {
        return Braspag_Pagador_Model_Config::METHOD_CREDITCARD;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     */
    public function execute($payment, $transactionDataPayment)
    {
        return parent::execute($payment, $transactionDataPayment);
    }
}
