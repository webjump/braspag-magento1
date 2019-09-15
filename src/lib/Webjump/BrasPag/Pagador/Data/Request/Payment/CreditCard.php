<?php
/**
 * Pagador Data Payment CreditCard
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard
    extends Webjump_BrasPag_Pagador_Data_Request_Payment_Abstract
    implements Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCardInterface
{
    public $installments;
    public $interest;
    public $capture;
    public $authenticate;
    public $externalAuthentication;
    public $recurrent;
    public $softDescriptor;
    public $doSplit;
    public $cardHolder;
    public $cardNumber;
    public $cardSecurityCode;
    public $cardExpirationDate;
    public $cardToken;
    public $cardBrand;
    public $saveCard;
    public $cardAlias;
    public $fraudAnalysis;

    const METHOD = 'webjump_braspag_cc';
    const MINIMUM_NUMBER_OF_INSTALLMENTS = 1;

    /**
     * @return mixed
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @param mixed $installments
     */
    public function setInstallments($installments)
    {
        $this->installments = $installments;
    }

    /**
     * @return mixed
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * @param mixed $interest
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;
    }

    /**
     * @return mixed
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @param mixed $capture
     */
    public function setCapture($capture)
    {
        $this->capture = $capture;
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
    public function getRecurrent()
    {
        return $this->recurrent;
    }

    /**
     * @param mixed $recurrent
     */
    public function setRecurrent($recurrent)
    {
        $this->recurrent = $recurrent;
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
    public function getDoSplit()
    {
        return $this->doSplit;
    }

    /**
     * @param mixed $doSplit
     */
    public function setDoSplit($doSplit)
    {
        $this->doSplit = $doSplit;
    }

    /**
     * @return mixed
     */
    public function getCardHolder()
    {
        return $this->cardHolder;
    }

    /**
     * @param mixed $cardHolder
     */
    public function setCardHolder($cardHolder)
    {
        $this->cardHolder = $cardHolder;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return mixed
     */
    public function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    /**
     * @param mixed $cardSecurityCode
     */
    public function setCardSecurityCode($cardSecurityCode)
    {
        $this->cardSecurityCode = $cardSecurityCode;
    }

    /**
     * @return mixed
     */
    public function getCardExpirationDate()
    {
        return $this->cardExpirationDate;
    }

    /**
     * @param mixed $cardExpirationDate
     */
    public function setCardExpirationDate($cardExpirationDate)
    {
        $this->cardExpirationDate = $cardExpirationDate;
    }

    /**
     * @return mixed
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * @param mixed $cardToken
     */
    public function setCardToken($cardToken)
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @return mixed
     */
    public function getCardBrand()
    {
        return $this->cardBrand;
    }

    /**
     * @param mixed $cardBrand
     */
    public function setCardBrand($cardBrand)
    {
        $this->cardBrand = $cardBrand;
    }

    /**
     * @return mixed
     */
    public function getSaveCard()
    {
        return $this->saveCard;
    }

    /**
     * @param mixed $saveCard
     */
    public function setSaveCard($saveCard)
    {
        $this->saveCard = $saveCard;
    }

    /**
     * @return mixed
     */
    public function getCardAlias()
    {
        return $this->cardAlias;
    }

    /**
     * @param mixed $cardAlias
     */
    public function setCardAlias($cardAlias)
    {
        $this->cardAlias = $cardAlias;
    }

    /**
     * @return mixed
     */
    public function getFraudAnalysis()
    {
        return $this->fraudAnalysis;
    }

    /**
     * @param Varien_Object $fraudAnalysis
     */
    public function setFraudAnalysis(Varien_Object $fraudAnalysis)
    {
        $this->fraudAnalysis = $fraudAnalysis;
    }

    public function getArrayCopy()
    {
        $data = parent::getArrayCopy();

        if ($this->getCardToken()) {
            $data['cardNumber'] = null;
            $data['cardHolder'] = null;
            $data['saveCreditCard'] = false;
        }

        return $data;
    }
}
