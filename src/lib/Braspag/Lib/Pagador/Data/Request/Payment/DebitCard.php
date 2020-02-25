<?php
/**
 * Pagador Data Payment DebitCard
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Request_Payment_DebitCard
    extends Braspag_Lib_Pagador_Data_Request_Payment
    implements Braspag_Lib_Pagador_Data_Request_Payment_DebitCardInterface
{
    public $interest;
    public $installments;
    public $capture;
    public $authenticate;
    public $externalAuthentication;
    public $recurrent;
    public $card;
    public $softDescriptor;
    public $returnUrl;

    const METHOD = 'Braspag_Lib_dc';

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
}