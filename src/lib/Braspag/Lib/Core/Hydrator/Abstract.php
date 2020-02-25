<?php

abstract class Braspag_Lib_Core_Hydrator_Abstract
{
    protected $serviceManager;

    /**
     * Braspag_Lib_Core_Hydrator_Abstract constructor.
     * @param Braspag_Lib_Core_Service_ManagerInterface $serviceManager
     */
    public function __construct(Braspag_Lib_Core_Service_ManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return Braspag_Lib_Pagador_Service_ServiceManagerInterface
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}