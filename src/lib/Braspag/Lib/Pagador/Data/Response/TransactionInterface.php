<?php
/**
 * Pagador Data Response Transaction Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Response_TransactionInterface
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
