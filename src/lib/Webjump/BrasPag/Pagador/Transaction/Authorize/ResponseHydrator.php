<?php

class Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseHydrator
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
     * Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseHydrator constructor.
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
     * @param Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseInterface $response
    ){
        $this->responseData = $data;
        $this->responseClass = $response;

        if (!$this->responseData || !$this->prepareBody()) {
            $this->errorMessages[] = json_encode([['Code' => 500, 'Message' => "Invalid Data Response"]]);
            $this->hydrateErrors();
            return $this;
        }

        if ($this->responseData->getStatus() != 200 && $this->responseData->getStatus() != 201) {

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

        if (!$this->prepare()) {
            $this->errorMessages[] = json_encode([['Code' => 500, 'Message' => "Invalid Data Response"]]);

            $this->hydrateErrors();
            return $this;
        }

        $this->hydrateCustomer();
        $this->hydrateOrder();
        $this->hydratePayment();

        if (in_array($this->dataPaymentObject->getData('Status'), [
                Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED,
                Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_VOIDED,
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

        $this->responseStatus = true;

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
     * @return bool
     */
	protected function prepare()
    {

        if (!$paymentData = $this->dataBodyObject->getData('Payment')) {
            return false;
        }

        $this->dataPaymentObject->addData($paymentData);

        if (!$merchantOrderId = $this->dataBodyObject->getData('MerchantOrderId')) {
            return false;
        }

        $this->dataOrderObject->setData('orderId', $merchantOrderId);
        $this->dataOrderObject->setData('braspagOrderId', $this->dataPaymentObject->getData('PaymentId'));

        return true;
    }

    /**
     * @return $this
     */
	protected function hydrateDefault()
    {
        $this->responseClass->setPaymentId($this->dataPaymentObject->getData('PaymentId'));
        $this->responseClass->setSuccess($this->responseStatus);

        return $this;
	}

    /**
     * @return $this
     */
	protected function hydrateCustomer()
    {
        $this->responseClass->getCustomer()->populate($this->dataCustomerObject->getData('Customer'));

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

		    if ($paymentTypeData = $paymentDataResponse->getData($paymentDataResponse->getData('Type'))) {
		        if (isset($paymentTypeData['CardNumber'])) {
                    $paymentDataResponse->setData('maskedCreditCardNumber', $paymentTypeData['CardNumber']);
                }
            }

			$payment = $this->getPaymentTypeByDataResponse($paymentDataResponse);
            $payment->populate($paymentDataResponse->getData());

            $this->responseClass->getPayment()->set($payment);
		}

        return $this;
	}

    /**
     * @param $paymentDataResponse
     * @return mixed
     * @throws Exception
     */
	protected function getPaymentTypeByDataResponse($paymentDataResponse)
	{
		switch ($paymentDataResponse->getData('Type')) {
			case 'Boleto':
        	    return $this->getServiceManager()->get('Pagador\Data\Response\Payment\Boleto');
        	    break;
        	
        	case 'CreditCard':
        	    return $this->getServiceManager()->get('Pagador\Data\Response\Payment\CreditCard');
        	    break;
        	
        	case 'DebitCard':
        	    return $this->getServiceManager()->get('Pagador\Data\Response\Payment\DebitCard');
        	    break;
        	
        	default:
	            throw new Exception(sprintf('Invalid PaymentDataResponse type: %1$s', $paymentDataResponse->getData('Type')));
        		break;
		}
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