<?php
/**
 * Mpi Method Authorize Request
 *
 * @category  Method
 * @package   Braspag_Lib_Mpi_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Mpi_Auth_GetToken_RequestInterface
{
    public function getAuthorization();

    public function getEstablishmentCode();

    public function getMerchantName();

    public function getMcc();
}
