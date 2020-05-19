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
class Braspag_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DEBUG_FILE = 'braspag_core.log';

    /**
     * @return string
     */
	public function getModuleName()
	{
		return $this->_getModuleName();
	}

    /**
     * @return string
     */
	public function getModuleVersion()
	{
		return (string)Mage::getConfig()->getModuleConfig($this->_getModuleName())->version;
	}

    /**
     * @param null $storeId
     * @return mixed
     */
	public function getDebugFlag($storeId = null)
	{	
		 return Mage::getStoreConfig('braspag_core/general/debug', $storeId);
	}

    /**
     * @param $data
     * @return $this
     */
	public function debug($data)
	{
		if ($this->getDebugFlag()) {
			Mage::log($data, null, self::DEBUG_FILE);
		}
		return $this;
	}

    /**
     * @return Mage_Core_Model_Abstract
     */
	public function getConfigModel()
	{
		return Mage::getSingleton('braspag_core/config');
	}

    /**
     * @param $orderIncrementId
     * @return string
     */
    public function generateGuid($orderIncrementId)
    {
       	$orderIncrementId = preg_replace('/[^0-9]/', '0', $orderIncrementId);
        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16,  4) . '-' . str_pad($orderIncrementId, 12, '0', STR_PAD_LEFT);

        return $guid;
    }

    /**
     * @param $string
     * @return string|string[]|null
     */
    public function removeAccents($string) {
        return preg_replace(
            array("/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ç)/",
                "/(Ç)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            array("a","A","e","E","i","I","o","O","u","U","c","C","n","N"),
            $string
        );
    }

    /**
     * @param $string
     * @return string|string[]|null
     */
    public function clearSpaces($string)
    {
        return preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $string);
    }

    /**
     * @param $taxvat
     * @return string|string[]|null
     */
    public function clearTaxVat($taxvat)
    {
        return $this->clearNumberString($taxvat);
    }

    /**
     * @param $taxvat
     * @return string|string[]|null
     */
    public function clearPhoneNumber($phoneNumber)
    {
        return $this->clearNumberString($phoneNumber);
    }

    /**
     * @param $numberString
     * @return string|string[]|null
     */
    protected function clearNumberString($numberString)
    {
        return preg_replace('/[^0-9]/', '', $numberString);
    }
}
