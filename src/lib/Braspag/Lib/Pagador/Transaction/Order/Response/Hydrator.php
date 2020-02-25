<?php

class Braspag_Lib_Pagador_Transaction_Order_Response_Hydrator
    implements Braspag_Lib_Pagador_Transaction_Order_Response_HydratorInterface
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
     * Braspag_Lib_Pagador_Transaction_Order_Response_Hydrator constructor.
     * @param Braspag_Lib_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Braspag_Lib_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->responseStatus = false;
        $this->dataBodyObject = $this->dataCustomerObject = $this->dataOrderObject = $this->dataPaymentObject= new Varien_Object();
    }

    /**
     * @param Zend_Http_Response $data
     * @param Braspag_Lib_Pagador_Transaction_Order_ResponseInterface $response
     * @return $this
     * @throws Exception
     */
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Pagador_Transaction_Order_ResponseInterface $response
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

        $this->hydrateCustomer();
        $this->hydrateOrder();
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

            $this->responseClass->setPayment($payment);
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
     * @return Braspag_Lib_Pagador_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}