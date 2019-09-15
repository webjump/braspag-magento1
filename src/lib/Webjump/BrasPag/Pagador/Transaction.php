<?php
/**
 * Pagador Transaction Pagador
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction extends Webjump_BrasPag_Pagador_TransactionAbstract
{
    public function __construct(array $config, Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager = null)
    {
        return parent::__construct($this->config = $config, $this->serviceManager = $serviceManager);
    }
}
