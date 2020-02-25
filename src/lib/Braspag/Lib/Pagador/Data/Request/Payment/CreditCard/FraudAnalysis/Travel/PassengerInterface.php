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
interface Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Travel_PassengerInterface
{
    public function getName();

    public function getIdentity();

    public function getStatus();

    public function getRating();

    public function getEmail();

    public function getPhone();

    public function getTravelLegs();
}
