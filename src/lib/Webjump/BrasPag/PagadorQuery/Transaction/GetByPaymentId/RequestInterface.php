<?php
/**
 * Pagador Method GetByPaymentId Request
 *
 * @category  Method
 * @package   Webjump_BrasPag_PagadorQuery_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_RequestInterface
{
    public function getRequestId();

    public function getOrder();
}
