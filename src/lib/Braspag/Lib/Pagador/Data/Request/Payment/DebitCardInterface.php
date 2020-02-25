<?php
/**
 * Pagador Data Payment DebitCardInterface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Request_Payment_DebitCardInterface
{
    public function getAmount();

    public function getCurrency();

    public function getCountry();

    public function getInstallments();

    public function getInterest();

    public function getCapture();

    public function getAuthenticate();

    public function getExternalAuthentication();

    public function getRecurrent();

    public function getSoftDescriptor();

    public function getReturnUrl();
}
