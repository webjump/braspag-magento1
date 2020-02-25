<?php
/**
 * Pagador Data Response Payment CreditCard Interface
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Response_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Pagador_Data_Response_Payment_DebitCardInterface
{
    public function getAcquirerTransactionId();

    public function getPaymentId();

    public function getReceivedDate();

    public function getCurrency();

    public function getCountry();

    public function getProvider();

    public function getReturnUrl();

    public function getReasonCode();

    public function getReasonMessage();

    public function getStatus();

    public function getProviderReturnCode();

    public function getSplitPayments();
}
