<?php

/**
 * Mpi Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Core_Helper_Auth extends Mage_Core_Helper_Abstract
{
    /**
     * @var Braspag_Core_Model_Config_Global_Auth
     */
    protected $authConfig;

    public function __construct()
    {
        $this->authConfig = Mage::getSingleton('braspag_core/config_auth');
    }

    /**
     * @return string
     */
    public function getAuthorizationHeader()
    {
        return base64_encode($this->authConfig->getClientId().":".$this->authConfig->getClientSecret());
    }

    /**
     * @param $total
     * @return float|int
     */
    public function convertReaisToCentavos($total)
    {
        return floatval($total) * 100;
    }
}
