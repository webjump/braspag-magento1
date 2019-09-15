<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Validator_Dc extends Mage_Core_Model_Abstract
    implements Webjump_BraspagPagador_Model_Method_Transaction_Validator_DcInterface
{
    protected $validAttributes = array(
        'type',
        'dc_type',
        'dc_type_label',
        'dc_owner',
        'dc_number_masked',
        'dc_exp_month',
        'dc_exp_year',
        'amount',
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
     * @return array
     */
	public function validate(array $paymentRequest, $total = null)
    {
        $paymentRequest = $this->validateTotal($paymentRequest, $total);
        $paymentRequest = $this->filterValidAttributes($paymentRequest);
        $paymentRequest = $this->validateType($paymentRequest);
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
        if ((isset($paymentRequest['dc_type'])) &&
           (!$paymentRequest['dc_type_label'] = $this->getDebitCardPaymentMethod()->getDcAvailableTypesLabelByCode($paymentRequest['dc_type']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Selected debit card type is not allowed.'));
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return mixed
     */
    protected function validateInstallment($paymentRequest)
    {
        if (!isset($paymentRequest['installments']) ||
            (!$paymentRequest['installments_label'] = $this->getDcInstallments()->getInstallmentLabel($paymentRequest['installments'], $paymentRequest['amount']))) {
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

        if ($mpiConfig->isMpiDcActive()) {

            $failureType = $paymentRequest['authentication_failure_type'];

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$mpiConfig->isBpmpiDcAuthorizedOnError()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$mpiConfig->isBpmpiDcAuthorizedOnFailure()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$mpiConfig->isBpmpiDcAuthorizedOnUnenrolled()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if (!$generalConfig->isTestEnvironmentEnabled()
                && !preg_match("#cielo#is", $paymentRequest['dc_type_label'])
                && $failureType != self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
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
        return (($paymentRequest['installments'] > 0) && ($paymentRequest['installments'] <= $this->getDebitCardPaymentMethod()->getConfigData('installments')));
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
    protected function getDebitCardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_dc');
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

}