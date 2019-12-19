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
class Webjump_BraspagPagador_Model_Method_Transaction_Authorize_Creditcard
    extends Webjump_BraspagPagador_Model_Method_Transaction_Authorize
{
    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_authorize_creditcard';

    public function assignData($data)
    {
        parent::assignData($data);

        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();

        $paymentRequest = $paymentRequestInfo = array();

        if ($this->getCode() != Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(
            array(
                'type',
                'creditcard_type',
                'creditcard_owner',
                'creditcard_number',
                'creditcard_exp_month',
                'creditcard_exp_year',
                'creditcard_cid',
                'installments',
                'amount',
                'creditcard_justclick',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'
            ), true);
        $allowedVarsInfo = array_fill_keys(
            array(
                'type',
                'creditcard_type',
                'creditcard_type_label',
                'creditcard_owner',
                'creditcard_number_masked',
                'creditcard_exp_month',
                'creditcard_exp_year',
                'installments',
                'installments_label',
                'amount',
                'creditcard_justclick',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'
            ), true);
        $installments = $this->getInstallments();
        $creditCardTypes = $this->getCreditCardAvailableTypes();
        ($this->isJustClickActive()) ? $allowedVars['creditcard_justclick'] = true : null;

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key(reset($paymentRequestData), $allowedVars);

            if (!empty($data)) {
                if ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD) {
                    if (!empty($data['creditcard_number'])) {
                        $data['creditcard_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $data['creditcard_number']), str_repeat('*', 8), 4, 8);
                    }

                    if (!empty($data['creditcard_type'])) {
                        if (isset($creditCardTypes[$data['creditcard_type']])) {
                            $data['creditcard_type_label'] = $creditCardTypes[$data['creditcard_type']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected credit card type is not allowed.'));
                        }
                    }

                    if (!empty($data['installments'])) {
                        if (isset($installments[$data['installments']])) {
                            $data['installments_label'] = $installments[$data['installments']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected installments is not allowed.'));
                        }
                    }

                }

                $paymentRequest = $data;
                $paymentRequestInfo = array_intersect_key($data, $allowedVarsInfo);
            }
        }

        if (!empty($paymentRequest)) {
            $info->setAdditionalInformation('payment_request', $paymentRequestInfo);
            $info->setPaymentRequest($paymentRequest); //Also added fieldset in config.xml
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitializeNeeded()
    {
        return true;
    }

    public function initialize($paymentAction, $stateObject)
    {
        $payment = $this->getInfoInstance();

        $payment->authorize(true, $payment->getOrder()->getTotalDue());

        $stateObject->setData('is_notified', false);
    }

    /**
     * Return creditcard avaliable types
     *
     * @return array list of creditcard avaliable types
     */
    public function getCreditCardAvailableTypes()
    {
        $creditCardTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableCreditCardPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);

            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $creditCardTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $creditCardTypes;
    }

    /**
     * @return array
     */
    public function getCreditCardAvailableTypesCodes()
    {
        return array_keys($this->getCreditCardAvailableTypes());
    }

    /**
     * @param $code
     * @return bool|mixed
     */
    public function getCreditCardAvailableTypesLabelByCode($code)
    {
        $creditCardAvaliabletypes = $this->getCreditCardAvailableTypes();

        if (isset($creditCardAvaliabletypes[$code])) {
            return $creditCardAvaliabletypes[$code];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isJustClickActive()
    {
        return (boolean) $this->getConfigData('justclick_active');
    }

    /**
     * @return array|bool
     */
    public function getInstallments()
    {
        $installments = $this->getConfigData('installments');

        if (empty($installments)) {
            return false;
        }

        $_hlp = $this->getHelper();
        $_hlpCore = Mage::helper('core');
        $installmentsMinAmount = $this->getConfigData('installments_min_amount');
        $return = array();
        $installments++;

        $paymentInfo = $this->getInfoInstance();
        if ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
            $grandTotal = $paymentInfo->getOrder()->getGrandTotal();
        } else {
            $grandTotal = $paymentInfo->getQuote()->getGrandTotal();
        }

        for ($i = 1; $i < $installments; $i++) {
            $installmentAmount = $grandTotal / $i;

            if ($i > 1 && $installmentAmount < $installmentsMinAmount) {
                break;
            }

            $return[$i] = $_hlp->__('%1$sx %2$s without interest', $i, $_hlpCore->currency($installmentAmount, true, false));
        }

        return $return;
    }

    /**
     * @param $result
     * @param $payment
     * @param $amount
     * @return $this
     */
    protected function _importAuthorizeResultToPayment($result, $payment, $resultPayment)
    {
        $antiFraudConfig = Mage::getModel('webjump_braspag_pagador/config_antifraud');

        $resultData = $result->getDataAsArray();

        $status = $resultPayment->getStatus();

        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED
            || $status = Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED) {

            $this->totalPaid += $resultPayment->getAmount() / 100;

            $this->processAuthorizeInfoData($resultData['payment']);

            if ($antiFraudConfig->isAntifraudActive()
                && $fraudAnalysisStatus = $resultPayment->getFraudAnalysis()['Status']
            ) {
                $payment = $this->getAuthorizeFraudAnalysis($fraudAnalysisStatus, $payment);
            }
        }

        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING) {

            $payment->setIsTransactionPending(true);
            $this->processAuthorizeInfoData($resultData['payment']);
        }

        if ((!$this->totalPaid) && $resultData['payment']) {
            $this->errorMsg[] = $this->getHelper()->__('The payment was unauthorized.');
        }

        $payment
            ->setTransactionId($resultData['order']['braspagOrderId'])
            ->setIsTransactionClosed(0);

        $this->processAuthorizeRawDetails($resultData['payment'], $payment);
        $this->processAuthorizeErrors($payment);

        return $this;
    }

    /**
     * @param $fraudAnalysisStatus
     * @param $payment
     * @return mixed
     */
    protected function getAuthorizeFraudAnalysis($fraudAnalysisStatus, $payment)
    {
        $antiFraudConfig = Mage::getModel('webjump_braspag_pagador/config_antifraud');

        if ($antiFraudConfig->getOptionsSequence() === 'AnalyseFirst') {

            if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
                || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
                || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN
            ) {
                $payment->setIsFraudDetected(true);
            }

            if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {
                $payment->setIsTransactionPending(true);
            }
        }

        return $payment;
    }
}
