<?php
/**
 * Pagador Data Response ErrorReport
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data_Response
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Response_ErrorReport extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Response_ErrorReportInterface
{
    protected $errors = array();

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }
}
