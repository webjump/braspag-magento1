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
class Braspag_Auth3ds20_Helper_Mpi extends Mage_Core_Helper_Abstract
{
    /**
     * @var Braspag_Auth3ds20_Model_Config_Global_Auth
     */
    protected $authConfig;

    public function __construct()
    {
        $this->authConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi_auth');
    }

    /**
     * @return string
     * @throws Exception
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
