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
class Braspag_Lib_Pagador_Transaction_VoidService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Void' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Pagador_Transaction_Void($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Void\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Void\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Void\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Pagador_Transaction_Void_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment'));

                return $request;
            },
            'Pagador\Transaction\Void\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Pagador_Transaction_Void_Response($serviceManager);
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment'));
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));
                return $response;
            },
            'Pagador\Transaction\Void\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Void_Request_Builder($serviceManager);
            },

            'Pagador\Transaction\Void\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Pagador_Transaction_Void_Response_Hydrator($serviceManager);
            }
        );
    }
}
