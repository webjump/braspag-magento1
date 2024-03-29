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
 * @package   Braspag_Auth3ds20_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Auth3ds20_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Auth3ds20_Model_Mpi_Auth extends Mage_Core_Model_Abstract
{
    protected $serviceManager;

    /**
     * @var Braspag_Auth3ds20_Helper_Mpi
     */
    protected $mpiHelper;

    /**
     * @var Braspag_Auth3ds20_Model_Config
     */
    protected $generalConfig;

    /**
     * @var Braspag_Auth3ds20_Model_Config_Mpi
     */
    protected $mpiConfig;

    public function __construct()
    {
        $this->generalConfig = Mage::getSingleton('braspag_core/config_general');
        $this->mpiConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi');
        $this->mpiHelper = Mage::helper('braspag_auth3ds20/mpi');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getToken()
    {
        $transaction = $this->getServiceManager()->get('Mpi\Auth\GetToken');
        $transaction->setRequest($this->getAuthRequest());

        return $transaction->execute();
    }

    /**
     * @return bool|mixed
     * @throws Exception
     */
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
     * @return Braspag_Lib_Core_Service_Manager
     * @throws Mage_Core_Model_Store_Exception
     */
    protected function getServiceManager()
    {
        return new Braspag_Lib_Core_Service_Manager($this->mpiConfig->getEndPoint());
    }
}