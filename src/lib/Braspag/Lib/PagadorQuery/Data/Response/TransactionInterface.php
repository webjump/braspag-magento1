<?php
/**
 * Pagador Data Response Transaction Interface
 *
 * @category  Data
 * @package   Braspag_Lib_PagadorQuery_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_PagadorQuery_Data_Response_TransactionInterface
{
    public function getPayments();

    public function getReasonCode();

    public function getReasonMessage();

    public function getStatus();
}
