<?php
/**
 * Pagador Data Response Transaction
 *
 * @category  Data
 * @package   Braspag_Lib_PagadorQuery_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Data_Response_Transaction extends Braspag_Lib_Core_Data_Abstract implements Braspag_Lib_PagadorQuery_Data_Response_TransactionInterface
{
    protected $payments;
    protected $reasonCode;
    protected $reasonMessage;
    protected $status;

    /**
     * @return mixed
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param mixed $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
