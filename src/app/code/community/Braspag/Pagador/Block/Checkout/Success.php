<?php
class Braspag_Pagador_Block_Checkout_Success extends Mage_Checkout_Block_Onepage_Success
{
    private $order;

    public function getOrder()
    {
        if (!$this->order) {
            $this->order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
        }

        return $this->order;
    }

    protected function getPaymentLink()
    {
        $order = $this->getOrder();
        $payment = $order->getPayment();
        $additionalData = $payment->getAdditionalInformation();

        $data = array();

        if (!empty($additionalData['payment_response'])) {
            if ($payment = $additionalData['payment_response']) {
                if ($payment['integrationType'] == 'TRANSACTION_BOLETO') {
                    $data[] = array(
                        'target' => '_blank',
                        'label' => Mage::getStoreConfig('payment/braspag_boleto/payment_button_label'),
                        'url' => $payment['url'],
                    );
                } elseif ($payment['integrationType'] == 'TRANSACTION_DEBITCARD' && !empty($payment['authenticationUrl'])) {
                    $data[] = array(
                        'target' => '',
                        'label' => Mage::getStoreConfig('payment/braspag_debitcard/payment_button_label'),
                        'url' => $payment['authenticationUrl'],
                        'autoredirect' => Mage::getStoreConfig('braspag_pagador/dc/autoredirect'),
                    );
                }
            }
        }

        return $data;
    }
}
