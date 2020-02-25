<?php
/**
 * Pagador Method Authorize Response
 *
 * @category  Method
 * @package   Braspag_Lib_PagadorQuery_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface
{
    public function isSuccess();

    public function getErrorReport();

    public function getPayments();

    public function getReasonCode();

    public function getReasonMessage();
}
