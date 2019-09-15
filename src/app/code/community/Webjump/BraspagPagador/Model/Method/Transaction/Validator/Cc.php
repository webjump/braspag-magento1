<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Validator_Cc extends Mage_Core_Model_Abstract
    implements Webjump_BraspagPagador_Model_Method_Transaction_Validator_CcInterface
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
        'authentication_failure_type',
        'authentication_cavv',
        'authentication_xid',
        'authentication_eci',
        'authentication_version',
        'authentication_reference_id'
    );

    protected $total;

    /**
     * @param array $paymentRequest
     * @param null $total
     * @return array|mixed
     */
	public function validate(array $paymentRequest, $total = null)
    {
        $paymentRequest = $this->addEncryptedNumber($paymentRequest);
        $paymentRequest = $this->validateTotal($paymentRequest, $total);
        $paymentRequest = $this->filterValidAttributes($paymentRequest);
        $paymentRequest = $this->validateType($paymentRequest);
        $paymentRequest = $this->validateInstallments($paymentRequest);
        $paymentRequest = $this->validateMpi($paymentRequest);

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return array
     */
    protected function filterValidAttributes($paymentRequest)
    {
        return array_intersect_key($paymentRequest, array_fill_keys($this->getValidAttributes(), true));
    }

    /**
     * @param $paymentRequest
     * @return mixed
     */
    protected function validateType($paymentRequest)
    {
        if ((isset($paymentRequest['cc_type'])) &&
           (!$paymentRequest['cc_type_label'] = $this->getCreditCardPaymentMethod()->getCcAvailableTypesLabelByCode($paymentRequest['cc_type']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Selected credit card type is not allowed.'));
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return mixed
     */
    protected function validateInstallments($paymentRequest)
    {
        if (!isset($paymentRequest['installments']) ||
            (!$paymentRequest['installments_label'] = $this->getCcInstallments()->getInstallmentLabel($paymentRequest['installments'], $paymentRequest['amount']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__("Selected installments is not allowed."));
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return mixed
     */
    protected function validateMpi($paymentRequest)
    {
        $mpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_mpi');
        $generalConfig = Mage::getSingleton('webjump_braspag_pagador/config');

        if ($mpiConfig->isMpiCcActive()) {

            $failureType = $paymentRequest['authentication_failure_type'];

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$mpiConfig->isBpmpiCcAuthorizedOnError()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$mpiConfig->isBpmpiCcAuthorizedOnFailure()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$mpiConfig->isBpmpiCcAuthorizedOnUnenrolled()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if (!$generalConfig->isTestEnvironmentEnabled()
                && !preg_match("#cielo#is", $paymentRequest['cc_type_label'])
                && $failureType != self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return bool
     */
    protected function isValidInstallment($paymentRequest)
    {
        return (($paymentRequest['installments'] > 0) && ($paymentRequest['installments'] <= $this->getCreditCardPaymentMethod()->getConfigData('installments')));
    }

    /**
     * @return array
     */
    protected function getValidAttributes()
    {
        return $this->validAttributes;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getCreditCardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getCcInstallments()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments');
    }

    /**
     * @param $paymentRequest
     * @param $total
     * @return mixed
     */
    protected function validateTotal($paymentRequest, $total)
    {
        if ((!isset($paymentRequest['amount'])) && ($total)) {
            $paymentRequest['amount'] = $total;
        }

        $paymentRequest['amount'] = Mage::helper('core')->currency($paymentRequest['amount'], false, false);

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return mixed
     */
    protected function addEncryptedNumber($paymentRequest)
    {
        if ($paymentRequest['cc_number']) {
            $paymentRequest['cc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $paymentRequest['cc_number']), str_repeat('*', 8), 4, 8);
        }

        return $paymentRequest;        
    }
}