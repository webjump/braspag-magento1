<?php
/**
 * Core ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_Core_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Core_Auth_GetTokenService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Core\Auth\GetToken' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Core_Auth_GetToken($serviceManager);
                $transaction->setRequest($serviceManager->get('Core\Auth\GetToken\Request'));
                $transaction->setResponse($serviceManager->get('Core\Auth\GetToken\Response'));

                return $transaction;
            },
            'Core\Auth\GetToken\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Core_Auth_GetToken_Request($serviceManager);
                return $request;
            },
            'Core\Auth\GetToken\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Core_Auth_GetToken_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Core\Auth\GetToken\Request\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Core_Auth_GetToken_RequestHydrator($serviceManager);
            },
            'Core\Auth\GetToken\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Core_Auth_GetToken_Response_Hydrator($serviceManager);
            },
            'Core\Auth\GetToken\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Core_Auth_GetToken_Request_Builder($serviceManager);
            }
        );
    }
}
