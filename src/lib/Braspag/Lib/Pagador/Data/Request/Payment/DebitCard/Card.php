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
class Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_Card
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_CardInterface
{
    protected $cardHolder;
    protected $cardNumber;
    protected $cardSecurityCode;
    protected $cardExpirationDate;
    protected $cardToken;
    protected $cardBrand;
    protected $saveCard;
    protected $cardAlias;

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
}
