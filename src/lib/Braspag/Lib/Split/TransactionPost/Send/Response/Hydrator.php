<?php

class Braspag_Lib_Split_TransactionPost_Send_Response_Hydrator
    implements Braspag_Lib_Split_TransactionPost_Send_Response_HydratorInterface
{
    protected $serviceManager;
    protected $responseData;
    protected $responseClass;
    protected $responseStatus;
    protected $dataBodyObject;

    /**
     * Braspag_Lib_Split_TransactionPost_Send_ResponseHydrator constructor.
     * @param Braspag_Lib_Split_Service_ServiceManagerInterface $serviceManager
     */
    public function __construct(Braspag_Lib_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = true;
        $this->dataBodyObject = new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Braspag_Lib_Split_TransactionPost_Send_ResponseInterface $response
     * @return $this
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Split_TransactionPost_Send_ResponseInterface $response
    ){
        $this->responseData = $data;
        $this->responseClass = $response;

        if (!$this->responseData) {
            $this->responseStatus = false;
            return $this;
        }

        if ($this->responseData->getStatus() != 200 && $this->responseData->getStatus() != 201) {
            $this->responseStatus = false;
            $this->hydrateErrors();
            return $this;
        }

        if (!$this->prepare()) {
            $this->hydrateErrors(['Code' => 500, 'Message' => "Invalid Data Response"]);
            return $this;
        }

        $this->responseClass->setPaymentId($this->dataBodyObject->getPaymentId());
        $this->responseClass->setSplitPayments($this->dataBodyObject->getSplitPayments());

		return $this;
	}

    /**
     * @return bool
     */
	protected function prepare()
    {
        $this->dataBodyObject->addData(json_decode($this->responseData->getBody(), true));

        if (!$paymentData = $this->dataBodyObject->getData()) {
            return false;
        }

        $this->dataBodyObject->addData($paymentData);

        return true;
    }

    /**
     * @param null $error
     * @return $this
     */
	protected function hydrateErrors($error = null)
    {
		$errorMessages = array();

		if (!empty($error)) {
            $errorMessages[] = json_encode([$error]);
        }

        if (empty($this->responseData->getBody())) {
            $errorMessages[] = json_encode([
                [
                    'Code' => $this->responseData->getStatus(),
                    'Message' => $this->responseData->getMessage()
                ]
            ]);
        }

        $errorMessages[] = $this->responseData->getBody();

        $this->responseClass->getErrorReport()->setErrors($errorMessages);

        return $this;
	}

    /**
     * @return Braspag_Lib_Split_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}