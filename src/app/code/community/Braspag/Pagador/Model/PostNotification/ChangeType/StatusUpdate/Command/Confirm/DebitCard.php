<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm_DebitCard
    extends Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm
{
    /**
     * @return string
     */
    public function getPaymentMethodCode()
    {
        return Braspag_Pagador_Model_Config::METHOD_DEBITCARD;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     * @throws Exception
     */
    public function execute($payment, $transactionDataPayment)
    {
        return parent::execute($payment, $transactionDataPayment);
    }
}
