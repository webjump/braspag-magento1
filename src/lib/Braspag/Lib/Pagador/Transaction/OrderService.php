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
class Braspag_Lib_Pagador_Transaction_OrderService implements Braspag_Lib_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Order' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Pagador_Transaction_Order($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Order\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Order\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Order\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Pagador_Transaction_Order_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment'));

                return $request;
            },
            'Pagador\Transaction\Order\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Pagador_Transaction_Order_Response($serviceManager);
                $response->setCustomer($serviceManager->get('Pagador\Data\Response\Customer'));
                $response->setOrder($serviceManager->get('Pagador\Data\Response\Order'));
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Pagador\Transaction\Order\Request\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Order_Request_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Order\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Order_Response_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Order\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Order_Request_Builder($serviceManager);
            }
        );
    }
}
