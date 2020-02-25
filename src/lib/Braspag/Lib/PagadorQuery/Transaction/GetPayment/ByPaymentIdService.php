<?php
/**
 * Pagador ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_PagadorQuery_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentIdService
    implements Braspag_Lib_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'PagadorQuery\Transaction\GetPayment\ByPaymentId' => function ($serviceManager) {
                $transaction = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId($serviceManager);
                $transaction->setRequest($serviceManager->get('PagadorQuery\Transaction\GetPayment\ByPaymentId\Request'));
                $transaction->setResponse($serviceManager->get('PagadorQuery\Transaction\GetPayment\ByPaymentId\Response'));

                return $transaction;
            },
            'PagadorQuery\Transaction\GetPayment\ByPaymentId\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Request($serviceManager);
                $request->setOrder($serviceManager->get('Pagador\Data\Request\Order'));

                return $request;
            },
            'PagadorQuery\Transaction\GetPayment\ByPaymentId\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Response($serviceManager);
                $response->setCustomer($serviceManager->get('Pagador\Data\Response\Customer'));
                $response->setOrder($serviceManager->get('Pagador\Data\Response\Order'));
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'PagadorQuery\Transaction\GetPayment\ByPaymentId\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Response_Hydrator($serviceManager);
            },
            'PagadorQuery\Transaction\GetPayment\ByPaymentId\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Request_Builder($serviceManager);
            }
        );
    }
}
