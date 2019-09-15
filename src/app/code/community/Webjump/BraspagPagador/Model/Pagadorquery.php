<?php
class Webjump_BraspagPagador_Model_Pagadorquery extends Mage_Core_Model_Abstract
{
    public function __construct($serviceManager)
    {
        return parent::__construct();
    }
    
	protected function getHelper()
	{
		return Mage::helper('webjump_braspag_pagador');
	}

    public function getConfigWsdl() {
        return $this->getConfig();
    }

	protected function getConfig()
	{
		$storeId = $this->getStoreId();
		$sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

		if ($sandboxFlag) {
			$config = Mage::getStoreConfig('webjump_braspag_pagador/pagadorquery/config/sandbox', $storeId);
		} else {
			$config = Mage::getStoreConfig('webjump_braspag_pagador/pagadorquery/config/production', $storeId);
		}

		return $config;
	}

	protected function getPagadorquery()
	{
		return new Webjump_BrasPag_Pagadorquery($this->getConfig());
	}

    /**
     * @return array
     * @throws Exception
     */
	public function getOrderIdData()
	{
        $libClass = $this->getPagadorquery()->setData($this->getData());
        return $libClass->getOrderIdData();
	}

    /**
     * @return mixed
     * @throws Exception
     */
	public function getTransactionData()
	{
        $libClass = $this->getPagadorquery()->setData($this->getData());
        return $libClass->getTransactionData();
	}
}