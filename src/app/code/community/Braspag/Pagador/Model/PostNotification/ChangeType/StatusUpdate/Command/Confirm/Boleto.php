<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm_Boleto
    extends Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate_Command_Confirm
{
    /**
     * @return int
     */
    public function getPaymentMethodCode()
    {
        return Braspag_Pagador_Model_Config::METHOD_BOLETO;
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
