<?php

class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response_Hydrator
    implements Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response_HydratorInterface
{
    protected $serviceManager;
    protected $responseData;
    protected $responseClass;
    protected $responseStatus;
    protected $dataBodyObject;
    protected $errorMessages = [];

    /**
     * Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response_Hydrator constructor.
     * @param Braspag_Lib_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Braspag_Lib_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = false;
        $this->dataBodyObject = new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface $response
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

        if (!$this->prepare()) {
            throw new \Exception("Invalid Response Data.");
        }

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
     * @return bool
     */
	protected function prepare()
    {
        if (!$paymentsData = $this->dataBodyObject->getData('Payments')) {
            return false;
        }

        $this->dataBodyObject->addData($paymentsData);

        return true;
    }

    /**
     * @return $this
     */
	protected function hydrateDefault()
    {
        $this->responseClass->setPayments($this->dataBodyObject->getData('Payments'));
        $this->responseClass->setReasonCode($this->dataBodyObject->getData('ReasonCode'));
        $this->responseClass->setReasonMessage($this->dataBodyObject->getData('ReasonMessage'));
        $this->responseClass->setSuccess($this->responseStatus);

        return $this;
	}

    /**
     * @return Braspag_Lib_PagadorQuery_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}