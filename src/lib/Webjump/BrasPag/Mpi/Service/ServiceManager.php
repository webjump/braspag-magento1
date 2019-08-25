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
class Webjump_BrasPag_Mpi_Service_ServiceManager
    implements Webjump_BrasPag_Mpi_Service_ServiceManagerInterface
{
    protected $services = array();
    protected $config;

    /**
     * Webjump_BrasPag_Mpi_Service_ServiceManager constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Webjump_BrasPag_Mpi_Service_Config($config);
        $this->services = $this->getServiceConfig();
    }

    /**
     * @return array
     */
    protected function getServiceConfig()
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
                $response->setErrorReport($serviceManager->get('Mpi\Data\Response\ErrorReport'));

                return $response;
            },
            'Mpi\Auth\GetToken\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_RequestHydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\ResponseHydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_ResponseHydrator($serviceManager);
            },
            'Mpi\Auth\GetToken\Template\Default' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Auth_GetToken_Template_Default($serviceManager);
            },
            'Mpi\Data\Response\ErrorReport' => function ($serviceManager) {
                return new Webjump_BrasPag_Mpi_Data_Response_ErrorReport($serviceManager);
            }
        );
    }

    /**
     * @return Webjump_BrasPag_Mpi_Service_Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $serviceName
     * @return bool|mixed
     * @throws Exception
     */
    public function get($serviceName)
    {
        if (!$service = $this->exists($serviceName)) {
            throw new Exception("Service Invalid: \"{$serviceName}\"", 1);
        }

        if (is_callable($service)) {
            return call_user_func($service, $this);
        }

        return $service;
    }

    /**
     * @param $serviceName
     * @param $service
     */
    public function set($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }

    /**
     * @param $serviceName
     * @return bool|mixed
     */
    protected function exists($serviceName)
    {
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName];
        }

        return false;
    }
}
