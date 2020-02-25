<?php

abstract class Braspag_Pagador_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract
{
	protected $_apiType;

    protected $_canSaveCreditCard     = false;

    protected $_isInitializeNeeded			= false;
    protected $_isGateway                   = true;
    protected $_canOrder                    = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = false;
    protected $_canCapturePartial           = false;
    protected $_canRefund                   = false;
    protected $_canRefundInvoicePartial     = false;
    protected $_canVoid                     = false;
    protected $_canUseInternal              = true;
    protected $_canUseCheckout              = true;
    protected $_canUseForMultishipping      = true;
    protected $_canFetchTransactionInfo     = true;
    protected $_canReviewPayment            = false;
    protected $_canCreateBillingAgreement   = false;
    protected $_canManageRecurringProfiles  = false;

    public function __construct()
    {
        $braspagCoreConfigHelper = $this->getBraspagCoreConfigHelper();

        $this->_formBlockType = $braspagCoreConfigHelper
            ->getDefaultConfigClassModel('payment/'.$this->getCode().'/block/form');

        $this->_infoBlockType = get_class($braspagCoreConfigHelper
            ->getDefaultConfigClassModel('payment/'.$this->getCode().'/block/info'));
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }

    /**
     * Retrieve model helper
     *
     * @return Mage_Payment_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('braspag_pagador');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getBraspagCoreHelper()
    {
        return Mage::helper('braspag_core');
    }

	public function getConfigModel()
	{
		return Mage::getSingleton('braspag_pagador/config');
	}

    /**
     * Define if debugging is enabled
     *
     * @return bool
     */
    public function getDebugFlag()
    {
    	return $this->getBraspagCoreHelper()->getDebugFlag($this->getStore());
    }

    public function getApiType()
    {
        return $this->_apiType;
    }

	/**
	* Gateway response wrapper
	*
	* @param string $text
	* @return string
	*/
	protected function _wrapGatewayError($text)
	{
		return $this->getHelper()->__('Gateway error: %s', $text);
	}

	/**
     * Get Model Pagador
     * 
     * @return Webjump_BrasPag_Model_Pagador
     */
    public function getPagador()
    {
        return Mage::getModel('braspag_pagador/pagador');
    }
}