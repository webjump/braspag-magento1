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
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction_Creditcard
    extends Webjump_BraspagPagador_Model_Pagador_Transaction
{
    CONST PAYMENT_TYPE = "CreditCard";

    /**
     * @return bool|mixed
     * @throws Exception
     */
    protected function getPaymentData()
    {
        $helper = $this->getHelper();
        $payment = $this->getPayment();
        $amount = $this->getAmount();
//        $configModel = $this->getConfigModel();
        $method = $this->getMethod();
        $order = $this->getOrder();
        $storeId = $this->getStoreId();

//        $paymentPlan = $method->getConfigData('installments_plan', $storeId);
        $paymentAction = $method->getConfigData('payment_action', $storeId);

        switch ($paymentAction) {
            case $method::ACTION_AUTHORIZE:$transactionType = 1;
                break;
            case $method::ACTION_AUTHORIZE_CAPTURE:$transactionType = 2;
                break;
            default:
                throw new Exception($helper->__('Invalid transaction type'));
        }

        $currency = $order->getOrderCurrencyCode();
        $country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);

        $currentPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');

        if ($dataPayment = $payment->getPaymentRequest()) {
            $card = $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');

            if (!isset($dataPayment['installments'])) {
                $dataPayment['installments'] = 1;
            }

            $providerBrand = explode("-", $dataPayment['cc_type_label']);

            $dataCard = array(
                'type' => self::PAYMENT_TYPE,
                'provider' => trim(isset($providerBrand[0]) ? $providerBrand[0] : 'InvalidProvider'),
                'amount' => number_format((empty($dataPayment['amount']) ? $amount : $dataPayment['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'installments' => $dataPayment['installments'],
//                'paymentPlan' => $dataPayment['installments'] == 1 ? $configModel::PAYMENT_PLAN_CASH : $paymentPlan,
//                'transactionType' => $transactionType,
                'cardHolder' => $dataPayment['cc_owner'],
                'cardNumber' => str_replace(array('.', ' '), '', $dataPayment['cc_number']),
                'cardSecurityCode' => (isset($dataPayment['cc_cid'])) ? $dataPayment['cc_cid'] : null,
                'cardExpirationDate' => sprintf('%1$02s/%2$s', $dataPayment['cc_exp_month'], $dataPayment['cc_exp_year']),
                'cardBrand' => trim(isset($providerBrand[1]) ? $providerBrand[1] : 'Visa'),
                'cardAlias' => '',
                'saveCard' => true,
                "interest" => 'ByMerchant',
                "capture" => $transactionType == 2 ? true : false,
                "authenticate" => false,
                "recurrent" => false,
                "softDescriptor" => '',
                "doSplit" => false

            );

            $card->populate($dataCard);
            $currentPayment->set($card);
        }

        return $currentPayment;
    }
}
