<?php
abstract class Webjump_BraspagPagador_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract {

	protected $_apiType;

    protected $_canSaveCc     = false;

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

    /**
     * Retrieve model helper
     *
     * @return Mage_Payment_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

	public function getConfigModel()
	{
		return Mage::getSingleton('webjump_braspag_pagador/config');
	}

    /**
     * Define if debugging is enabled
     *
     * @return bool
     */
    public function getDebugFlag()
    {
    	return $this->getHelper()->getDebugFlag($this->getStore());
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
        return Mage::getModel('webjump_braspag_pagador/pagadorold');
    }

  
}