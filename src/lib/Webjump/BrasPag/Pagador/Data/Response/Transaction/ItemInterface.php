<?php
/**
 * Pagador Data Response Transaction Item Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Response_Transaction_ItemInterface
{
    public function getAcquirerTransactionId();

    public function getAuthorizationCode();

    public function getReturnCode();

    public function getReturnMessage();

    public function getProofOfSale();

    public function getStatus();

    public function getServiceTaxAmount();
}
