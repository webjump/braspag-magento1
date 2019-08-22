<?php
/**
 * Pagador Data Response Payment CreditCard
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard
    extends Webjump_BrasPag_Pagador_Data_Response_Payment_Abstract
    implements Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCardInterface
{
	protected $integrationType = 'TRANSACTION_CC';
    protected $acquirerTransactionId;
    protected $authorizationCode;
    protected $providerReturnCode;
    protected $providerReturnMessage;
    protected $proofOfSale;
    protected $status;
    protected $cardToken;
    protected $serviceTaxAmount;
    protected $maskedCreditCardNumber;

    public function getAcquirerTransactionId()
    {
        return $this->acquirerTransactionId;
    }

    public function setAcquirerTransactionId($acquirerTransactionId)
    {
        $this->acquirerTransactionId = $acquirerTransactionId;

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

    public function getProviderReturnCode()
    {
        return $this->providerReturnCode;
    }

    public function setProviderReturnCode($providerReturnCode)
    {
        $this->providerReturnCode = $providerReturnCode;

        return $this;
    }

    public function getProviderReturnMessage()
    {
        return $this->providerReturnMessage;
    }

    public function setProviderReturnMessage($providerReturnMessage)
    {
        $this->providerReturnMessage = $this->convertEncode($providerReturnMessage);

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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = (int) $status;

        return $this;
    }

    public function getCardToken()
    {
        return $this->cardToken;
    }

    public function setCardToken($cardToken)
    {
        $this->cardToken = (string) $cardToken;

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

    public function getMaskedCreditCardNumber()
    {
        return $this->maskedCreditCardNumber;
    }

    protected function setMaskedCreditCardNumber($maskedCreditCardNumber)
    {
        $this->maskedCreditCardNumber = $maskedCreditCardNumber;

        return $this;
    }
}
