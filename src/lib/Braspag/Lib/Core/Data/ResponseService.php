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
class Braspag_Lib_Core_Data_ResponseService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Core\Data\Response\ErrorReport' => function ($serviceManager) {
                return new Braspag_Lib_Core_Data_Response_ErrorReport($serviceManager);
            }
        );
    }
}
