<?php
class Webjump_BraspagPagador_Block_Info extends Mage_Payment_Block_Info
{

    protected $_ccAvailableTypes = null;
    protected $_installments = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('webjump/braspag_pagador/payment/info/default.phtml');
    }

    /**
     * Prepare credit card related payment info
     *
     * @param  Varien_Object|array $transport
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        $additionalData = $this->getInfo()->getAdditionalInformation();
        $paymentRequest = (isset($additionalData['payment_request'])) ? $additionalData['payment_request'] : array();

        $_hlp = Mage::helper('webjump_braspag_pagador');

        $order = $this->getInfo()->getOrder();
        
        if ($order) {
			$payment = $order->getPayment();
			$paymentTotalDue = ($order->getGrandTotal() - $payment->getAdditionalInformation('authorized_total_paid'));

            if (!isset($paymentRequest['type'])) {
                $paymentTotalDue = 0;
            } elseif (($paymentRequest['type'] == 'webjump_braspag_boleto') || ($order->getPayment()->getMethodInstance()->getCode() != 'webjump_braspag_cccc') || (
                $this->getLayout() &&
                (in_array('braspag_payment_reorder', $this->getLayout()->getUpdate()->getHandles())))) {
                $paymentTotalDue = 0;
            }
		}
		
        if (!empty($paymentRequest)) {
            $paymentCount = count($additionalData['payment_request']);

            $tmp = array();

            if (
                $order &&
                $order->getBaseTotalDue() &&
                !empty($additionalData['payment_response']) &&
                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_DC'
            ) {
                $paymentResponse = $additionalData['payment_response'];
                $tmp[] = '<a href="' . $paymentResponse['authenticationUrl'] . '" class="button webjump-braspagpagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Order') . '</a>';
            }

            if (!empty($paymentRequest['cc_type']) && !empty($paymentRequest['cc_type_label'])) {
                $tmp[$_hlp->__('Credit Card Type')] = $paymentRequest['cc_type_label'];
            }

            if (!empty($paymentRequest['cc_owner'])) {
                $tmp[$_hlp->__('Name on the Card')] = $paymentRequest['cc_owner'];
            }

            if (!empty($paymentRequest['cc_number_masked'])) {
                $tmp[$_hlp->__('Credit Card Number')] = $paymentRequest['cc_number_masked'];
            }

            if (!empty($paymentRequest['cc_exp_month']) && !empty($paymentRequest['cc_exp_year'])) {
                $tmp[$_hlp->__('Expiration Date')] = $_hlp->__('%1$02s/%2$s', $paymentRequest['cc_exp_month'], $paymentRequest['cc_exp_year']);
            }

            if (!empty($paymentRequest['installments']) && !empty($paymentRequest['installments_label'])) {
                $tmp[$_hlp->__('Installments')] = $paymentRequest['installments_label'];
            }

            if ((!empty($paymentRequest['cc_justclick']) && (!empty($paymentRequest['cc_number_masked'])))) {
                $tmp[$_hlp->__('Add to braspag justclick as')] = $paymentRequest['cc_number_masked'];
            }

            if (!empty($paymentRequest['dc_type']) && !empty($paymentRequest['dc_type_label'])) {
                $tmp[$_hlp->__('Debit Card Type')] = $paymentRequest['dc_type_label'];
            }

            if (!empty($paymentRequest['dc_owner'])) {
                $tmp[$_hlp->__('Name on the Card')] = $paymentRequest['dc_owner'];
            }

            if (!empty($paymentRequest['dc_number_masked'])) {
                $tmp[$_hlp->__('Debit Card Number')] = $paymentRequest['dc_number_masked'];
            }

            if (!empty($paymentRequest['dc_exp_month']) && !empty($paymentRequest['dc_exp_year'])) {
                $tmp[$_hlp->__('Expiration Date')] = $_hlp->__('%1$02s/%2$s', $paymentRequest['dc_exp_month'], $paymentRequest['dc_exp_year']);
            }

            if ($paymentCount > 1 && !empty($paymentRequest['boleto_type'])) {
                $tmp[] = $paymentRequest['boleto_type'];
            }

            if (!empty($paymentRequest['amount'])) {
                $tmp[$_hlp->__('Amount')] = Mage::helper('core')->currency($paymentRequest['amount'], true, false);
            }

            if (
                $order &&
                $order->getBaseTotalDue() &&
                !empty($additionalData['payment_response']) &&
                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_BOLETO'
            ) {

                $paymentResponse = $additionalData['payment_response'];

                $tmp[$_hlp->__('Barcode representation')] = $paymentResponse['barCodeNumber'];
                $tmp[$_hlp->__('Expiration Date')] = \DateTime::createFromFormat('Y-m-d', $paymentResponse['expirationDate'])->format('d/m/Y');
                $tmp[] = '<a href="' . $paymentResponse['url'] . '" target="_blank" class="button webjump-braspagpagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Print Boleto') . '</a>';
            }

            if (!empty($paymentTotalDue)) {
                $tmp[] = '<a href="' . $this->getUrl('braspag/payment/reorder') . '?order=' . $order->getIncrementId() . '"class="button webjump-braspagpagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Now') . '</a>';
            }

            if (!$this->getIsSecureMode()) {
                //Add Secure information available only in backend
            }
//            if ($paymentCount > 1) {
//                $data[$_hlp->__('Payment %s', ($key + 1))] = $tmp;
//            } else {
                $data += $tmp;
//            }

        }

        return $transport->setData(array_merge($data, $transport->getData()));
    }

}
