<?php

interface Braspag_Pagador_Model_PostNotification_ChangeTypeInterface
{
    const POST_NOTIFICATION_CHANGE_TYPE_STATUS_UPDATE = 1;
    const POST_NOTIFICATION_CHANGE_TYPE_RECURRENCE_CREATED = 2;
    const POST_NOTIFICATION_CHANGE_TYPE_ANTI_FRAUD_STATUS_UPDATE = 3;
    const POST_NOTIFICATION_CHANGE_TYPE_RECURRENCE_STATUS_UPDATE = 4;
    const POST_NOTIFICATION_CHANGE_TYPE_REFUND_DENIED = 5;
    const POST_NOTIFICATION_CHANGE_TYPE_BOLETO_REGISTRADO_UNDERPAID = 6;
    const POST_NOTIFICATION_CHANGE_TYPE_CHARGE_BACK = 7;
    const POST_NOTIFICATION_CHANGE_TYPE_FRAUD_ALERT = 8;

    /**
     * @param $paymentId
     * @param $recurrentPaymentId
     * @return mixed
     */
    public function notify($paymentId, $recurrentPaymentId);

    /**
     * @return int
     */
    public function getChangeTypeId();
}
