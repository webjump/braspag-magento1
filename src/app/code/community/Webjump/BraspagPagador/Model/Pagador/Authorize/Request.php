<?php
//
//class Webjump_BraspagPagador_Model_Pagador_Authorize_Request extends varien_Object
//{
//	public function import(array $data)
//	{
//		if (!isset($data['order'])) {
//			return Mage::throwException('Order not set');
//		}
//
//		$order = $data['order'];
//
//		$request = $this->importDefaultData($order);
//		$request = $this->importOrderData($data, $request, $order);
//	    $request = $this->importCustomerData($data, $request, $order);
//	    $request = $this->importPaymentData($data, $request, $order);
//
//	    return $request;
//	}
//
//	protected function importDefaultData($order)
//	{
//		return array(
//	        'requestId' => $this->generateRequestId($order->getIncrementId()),
//	    );
//	}
//
//	protected function importOrderData($data, $request, $order)
//	{
//		$request['order'] =  array(
//            'merchantId' => Mage::getSingleton('webjump_braspag_pagador/config')->getMerchantId(),
//            'merchantKey' => Mage::getSingleton('webjump_braspag_pagador/config')->getMerchantKey(),
//            'orderId' => $order->getIncrementId(),
//	    );
//
//	    if (isset($data['braspagOrderId'])) {
//	    	$request['order']['braspagOrderId'] = $data['braspagOrderId'];
//	    }
//
//	    return $request;
//	}
//
//	protected function importCustomerData($data, $request, $order)
//	{
//		$request['customer'] = array(
//	        'identity' => $order->getCustomerId(),
//	        'identityType' => null,
//	        'name' => $order->getCustomerName(),
//	        'email' => $order->getCustomerEmail(),
//	        'birthdate' => $order->getCustomerEmail(),
//	    );
//
//		if ($taxvat = $order->getCustomerTaxvat()) {
//			$_hlpValidate = Mage::helper('webjump_braspag_pagador/validate');
//
//			switch ($_hlpValidate->isCpfOrCnpj($taxvat)) {
//				case $_hlpValidate::CPF: $identityType = 'CPF';break;
//				case $_hlpValidate::CNPJ: $identityType = 'CNPJ';break;
//				default:
//					throw new Exception(Mage::helper('webjump_braspag_pagador')->__('Invalid identity type'));
//			}
//
//			$request['customer']['identity'] = $taxvat;
//			$request['customer']['identityType'] = $identityType;
//		}
//
//		$address = $order->getShippingAddress();
//		$street = $address->getStreet();
//
//		$request['customer']['address']['street'] = $street[0];
//		$request['customer']['address']['zipCode'] = $address->getPostcode();
//		$request['customer']['address']['city'] = $address->getCity();
//		$request['customer']['address']['country'] = $address->getCountry();
//
//		return $request;
//	}
//
//	protected function importPaymentData($data, $request, $order)
//	{
//		if (!isset($data['payment'])) {
//			return Mage::throwException('Payment not set');
//		}
//
//		switch (Mage::getStoreConfig('payment/webjump_braspag_cc/payment_action')) {
//			case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE: $transactionType = 1;break;
//			case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE: $transactionType = 2;break;
//			default:
//				throw new Exception(Mage::helper('webjump_braspag_pagador')->__('Invalid transaction type'));
//		}
//
//		if($payment = $data['payment']) {
//	    	switch ($payment['type']) {
//	    		case 'webjump_braspag_cc':
//	    			$request['payment'] = array(
//	    				'type' => 'webjump_braspag_cc',
//						'paymentMethod' => $payment['cc_type'],
//						'amount' => number_format($payment['amount'], 2, '', ''),
//						'currency' => $order->getOrderCurrencyCode(),
//						'country' => Mage::getStoreConfig('webjump_braspag_pagador/general/country'),
//						'numberOfPayment' => $payment['installments'],
//						'paymentPlan' => $payment['installments'] == 1 ? Webjump_BraspagPagador_Model_Config::PAYMENT_PLAN_CASH : Mage::getStoreConfig('payment/webjump_braspag_cc/installments_plan'),
//						'transactionType' => $transactionType,
//						'cardHolder' => $payment['cc_owner'],
//						'cardNumber' => str_replace(array('.', ' '), '', $payment['cc_number']),
//						'cardSecurityCode' => (isset($payment['cc_cid'])) ? $payment['cc_cid'] : null,
//						'cardExpirationDate' => sprintf('%1$02s/%2$s', $payment['cc_exp_month'], $payment['cc_exp_year']),
//						'saveCreditCard' => isset($payment['cc_justclick']),
//						'cardToken' => ((isset($payment['cc_token'])) ? $payment['cc_token'] : null),
//			        );
//	    			break;
//	    		case 'webjump_braspag_dc':
//	    			$request['payment'] = array(
//                        'type' => 'webjump_braspag_dc',
//						'paymentMethod' => $payment['dc_type'],
//						'amount' => number_format($payment['amount'], 2, '', ''),
//						'currency' => $order->getOrderCurrencyCode(),
//						'country' => Mage::getStoreConfig('webjump_braspag_pagador/general/country'),
//						'cardHolder' => $payment['dc_owner'],
//						'cardNumber' => str_replace(array('.', ' '), '', $payment['dc_number']),
//						'cardSecurityCode' => $payment['dc_cid'],
//						'cardExpirationDate' => sprintf('%1$02s/%2$s', $payment['dc_exp_month'], $payment['dc_exp_year']),
//                    );
//                    break;
//                case 'webjump_braspag_boleto':
//                	$boletoExpirationDate = date('m/d/Y', strtotime(date('Y-m-d') . ' +' . (int) Mage::getStoreConfig('payment/webjump_braspag_boleto/boleto_expiration_date') . ' day'));
//                	$request['payment'] = array(
//                		'type' => 'webjump_braspag_boleto',
//						'paymentMethod' => $payment['boleto_type'],
//						'amount' => number_format($payment['amount'], 2, '', ''),
//						'currency' => $order->getOrderCurrencyCode(),
//						'country' => Mage::getStoreConfig('webjump_braspag_pagador/general/country'),
//						'boletoNumber' => $order->getIncrementId(),
//						'boletoInstructions' => Mage::getStoreConfig('payment/webjump_braspag_boleto/boleto_instructions'),
//						'boletoExpirationDate' => $boletoExpirationDate,
//                	);
//
//	    	}
//		}
//
//		return $request;
//	}
//
//    protected function generateRequestId($orderIncrementId)
//    {
//    	$orderIncrementId = preg_replace('/[^0-9]/', '0', $orderIncrementId);
//        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
//        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16,  4) . '-' . str_pad($orderIncrementId, 12, '0', STR_PAD_LEFT);
//
//        return $guid;
//    }
//}