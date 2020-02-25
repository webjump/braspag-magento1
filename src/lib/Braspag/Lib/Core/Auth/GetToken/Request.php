<?php
/**
 * Core Transaction Authorize request
 *
 * @category  Method
 * @package   Braspag_Lib_Core_Auth_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Core_Auth_GetToken_Request
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Core_Auth_GetToken_RequestInterface
{
    protected $authorization;

    /**
     * @return mixed
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param mixed $authorization
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
    }
}
