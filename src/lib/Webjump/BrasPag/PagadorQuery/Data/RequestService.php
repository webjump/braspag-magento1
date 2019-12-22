<?php
/**
 * Pagador ServiceManager
 *
 * @category  Service
 * @package   Webjump_BrasPag_PagadorQuery_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_PagadorQuery_Data_RequestService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'PagadorQuery\Data\Request\Order' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Order($serviceManager);
            },
            'PagadorQuery\Data\Request\Payment\CreditCard' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Payment_CreditCard($serviceManager);
            },
            'PagadorQuery\Data\Request\Payment\Billet' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Payment_Billet($serviceManager);
            },
            'PagadorQuery\Data\Request\Payment\DebitCard' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Payment_DebitCard($serviceManager);
            },
            'PagadorQuery\Data\Request\Payment\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Payment_Current($serviceManager);
            },
            'PagadorQuery\Data\Request\Address' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Address($serviceManager);
            },
            'PagadorQuery\Data\Request\Customer' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Customer($serviceManager);
            },
            'PagadorQuery\Data\Request\Transaction\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Transaction_Current($serviceManager);
            },
            'PagadorQuery\Data\Request\Transaction\Item' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Data_Request_Transaction_Item($serviceManager);
            }
        );
    }
}
