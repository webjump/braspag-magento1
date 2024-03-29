<?php

class Braspag_Auth3ds20_MpiController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return Zend_Controller_Response_Abstract
     */
    public function authAction()
	{
        $responseAjax = new Varien_Object();
        $responseAjax->setSuccess(false);
        $responseAjax->setError('');

		if ($this->getRequest()->isAjax()) {
			try {
			    $mpiAuthToken = Mage::getSingleton('braspag_auth3ds20/mpi')->getAuthToken();
                $responseAjax->setToken($mpiAuthToken->getAccessToken());
                $responseAjax->setTokenType($mpiAuthToken->getTokenType());
                $responseAjax->setExpiresIn($mpiAuthToken->getExpiresIn());
                $responseAjax->setSuccess(true);
			} catch (Exception $e) {
                $responseAjax->setError($e->getMessage());
			}

			$this->getResponse()->setHeader('Content-type', 'application/json');
			return $this->getResponse()->setBody($responseAjax->toJson());
		}

		$this->loadLayout();
		$this->renderLayout();
	}

    /**
     * @return Zend_Controller_Response_Abstract
     */
    public function loadDataAction()
    {
        $responseAjax = new Varien_Object();
        $responseAjax->setSuccess(false);
        $responseAjax->setError('');

        if ($this->getRequest()->isAjax()) {

            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $mpiHelper = Mage::helper('braspag_auth3ds20/mpi');

            try {
                $grandTotal = $mpiHelper->convertReaisToCentavos($quote->getGrandTotal());

                $responseAjax->setCartTotalAmount($grandTotal);
                $responseAjax->setCartCurrency($quote->getQuoteCurrencyCode());
                $responseAjax->setCartOrderNumber($quote->getId());
                $responseAjax->setSuccess(true);
            } catch (Exception $e) {
                $responseAjax->setError($e->getMessage());
            }

            $this->getResponse()->setHeader('Content-type', 'application/json');
            return $this->getResponse()->setBody($responseAjax->toJson());
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return Zend_Controller_Response_Abstract
     */
    public function authenticateDataAction()
    {
        $responseAjax = new Varien_Object();
        $responseAjax->setSuccess(false);
        $responseAjax->setError('');

        if ($this->getRequest()->isAjax()) {

            $paymentType = $this->getRequest()->getParam('payment_type');

            try {
                $mpiData = Mage::getModel('braspag_auth3ds20/mpi_data');
                $responseAjax->setData($mpiData->getDataContent($paymentType));
                $responseAjax->setSuccess(true);
            } catch (Exception $e) {
                $responseAjax->setError($e->getMessage());
            }

            $this->getResponse()->setHeader('Content-type', 'application/json');
            return $this->getResponse()->setBody($responseAjax->toJson());
        }

        $this->loadLayout();
        $this->renderLayout();
    }
}