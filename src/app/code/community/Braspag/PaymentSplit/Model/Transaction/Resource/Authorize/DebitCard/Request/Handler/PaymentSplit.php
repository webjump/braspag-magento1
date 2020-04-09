<?php

/**
 * Model Cart
 *
 * @package     Webjump_AmbevCart
 * @author      Webjump Core Team <contato@webjump.com.br>
 * @copyright   2019 Webjump. (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */
class Braspag_PaymentSplit_Model_Transaction_Resource_Authorize_DebitCard_Request_Handler_PaymentSplit
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Handler
{
    /**
     * @param $payment
     * @param $request
     * @return $this
     */
    public function handle($payment, $request)
    {
        $splitPaymentConfigModel = Mage::getSingleton('braspag_paymentsplit/config_debitCard');

        if (!$splitPaymentConfigModel->isActive()) {
            return $this;
        }

        $splitPaymentData = $request->getPayment()->getSplitPayments();

        if (empty($splitPaymentData)) {
            return $this;
        }

        $quote = $payment->getOrder()->getQuote();

        $paymentSplitData = Mage::getModel('braspag_paymentsplit/paymentSplit')
            ->getPaymentSplitDataFromQuote($quote, $splitPaymentConfigModel);

        Mage::getSingleton('braspag_paymentsplit/paymentSplitManager')
            ->createPaymentSplitByQuote($quote, $paymentSplitData);

        return $this;
    }
}
