<?php

class Webjump_BraspagPagador_Block_Checkout_Antifraud_DeviceFingerPrint extends Mage_Core_Block_Template
{
    /**
     * @var Webjump_BraspagPagador_Model_Config_Antifraud
     */
    protected $antifraudConfigModel;

    /**
     * Webjump_BraspagPagador_Block_Checkout_Antifraud_DeviceFingerPrint constructor.
     */
    public function __construct()
    {
        $this->antifraudConfigModel = Mage::getModel('webjump_braspag_pagador/config_antifraud');
    }

    /**
     * @return string
     */
    protected function _toHtml()
	{
		if (!$this->antifraudConfigModel->isAntifraudActive()) {
			return '';
		}

		return parent::_toHtml();
	}

    /**
     * @return string
     */
	public function getDomain()
	{
		return $this->antifraudConfigModel->getFingerPrintDomain();
	}

    /**
     * @return string
     */
	public function getMerchantId()
	{
		return $this->antifraudConfigModel->getFingerPrintMerchantId();
	}

    /**
     * @return string
     */
	public function getOrgId()
	{
		return $this->antifraudConfigModel->getFingerPrintOrgId();
	}

    /**
     * @return mixed
     */
	public function getSessionId()
	{
		 return $this->getMerchantId().Mage::getSingleton("core/session")->getEncryptedSessionId();
	}
}