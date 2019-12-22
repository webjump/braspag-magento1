<?php
class Webjump_BraspagPagador_StatusController extends Mage_Core_Controller_Front_Action
{
    public function updateAction()
    {
        $statusUpdate = Mage::getModel('webjump_braspag_pagador/status_update');
        
        try {
            $data = json_decode($this->getRequest()->getRawBody());

            $paymentId = $data->PaymentId;
            $changeType = $data->ChangeType;
            $recurrentPaymentId = $data->RecurrentPaymentId;

            $statusUpdate->process($paymentId, $changeType, $recurrentPaymentId);
        } catch (Exception $e) {

            $pagadorHelper = Mage::helper('webjump_braspag_pagador');
            $pagadorHelper->debug($e->getMessage());
            $pagadorHelper->debug($this->getRequest()->getPost());

            $order = $statusUpdate->getOrder();
            if ($order && $order->getId()) {
                $order->addStatusHistoryComment(
                    $pagadorHelper->
                    __(
                        'Order status updated (2º Post): Exception occurred during update action. Exception message: %s.'
                        , $e->getMessage()
                    )
                , false);
                $order->save();
            }
            $this->getResponse()->setHeader('Content-Type', 'application/json');
            $this->getResponse()->setBody(
                json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ])
            );

            $code = $e->getCode();

            if (empty($code)) {
                $code = 500;
            }

            return $this->getResponse()->setHttpResponseCode($code);
        }

        return $this->getResponse()->setHttpResponseCode(200);
    }
}
