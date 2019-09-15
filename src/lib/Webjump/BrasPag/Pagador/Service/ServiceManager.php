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
class Webjump_BrasPag_Pagador_Service_ServiceManager implements Webjump_BrasPag_Pagador_Service_ServiceManagerInterface
{
    protected $services = array();
    protected $config;

    public function __construct(array $config)
    {
        $this->config = new Webjump_BrasPag_Pagador_Service_Config($config);
        $this->services = $this->getServiceConfig();
    }

    protected function getServiceConfig()
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
                $response->setErrorReport($serviceManager->get('Pagador\Data\Response\ErrorReport'));

                return $response;
            },
            'Pagador\Transaction\Authorize\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_RequestHydrator($serviceManager);
            },
            'Pagador\Transaction\Authorize\ResponseHydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseHydrator($serviceManager);
            },
            'Pagador\Transaction\Authorize\Template\Default' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Authorize_Template_Default($serviceManager);
            },
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
                $response->setErrorReport($serviceManager->get('Pagador\Data\Response\ErrorReport'));
                return $response;
            },
            'Pagador\Transaction\Capture\Template\Default' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Capture_Template_Default($serviceManager);
            },

            'Pagador\Transaction\Capture\ResponseHydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Capture_ResponseHydrator($serviceManager);
            },
            'Pagador\Transaction\Void' => function ($serviceManager) {
                $transaction = new Webjump_BrasPag_Pagador_Transaction_Void($serviceManager);
                $transaction->setRequest($serviceManager->get('Pagador\Transaction\Void\Request'));
                $transaction->setResponse($serviceManager->get('Pagador\Transaction\Void\Response'));

                return $transaction;
            },
            'Pagador\Transaction\Void\Request' => function ($serviceManager) {
                $request = new Webjump_BrasPag_Pagador_Transaction_Void_Request($serviceManager);
                $request->setPayment($serviceManager->get('Pagador\Data\Request\Payment\Current'));

                return $request;
            },
            'Pagador\Transaction\Void\Response' => function ($serviceManager) {
                $response = new Webjump_BrasPag_Pagador_Transaction_Void_Response($serviceManager);
                $response->setPayment($serviceManager->get('Pagador\Data\Response\Payment\Current'));
                $response->setErrorReport($serviceManager->get('Pagador\Data\Response\ErrorReport'));
                return $response;
            },
            'Pagador\Transaction\Void\Template\Default' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Void_Template_Default($serviceManager);
            },

            'Pagador\Transaction\Void\ResponseHydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Void_ResponseHydrator($serviceManager);
            },
            'Pagador\Data\Request\Order' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Order($serviceManager);
            },
            'Pagador\Data\Request\Order' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Order($serviceManager);
            },
            'Pagador\Data\Response\Order' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Order($serviceManager);
            },
            'Pagador\Data\Response\Customer' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Customer($serviceManager);
            },
            'Pagador\Data\Request\Payment\CreditCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard($serviceManager);
            },
            'Pagador\Data\Response\Payment\CreditCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard($serviceManager);
            },
            'Pagador\Data\Request\Payment\Boleto' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Payment_Boleto($serviceManager);
            },
            'Pagador\Data\Response\Payment\Boleto' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_Boleto($serviceManager);
            },
            'Pagador\Data\Request\Payment\DebitCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Payment_DebitCard($serviceManager);
            },
            'Pagador\Data\Response\Payment\DebitCard' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_DebitCard($serviceManager);
            },
            'Pagador\Data\Request\Payment\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Payment_Current($serviceManager);
            },
            'Pagador\Data\Response\Payment\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Payment_Current($serviceManager);
            },
            'Pagador\Data\Request\Address' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Address($serviceManager);
            },
            'Pagador\Data\Request\Customer' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Customer($serviceManager);
            },
            'Pagador\Data\Request\Transaction\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Transaction_Current($serviceManager);
            },
            'Pagador\Data\Request\Transaction\Item' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Request_Transaction_Item($serviceManager);
            },
            'Pagador\Data\Response\Transaction\Current' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Transaction_Current($serviceManager);
            },
            'Pagador\Data\Response\Transaction\Item' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_Transaction_Item($serviceManager);
            },
            'Pagador\Data\Response\ErrorReport' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Data_Response_ErrorReport($serviceManager);
            },
            'Pagador\Transaction\Query' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Query($serviceManager);
            },
            'Pagador\Transaction\Query\Client' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Service_Client();
            },
            'Pagador\Transaction\Client' => function ($serviceManager) {
                $config = $serviceManager->getConfig();
                return new Webjump_BrasPag_Pagador_Service_Client($config['webservice_wsdl']);
            },
            'Pagador\Transaction\Refund' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund($serviceManager);
            },
            'Pagador\Transaction\Refund\Request' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_Request($serviceManager);
            },
            'Pagador\Transaction\Refund\Request\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_RequestHydrator($serviceManager);
            },
            'Pagador\Transaction\Refund\Response' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_Response($serviceManager);
            },
            'Pagador\Transaction\Refund\Response\Hydrator' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Refund_ResponseHydrator($serviceManager);
            },
        );
    }

    public function getConfig()
    {
        return $this->config;
    }

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

    public function set($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }

    protected function exists($serviceName)
    {
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName];
        }

        return false;
    }
}
