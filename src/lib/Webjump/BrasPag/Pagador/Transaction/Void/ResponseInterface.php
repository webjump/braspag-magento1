<?php

/* Pagador Transaction Void Response Interface
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Void_Response_Interface
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Transaction_Void_ResponseInterface
{
    public function getCorrelationId();

    public function isSuccess();

    public function getErrorReport();

    public function getTransactions();
}
