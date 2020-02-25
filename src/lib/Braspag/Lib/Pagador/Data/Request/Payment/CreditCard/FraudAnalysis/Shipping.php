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
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Shipping
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_ShippingInterface
{
    protected $addressee;
    protected $method;
    protected $phone;

    /**
     * @return mixed
     */
    public function getAddressee()
    {
        return $this->addressee;
    }

    /**
     * @param mixed $addressee
     */
    public function setAddressee($addressee)
    {
        $this->addressee = $addressee;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}
