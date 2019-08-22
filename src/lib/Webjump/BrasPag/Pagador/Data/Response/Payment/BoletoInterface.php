<?php
/**
 * Pagador Data Response Payment Boleto Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Response_Payment_BoletoInterface
{
    public function getBoletoNumber();

    public function getExpirationDate();

    public function getUrl();

    public function getBarCodeNumber();

    public function getAssignor();
}
