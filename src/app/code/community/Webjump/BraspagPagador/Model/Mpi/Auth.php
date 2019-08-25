<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Mpi_Auth extends Mage_Core_Model_Abstract
{
    protected $_serviceManager;

    /**
     * @var Webjump_BraspagPagador_Helper_Mpi
     */
    protected $mpiHelper;

    /**
     * @var Webjump_BraspagPagador_Model_Config
     */
    protected $generalConfig;

    /**
     * @var Webjump_BraspagPagador_Model_Config_Mpi
     */
    protected $mpiConfig;

    public function __construct()
    {
        $this->generalConfig = Mage::getSingleton('webjump_braspag_pagador/config');
        $this->mpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_mpi');
        $this->mpiHelper = Mage::helper('webjump_braspag_pagador/mpi');
    }

    public function getToken()
    {
        $transaction = $this->getServiceManager()->get('Mpi\Auth\GetToken');
        $transaction->setRequest($this->getAuthRequest());

        return $transaction->execute();
    }

    protected function getAuthRequest()
    {
        $data = array(
            'Authorization' => $this->mpiHelper->getAuthorizationHeader(),
            'EstablishmentCode' => $this->generalConfig->getEstablishmentCode(),
            'MerchantName' => $this->generalConfig->getMerchantName(),
            'Mcc' => $this->generalConfig->getMcc()
        );

        $request = $this->getServiceManager()->get('Mpi\Auth\GetToken\Request');
        $request->populate($data);

        return $request;
    }

    /**
     * @return Webjump_BrasPag_Mpi_Service_ServiceManager
     */
    protected function getServiceManager()
    {
        $this->_serviceManager = new Webjump_BrasPag_Mpi_Service_ServiceManager($this->mpiConfig->getEndPoint());
        return $this->_serviceManager;
    }
}