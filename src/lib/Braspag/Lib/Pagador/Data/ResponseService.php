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
class Braspag_Lib_Pagador_Data_ResponseService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Data\Response\Order' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Order($serviceManager);
            },
            'Pagador\Data\Response\Customer' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Customer($serviceManager);
            },
            'Pagador\Data\Response\Payment\CreditCard' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Payment_CreditCard($serviceManager);
            },
            'Pagador\Data\Response\Payment\Boleto' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Payment_Boleto($serviceManager);
            },
            'Pagador\Data\Response\Payment\DebitCard' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Payment_DebitCard($serviceManager);
            },
            'Pagador\Data\Response\Payment' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Data_Response_Payment($serviceManager);
            }
        );
    }
}
