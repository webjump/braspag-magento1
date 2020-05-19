<?php

class Braspag_Pagador_Model_PostNotification_ChangeType_StatusUpdate
    extends Braspag_Pagador_Model_PostNotification_AbstractChangeType
    implements Braspag_Pagador_Model_PostNotification_ChangeTypeInterface
{
    /**
     * @return int
     */
    public function getChangeTypeId()
    {
        return Braspag_Pagador_Model_PostNotification_ChangeTypeInterface::POST_NOTIFICATION_CHANGE_TYPE_STATUS_UPDATE;
    }

    /**
     * @param $transactionId
     * @param null $recurrentPaymentId
     * @param null $payment
     * @return mixed
     * @throws Exception
     */
    public function notify($transactionId, $recurrentPaymentId = null, $payment = null)
    {
        $braspagTransactionManager = Mage::getModel('braspag_pagador/payment_braspagTransactionManager');

        try{
            
            if (empty($payment)) {
                $magentoTransaction = $this->getTransactionManager()->loadByTxnId($transactionId);

                $payment = $magentoTransaction->getOrder()->getPayment();

                if (empty($magentoTransaction->getId())) {
                    throw new Exception('Invalid Transaction Id.');
                }
            }

            $braspagTransaction = $braspagTransactionManager->getTransactionByPaymentId($transactionId);

            if (empty($braspagTransaction->getPaymentId())) {
                Mage::throwException($this->getHelper()->__('Error: While retrieving transaction data'));
            }

            $transactionDataPayment = $braspagTransaction->getPayment();

            if (in_array($transactionDataPayment->getStatus(), [0])) {
                $errorMsg = 'There is no update for the payment.';
                Mage::throwException($this->getHelper()->__($errorMsg));
            }

            $statusUpdateCommandPool = $this->getBraspagCoreConfigHelper()
                ->getDefaultConfigPath('braspag_pagador/post_notification/change_type/composite/status_update/command');

            $statusToExecute = $this
                ->getCommandByStatusToExecute($statusUpdateCommandPool, $transactionDataPayment->getStatus());

            if (empty($statusToExecute)) {
                $errorMsg = 'Error: While retrieving transaction data';
                Mage::throwException($this->getHelper()->__($errorMsg));
            }

            $paymentMethodToExecute = $this->getCommandPaymentMethodToExecute($statusToExecute, $payment->getMethod());

            if (empty($paymentMethodToExecute)) {
                $errorMsg = 'Error: While retrieving transaction data';
                Mage::throwException($this->getHelper()->__($errorMsg));
            }

            return $paymentMethodToExecute->execute($payment, $transactionDataPayment);

        } catch(\Exception $e) {
            $order = $payment->getOrder();
            if ($order && $order->getId()) {
                $order->addStatusHistoryComment(
                    $this->getHelper()->__(
                        'Message from update action : %s.'
                        , $e->getMessage()
                    ), false);
                $order->save();
            }

            throw $e;
        }
    }

    /**
     * @param $statusUpdateCommandPool
     * @param $paymentStatus
     * @return |null
     */
    protected function getCommandByStatusToExecute($statusUpdateCommandPool, $paymentStatus)
    {
        foreach ($statusUpdateCommandPool as $command => $statusUpdateCommand) {

            $commandPath = 'braspag_pagador/post_notification/change_type/composite/status_update/command/'.$command;

            $commandClassModel = $this->getBraspagCoreConfigHelper()->getDefaultConfigClassModel($commandPath);

            if ($commandClassModel->getPaymentStatus() != $paymentStatus) {
                continue;
            }

            return  $this->getBraspagCoreConfigHelper()->getDefaultConfigClassComposite($commandPath);
        }

        return null;
    }

    /**
     * @param $commandComposite
     * @param $paymentMethod
     * @return |null
     */
    protected function getCommandPaymentMethodToExecute($commandComposite, $paymentMethod)
    {
        foreach ($commandComposite as $commandCompositeClassItem) {

            if ($paymentMethod != $commandCompositeClassItem->getPaymentMethodCode()) {
                continue;
            }

            return $commandCompositeClassItem;
        }

        return null;
    }
}
