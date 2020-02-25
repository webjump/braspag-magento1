<?php
/**
 * Pagador Method Authorize response
 *
 * @category  Method
 * @package   Braspag_Lib_PagadorQuery_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface
{
    protected $success;
    protected $errorReport;
    protected $reasonCode;
    protected $reasonMessage;
    protected $payments;

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

    public function getPayments()
    {
        return $this->payments;
    }

    public function setPayments(array $payments = [])
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param mixed $reasonCode
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
    }

    /**
     * @return mixed
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * @param mixed $reasonMessage
     */
    public function setReasonMessage($reasonMessage)
    {
        $this->reasonMessage = $reasonMessage;
    }
}
