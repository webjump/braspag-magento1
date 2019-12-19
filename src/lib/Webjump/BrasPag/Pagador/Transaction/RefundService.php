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
class Webjump_BrasPag_Pagador_Transaction_RefundService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Refund' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund($serviceManager);
            },
            'Pagador\Transaction\Refund\Request' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_Request($serviceManager);
            },
            'Pagador\Transaction\Refund\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_RequestHydrator($serviceManager);
            },
            'Pagador\Transaction\Refund\Response' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_Response($serviceManager);
            },
            'Pagador\Transaction\Refund\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_ResponseHydrator($serviceManager);
            },
        );
    }
}
