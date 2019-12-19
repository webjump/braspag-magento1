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
class Webjump_BrasPag_Pagador_Transaction_ClientService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Client' => function ($serviceManager) {
                $config = $serviceManager->getConfig();
                return new Webjump_BrasPag_Pagador_Service_Client($config['webservice_wsdl']);
            }
        );
    }
}
