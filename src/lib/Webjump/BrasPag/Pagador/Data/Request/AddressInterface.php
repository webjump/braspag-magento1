<?php
/**
 * Pagador Data Address Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Request_AddressInterface
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
