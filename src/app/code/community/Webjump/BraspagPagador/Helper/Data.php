<?php

/**
 * Data Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DEBUG_FILE = 'webjump_braspag.log';
	
	public function getModuleName()
	{
		return $this->_getModuleName();
	}
	
	public function getModuleVersion()
	{
		return (string)Mage::getConfig()->getModuleConfig($this->_getModuleName())->version;
	}

	public function isRequestFromBraspag()
	{
		$allowedIps = array_filter(array_map('trim', explode("\n", Mage::getStoreConfig('webjump_braspag_pagador/status_update/allowed_ips'))));
		$remoteAddr = Mage::helper('core/http')->getRemoteAddr();
		if (!in_array($remoteAddr, $allowedIps)) {
			return false;
		}
		else{
			return true;
		}
	}

	public function getDebugFlag($storeId = null)
	{	
		 return Mage::getStoreConfig('webjump_braspag_pagador/general/debug', $storeId);
	}
	
	public function debug($data)
	{
		if ($this->getDebugFlag()) {
			Mage::log($data, null, self::DEBUG_FILE);
		}
		return $this;
	}

	public function getCurrentRequestInfo()
	{
		$controller = Mage::app()->getRequest()->getControllerName();
		$action = Mage::app()->getRequest()->getActionName();
		$route = Mage::app()->getRequest()->getRouteName();
		$module = Mage::app()->getRequest()->getModuleName();
		return $controller . '/' . $action . ':' .$route . ':' . $module;		
	}

	public function getConfigModel()
	{
		return Mage::getSingleton('webjump_braspag_pagador/config');
	}
	
	public function getMerchantId($storeId = null)
	{
        $merchantId = trim(Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_id', $storeId));
        if (empty($merchantId)) {
            throw new Exception($this->__('Invalid merchant id in production environment. Please check configuration.'));
        }

		return $merchantId;	
	}

	public function getMerchantKey($storeId = null)
	{
        $merchantKey = trim(Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_key', $storeId));
        if (empty($merchantKey)) {
            throw new Exception($this->__('Invalid merchant key in production environment. Please check configuration.'));
        }

		return $merchantKey;
	}

    public function generateGuid($orderIncrementId)
    {
       	$orderIncrementId = preg_replace('/[^0-9]/', '0', $orderIncrementId);
        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16,  4) . '-' . str_pad($orderIncrementId, 12, '0', STR_PAD_LEFT);

        return $guid;
    }

	public function getOrderWithPendingPayment($orderId)
	{
		$order = Mage::getModel('sales/order')->getCollection()
			->addAttributeToFilter('customer_id', array('eq' => Mage::helper('customer')->getCustomer()->getId()))
			->addAttributeToFilter('increment_id', array('eq' => $orderId))
			->getFirstItem();

		if ($order->getEntityId()) {
			return $order;
		}

		return false;
	}

	public function invoiceOrder(Mage_Sales_Model_Order $order, $sendEmail, $captureOnline = true, $paymentDataResponse = [])
	{
		try {
	        $payment = $order->getPayment();
	        $method = $payment->getMethodInstance();

            $payment->registerCaptureNotification($paymentDataResponse['amount'], true);

	        $invoice = $order->prepareInvoice();
	        
	        if ($invoice->getTotalQty()) {
	            if ($method->canCapture() && $captureOnline) {
	                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
	            } else {
	                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);

                    $payment->setParentTransactionId($payment->getLastTransId())
                        ->setTransactionId($payment->getLastTransId()."-capture")
                        ->setIsTransactionClosed(0);

                    $raw_details = [];
                    foreach ($paymentDataResponse as $r_key => $r_value) {
                        $raw_details['payment_capture_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
                    }

                    $payment->resetTransactionAdditionalInfo();
                    $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);
	            }
	            
	            if ($sendEmail) {
	                $invoice->setEmailSent(true);
	                $invoice->getOrder()->setCustomerNoteNotify(true);
	            }

	            $invoice->getOrder()->setIsInProcess(true);

	            $invoice->register();

	            $transactionSave = Mage::getModel('core/resource_transaction')
	                ->addObject($invoice)
	                ->addObject($invoice->getOrder());
	            
	            $transactionSave->save();

                $payment->save();

	            if ($sendEmail) {
	                $invoice->sendEmail(true);
	            }
	        }
	    } catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
        return true;
    }

    public function removeAccents($string) {
        return preg_replace(
            array("/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ç)/",
                "/(Ç)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            array("a","A","e","E","i","I","o","O","u","U","c","C","n","N"),
            $string
        );
    }

    public function clearSpaces($string)
    {
        return preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $string);
    }

    public function clearTaxVat($taxvat)
    {
        return preg_replace('/[^0-9]/', '', $taxvat);
    }
}
