<?php
/**
 * Pagador Data Payment Current
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_Payment_Current extends Webjump_BrasPag_Core_Data_Object
    implements Webjump_BrasPag_Pagador_Data_Response_Payment_CurrentInterface
{
    protected $payment;

    public function set(Webjump_BrasPag_Pagador_Data_Response_Payment_Abstract $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    public function get()
    {
        return $this->payment;
    }
}
