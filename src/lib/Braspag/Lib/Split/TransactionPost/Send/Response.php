<?php
/**
 * Split Method TransactionPostorize response
 *
 * @category  Method
 * @package   Braspag_Lib_Split_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Split_TransactionPost_Send_Response
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Split_TransactionPost_Send_ResponseInterface
{
    protected $paymentId;
    protected $splitPayments;
    protected $errorReport;

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return mixed
     */
    public function getSplitPayments()
    {
        return $this->splitPayments;
    }

    /**
     * @param mixed $splitPayments
     */
    public function setSplitPayments($splitPayments)
    {
        $this->splitPayments = $splitPayments;
    }

    /**
     * @return mixed
     */
    public function getErrorReport()
    {
        return $this->errorReport;
    }

    /**
     * @param mixed $errorReport
     */
    public function setErrorReport($errorReport)
    {
        $this->errorReport = $errorReport;
    }
}
