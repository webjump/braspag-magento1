<?php
/**
 * BrasPag Mpi Data Response ErrorReport Interface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Mpi_Data_Response_Order
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Core_Data_Response_ErrorReportInterface
{
    public function getErrors();

    public function setErrors(array $errors);
}