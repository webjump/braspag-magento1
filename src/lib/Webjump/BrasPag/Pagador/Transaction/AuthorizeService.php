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
class Webjump_BrasPag_Pagador_Transaction_AuthorizeService implements Webjump_BrasPag_Core_Service_Interface
{
    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Authorize' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Pagador_Transaction_Authorize($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Authorize\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Authorize\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Authorize\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Pagador_Transaction_Authorize_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment\Current'));

                return $request;
            },
            'Pagador\Transaction\Authorize\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Pagador_Transaction_Authorize_Response($serviceManager);
                $response->setCustomer($serviceManager->get('Pagador\Data\Response\Customer'));
                $response->setOrder($serviceManager->get('Pagador\Data\Response\Order'));
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment\Current'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Pagador\Transaction\Authorize\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_Request_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Authorize\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_Response_Hydrator($serviceManager);
            },
            'Pagador\Transaction\Authorize\Request\Builder' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_Request_Builder($serviceManager);
            }
        );
    }
}
