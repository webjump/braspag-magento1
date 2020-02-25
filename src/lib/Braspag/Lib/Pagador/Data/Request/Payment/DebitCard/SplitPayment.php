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
class Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_SplitPayment
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_SplitPaymentInterface
{
    protected $subordinateMerchantId;
    protected $amount;
    protected $fares;

    /**
     * @return mixed
     */
    public function getSubordinateMerchantId()
    {
        return $this->subordinateMerchantId;
    }

    /**
     * @param mixed $subordinateMerchantId
     */
    public function setSubordinateMerchantId($subordinateMerchantId)
    {
        $this->subordinateMerchantId = $subordinateMerchantId;
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
    public function getFares()
    {
        return $this->fares;
    }

    /**
     * @param mixed $fares
     */
    public function setFares($fares)
    {
        $this->fares = $fares;
    }
}
