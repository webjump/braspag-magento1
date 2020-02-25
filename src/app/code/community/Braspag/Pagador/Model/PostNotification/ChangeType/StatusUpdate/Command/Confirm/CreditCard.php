<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm_CreditCard
extends Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm
{
    /**
     * @return string
     */
    public function getPaymentMethodCode()
    {
        return Braspag_Pagador_Model_Config::METHOD_CREDITCARD;
    }

    /**
     * @param $payment
     * @param $transactionDataPayment
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function execute($payment, $transactionDataPayment)
    {
        return parent::execute($payment, $transactionDataPayment);
    }
}
