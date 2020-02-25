<?php
/**
 * Pagador Data Response Payment Boleto
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Response_Payment_Boleto
    extends Braspag_Lib_Pagador_Data_Response_Payment
    implements Braspag_Lib_Pagador_Data_Response_Payment_BoletoInterface
{
	protected $integrationType = 'TRANSACTION_BOLETO';
    protected $instructions;
    protected $expirationDate;
    protected $demonstrative;
    protected $url;
    protected $boletoNumber;
    protected $barCodeNumber;
    protected $digitableLine;
    protected $assignor;
    protected $address;
    protected $identification;
    protected $isRecurring;
    protected $receivedDate;

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
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param mixed $instructions
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return mixed
     */
    public function getDemonstrative()
    {
        return $this->demonstrative;
    }

    /**
     * @param mixed $demonstrative
     */
    public function setDemonstrative($demonstrative)
    {
        $this->demonstrative = $demonstrative;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getBoletoNumber()
    {
        return $this->boletoNumber;
    }

    /**
     * @param mixed $boletoNumber
     */
    public function setBoletoNumber($boletoNumber)
    {
        $this->boletoNumber = $boletoNumber;
    }

    /**
     * @return mixed
     */
    public function getBarCodeNumber()
    {
        return $this->barCodeNumber;
    }

    /**
     * @param mixed $barCodeNumber
     */
    public function setBarCodeNumber($barCodeNumber)
    {
        $this->barCodeNumber = $barCodeNumber;
    }

    /**
     * @return mixed
     */
    public function getDigitableLine()
    {
        return $this->digitableLine;
    }

    /**
     * @param mixed $digitableLine
     */
    public function setDigitableLine($digitableLine)
    {
        $this->digitableLine = $digitableLine;
    }

    /**
     * @return mixed
     */
    public function getAssignor()
    {
        return $this->assignor;
    }

    /**
     * @param mixed $assignor
     */
    public function setAssignor($assignor)
    {
        $this->assignor = $assignor;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @param mixed $identification
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
    }

    /**
     * @return mixed
     */
    public function getisRecurring()
    {
        return $this->isRecurring;
    }

    /**
     * @param mixed $isRecurring
     */
    public function setIsRecurring($isRecurring)
    {
        $this->isRecurring = $isRecurring;
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
}
