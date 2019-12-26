<?php
/**
 * Mpi ServiceManager
 *
 * @category  Service
 * @package   Webjump_BrasPag_Mpi_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Mpi_Auth_GetTokenService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Mpi\Auth\GetToken' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Mpi_Auth_GetToken($serviceManager);
                $transaction->setRequest($serviceManager->get('Mpi\Auth\GetToken\Request'));
                $transaction->setResponse($serviceManager->get('Mpi\Auth\GetToken\Response'));

                return $transaction;
            },
            'Mpi\Auth\GetToken\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Mpi_Auth_GetToken_Request($serviceManager);
                return $request;
            },
            'Mpi\Auth\GetToken\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Mpi_Auth_GetToken_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Mpi\Auth\GetToken\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_RequestHydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_Response_Hydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\Request\Builder' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_Request_Builder($serviceManager);
            }
        );
    }
}
