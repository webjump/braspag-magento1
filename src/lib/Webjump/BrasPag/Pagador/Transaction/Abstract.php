<?php
///**
// * Pagador Transaction Abstract
// *
// * @category  Transaction
// * @package   Webjump_BrasPag_Pagador_Transaction
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2019 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//abstract class Webjump_BrasPag_Pagador_Transaction_Abstract extends \Zend_Http_Client
//{
//    protected $namespace;
//    protected $_lastRequest;
//    protected $defaultOptions = array(
//        'trace' => 1,
//        'exceptions' => 1,
//        'encoding' => 'UTF-8',
//    );
//
//    const CONFIG_NAMESPACE_KEY = 'webservice_namespace';
//
//    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
//    {
//        $config = $serviceManager->getConfig()->toArray();
//        $this->setNamespace($config[self::CONFIG_NAMESPACE_KEY]);
//
//        return parent::__construct($config[self::CONFIG_NAMESPACE_KEY]);
//    }
//
//    public function debug()
//    {
//        return $this->getLastRequest();
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getNamespace()
//    {
//        return $this->namespace;
//    }
//
//    /**
//     * @param $namespace
//     */
//    public function setNamespace($namespace)
//    {
//        $this->namespace = $namespace;
//    }
//
//    public function getLastRequest()
//    {
//        return $this->_lastRequest;
//    }
//
//    public function setLastRequest($request)
//    {
//        $this->_lastRequest = $request;
//    }
//
//    /**
//     * @param $request
//     * @param $path
//     * @param $action
//     * @return $this
//     * @throws Zend_Http_Client_Exception
//     */
//    public function __doRequest($request, $path, $action)
//    {
//        $dataRequest = $this->prepareRequest($request, $path, $action);
//        $this->setLastRequest($dataRequest);
//
//        $this->setUri($this->getUri().$path);
//
//        $this->setHeaders('Content-Type', 'application/json');
//        $this->setHeaders($dataRequest['Header']);
//
//        switch ($action) {
//
//            case 'GET':
//                $this->setMethod("GET");
//                break;
//
//            case 'POST':
//                $this->setMethod("POST");
//                $this->setRawData(json_encode($dataRequest['Body']));
//                break;
//
//            case 'PUT':
//                $this->setMethod("PUT");
//                break;
//
//            default:
//                $this->setMethod("POST");
//                $this->setRawData(json_encode($dataRequest['Body']));
//                break;
//        }
//
//        try {
//            $this->request();
//        } catch (\Exception $e) {
//            Mage::log($e->getMessage());
//        }
//
//        return $this;
//    }
//
//    public function getDefaultOptions(array $config)
//    {
//        if (isset($config[self::CONFIG_OPTIONS_KEY])) {
//            $this->defaultOptions = array_merge($this->defaultOptions, $config[self::CONFIG_OPTIONS_KEY]);
//        }
//
//        return $this->defaultOptions;
//    }
//
//    abstract protected function prepareRequest($request, $path, $action);
//
//    abstract public function execute();
//}
