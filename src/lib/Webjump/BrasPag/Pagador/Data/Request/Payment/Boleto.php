<?php
/**
 * Pagador Data Payment Boleto
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Request_Payment_Boleto
    extends Webjump_BrasPag_Pagador_Data_Request_Payment_Abstract
    implements Webjump_BrasPag_Pagador_Data_Request_Payment_BoletoInterface
{
    protected $boletoNumber;
    protected $instructions;
    protected $expirationDate;
    protected $assignor;
    protected $demonstrative;
    protected $identification;
    protected $daysToFine;
    protected $fineRate;
    protected $fineAmount;
    protected $daysToInterest;
    protected $interestRate;
    protected $interestAmount;

    const METHOD = 'webjump_braspag_boleto';
    const TYPE = 'Boleto';

    public function getBoletoNumber()
    {
        return $this->boletoNumber;
    }

    public function setBoletoNumber($boletoNumber)
    {
        $this->boletoNumber = $boletoNumber;

        return $this;
    }

    public function getInstructions()
    {
        return $this->instructions;
    }

    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
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
    public function getDaysToFine()
    {
        return $this->daysToFine;
    }

    /**
     * @param mixed $daysToFine
     */
    public function setDaysToFine($daysToFine)
    {
        $this->daysToFine = $daysToFine;
    }

    /**
     * @return mixed
     */
    public function getFineRate()
    {
        return $this->fineRate;
    }

    /**
     * @param mixed $fineRate
     */
    public function setFineRate($fineRate)
    {
        $this->fineRate = $fineRate;
    }

    /**
     * @return mixed
     */
    public function getFineAmount()
    {
        return $this->fineAmount;
    }

    /**
     * @param mixed $fineAmount
     */
    public function setFineAmount($fineAmount)
    {
        $this->fineAmount = $fineAmount;
    }

    /**
     * @return mixed
     */
    public function getDaysToInterest()
    {
        return $this->daysToInterest;
    }

    /**
     * @param mixed $daysToInterest
     */
    public function setDaysToInterest($daysToInterest)
    {
        $this->daysToInterest = $daysToInterest;
    }

    /**
     * @return mixed
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * @param mixed $interestRate
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
    }

    /**
     * @return mixed
     */
    public function getInterestAmount()
    {
        return $this->interestAmount;
    }

    /**
     * @param mixed $interestAmount
     */
    public function setInterestAmount($interestAmount)
    {
        $this->interestAmount = $interestAmount;
    }
}
