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
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_SplitPayment_Fare
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_SplitPayment_FareInterface
{
    protected $mdr;
    protected $fee;

    /**
     * @return mixed
     */
    public function getMdr()
    {
        return $this->mdr;
    }

    /**
     * @param mixed $mdr
     */
    public function setMdr($mdr)
    {
        $this->mdr = $mdr;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }
}
