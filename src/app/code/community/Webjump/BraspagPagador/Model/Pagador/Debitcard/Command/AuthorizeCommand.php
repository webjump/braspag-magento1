<?php
class Webjump_BraspagPagador_Model_Pagador_Debitcard_Command_AuthorizeCommand
    extends Webjump_BraspagPagador_Model_Pagador_AuthorizeAbstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_debitcard_resource_authorize_request_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getResponseValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_debitcard_resource_authorize_response_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_debitcard_resource_authorize_requestBuilder');
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
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

    /**
     * @param $response
     * @return $this
     * @throws Exception
     */
    protected function processResponse($response, $payment)
    {
        if (!$payment) {
            throw new \Exception("Invalid Payment Instance");
        }
        $errors = [];
        $paymentDataResponse = $response->getPayment()->get();

        $payment->setIsTransactionPending(true);

        $this->saveInfoData($paymentDataResponse, $payment);
        $this->saveRawDetails($paymentDataResponse, $payment);

        $payment
            ->setTransactionId($response->getOrder()->getBraspagOrderId())
            ->setIsTransactionClosed(0);

        $this->saveErrors($errors, $paymentDataResponse, $payment);

        return $this;
    }
}
