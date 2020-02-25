<?php

interface Braspag_Pagador_Model_PostNotificationInterface
{
    /**
     * @param $changeTypeId
     * @param $paymentId
     * @param $recurrentPaymentId
     * @return mixed
     */
    public function notify($changeTypeId, $paymentId, $recurrentPaymentId);
}
