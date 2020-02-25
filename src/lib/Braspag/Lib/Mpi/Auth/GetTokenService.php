<?php
/**
 * Mpi ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_Mpi_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Mpi_Auth_GetTokenService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Mpi\Auth\GetToken' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Mpi_Auth_GetToken($serviceManager);
                $transaction->setRequest($serviceManager->get('Mpi\Auth\GetToken\Request'));
                $transaction->setResponse($serviceManager->get('Mpi\Auth\GetToken\Response'));

                return $transaction;
            },
            'Mpi\Auth\GetToken\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Mpi_Auth_GetToken_Request($serviceManager);
                return $request;
            },
            'Mpi\Auth\GetToken\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Mpi_Auth_GetToken_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Mpi\Auth\GetToken\Request\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Mpi_Auth_GetToken_RequestHydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Mpi_Auth_GetToken_Response_Hydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Mpi_Auth_GetToken_Request_Builder($serviceManager);
            }
        );
    }
}
