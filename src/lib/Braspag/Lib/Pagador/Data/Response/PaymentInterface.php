<?php
/**
 * Pagador Data  Abstract
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Response_PaymentInterface
{
    /**
     * @return mixed
     */
    public function getProvider();

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getAmount();

    /**
     * @return mixed
     */
    public function getCurrency();

    /**
     * @return mixed
     */
    public function getCountry();
}
