<?php
/**
 * Pagador Data Payment CreditCardInterface
 *
 * @category  Data
 * @package   Webjump_BrasPag_PagadorQuery_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_PagadorQuery_Data_Request_Payment_CreditCardInterface
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

    public function getDoSplit();

    public function getCardHolder();

    public function getCardNumber();

    public function getCardSecurityCode();

    public function getCardExpirationDate();

    public function getCardToken();

    public function getCardBrand();

    public function getSaveCard();

    public function getCardAlias();

    public function getFraudAnalysis();
}
