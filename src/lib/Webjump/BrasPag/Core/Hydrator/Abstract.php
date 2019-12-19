<?php

abstract class Webjump_BrasPag_Core_Hydrator_Abstract
{
    protected $serviceManager;

    /**
     * Webjump_BrasPag_Core_Hydrator_Abstract constructor.
     * @param Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Webjump_BrasPag_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return Webjump_BrasPag_Pagador_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}