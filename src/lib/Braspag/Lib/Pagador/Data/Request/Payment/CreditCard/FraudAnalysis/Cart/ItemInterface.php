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
interface Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart_ItemInterface
{
    public function getGiftCategory();

    public function getHostHedge();

    public function getNonSensicalHedge();

    public function getObscenitiesHedge();

    public function getPhoneHedge();

    public function getName();

    public function getQuantity();

    public function getSku();

    public function getUnitPrice();

    public function getRisk();

    public function getTimeHedge();

    public function getType();

    public function getVelocityHedge();
}
