<?php
/**
 * Pagador Data Response Transaction
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_Transaction extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Response_TransactionInterface
{
    protected $braspagTransactionId;
    protected $acquirerTransactionId;
    protected $amount;
    protected $authorizationCode;
    protected $proofOfSale;
    protected $returnCode;
    protected $returnMessage;
    protected $status;
    protected $serviceTaxAmount;

    public function getBraspagTransactionId()
    {
        return $this->braspagTransactionId;
    }

    public function setBraspagTransactionId($braspagTransactionId)
    {
        $this->braspagTransactionId = $braspagTransactionId;

        return $this;
    }

    public function getAcquirerTransactionId()
    {
        return $this->acquirerTransactionId;
    }

    public function setAcquirerTransactionId($acquirerTransactionId)
    {
        $this->acquirerTransactionId = $acquirerTransactionId;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    public function getProofOfSale()
    {
        return $this->proofOfSale;
    }

    public function setProofOfSale($proofOfSale)
    {
        $this->proofOfSale = $proofOfSale;

        return $this;
    }

    public function getReturnCode()
    {
        return $this->returnCode;
    }

    public function setReturnCode($returnCode)
    {
        $this->returnCode = $returnCode;

        return $this;
    }

    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    public function setReturnMessage($returnMessage)
    {
        $this->returnMessage = $returnMessage;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;

        return $this;
    }
}
