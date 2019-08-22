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
class Webjump_BraspagPagador_Model_Pagador_Transaction_Boleto
    extends Webjump_BraspagPagador_Model_Pagador_Transaction
{
    CONST PAYMENT_TYPE = "Boleto";

    /**
     * @deprecated
     */
    protected function getPaymentsData()
    {
        return $this->getPaymentData();
    }

    /**
     * @return bool|mixed
     * @throws Exception
     */
    protected function getPaymentData()
    {
        $payment = $this->getPayment();
        $amount = $this->getAmount();
        $method = $this->getMethod();
        $order = $this->getOrder();
        $storeId = $this->getStoreId();

        $currency = $order->getOrderCurrencyCode();
        $country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);

        $configBoletoExpirationDate = $method->getConfigData('boleto_expiration_date');
        if (trim($configBoletoExpirationDate) == '') {
            $boletoExpirationDate = '';
        } else {
            $nowDate = new \DateTime('now');
            $nowDate->add(new DateInterval('P'.(int) $configBoletoExpirationDate.'D')).
            $boletoExpirationDate = $nowDate->format("Y-m-d");
        }

        $dataPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');
        if ($boletoData = $payment->getPaymentRequest()) {

            $currentData = array(
                'type' => self::PAYMENT_TYPE,
                'provider' => $method->getBoletoType(),
                'amount' => number_format((empty($boletoData['amount']) ? $amount : $boletoData['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'boletoNumber' => $order->getIncrementId(),
                'instructions' => $method->getConfigData('boleto_instructions'),
                'expirationDate' => $boletoExpirationDate,
                'assignor' => "",
                'demonstrative' => "",
                'identification' => "",
                'daysToFine' => "",
                'fineRate' => "",
                'fineAmount' => "",
                'daysToInterest' => "",
                'interestRate' => "",
                'interestAmount' => ""
            );

            $dataRequest = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Boleto');
            $dataRequest->populate($currentData);
            $dataPayment->set($dataRequest);
        }

        return $dataPayment;
    }
}