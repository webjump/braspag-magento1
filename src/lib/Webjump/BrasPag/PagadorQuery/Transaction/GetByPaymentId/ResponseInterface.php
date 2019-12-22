<?php
/**
 * Pagador Method GetByPaymentId Response
 *
 * @category  Method
 * @package   Webjump_BrasPag_PagadorQuery_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_ResponseInterface
{
    public function getPaymentId();

    public function isSuccess();

    public function getErrorReport();

    public function getCustomer();

    public function getOrder();

    public function getPayment();
}
