<?php
/**
 * Mpi Data Abstract
 *
 * @category  Data
 * @package   Braspag_Lib_Mpi_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Braspag_Lib_Core_Data_Abstract
    extends Braspag_Lib_Core_Data_Object
{
    public function populate(array $data)
    {
        $attributes = array_keys(get_class_vars(get_class($this)));
        $dataAlt = array();

        foreach ($data as $key => $value) {
            $dataAlt[lcfirst($key)] =  $value;
        }

        foreach ($attributes as $attribute) {
            $method = 'set' . ucfirst($attribute);

            if (method_exists($this, $method)) {
                $value = (isset($dataAlt[$attribute])) ? $dataAlt[$attribute] : null;

                if (!is_null($value)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }
}
