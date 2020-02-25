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
class Braspag_Lib_Pagador_Transaction_CaptureService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Capture' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Pagador_Transaction_Capture($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Capture\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Capture\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Capture\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Pagador_Transaction_Capture_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment'));

                return $request;
            },
            'Pagador\Transaction\Capture\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Pagador_Transaction_Capture_Response($serviceManager);
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));
                return $response;
            },
            'Pagador\Transaction\Capture\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Capture_Request_Builder($serviceManager);
            },
            'Pagador\Transaction\Capture\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Capture_Response_Hydrator($serviceManager);
            }
        );
    }
}
