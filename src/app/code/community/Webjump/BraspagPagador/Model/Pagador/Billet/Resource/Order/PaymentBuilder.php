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
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Billet_Resource_Order_PaymentBuilder
{
    /**
     * @param $payment
     * @param $amount
     * @param $paymentType
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount, $paymentType)
    {
        $order = $payment->getOrder();
        $storeId = $order->getStoreId();

        $method = $payment->getMethodInstance();

        $currency = $order->getOrderCurrencyCode();
        $country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);

        $configBilletExpirationDate = $method->getConfigData('billet_expiration_date');

        if (trim($configBilletExpirationDate) == '') {
            $billetExpirationDate = '';
        } else {
            $nowDate = new \DateTime('now');
            $nowDate->add(new DateInterval('P'.(int) $configBilletExpirationDate.'D'));
            $billetExpirationDate = $nowDate->format("Y-m-d");
        }

        $dataPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');
        if ($billetData = $payment->getPaymentRequest()) {

            $currentData = array(
                'type' => $paymentType,
                'provider' => $method->getBilletType(),
                'amount' => number_format((empty($billetData['amount']) ? $amount : $billetData['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'billetNumber' => $order->getIncrementId(),
                'instructions' => $method->getConfigData('billet_instructions'),
                'expirationDate' => $billetExpirationDate,
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

            $dataRequest = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Billet');
            $dataRequest->populate($currentData);
            $dataPayment->set($dataRequest);
        }

        return $dataPayment;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager([]);
    }
}
