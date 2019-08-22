<?php

class Webjump_BraspagPagador_PaymentController extends Mage_Core_Controller_Front_Action
{
	public function reorderAction()
	{
		$orderId = $this->getRequest()->getQuery('order', Mage::getSingleton('webjump_braspag_pagador/session')->getOrderId());

		if (!$order = Mage::helper('webjump_braspag_pagador')->getOrderWithPendingPayment($orderId)) {
			return $this->_redirect();
		}

		if (!Mage::getSingleton('webjump_braspag_pagador/session')->getOrderId()) {
			Mage::getSingleton('webjump_braspag_pagador/session')->setOrderId($orderId);
		}		

		$payment = $order->getPayment();
		$paymentTotalPaid = $payment->getAdditionalInformation('authorized_total_paid');
		$paymentTotalDue = ($order->getGrandTotal() - $paymentTotalPaid);

		if ($paymentTotalDue <= 0) {
			return $this->_redirect();
		}

		foreach ($payment->getAdditionalInformation('payment_request') as $paymentRequest) {
			if ($paymentRequest['type'] == 'webjump_braspag_boleto') {
				return $this->_redirect();
			}
		}		

		//TODO: gambiarra para ser usada no bloco form reoder [refatorar]
		Mage::register('paymentTotalDue', $paymentTotalDue);

		if (($this->getRequest()->isPost()) && ($this->getRequest()->getQuery('ajax'))) {
			$result = array();
			try {
				$newPaymentRequest = $this->getRequest()->getPost('payment', array());
				$newPaymentRequest = $newPaymentRequest['payment_request'];

	            $newPaymentRequest = Mage::getSingleton('webjump_braspag_pagador/method_transaction_validator')->validate($newPaymentRequest);
	            $newPaymentRequest = Mage::getSingleton('webjump_braspag_pagador/method_transaction_validator_multi_totals')->validate($newPaymentRequest, $paymentTotalDue);
				$response = Mage::getSingleton('webjump_braspag_pagador/pagador')->authorize(array('order' => $order, 'payment' => $newPaymentRequest));

				if (!$response->getIsAuthorized()) {
					return Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Payment Not authorized'));
				}
				
				$paymentRequest = $payment->getAdditionalInformation('payment_request');
				$paymentRequest = (!empty($paymentRequest)) ? $paymentRequest : array();

				$payment->setAdditionalInformation('payment_request', array_merge($paymentRequest, $newPaymentRequest));
				$payment->setAdditionalInformation('payment_response', $response->updatePaymentResponse($payment->getAdditionalInformation('payment_response' )));
				$payment->setAdditionalInformation('authorized_total_paid', $response->updateAuthorizedTotalPaid($paymentTotalPaid));			
				$payment->save();

				$transaction = Mage::getModel('sales/order_payment_transaction')
							->setOrderPaymentObject($payment)
							->setOrder($order)
							->setTxnId($response->getTransactionId())
							->setTxnType($response->getTransactionType())
							->setIsClosed($response->getIsTransactionClose())
							->setAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $response->getTransactionAdditionalInfo())
							->save();

				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				$order->save();	

				Mage::getSingleton('checkout/type_onepage')->getCheckout()
				        ->setLastOrderId($order->getId())
				        ->setLastSuccessQuoteId($order->getQuoteId())
				        ->setLastQuoteId($order->getQuoteId());

				$result['success'] = true;
			} catch (Exception $e) {
				$result['success'] = false;
				$result['error'] = $e->getMessage();
			}
			$this->getResponse()->setHeader('Content-type', 'application/json');
			return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}

		$this->loadLayout();
		$this->renderLayout();
	}
}