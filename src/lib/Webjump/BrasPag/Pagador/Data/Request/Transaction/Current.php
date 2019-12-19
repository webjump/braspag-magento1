<?php
/**
 * Pagador Data Transaction Current
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Request_Transaction_Current extends Webjump_BrasPag_Core_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Request_Transaction_CurrentInterface
{
    private $transaction;

    public function add(Webjump_BrasPag_Pagador_Data_Request_Transaction_Abstract $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getDataAsArray()
    {
        return $this->transaction->getDataAsArray();
    }
}
