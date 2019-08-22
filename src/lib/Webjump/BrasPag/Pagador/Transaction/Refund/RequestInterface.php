<?php
/**
 * Pagador Method Refund Request Interface
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund_Request_Interface
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface
{
    public function getRequestId();

    public function getVersion();

    public function getMerchantId();

    public function getTransactionDataCollection();
}
