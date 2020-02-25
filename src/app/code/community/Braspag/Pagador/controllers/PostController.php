<?php
class Braspag_Pagador_PostController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return Zend_Controller_Response_Abstract
     * @throws Zend_Controller_Response_Exception
     */
    public function notificationAction()
    {
        $postNotification = Mage::getModel('braspag_pagador/postNotification');

        try {
            $data = json_decode($this->getRequest()->getRawBody());

            $transactionId = $data->PaymentId;
            $changeType = intval($data->ChangeType);
            $recurrentPaymentId = $data->RecurrentPaymentId;

            $postNotification->notify($changeType, $transactionId, $recurrentPaymentId);
            
        } catch (Exception $e) {

            Mage::helper('braspag_core')
                ->debug($e->getMessage())
                ->debug($this->getRequest()->getPost());

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
