<?php
/**
 * Pagador Data Transaction Abstract
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BrasPag_Pagador_Data_Request_Transaction_Abstract extends Webjump_BrasPag_Pagador_Data_Abstract
{
    protected $braspagTransactionId;
    protected $amount;
    protected $serviceTaxAmount;

    public function getBraspagTransactionId()
    {
        return $this->braspagTransactionId;
    }

    public function setBraspagTransactionId($braspagTransactionId)
    {
        $this->braspagTransactionId = $braspagTransactionId;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
