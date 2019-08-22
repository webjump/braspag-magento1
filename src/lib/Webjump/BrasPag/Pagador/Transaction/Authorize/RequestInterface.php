<?php
/**
 * Pagador Method Authorize Request
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Transaction_Authorize_RequestInterface
{
    public function getRequestId();

    public function getOrder();

    public function getPayment();

    public function getCustomer();
}
