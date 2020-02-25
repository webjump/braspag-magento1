<?php
/**
 * Pagador ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_Pagador_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Transaction_ClientService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Client' => function ($serviceManager) {
                $config = $serviceManager->getConfig();
                return new Braspag_Lib_Pagador_Service_Client($config['webservice_url']);
            }
        );
    }
}
