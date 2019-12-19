<?php
class Webjump_BraspagPagador_Block_Checkout_Success extends Mage_Checkout_Block_Onepage_Success
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
                if ($payment['integrationType'] == 'TRANSACTION_BILLET') {
                    $data[] = array(
                        'target' => '_blank',
                        'label' => Mage::getStoreConfig('payment/webjump_braspag_boleto/payment_button_label'),
                        'url' => $payment['url'],
                    );
                } elseif ($payment['integrationType'] == 'TRANSACTION_DEBITCARD') {
                    $data[] = array(
                        'target' => '',
                        'label' => Mage::getStoreConfig('payment/webjump_braspag_dc/payment_button_label'),
                        'url' => $payment['authenticationUrl'],
                        'autoredirect' => Mage::getStoreConfig('payment/webjump_braspag_dc/autoredirect'),
                    );
                }
            }
        }

        return $data;
    }
}
