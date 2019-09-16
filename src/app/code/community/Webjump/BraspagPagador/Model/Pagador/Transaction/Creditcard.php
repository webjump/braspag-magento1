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
 * @copyright 2019 Webjump (http://www.webjump.com.br)
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
        $payment = $this->getPayment();
        $amount = $this->getAmount();
        $order = $this->getOrder();
        $storeId = $this->getStoreId();

        $antiFraudModel = Mage::getSingleton('webjump_braspag_pagador/antifraud');

        $currency = $order->getOrderCurrencyCode();
        $country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);

        $currentPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');

        if ($dataPayment = $payment->getPaymentRequest()) {

            $card = $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');

            $providerBrand = explode("-", $dataPayment['cc_type_label']);

            if (isset($dataPayment[0])) {
                $dataPayment = reset($dataPayment);
                $providerBrand = explode("-", $dataPayment['cc_type']);
            }

            if (!isset($dataPayment['installments'])) {
                $dataPayment['installments'] = 1;
            }

            $dataCard = array(
                'type' => self::PAYMENT_TYPE,
                'provider' => trim(isset($providerBrand[0]) ? $providerBrand[0] : 'InvalidProvider'),
                'amount' => number_format((empty($dataPayment['amount']) ? $amount : $dataPayment['amount']), 2, '', ''),
                'currency' => $currency,
                'country' => $country,
                'installments' => $dataPayment['installments'],
                'cardHolder' => $dataPayment['cc_owner'],
                'cardNumber' => str_replace(array('.', ' '), '', $dataPayment['cc_number']),
                'cardSecurityCode' => (isset($dataPayment['cc_cid'])) ? $dataPayment['cc_cid'] : null,
                'cardExpirationDate' => sprintf('%1$02s/%2$s', $dataPayment['cc_exp_month'], $dataPayment['cc_exp_year']),
                'cardBrand' => trim(isset($providerBrand[1]) ? $providerBrand[1] : 'Visa'),
                'cardAlias' => '',
                'saveCard' => true,
                "interest" => 'ByMerchant',
                "capture" => false,
                "authenticate" => isset($dataPayment['authentication_failure_type']) ? true : false,
                "recurrent" => false,
                "softDescriptor" => '',
                "doSplit" => false
            );

            if (isset($dataPayment['authentication_failure_type'])) {
                $dataCard['authenticate'] = true;
                $dataCard['ExternalAuthentication'] = [
                    "Cavv" => $dataPayment['authentication_cavv'],
                    "Xid" => $dataPayment['authentication_xid'],
                    "Eci" => $dataPayment['authentication_eci'],
                    "Version" => $dataPayment['authentication_version'],
                    "ReferenceID" => $dataPayment['authentication_reference_id']
                ];
            }

            $antiFraudConfigModel = Mage::getSingleton('webjump_braspag_pagador/config_antifraud')
                ->setPaymentRequest($payment->getPaymentRequest());

            $dataFraudAnalysis = new Varien_Object();
            $dataFraudAnalysis->setIsActive(false);

            if ($antiFraudConfigModel->isAntifraudActive() && !$dataCard['authenticate']) {

                $dataFraudAnalysis->addData([
                    "sequence" => $antiFraudConfigModel->getOptionsSequence(),
                    "sequence_criteria" => $antiFraudConfigModel->getOptionsSequenceCriteria(),
                    "provider" => "Cybersource",
                    "capture_on_low_risk" => (bool) $antiFraudConfigModel->getOptionsCaptureOnLowRisk(),
                    "void_on_high_risk" => (bool) $antiFraudConfigModel->getOptionsVoidOnHighRisk(),
                    "total_order_amount" => $dataCard['amount'],
                    "finger_print_id" => $antiFraudModel->getFingerPrintId(),
                    "browser" => $antiFraudModel->getBrowserData(),
                    "cart" => $antiFraudModel->getCartData(),
                    "merchant_defined_fields" => $antiFraudModel->getMerchantDefinedFieldsData(),
                    "shipping" => $antiFraudModel->getShippingData(),
                    "travel" => $antiFraudModel->getTravelData(),
                ]);
                $dataFraudAnalysis->setIsActive(true);
            }

            $dataCard['FraudAnalysis'] = $dataFraudAnalysis;

            $card->populate($dataCard);
            $currentPayment->set($card);
        }

        return $currentPayment;
    }
}
