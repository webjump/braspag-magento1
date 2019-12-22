<?php
class Webjump_BraspagPagador_Model_Pagador_Billet_Command_OrderCommand
    extends Webjump_BraspagPagador_Model_Pagador_OrderAbstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_billet_resource_order_request_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getResponseValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_billet_resource_order_response_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_billet_resource_order_requestBuilder');
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager($this->getConfigData());
    }

    /**
     * @return mixed
     */
    protected function getConfigData()
    {
        return Mage::getModel('webjump_braspag_pagador/config')->getConfig();
    }

    /**
     * @param $response
     * @param $payment
     * @return $this
     * @throws Exception
     */
    protected function processResponse($response, $payment)
    {
        if (!$payment) {
            throw new \Exception("Invalid Payment Instance");
        }

        $paymentDataResponse = $response->getPayment()->get();

        $errorMsg = [];
        if (empty($paymentDataResponse->getUrl())) {

            if (!empty($paymentDataResponse->getMessage())) {
                $errorMsg[] = $this->getHelper()->__($paymentDataResponse->getMessage());
            } else {
                $errorMsg[] = $this->getHelper()->__('An error occurs while generating the billet.');
            }
        }

        $this->saveRawDetails($paymentDataResponse, $payment);
        $this->saveInfoData($paymentDataResponse, $payment);

        $payment
            ->setTransactionId($response->getOrder()->getBraspagOrderId())
            ->setIsTransactionClosed(0);

        $this->saveErrors($errorMsg, $paymentDataResponse, $payment);

        if ($payment->getIsTransactionPending()){
            $message = $this->getHelper()->__('Waiting response from acquirer.');
            $payment->getOrder()->setState(\Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true, $message);
        }

        return $this;
    }

}
