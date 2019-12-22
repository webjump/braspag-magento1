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
class Webjump_BrasPag_Pagador_Transaction_OrderService implements Webjump_BrasPag_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Order' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Pagador_Transaction_Order($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Order\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Order\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Order\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Pagador_Transaction_Order_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment\Current'));

                return $request;
            },
            'Pagador\Transaction\Order\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Pagador_Transaction_Order_Response($serviceManager);
                $response->setCustomer($serviceManager->get('Pagador\Data\Response\Customer'));
                $response->setOrder($serviceManager->get('Pagador\Data\Response\Order'));
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment\Current'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Pagador\Transaction\Order\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Order_Request_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Order\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Order_Response_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Order\Request\Builder' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Order_Request_Builder($serviceManager);
            }
        );
    }
}
