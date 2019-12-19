<?php
/**
 * Pagador ServiceManager
 *
 * @category  Service
 * @package   Webjump_BrasPag_Pagador_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_ResponseService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Data\Response\Order' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Order($serviceManager);
            },
            'Pagador\Data\Response\Customer' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Customer($serviceManager);
            },
            'Pagador\Data\Response\Payment\CreditCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard($serviceManager);
            },
            'Pagador\Data\Response\Payment\Billet' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_Billet($serviceManager);
            },
            'Pagador\Data\Response\Payment\DebitCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCard($serviceManager);
            },
            'Pagador\Data\Response\Payment\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_Current($serviceManager);
            },
            'Pagador\Data\Response\Transaction\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Transaction_Current($serviceManager);
            },
            'Pagador\Data\Response\Transaction\Item' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Transaction_Item($serviceManager);
            }
        );
    }
}
