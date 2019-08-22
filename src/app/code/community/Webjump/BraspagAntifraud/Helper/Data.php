<?php
class Webjump_BraspagAntifraud_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isActive($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/general/active', $storeId);
	}

	public function getModuleName()
	{
		return $this->_getModuleName();
	}
	
	public function getModuleVersion()
	{
		return (string)Mage::getConfig()->getModuleConfig($this->_getModuleName())->version;
	}

	public function getDebugFlag($storeId = null)
	{	
		 return Mage::getStoreConfig('webjump_braspag_antifraud/general/debug', $storeId);
	}
	
	public function debug($data)
	{
		if ($this->getDebugFlag()) {
			Mage::log($data, null, Webjump_BraspagAntifraud_Model_Config::DEBUG_FILE);
		}
		return $this;
	}

	public function getConfigModel()
	{
		return Mage::getSingleton('webjump_braspag_antifraud/config');
	}
	
	public function isSandbox($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/general/sandbox_flag', $storeId);
	}
	
	public function isStatusUpdateActive($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/status_update/active', $storeId);
	}

	public function getStatusUpdateApproved($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/status_update/status_approved', $storeId);
	}
		
	public function getStatusUpdateRejected($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/status_update/status_rejected', $storeId);
	}

	public function getStatusUpdateReview($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/status_update/status_review', $storeId);
	}
	
	public function getStatusUpdateError($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/status_update/status_error', $storeId);
	}
	
	public function isDecisionManagerActive($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/general/decision_manager', $storeId);
	}

	public function getMerchantId($storeId = null)
	{
        $merchantId = Mage::getStoreConfig('webjump_braspag_antifraud/general/merchant_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception($this->__('Invalid merchant id in production environment. Please check configuration.'));
        }

		return $merchantId;	
	}
	
	public function getEnvironment($storeId = null)
	{
		$environment = $this->isSandbox($storeId) ? Webjump_BraspagAntifraud_Model_Config::ENVIRONMENT_SANDBOX : Webjump_BraspagAntifraud_Model_Config::ENVIRONMENT_PRODUCTION;
		return $environment;
	}
	
	public function isAutoReviewOrder($order)
	{
		if (!Mage::helper('webjump_braspag_antifraud')->isActive($order->getStoreId())) {
			return false;
		}
		
		if (!Mage::getStoreConfig('webjump_braspag_antifraud/auto/active', $order->getStoreId())) {
			return false;
		}
		
	
		$minimumAmount = Mage::getStoreConfig('webjump_braspag_antifraud/auto/minimum_amount', $order->getStoreId());	
		if ($order->getGrandTotal() < $minimumAmount) {
			return false;
		}
		
		$paymentMethods = explode(',', Mage::getStoreConfig('webjump_braspag_antifraud/auto/payment_methods', $order->getStoreId()));	
		if (!in_array($order->getPayment()->getMethodInstance()->getCode(), $paymentMethods)) {
			return false;
		}
		
		return true;
	}

	public function isAutoInvoiceOrder($order)
	{
		if (!Mage::helper('webjump_braspag_antifraud')->isActive($order->getStoreId())) {
			return false;
		}
		
		if (!Mage::getStoreConfig('webjump_braspag_antifraud/autoinvoice/active',$order->getStoreId())) {
			return false;
		}
		
		$paymentMethods = explode(',', Mage::getStoreConfig('webjump_braspag_antifraud/autoinvoice/payment_methods', $order->getStoreId()));
		
		if (!in_array($order->getPayment()->getMethodInstance()->getCode(), $paymentMethods)) {
			return false;
		}
		
		return true;
	}

	public function isAutoInvoiceSendMail($storeId = null)
	{
		return Mage::getStoreConfig('webjump_braspag_antifraud/autoinvoice/send_email', $storeId);
	}

    public function getInternationalInfoCodeLabel($code)
    {
        if ($code) {
            return $this->__($code);
        }

        return false;
    }
    
    public function getOrderReviewBlock()
	{
		return Mage::app()->getLayout()->createBlock(
			'webjump_braspag_antifraud/adminhtml_sales_order_view_info_antifraud',
			'webjump.braspag_antifraud.order_info_antifraud',
			array('template' => 'webjump/braspag_antifraud/sales/order/view/info/antifraud.phtml')
		);
	}
	
	public function getStatusName($statusCode)
	{
		$_statusCodeConst = 'STATUS_CODE_' . $statusCode;
		return constant('Webjump_BraspagAntifraud_Model_Config::' . $_statusCodeConst);
	}
	
	public function getStatusMsg($statusCode)
	{
		$_statusMsgConst = 'MESSAGE_STATUS_' . $this->getStatusName($statusCode);

		return $this->__(constant('Webjump_BraspagAntifraud_Model_Config::' . $_statusMsgConst));
	}

	public function getStatusShortMsg($statusCode)
	{
		$_statusMsgConst = 'SHORT_MESSAGE_STATUS_' . $this->getStatusName($statusCode);

		return $this->__(constant('Webjump_BraspagAntifraud_Model_Config::' . $_statusMsgConst));
	}
	
	public function getStatusLabelByCode($statusCode)
	{
        $status = $this->getStatus();
        return (isset($status[$statusCode])) ? $status[$statusCode] : false;
	}

    public function getStatus()
    {
        return array(
            Webjump_BraspagAntifraud_Model_Api::STATUS_STARTED => $this->__('Review'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_ACCEPT => $this->__('Accept'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW => $this->__('Review'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_REJECT => $this->__('Reject'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_PENDENT => $this->__('Pendent'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_UNFINISHED => $this->__('Unfinished'),
            Webjump_BraspagAntifraud_Model_Api::STATUS_ABORTED => $this->__('Aborted'),
        );
    }
	
	public function getSignatureBlock()
	{
		return Mage::app()->getLayout()->createBlock('webjump_braspag_antifraud/signature');
	}
}
