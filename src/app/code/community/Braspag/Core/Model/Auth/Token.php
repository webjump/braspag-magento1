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
 * @package   Braspag_Core_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Core_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Core_Model_Auth_Token extends Mage_Core_Model_Abstract
{
    protected $serviceManager;

    /**
     * @var Braspag_Core_Helper
     */
    protected $authHelper;

    /**
     * @var Braspag_Core_Model_Config
     */
    protected $generalConfig;

    /**
     * @var Braspag_Core_Model_Config
     */
    protected $authConfig;

    public function __construct()
    {
        $this->generalConfig = Mage::getSingleton('braspag_core/config_global_general');
        $this->authConfig = Mage::getSingleton('braspag_core/config_global_auth');
        $this->authHelper = Mage::helper('braspag_core/auth');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getToken()
    {
        $transaction = $this->getServiceManager()->get('Core\Auth\GetToken');
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
            'Authorization' => $this->authHelper->getAuthorizationHeader(),
        );

        $request = $this->getServiceManager()->get('Core\Auth\GetToken\Request');
        $request->populate($data);

        return $request;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     * @throws Mage_Core_Model_Store_Exception
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager($this->authConfig->getEndPoint());
    }
}