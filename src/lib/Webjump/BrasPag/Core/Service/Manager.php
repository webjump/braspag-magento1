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
class Webjump_BrasPag_Core_Service_Manager
    implements Webjump_BrasPag_Core_Service_ManagerInterface
{
    protected $services = array();
    protected $config;
    protected $serviceManagerModel;

    public function __construct(array $config)
    {
        $this->serviceManagerModel = Mage::getModel('webjump_braspag_pagador/serviceManager');

        $this->config = new Webjump_BrasPag_Core_Service_Config($config);
        $this->preLoadAllServices();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function preLoadAllServices()
    {
        $allServicesClasses = $this->serviceManagerModel->getServicesClasses();

        foreach ($allServicesClasses as $serviceClass) {

            if (!class_exists($serviceClass)){
                throw new Exception("Invalid Service Class: \"{$serviceClass}\"", 1);
            }

            $class = new $serviceClass;

            if (!method_exists($class, 'getServices')) {
                throw new Exception("Service Class : \"{$serviceClass}\" Without method: getServices", 1);
            }

            $classServices = $class->getServices();

            if (!is_array($classServices)) {
                throw new Exception("Service Class : \"{$serviceClass}\" Invalid method return", 1);
            }

            $this->services = array_merge($this->services, $classServices);
        }

        return $this;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Config
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

        if (!is_callable($service)) {
            $service;
        }

        return call_user_func($service, $this);
    }

    /**
     * @param $serviceName
     * @param $service
     * @return $this
     */
    public function set($serviceName, $service)
    {
        $this->services[$serviceName] = $service;

        return $this;
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
