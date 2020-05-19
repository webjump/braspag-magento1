<?php
/**
 * Pagador Data Customer Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Request_CustomerInterface
{
    public function getIdentity();

    public function getIdentityType();

    public function getEmail();

    public function getBirthDate();

    public function getPhone();

    public function getAddress();

    public function getDeliveryAddress();
}
