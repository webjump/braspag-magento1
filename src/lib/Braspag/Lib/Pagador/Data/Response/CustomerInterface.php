<?php
/**
 * BrasPag Pagador Data Response Order Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Response_Order
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Response_CustomerInterface
{
    public function getName();

    public function getIdentity();

    public function getIdentityType();

    public function getEmail();

    public function getAddress();

    public function getDeliveryAddress();
}
