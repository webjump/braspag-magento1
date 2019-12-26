<?php

class Webjump_BrasPag_Pagador_Transaction_Void_Response_Hydrator
    implements Webjump_BrasPag_Pagador_Transaction_Void_Response_HydratorInterface
{
    protected $serviceManager;
    protected $responseData;
    protected $responseClass;
    protected $responseStatus;
    protected $dataBodyObject;
    protected $dataCustomerObject;
    protected $dataOrderObject;
    protected $dataPaymentObject;
    protected $errorMessages = [];

    /**
     * Webjump_BrasPag_Pagador_Transaction_Void_Response_Hydrator constructor.
     * @param Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = false;
        $this->dataBodyObject = $this->dataCustomerObject = $this->dataOrderObject = $this->dataPaymentObject= new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
    public function hydrate(
        \Zend_Http_Response $data,
        Webjump_BrasPag_Pagador_Transaction_Void_ResponseInterface $response
    ){
        $this->responseData = $data;
        $this->responseClass = $response;

        if (!$this->responseData) {
            throw new \Exception("Invalid Request, try again.");
        }

        if (!$this->prepareBody()) {
            throw new \Exception("(Code {$this->responseData->getStatus()}) ".$this->responseData->getMessage());
        }

        if ($this->responseData->getStatus() != 200 && $this->responseData->getStatus() != 201) {

            $errorData = array_map(function ($data){
                if (!isset($data['Message'])) {
                    return null;
                }

                return "(Code ".$data['Code'].") ".$data['Message'];
            }, $this->dataBodyObject->getData());

            throw new \Exception(implode("\n", $errorData));
        }

        $this->hydratePayment();

        $this->responseStatus = true;

        $this->hydrateDefault();

        return $this;
    }

    /**
     * @return bool
     */
    protected function prepareBody()
    {
        if (!$dataBody = json_decode($this->responseData->getBody(), true)) {
            return false;
        }

        $this->dataBodyObject->addData($dataBody);

        return true;
    }

    /**
     * @return $this
     */
    protected function hydrateDefault()
    {
        $this->responseClass->setSuccess($this->responseStatus);

        return $this;
    }

    /**
     * @return $this
     */
    protected function hydratePayment()
    {
        if ($paymentDataResponse = $this->dataPaymentObject) {

            $payment = $this->getServiceManager()->get('Pagador\Data\Response\Payment\CreditCard');
            $payment->populate($paymentDataResponse->getData());

            $this->responseClass->getPayment()->set($payment);
        }

        return $this;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_ManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}