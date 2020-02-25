<?php
/**
 * Pagador ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_Pagador_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_RequestService implements Braspag_Lib_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'Pagador\Data\Request\Order' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Order($serviceManager);
            },
            'Pagador\Data\Request\Payment' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\Card' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_Card($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Cart' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Browser' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Browser($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Cart' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Cart\Item' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart_Item($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\MerchantDefinedField' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_MerchantDefinedField($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Shipping' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Shipping($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Travel' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Travel($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Travel\Passenger' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Travel_Passenger($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\FraudAnalysis\Travel\Passenger\TravelLeg' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Travel_Passenger_TravelLeg($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\SplitPayment' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_SplitPayment($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard\SplitPayment\Fare' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_SplitPayment_Fare($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCardJustClick' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_CreditCardJustClick($serviceManager);
            },
            'Pagador\Data\Request\Payment\Boleto' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_Boleto($serviceManager);
            },
            'Pagador\Data\Request\Payment\DebitCard' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_DebitCard($serviceManager);
            },
            'Pagador\Data\Request\Payment\DebitCard\Card' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_Card($serviceManager);
            },
            'Pagador\Data\Request\Payment\DebitCard\SplitPayment' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_SplitPayment($serviceManager);
            },
            'Pagador\Data\Request\Payment\DebitCard\SplitPayment\Fare' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Payment_DebitCard_SplitPayment_Fare($serviceManager);
            },
            'Pagador\Data\Request\Customer\Address' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Customer_Address($serviceManager);
            },
            'Pagador\Data\Request\Customer' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Request_Customer($serviceManager);
            }
        );
    }
}
