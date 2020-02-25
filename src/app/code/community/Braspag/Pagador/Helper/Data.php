<?php

/**
 * Data Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $transactionId
     * @return mixed
     */
    public function cleanTransactionId($transactionId)
    {
        return str_replace(['-capture', '-refund', '-void'], '', $transactionId);
    }
}
