<?php
/**
 * Pagador Data Payment CreditCard
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_CardInterface
{
    /**
     * @return mixed
     */
    public function getCardHolder();

    /**
     * @return mixed
     */
    public function getCardNumber();

    /**
     * @return mixed
     */
    public function getCardSecurityCode();

    /**
     * @return mixed
     */
    public function getCardExpirationDate();
    /**
     * @return mixed
     */
    public function getCardToken();

    /**
     * @return mixed
     */
    public function getCardBrand();

    /**
     * @return mixed
     */
    public function getSaveCard();

    /**
     * @return mixed
     */
    public function getCardAlias();
}
