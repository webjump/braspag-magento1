<?php

class Braspag_AntiFraud_Block_Checkout_DeviceFingerPrint extends Mage_Core_Block_Template
{
    /**
     * @var false|Mage_Core_Model_Abstract 
     */
    protected $antiFraudConfigModel;

    /**
     * Braspag_AntiFraud_Block_Checkout_Antifraud_DeviceFingerPrint constructor.
     */
    public function __construct()
    {
        $this->antiFraudConfigModel = Mage::getModel('braspag_antifraud/config');
    }

    /**
     * @return string
     */
    protected function _toHtml()
	{
		if (!$this->antiFraudConfigModel->getGeneralConfig()->isAntifraudActive()) {
			return '';
		}

		return parent::_toHtml();
	}

    /**
     * @return string
     */
	public function getDomain()
	{
		return $this->antiFraudConfigModel->getFingerPrintConfig()->getFingerPrintDomain();
	}

    /**
     * @return string
     */
	public function getMerchantId()
	{
		return $this->antiFraudConfigModel->getFingerPrintConfig()->getFingerPrintMerchantId();
	}

    /**
     * @return string
     */
	public function getOrgId()
	{
		return $this->antiFraudConfigModel->getFingerPrintConfig()->getFingerPrintOrgId();
	}

    /**
     * @return mixed
     */
	public function getSessionId()
	{
		 return $this->getMerchantId().Mage::getSingleton("core/session")->getEncryptedSessionId();
	}
}