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
class Webjump_BraspagPagador_Model_Pagador_Debitcard_Resource_Authorize_Request_Validator
{
    protected $validAttributes = array(
        'type',
        'dc_type',
        'dc_type_label',
        'dc_owner',
        'dc_number',
        'dc_number_masked',
        'dc_exp_month',
        'dc_exp_year',
        'dc_cid',
        'dc_token',
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
     * @param $paymentRequest
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validate($paymentRequest, $amount)
    {
//        $this->addEncryptedNumber($paymentRequest);
//        $this->validateTotal($paymentRequest);
        $this->filterValidAttributes($paymentRequest);
        $this->validateType($paymentRequest);
        $this->validateMpi($paymentRequest);

        return $this;
    }

    /**
     * @param $paymentRequest
     * @return array
     */
    protected function filterValidAttributes($paymentRequest)
    {
        return array_intersect_key($paymentRequest->getData(), array_fill_keys($this->getValidAttributes(), true));
    }

    /**
     * @param $paymentRequest
     * @return mixed
     * @throws Mage_Core_Exception
     */
    protected function validateType($paymentRequest)
    {
        if ((isset($paymentRequest['dc_type'])) &&
            (!$paymentRequest['dc_type_label'] = $this->getDebitcardPaymentMethod()->getDebitcardAvailableTypesLabelByCode($paymentRequest['dc_type']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Selected debit card type is not allowed.'));
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return mixed
     * @throws Mage_Core_Exception
     */
    protected function validateMpi($paymentRequest)
    {
        $mpiConfig = Mage::getSingleton('webjump_braspag_pagador/config_mpi');
        $generalConfig = Mage::getSingleton('webjump_braspag_pagador/config');

        if ($mpiConfig->isMpiDebitcardActive()) {

            $failureType = $paymentRequest['authentication_failure_type'];

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$mpiConfig->isBpmpiDebitcardOrderdOnError()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$mpiConfig->isBpmpiDebitcardOrderdOnFailure()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$mpiConfig->isBpmpiDebitcardOrderdOnUnenrolled()
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if (!$generalConfig->isTestEnvironmentEnabled()
                && !preg_match("#cielo#is", $paymentRequest['dc_type_label'])
                && $failureType != self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                Mage::throwException(Mage::helper('webjump_braspag_pagador')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return bool]
     */
    protected function isValidInstallment($paymentRequest)
    {
        return (($paymentRequest['installments'] > 0) && ($paymentRequest['installments'] <= $this->getDebitcardPaymentMethod()->getConfigData('installments')));
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
    protected function getDebitcardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_Debitcard');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getDebitcardInstallments()
    {
        return Mage::getSingleton('webjump_braspag_pagador/pagador_Debitcard_resource_Authorize_installmentsBuilder');
    }

//    /**
//     * @param $paymentRequest
//     * @param $total
//     * @return mixed
//     */
//    protected function validateTotal($paymentRequest, $total)
//    {
//        if ((!isset($paymentRequest['amount'])) && ($total)) {
//            $paymentRequest['amount'] = $total;
//        }
//
//        $paymentRequest['amount'] = Mage::helper('core')->currency($paymentRequest['amount'], false, false);
//
//        return $paymentRequest;
//    }

//    /**
//     * @param $paymentRequest
//     * @return mixed
//     */
//    protected function addEncryptedNumber($paymentRequest)
//    {
//        if ($paymentRequest['dc_number']) {
//            $paymentRequest['dc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $paymentRequest['dc_number']), str_repeat('*', 8), 4, 8);
//        }
//
//        return $paymentRequest;
//    }
}
