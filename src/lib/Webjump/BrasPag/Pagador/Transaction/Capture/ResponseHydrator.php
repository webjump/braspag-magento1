<?php

class Webjump_BrasPag_Pagador_Transaction_Capture_ResponseHydrator
    implements Webjump_BrasPag_Pagador_Data_HydratorInterface
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
     * Webjump_BrasPag_Pagador_Transaction_Capture_ResponseHydrator constructor.
     * @param Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager
     */
    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = false;
        $this->dataBodyObject = $this->dataCustomerObject = $this->dataOrderObject = $this->dataPaymentObject= new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface $response
    )
    {
        $this->responseData = $data;
        $this->responseClass = $response;

        if (!$this->responseData || !$this->prepareBody()) {
            $this->errorMessages[] = json_encode([['Code' => 500, 'Message' => "Invalid Data Response"]]);
            $this->hydrateErrors();
            return $this;
        }

        if ($this->responseData->getStatus() != 200 && $this->responseData->getStatus() != 200) {

            $this->errorMessages[] = json_encode([[
                'Code' => $this->responseData->getStatus(),
                'Message' => $this->responseData->getMessage()]
            ]);

            foreach ($this->dataBodyObject->getData() as $data) {

                if (isset($data['Message'])) {
                    $this->errorMessages[] = json_encode([[
                        'Code' => $data['Code'],
                        'Message' => $data['Message']
                    ]]);
                }
            }

            $this->hydrateErrors();
            return $this;
        }

        if (in_array($this->dataPaymentObject->getData('Status'), [
            Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_NOT_FINISHED,
            Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED,
            Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_VOIDED,
            Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_REFUNDED,
            Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_ABORTED
        ])
        ) {

            $this->errorMessages[] = json_encode([
                [
                    'Code' => $this->dataPaymentObject->getData("ProviderReturnCode"),
                    'Message' => $this->dataPaymentObject->getData("ProviderReturnMessage")
                ]
            ]);

            $this->hydrateErrors();
            return $this;
        }

        if ($this->dataPaymentObject->getData('Status') == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED) {
            $this->responseStatus = true;
        }

        $this->hydrateDefault();

		return $this;
	}

    /**
     * @return bool
     */
	protected function prepareBody()
    {
        if (!$dataBody = json_decode($this->responseData->getBody(), HTTP_RAW_POST_DATA)) {
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
	protected function hydrateOrder()
    {
        $this->responseClass->getOrder()->populate($this->dataOrderObject->getData());

        return $this;
	}

    /**
     * @return $this
     * @throws Exception
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
     * @param null $error
     * @return $this
     */
	protected function hydrateErrors()
    {
        $this->responseClass->getErrorReport()->setErrors($this->errorMessages);
        return $this;
	}

    /**
     * @return Webjump_BrasPag_Pagador_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}