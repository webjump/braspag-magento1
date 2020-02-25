<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_ChargeBack
    extends Braspag_Pagador_Model_PostNotification_AbstractChangeType
    implements Braspag_Pagador_Model_PostNotification_ChangeTypeInterface
{
    /**
     * @return int
     */
    public function getChangeTypeId()
    {
        return Braspag_Pagador_Model_PostNotification_ChangeTypeInterface::POST_NOTIFICATION_CHANGE_TYPE_CHARGE_BACK;
    }

    /**
     * @param $transactionId
     * @param $recurrentPaymentId
     * @return $this|mixed
     */
    public function notify($transactionId, $recurrentPaymentId)
    {
        return $this->getPaymentManager()->loadByTransactionId($transactionId);
    }
}
