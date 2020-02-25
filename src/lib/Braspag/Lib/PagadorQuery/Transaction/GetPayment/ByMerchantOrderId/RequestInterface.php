<?php
/**
 * Pagador Method Authorize Request
 *
 * @category  Method
 * @package   Braspag_Lib_PagadorQuery_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_RequestInterface
{
    public function getMerchantId();

    public function getMerchantKey();

    public function getRequestId();

    public function getMerchantOrderId();
}
