<?php

class Braspag_Pagador_Model_PostNotification implements Braspag_Pagador_Model_PostNotificationInterface
{
    /**
     * @param $changeTypeId
     * @param $transactionId
     * @param $recurrentPaymentId
     * @return $this|mixed$transactionId
     */
    public function notify($changeTypeId, $transactionId, $recurrentPaymentId)
    {
        $postNotificationChangeTypes = Mage::helper('braspag_core/config')
            ->getDefaultConfigClassComposite('braspag_pagador/post_notification/change_type');

        $changeTypeComposite = Mage::getModel('braspag_pagador/postNotification_changeTypeComposite');

        foreach ($postNotificationChangeTypes as $postNotificationChangeType) {

            if ($postNotificationChangeType->getChangeTypeId() != $changeTypeId){
                continue;
            }

            $changeTypeComposite->addChangeType($postNotificationChangeType);
        }

        $changeTypeComposite->notifyAll($transactionId, $recurrentPaymentId);

        return $this;
    }
}
