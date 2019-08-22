<?php
class Webjump_BraspagPagador_Model_Pagadorquery extends Mage_Core_Model_Abstract
{
	const STATUS_INDEFINIDA 	= 0;// A transação está com status indefinido
	const STATUS_CAPTURADA 		= 1; //A transação foi capturada
	const STATUS_AUTORIZADA 	= 2; //A transação foi autorizada
	const STATUS_NAO_AUTORIZADA = 3; //A transação não foi autorizada
	const STATUS_CANCELADA 		= 4; //A transação foi cancelada
	const STATUS_ESTORNADA 		= 5; //A transação foi estornada
	const STATUS_AGUARDANDO		= 6; //A Transação está Aguardando resposta
	const STATUS_DESQUALIFICADA = 7; //A Transação foi desqualificada

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

	public function getOrderIdData()
	{
		return $this->request(__FUNCTION__);
	}

	public function getTransactionData()
	{
		return $this->request(__FUNCTION__);
	}
	
	protected function request($call)
	{
		try{
			$helper = $this->getHelper();
			$helper->debug('--' . $call . '-start-');
			$helper->debug($this->getData());

			$libClass = $this->getPagadorquery()->setData($this->getData());
			$return = $libClass->$call();
			$this->setLastRequest($libClass->getLastRequest());
			$this->setLastResponse($libClass->getLastResponse());

			$helper->debug($this->getLastRequest());
			$helper->debug($this->getLastResponse());
			$helper->debug($return);
			$helper->debug('--' . $call . '-end-');
			return $return;
		} catch (Exception $e) {
			$errorMsg = sprintf('Error %1$s: %2$s (code %3$s)', __METHOD__, $e->getMessage(), $e->getCode());
			$helper->debug($libClass->getLastRequest());
			$helper->debug($errorMsg);
			Mage::logException(new Exception($errorMsg));
			Mage::throwException($helper->__('Error while %1$s.', $call));
		}
	}
}