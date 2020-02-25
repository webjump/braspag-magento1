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
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment_Card
    extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment
{
    /**
     * @param $payment
     * @param $amount
     * @return array|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $dataPayment = $payment->getPaymentRequest();

        $providerBrand = explode("-", $dataPayment['dc_type']);

        $ccExpMonth = $dataPayment['dc_exp_month'];
        $ccExpYear = $dataPayment['dc_exp_year'];

        $cardData = [
            'CardHolder' => $dataPayment['dc_owner'],
            'CardNumber' => str_replace(array('.', ' '), '', $dataPayment['dc_number']),
            'CardSecurityCode' => (isset($dataPayment['dc_cid'])) ? $dataPayment['dc_cid'] : null,
            'CardExpirationDate' => sprintf('%1$02s/%2$s', $ccExpMonth, $ccExpYear),
            'CardBrand' => trim(isset($providerBrand[1]) ? $providerBrand[1] : 'Visa'),
            'SaveCard' => false,
            'CardAlias' => '',
            'CardOnFile' => [
                "Usage" =>"Used",
                "Reason" => "Unscheduled"
            ]
        ];

        $paymentCardCompositeData = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/authorize/debit_card/request/builder/composite/payment/card');

        $paymentCardComposite = $this->getTransactionBuilderComposite();

        foreach ($paymentCardCompositeData as $dataBuilder) {
            $paymentCardComposite->addData($dataBuilder);
        }

        $paymentCardCompositeData = $paymentCardComposite->getData($payment, $amount);

        $cardData = array_merge($cardData, $paymentCardCompositeData->getData());

        $creditCardCard = $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard\Card');
        $creditCardCard->populate($cardData);

        return $creditCardCard;
    }
}
