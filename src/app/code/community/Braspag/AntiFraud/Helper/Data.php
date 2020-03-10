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
class Braspag_AntiFraud_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $dateTimeStartString
     * @param string $dateFormat
     * @return mixed
     * @throws Exception
     */
    public function getDaysQtyUntilNowFromDate($dateTimeStartString, $dateFormat = DATE_ATOM)
    {
        if (empty($dateTimeStartString)) {
            return null;
        }

        $dateTime = DateTime::createFromFormat($dateFormat, $dateTimeStartString);

        if (!$dateTime) {
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeStartString);
        }

        if (!$dateTime) {
            return null;
        }
        $todayDateTime   = new DateTime('today');
        return $dateTime->diff($todayDateTime)->days;
    }

    /**
     * @param $dateTimeStartString
     * @param string $dateFormat
     * @return int
     * @throws Exception
     */
    public function getYearsQtyUntilNowFromDate($dateTimeStartString, $dateFormat = DATE_ATOM)
    {
        if (empty($dateTimeStartString)) {
            return null;
        }

        $dateTime = DateTime::createFromFormat($dateFormat, $dateTimeStartString);

        if (!$dateTime) {
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeStartString);
        }

        if (!$dateTime) {
            return null;
        }

        $todayDateTime   = new DateTime('today');
        return $dateTime->diff($todayDateTime)->y;
    }
}
