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
class Webjump_BraspagPagador_Model_Method_Transaction_Cc
    extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_CC;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_creditcard';
    protected $_validator = 'webjump_braspag_pagador/method_transaction_validator_cc';

    protected $_formBlockType = 'webjump_braspag_pagador/form_cc';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_cc';

    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;

    /**
     * Return cc avaliable types
     *
     * @return array list of cc avaliable types
     */
    public function getCcAvailableTypes()
    {
        $ccTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableCcPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $ccTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $ccTypes;
    }

    /**
     * @return array
     */
    public function getCcAvailableTypesCodes()
    {
        return array_keys($this->getCcAvailableTypes());
    }

    /**
     * @param $code
     * @return bool|mixed
     */
    public function getCcAvailableTypesLabelByCode($code)
    {
        $ccAvaliabletypes = $this->getCcAvailableTypes();

        if (isset($ccAvaliabletypes[$code])) {
            return $ccAvaliabletypes[$code];
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
        if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
            || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
            || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN
        ) {
            $payment->setIsFraudDetected(true);
        }

        if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {
            $payment->setIsTransactionPending(true);
        }

        return $payment;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);

        if ($amount <= 0) {
            Mage::throwException($this->getHelper()->__('Invalid amount for capture.'));
        }

        if (!$payment->getTransactionId()) {
            $this->authorize($payment, $amount);
            return $this;
        }

        try {
            $result = $this->getPagador()->capture($payment, $amount);
            if ($result === false) {
                $errorMsg = $this->getHelper()->__('Error processing the request.');
                throw new Exception($errorMsg);
            }
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        if (!$result->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $result->getErrorReport()->getErrors()));
            Mage::throwException($errorMsg);
        } else {
            $this->_importCaptureResultToPayment($result, $payment, $amount);
        }

        return $this;
    }

    /**
     * @param $result
     * @param $payment
     * @param $amount
     * @return $this
     */
    protected function _importCaptureResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $resultData = $result->getDataAsArray();

        $capturedAmount = 0;
        $errorMsg = array();

        $this->errorMsg = $resultData['errorReport']['errors'];

        if ($success = (bool) $resultData['success']) {
            $capturedAmount += $payment->getOrder()->getGrandTotal();
        }

        if (!empty($errorMsg)) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $errorMsg));
            Mage::throwException($errorMsg);
        } elseif ($capturedAmount != $amount) {
            $formatedCapturedAmount = $order->getBaseCurrency()->formatTxt($capturedAmount);
            $formatedAmount = $order->getBaseCurrency()->formatTxt($amount);
            Mage::throwException($this->getHelper()->__('The captured amount (%1$s) differs from requested (%2$s).', $formatedCapturedAmount, $formatedAmount));
        }

        $raw_details['transaction_success'] = true;

        $payment
            ->setTransactionId($payment->getTransactionId())
            ->setIsTransactionClosed(0)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        try {
            $result = $this->getPagadorTransaction()->void($payment, $amount);
            if ($result === false) {
                $errorMsg = $this->getHelper()->__('Error processing the request.');
                throw new Exception($errorMsg);
            }
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        if (!$result->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $result->getErrorReport()->getErrors()));
            Mage::throwException($errorMsg);
        } else {
            $this->_importRefundResultToPayment($result, $payment, $amount);
        }

        return $this;

        return $this;
    }

    /**
     * @param $result
     * @param $payment
     * @param $amount
     * @return $this
     */
    protected function _importRefundResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $resultData = $result->getDataAsArray();

        $refundedAmount = 0;
        $errorMsg = array();

        $this->errorMsg = $resultData['errorReport']['errors'];

        if ($success = (bool) $resultData['success']) {
            $refundedAmount += $payment->getOrder()->getTotalRefunded();
        }

        if (!empty($errorMsg)) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $errorMsg));
            Mage::throwException($errorMsg);
        } elseif ($refundedAmount != $amount) {
            $formatedVoidedAmount = $order->getBaseCurrency()->formatTxt($refundedAmount);
            $formatedAmount = $order->getBaseCurrency()->formatTxt($amount);
            Mage::throwException($this->getHelper()->__('The voided amount (%1$s) differs from requested (%2$s).', $formatedVoidedAmount, $formatedAmount));
        }

        $raw_details['transaction_success'] = true;

        $payment
            ->setTransactionId($payment->getTransactionId())
            ->setIsTransactionClosed(1)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }
}
