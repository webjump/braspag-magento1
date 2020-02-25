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
interface Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysisInterface
{
    public function getSequence();

    public function getSequenceCriteria();

    public function getProvider();

    public function getCaptureOnLowRisk();

    public function getVoidOnHighRisk();

    public function getTotalOrderAmount();

    public function getFingerPrintId();

    public function getBrowser();

    public function getCart();

    public function getMerchantDefinedFields();

    public function getShipping();

    public function getTravel();
}
