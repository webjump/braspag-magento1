<?php
/**
 * Pagador Data Response Transaction Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Response_TransactionInterface
{
    public function getBraspagTransactionId();

    public function getAcquirerTransactionId();

    public function getAmount();

    public function getAuthorizationCode();

    public function getProofOfSale();

    public function getReturnCode();

    public function getReturnMessage();

    public function getStatus();

    public function getServiceTaxAmount();
}
