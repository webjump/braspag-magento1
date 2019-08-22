<?php
class Webjump_BraspagAntifraud_Helper_Devicefingerprint extends Mage_Core_Helper_Abstract
{
	public function getDomain($storeId = null)
	{
		$sandboxFlag = Mage::helper('webjump_braspag_antifraud')->isSandbox();

		if ($sandboxFlag) {
			$domain = Mage::getStoreConfig('webjump_braspag_antifraud/devicefingerprint/domain_test');
		} else {
			$domain = Mage::getStoreConfig('webjump_braspag_antifraud/devicefingerprint/domain');
		}
 		
		return $domain;
	}
	
	public function getMerchantId()
	{
        $merchantId = Mage::getStoreConfig('webjump_braspag_antifraud/devicefingerprint/merchant_id');
        if (empty($merchantId)) {
            throw new Exception($this->__('Invalid DeviceFingerPrint merchant id in production environment. Please check configuration.'));
        }

		return $merchantId;	
	}
	
	public function getOrgId()
	{
		$sandboxFlag = Mage::helper('webjump_braspag_antifraud')->isSandbox();

		if ($sandboxFlag) {
			$orgId = Mage::getStoreConfig('webjump_braspag_antifraud/devicefingerprint/org_id_test');
		} else {
			$orgId = Mage::getStoreConfig('webjump_braspag_antifraud/devicefingerprint/org_id');
		}
 		
		return $orgId;	
	}
	
	public function getSessionId()
	{
		return Mage::getSingleton("core/session")->getEncryptedSessionId();
	}
}
