<?php
/**
 * Pagador Data Transaction Item
 *
 * @category  Data
 * @package   Webjump_BrasPag_PagadorQuery_Data_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_PagadorQuery_Data_Request_Transaction_Item extends Webjump_BrasPag_PagadorQuery_Data_Request_Transaction_Abstract implements Webjump_BrasPag_PagadorQuery_Data_Request_Transaction_ItemInterface
{
    protected $serviceTaxAmount;
    protected $amount;
    protected $braspagTransactionId;

    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;

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

    public function getBraspagTransactionId()
    {
        return $this->braspagTransactionId;
    }

    public function setBraspagTransactionId($braspagTransactionId)
    {
        $this->braspagTransactionId = $braspagTransactionId;

        return $this;
    }
}
