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
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction_JustClick extends Webjump_BraspagPagador_Model_Pagador_Transaction
{

    protected function getPaymentsData()
    {
        $helper = $this->getHelper();
        $payment = $this->getPayment();
        $amount = $this->getAmount();
        $configModel = $this->getConfigModel();
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

        $dataPayments = $this->getServiceManager()->get('Pagador\Data\Request\Payment\List');

        foreach ($payment->getPaymentRequest() as $key => $value) {
            $card = $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');

            if (!isset($value['installments'])) {
                $value['installments'] = 1;
            }

            $dataCard = array(
                'paymentMethod' => Mage::getSingleton('webjump_braspag_pagador/justclick_card')->getPaymentMethodIdByToken($value['cc_token']),
                'cardToken' => $value['cc_token'],
                'amount' => number_format((empty($value['amount']) ? $amount : $value['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'numberOfPayments' => $value['installments'],
//                'paymentPlan' => $value['installments'] == 1 ? $configModel::PAYMENT_PLAN_CASH : $paymentPlan,
                'transactionType' => $transactionType,
                'cardSecurityCode' => $value['cc_cid'],
            );

            $card->populate($dataCard);
            $dataPayments->add($card);
        }

        return $dataPayments;

    }
}
