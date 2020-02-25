<?php
/**
 * Pagador Data Payment CreditCard
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard
    extends Braspag_Lib_Pagador_Data_Request_Payment
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCardInterface
{
    protected $installments;
    protected $interest;
    protected $capture;
    protected $authenticate;
    protected $externalAuthentication;
    protected $recurrent;
    protected $softDescriptor;
    protected $doSplit;
    protected $card;
    protected $fraudAnalysis;
    protected $splitPayments;

    const METHOD = 'braspag_pagador_cc';
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
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
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
    public function setFraudAnalysis($fraudAnalysis)
    {
        $this->fraudAnalysis = $fraudAnalysis;
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

    public function getArrayCopy()
    {
        $data = parent::getArrayCopy();

        return $data;
    }
}
