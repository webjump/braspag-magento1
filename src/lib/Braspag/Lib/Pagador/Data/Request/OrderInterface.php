<?php
/**
 * Pagador Data Order Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Request_OrderInterface
{
    public function getOrderId();

    public function getOrderAmount();

    public function getBraspagOrderId();
}
