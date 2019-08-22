<?php
/**
 * Pagador Method Capture Response
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface
{
    public function getCorrelationId();

    public function isSuccess();

    public function getErrorReport();

    public function getTransactions();

    public function importBySoapClientResult($data, $xml);
}
