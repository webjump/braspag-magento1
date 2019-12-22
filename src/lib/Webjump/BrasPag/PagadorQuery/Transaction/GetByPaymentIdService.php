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
class Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentIdService
    implements Webjump_BrasPag_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'PagadorQuery\Transaction\GetByPaymentId' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId($serviceManager);
                $transaction->setRequest($serviceManager->get('PagadorQuery\Transaction\GetByPaymentId\Request'));
                $transaction->setResponse($serviceManager->get('PagadorQuery\Transaction\GetByPaymentId\Response'));

                return $transaction;
            },
            'PagadorQuery\Transaction\GetByPaymentId\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Request($serviceManager);
                $request->setPayment($serviceManager->get('PagadorQuery\Data\Request\Payment\Current'));

                return $request;
            },
            'PagadorQuery\Transaction\GetByPaymentId\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Response($serviceManager);
                $response->setCustomer($serviceManager->get('PagadorQuery\Data\Response\Customer'));
                $response->setOrder($serviceManager->get('PagadorQuery\Data\Response\Order'));
                $response->setPayment($serviceManager->get('PagadorQuery\Data\Response\Payment\Current'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'PagadorQuery\Transaction\GetByPaymentId\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Request_Hydrator($serviceManager);
            },
            'PagadorQuery\Transaction\GetByPaymentId\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Response_Hydrator($serviceManager);
            },
            'PagadorQuery\Transaction\GetByPaymentId\Request\Builder' => function ($serviceManager) {
                return new Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Request_Builder($serviceManager);
            }
        );
    }
}
