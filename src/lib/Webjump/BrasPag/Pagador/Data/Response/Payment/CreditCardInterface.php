<?php
/**
 * Pagador Data Response Payment CreditCard Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCardInterface
{
    public function getAcquirerTransactionId();

    public function getAuthorizationCode();

    public function getProviderReturnCode();

    public function getProviderReturnMessage();

    public function getProofOfSale();

    public function getStatus();

    public function getCardToken();

    public function getServiceTaxAmount();

    public function getMaskedCreditCardNumber();
}
