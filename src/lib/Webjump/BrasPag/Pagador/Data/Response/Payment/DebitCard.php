<?php
/**
 * Pagador Data Response Payment DebitCard
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCard
    extends Webjump_BrasPag_Pagador_Data_Response_Payment_Abstract
    implements Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCardInterface
{
	protected $integrationType = 'TRANSACTION_DC';
    protected $authenticationUrl;
    protected $acquirerTransactionId;
    protected $paymentId;
    protected $receivedDate;
    protected $currency;
    protected $country;
    protected $provider;
    protected $returnUrl;
    protected $reasonCode;
    protected $reasonMessage;
    protected $status;
    protected $providerReturnCode;

    /**
     * @return mixed
     */
    public function getAuthenticationUrl()
    {
        return $this->authenticationUrl;
    }

    /**
     * @param mixed $authenticationUrl
     */
    public function setAuthenticationUrl($authenticationUrl)
    {
        $this->authenticationUrl = $authenticationUrl;
    }

    /**
     * @return mixed
     */
    public function getAcquirerTransactionId()
    {
        return $this->acquirerTransactionId;
    }

    /**
     * @param mixed $acquirerTransactionId
     */
    public function setAcquirerTransactionId($acquirerTransactionId)
    {
        $this->acquirerTransactionId = $acquirerTransactionId;
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
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
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
}
