<?php
/**
 * Mpi Method Authorize Response
 *
 * @category  Method
 * @package   Braspag_Lib_Mpi_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Mpi_Auth_GetToken_ResponseInterface
{
    public function isSuccess();

    public function getErrorReport();

    public function getAccessToken();

    public function getTokenType();

    public function getExpiresIn();
}
