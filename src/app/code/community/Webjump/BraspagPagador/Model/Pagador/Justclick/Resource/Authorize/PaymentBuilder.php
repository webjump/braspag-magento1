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
class Webjump_BraspagPagador_Model_Pagador_Justclick_Resource_Authorize_PaymentBuilder
{
    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

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

        $helper = $this->getHelper();
        $method = $payment->getMethodInstance();
        $storeId = $this->getStoreId();

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

        foreach ($payment->getPaymentRequest() as $key => $value) {
            $card = $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');

            if (!isset($value['installments'])) {
                $value['installments'] = 1;
            }

            $dataCard = array(
                'type' => $paymentType,
                'paymentMethod' => Mage::getSingleton('webjump_braspag_pagador/justclick_card')->getPaymentMethodIdByToken($value['cc_token']),
                'cardToken' => $value['cc_token'],
                'amount' => number_format((empty($value['amount']) ? $amount : $value['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'numberOfPayments' => $value['installments'],
                'transactionType' => $transactionType,
                'cardSecurityCode' => $value['cc_cid'],
            );

            $card->populate($dataCard);
            $currentPayment->add($card);
        }

        return $currentPayment;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager([]);
    }
}
