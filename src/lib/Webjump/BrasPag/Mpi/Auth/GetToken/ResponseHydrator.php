<?php

class Webjump_BrasPag_Mpi_Auth_GetToken_ResponseHydrator
    implements Webjump_BrasPag_Mpi_Data_HydratorInterface
{
    protected $serviceManager;
    protected $responseData;
    protected $responseClass;
    protected $responseStatus;
    protected $dataBodyObject;

    /**
     * Webjump_BrasPag_Mpi_Auth_GetToken_ResponseHydrator constructor.
     * @param Webjump_BrasPag_Mpi_Service_ServiceManagerInterface $serviceManager
     */
    public function __construct(Webjump_BrasPag_Mpi_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = true;
        $this->dataBodyObject = new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Webjump_BrasPag_Mpi_Auth_GetToken_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Mpi_Auth_GetToken_ResponseInterface $response
    ){
        $this->responseData = $data;
        $this->responseClass = $response;

        if ($this->responseData->getStatus() != 200 && $this->responseData->getStatus() != 201) {
            $this->responseStatus = false;
            $this->hydrateErrors();
            return $this;
        }

        if (!$this->prepare()) {
            $this->hydrateErrors(['Code' => 500, 'Message' => "Invalid Data Response"]);
            return $this;
        }

        $this->responseClass->setSuccess($this->responseStatus);
        $this->responseClass->setAccessToken($this->dataBodyObject->getAccessToken());
        $this->responseClass->setTokenType($this->dataBodyObject->getTokenType());
        $this->responseClass->setExpiresIn($this->dataBodyObject->getExpiresIn());

		return $this;
	}

    /**
     * @return bool
     */
	protected function prepare()
    {
        $this->dataBodyObject->addData(json_decode($this->responseData->getBody(), HTTP_RAW_POST_DATA));

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
            $errorMessages[] = json_encode([['Code' => $this->responseData->getStatus(), 'Message' => $this->responseData->getMessage()]]);
        }

        $errorMessages[] = $this->responseData->getBody();

        $this->responseClass->getErrorReport()->setErrors($errorMessages);

        return $this;
	}

    /**
     * @return Webjump_BrasPag_Mpi_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}