<?php
/**
 * Pagador Method Order Request
 *
 * @category  Method
 * @package   Braspag_Lib_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Transaction_Order_RequestInterface
{
    public function getRequestId();

    public function getOrder();

    public function getPayment();

    public function getCustomer();
}
