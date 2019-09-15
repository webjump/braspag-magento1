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
class Webjump_BrasPag_Pagador_Service_Transaction_CaptureServiceManager
    extends Webjump_BrasPag_Pagador_Service_ServiceManager
{
    protected function getServiceConfig()
    {
        $parentServiceConfigs = parent::getServiceConfig();

        $serviceConfigs = array(
            'Pagador\Transaction\Capture' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Pagador_Transaction_Capture($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Capture\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Capture\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Capture\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Pagador_Transaction_Capture_Request($serviceManager);
                $request->setTransaction($serviceManager->get('Pagador\Data\Request\Transaction\Current'));

                return $request;
            },
            'Pagador\Transaction\Capture\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Pagador_Transaction_Capture_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Pagador\Data\Response\ErrorReport'));
                $response->setTransaction($serviceManager->get('Pagador\Data\Response\Transaction\Current'));

                return $response;
            },
            'Pagador\Transaction\Capture\Template\Default' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Capture_Template_Default($serviceManager);
            },
        );

        return array_merge($parentServiceConfigs, $serviceConfigs);
    }
}
