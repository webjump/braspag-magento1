<?php
/**
 * Pagador Data Address Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Request_Customer_AddressInterface
{
    public function getStreet();

    public function getNumber();

    public function getComplement();

    public function getDistrict();

    public function getZipCode();

    public function getCity();

    public function getState();

    public function getCountry();
}
