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
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderIdService
    implements Braspag_Lib_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'PagadorQuery\Transaction\GetPayment\ByMerchantOrderId' => function ($serviceManager) {
                $transaction = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId($serviceManager);
                $transaction->setRequest($serviceManager->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Request'));
                $transaction->setResponse($serviceManager->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Response'));

                return $transaction;
            },
            'PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Request($serviceManager);

                return $request;
            },
            'PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Response_Hydrator($serviceManager);
            },
            'PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Request_Builder($serviceManager);
            }
        );
    }
}
