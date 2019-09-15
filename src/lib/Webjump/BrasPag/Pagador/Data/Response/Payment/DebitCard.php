<?php
/**
 * Pagador Data Response Payment DebitCard
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCard
    extends Webjump_BrasPag_Pagador_Data_Response_Payment_Abstract
    implements Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCardInterface
{
	protected $integrationType = 'TRANSACTION_DC';
    protected $authenticate;
    protected $returnUrl;
    protected $acquirerTransactionId;
    protected $softDescriptor;
    protected $velocityAnalysis;
    protected $externalAuthentication;
    protected $debitCard;

    /**
     * @return string
     */
    public function getIntegrationType()
    {
        return $this->integrationType;
    }

    /**
     * @return mixed
     */
    public function getAuthenticate()
    {
        return $this->authenticate;
    }

    /**
     * @param mixed $authenticate
     */
    public function setAuthenticate($authenticate)
    {
        $this->authenticate = $authenticate;
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
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    /**
     * @param mixed $softDescriptor
     */
    public function setSoftDescriptor($softDescriptor)
    {
        $this->softDescriptor = $softDescriptor;
    }

    /**
     * @return mixed
     */
    public function getVelocityAnalysis()
    {
        return $this->velocityAnalysis;
    }

    /**
     * @param mixed $velocityAnalysis
     */
    public function setVelocityAnalysis($velocityAnalysis)
    {
        $this->velocityAnalysis = $velocityAnalysis;
    }

    /**
     * @return mixed
     */
    public function getExternalAuthentication()
    {
        return $this->externalAuthentication;
    }

    /**
     * @param mixed $externalAuthentication
     */
    public function setExternalAuthentication($externalAuthentication)
    {
        $this->externalAuthentication = $externalAuthentication;
    }

    /**
     * @return mixed
     */
    public function getDebitCard()
    {
        return $this->debitCard;
    }

    /**
     * @param mixed $debitCard
     */
    public function setDebitCard($debitCard)
    {
        $this->debitCard = $debitCard;
    }
}
