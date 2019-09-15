<?php
/**
 * Pagador Data Transaction Current Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Request_Transaction_CurrentInterface
{
    public function add(Webjump_BrasPag_Pagador_Data_Request_Transaction_Abstract $transaction);
}
