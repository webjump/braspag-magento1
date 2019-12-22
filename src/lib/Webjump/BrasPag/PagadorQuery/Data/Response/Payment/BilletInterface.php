<?php
/**
 * Pagador Data Response Payment Billet Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_PagadorQuery_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_PagadorQuery_Data_Response_Payment_BilletInterface
{
    public function getBilletNumber();

    public function getExpirationDate();

    public function getUrl();

    public function getBarCodeNumber();

    public function getAssignor();
}
