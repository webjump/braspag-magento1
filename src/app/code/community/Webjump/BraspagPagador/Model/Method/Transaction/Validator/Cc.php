<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Validator_Cc extends Mage_Core_Model_Abstract
{
    protected $validAttributes = array(
        'type',
        'cc_type',
        'cc_type_label',
        'cc_owner',
        'cc_number',
        'cc_number_masked',
        'cc_exp_month',
        'cc_exp_year',
        'cc_cid',
        'cc_token',
        'installments',
        'installments_label',
        'amount',
        'cc_justclick',
        'integrationType',
    );

    protected $total;

	public function validate($paymentRequest, $total = null)
    {
        $paymentRequest = $this->addEncryptedNumber($paymentRequest);
        $paymentRequest = $this->validateTotal($paymentRequest, $total);
        $paymentRequest = $this->filterValidAttributes($paymentRequest);
        $paymentRequest = $this->validateType($paymentRequest);
        $paymentRequest = $this->validateInstallment($paymentRequest);

        return $paymentRequest;
    }

    protected function filterValidAttributes($paymentRequest)
    {
        return array_intersect_key($paymentRequest, array_fill_keys($this->getValidAttributes(), true));
    }

    protected function validateType($paymentRequest)
    {
        if ((isset($paymentRequest['cc_type'])) &&
           (!$paymentRequest['cc_type_label'] = $this->getCreditCardPaymentMethod()->getCcAvailableTypesLabelByCode($paymentRequest['cc_type']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Selected credit card type is not allowed.'));
        }

        return $paymentRequest;
    }

    protected function validateInstallment($paymentRequest)
    {
        if (!isset($paymentRequest['installments']) ||
            (!$paymentRequest['installments_label'] = $this->getCcInstallments()->getInstallmentLabel($paymentRequest['installments'], $paymentRequest['amount']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__("Selected installments is not allowed."));
        }

        return $paymentRequest;
    }

    protected function isValidInstallment($paymentRequest)
    {
        return (($paymentRequest['installments'] > 0) && ($paymentRequest['installments'] <= $this->getCreditCardPaymentMethod()->getConfigData('installments')));
    }

    protected function getValidAttributes()
    {
        return $this->validAttributes;
    }

    protected function getCreditCardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc');
    }

    protected function getCcInstallments()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments');
    }

    protected function validateTotal($paymentRequest, $total)
    {
        if ((!isset($paymentRequest['amount'])) && ($total)) {
            $paymentRequest['amount'] = $total;
        }

        $paymentRequest['amount'] = Mage::helper('core')->currency($paymentRequest['amount'], false, false);

        return $paymentRequest;
    }

    protected function addEncryptedNumber($paymentRequest)
    {
        if ($paymentRequest['cc_number']) {
            $paymentRequest['cc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $paymentRequest['cc_number']), str_repeat('*', 8), 4, 8);
        }

        return $paymentRequest;        
    }
}