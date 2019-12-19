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
class Webjump_BrasPag_Core_Data_ResponseService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Core\Data\Response\ErrorReport' => function ($serviceManager) {
                return new Webjump_BrasPag_Core_Data_Response_ErrorReport($serviceManager);
            }
        );
    }
}
