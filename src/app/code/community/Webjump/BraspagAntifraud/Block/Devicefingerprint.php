<?php
class Webjump_BraspagAntifraud_Block_Devicefingerprint extends Mage_Core_Block_Template
{
	protected function _toHtml()
	{
		//Check if should display devicefingerprint
		if (!Mage::helper('webjump_braspag_antifraud')->isActive()) {
			return '';
		}

		return parent::_toHtml();
	}

	public function getDomain()
	{
		return $this->helper('webjump_braspag_antifraud/devicefingerprint')->getDomain();
	}
	
	public function getMerchantId()
	{
		return $this->helper('webjump_braspag_antifraud/devicefingerprint')->getMerchantId();
	}
	
	public function getOrgId()
	{
		return $this->helper('webjump_braspag_antifraud/devicefingerprint')->getOrgId();
	}
	
	public function getSessionId()
	{
		 return $this->helper('webjump_braspag_antifraud/devicefingerprint')->getSessionId();
	}
}