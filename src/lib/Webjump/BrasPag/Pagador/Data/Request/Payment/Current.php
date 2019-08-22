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
class Webjump_BrasPag_Pagador_Data_Request_Payment_Current
    implements Webjump_BrasPag_Pagador_Data_Request_Payment_CurrentInterface
{
    protected $payment;

    /**
     * @param Webjump_BrasPag_Pagador_Data_Request_Payment_Abstract $payment
     * @return $this
     */
    public function set(Webjump_BrasPag_Pagador_Data_Request_Payment_Abstract $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    public function get()
    {
        return $this->payment;
    }
}
