<?php
/**
 * Pagador Method Capture Response
 *
 * @category  Method
 * @package   Braspag_Lib_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Transaction_Capture_ResponseInterface
{
    public function getPaymentId();

    public function isSuccess();

    public function getErrorReport();

    public function getOrder();

    public function getPayment();
}
