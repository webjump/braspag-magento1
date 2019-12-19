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
class Webjump_BrasPag_Pagador_Transaction_QueryService implements Webjump_BrasPag_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Pagador\Transaction\Query' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Transaction_Query($serviceManager);
            },
            'Pagador\Transaction\Query\Client' => function ($serviceManager) {
                return new Webjump_BrasPag_Pagador_Service_Client();
            }
        );
    }
}
