<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction_Debitcard extends Webjump_BraspagPagador_Model_Pagador_Transaction
{
    CONST PAYMENT_TYPE = "DebitCard";

    protected function getPaymentData()
    {
		$payment = $this->getPayment();
		$amount = $this->getAmount();
		$configModel = $this->getConfigModel();
		$method = $this->getMethod();
		$order = $this->getOrder();
		$storeId = $this->getStoreId();
		
		$currency = $order->getOrderCurrencyCode();
		$country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);
		
		$dataPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');

		if ($payment = $payment->getPaymentRequest()) {

			$card = $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard');

            $providerBrand = explode("-", $payment['dc_type']);

			$dataCard = array(
                'type' => self::PAYMENT_TYPE,
                'provider' => trim(isset($providerBrand[0]) ? $providerBrand[0] : 'InvalidProvider'),
                'amount' => number_format((empty($payment['amount']) ? $amount : $payment['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'cardHolder' => $payment['dc_owner'],
                'cardNumber' => str_replace(array('.', ' '), '', $payment['dc_number']),
                'cardSecurityCode' => $payment['dc_cid'],
                'cardExpirationDate' => sprintf('%1$02s/%2$s', $payment['dc_exp_month'], $payment['dc_exp_year']),
                'cardBrand' => trim(isset($providerBrand[1]) && $providerBrand[1] ? $providerBrand[1] : 'Visa'),
                "interest" => 'ByMerchant',
                "capture" => true,
                "authenticate" => true,
                "recurrent" => false,
                "softDescriptor" => '',
                "returnUrl" => $configModel->getDcReturnUrl()
			);

	        $card->populate($dataCard);
            $dataPayment->set($card);
		}
		
		return $dataPayment;
		
	}
}