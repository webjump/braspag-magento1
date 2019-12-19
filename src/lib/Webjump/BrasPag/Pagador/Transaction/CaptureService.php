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
class Webjump_BrasPag_Pagador_Transaction_CaptureService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Capture' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Pagador_Transaction_Capture($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Capture\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Capture\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Capture\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Pagador_Transaction_Capture_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment\Current'));

                return $request;
            },
            'Pagador\Transaction\Capture\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Pagador_Transaction_Capture_Response($serviceManager);
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment\Current'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));
                return $response;
            },
            'Pagador\Transaction\Capture\Request\Builder' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Capture_Request_Builder($serviceManager);
            },
            'Pagador\Transaction\Capture\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Capture_Response_Hydrator($serviceManager);
            }
        );
    }
}
