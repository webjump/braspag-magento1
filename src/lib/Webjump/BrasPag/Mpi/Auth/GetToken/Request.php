<?php
/**
 * Mpi Transaction Authorize request
 *
 * @category  Method
 * @package   Webjump_BrasPag_Mpi_Auth_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Mpi_Auth_GetToken_Request
    extends Webjump_BrasPag_Mpi_Data_Abstract
    implements Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface
{
    protected $authorization;
    protected $establishmentCode;
    protected $merchantName;
    protected $mcc;

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

    /**
     * @return mixed
     */
    public function getEstablishmentCode()
    {
        return $this->establishmentCode;
    }

    /**
     * @param mixed $establishmentCode
     */
    public function setEstablishmentCode($establishmentCode)
    {
        $this->establishmentCode = $establishmentCode;
    }

    /**
     * @return mixed
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * @param mixed $merchantName
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
    }

    /**
     * @return mixed
     */
    public function getMcc()
    {
        return $this->mcc;
    }

    /**
     * @param mixed $mcc
     */
    public function setMcc($mcc)
    {
        $this->mcc = $mcc;
    }
}
