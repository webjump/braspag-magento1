<?php
/**
 * Pagador Method Capture response
 *
 * @category  Method
 * @package   Braspag_Lib_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Transaction_Capture_Response
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Transaction_Capture_ResponseInterface
{
    protected $paymentId;
    protected $success;
    protected $errorReport;
    protected $order;
    protected $customer;
    protected $payment;

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function isSuccess()
    {
        return (boolean) $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = (filter_var($success, FILTER_VALIDATE_BOOLEAN));

        return $this;
    }

    public function getErrorReport()
    {
        return $this->errorReport;
    }

    public function setErrorReport($errorReport)
    {
        $this->errorReport = $errorReport;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment(Braspag_Lib_Pagador_Data_Response_Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

	public function getDataAsArray()
    {
    	$data = parent::getDataAsArray();
    	
    	if (!empty($data['payment']['payment'])) {
    		$data['payment'] = $data['payment']['payment'];
    	}
        
    	return $data;
    }

    public function getData($field = '')
    {
        $data = parent::getDataAsArray();

        $dataObject = new Varien_Object();
        $dataObject->addData($data);

        if (!empty($field)) {
            return $dataObject->getData($field);
        }

        return $dataObject;
    }
}
