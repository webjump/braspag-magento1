<?php
/**
 * Pagador Transaction Abstract
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Core_Http_Client extends \Zend_Http_Client
{
    protected $namespace;
    protected $request;
    protected $response;
    protected $requestBuilder;
    protected $responseHydrator;
    protected $_lastRequest;
    protected $defaultOptions = array(
        'trace' => 1,
        'exceptions' => 1,
        'encoding' => 'UTF-8',
    );
    protected $serviceManager;

    const CONFIG_NAMESPACE_KEY = 'webservice_namespace';

    /**
     * Webjump_BrasPag_Core_Http_Client constructor.
     * @param Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager)
    {
        parent::__construct();

        $config = $serviceManager->getConfig()->toArray();

        $this->setNamespace($config[self::CONFIG_NAMESPACE_KEY]);

        $this->serviceManager = $serviceManager;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function debug()
    {
        return $this->getLastRequest();
    }

    /**
     * @return string
     */
    public function debugResponse()
    {
        $response = $this->getLastResponse();

        if (!$response) {
            return [];
        }

        return [
            'version' => $response->getVersion(),
            'code' => $response->getStatus(),
            'message' => $response->getMessage(),
            'headers' => $response->getHeaders(),
            'body' => $response->getRawBody()
        ];
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getLastRequest()
    {
        return $this->_lastRequest;
    }

    /**
     * @param $request
     */
    public function setLastRequest($request)
    {
        $this->_lastRequest = $request;
    }

    /**
     * @param $request
     * @param $path
     * @param $action
     * @return $this
     * @throws Zend_Http_Client_Exception
     */
    public function doRequest($request, $path, $action)
    {
        $dataRequest = $this->prepareRequest($request, $path, $action);

        $this->setLastRequest($dataRequest);

        $this->setUri($this->getNamespace().$path);

        $this->setHeaders('Content-Type', 'application/json');
        $this->setHeaders($dataRequest['Header']);

        $this->setConfig(['timeout' => 60]);

        switch ($action) {

            case 'GET':
                $this->setMethod("GET");
                break;

            case 'POST':
                $this->setMethod("POST");
                $this->setRawData(json_encode($dataRequest['Body']));
                break;

            case 'PUT':
                $this->setMethod("PUT");
                break;

            default:
                $this->setMethod("POST");
                $this->setRawData(json_encode($dataRequest['Body']));
                break;
        }

        try {
            $this->request();
        } catch (\Exception $e) {
            Mage::log($e->getMessage());
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function prepareRequest()
    {
        $requestBuilder = $this->getRequestBuilder();
        $requestBuilder->setRequest($this->getRequest());
        return $requestBuilder->build();
    }

    /**
     * @param $response
     * @throws Exception
     */
    protected function prepareResponse($response)
    {
        $responseData = $this->getLastResponse();

        if (!$responseData instanceof Zend_Http_Response) {
            throw new \Exception("Invalid Response");
        }

        $this->getResponseHydrator()->hydrate($responseData, $response);
    }

    /**
     * @return mixed
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
