<?php
/**
 * Pagador Data Payment Abstract
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BrasPag_Pagador_Data_Response_Payment_Abstract
    extends Webjump_BrasPag_Core_Data_Abstract
{
    protected $braspagOrderId;
    protected $amount;
    protected $type;
    protected $provider;
    protected $reasonCode;
    protected $reasonMessage;
    protected $status;
    protected $providerReturnCode;
    protected $providerReturnMessage;
    protected $paymentId;
    protected $receivedDate;
    protected $currency;
    protected $country;
    protected $links;

    /**
     * @return mixed
     */
    public function getBraspagOrderId()
    {
        return $this->braspagOrderId;
    }

    /**
     * @param mixed $braspagOrderId
     */
    public function setBraspagOrderId($braspagOrderId)
    {
        $this->braspagOrderId = $braspagOrderId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
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

    /**
     * @return mixed
     */
    public function getProviderReturnCode()
    {
        return $this->providerReturnCode;
    }

    /**
     * @param mixed $providerReturnCode
     */
    public function setProviderReturnCode($providerReturnCode)
    {
        $this->providerReturnCode = $providerReturnCode;
    }

    /**
     * @return mixed
     */
    public function getProviderReturnMessage()
    {
        return $this->providerReturnMessage;
    }

    /**
     * @param mixed $providerReturnMessage
     */
    public function setProviderReturnMessage($providerReturnMessage)
    {
        $this->providerReturnMessage = $providerReturnMessage;
    }

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
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    /**
     * @param mixed $receivedDate
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param mixed $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }
}
