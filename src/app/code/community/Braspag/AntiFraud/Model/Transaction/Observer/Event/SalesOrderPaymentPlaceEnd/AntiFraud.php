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
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Observer Autoloader
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Observer
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_AntiFraud_Model_Transaction_Observer_Event_SalesOrderPaymentPlaceEnd_AntiFraud
extends Mage_Core_Model_Abstract
{
    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function execute(\Varien_Event_Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();
        $paymentMethod = $payment->getMethodInstance()->getCode();
        $paymentOrderManager = Mage::getSingleton('braspag_pagador/payment_orderManager');
        $storeId = $payment->getOrder()->getStoreId();

        $paymentResponseData = $payment->getAdditionalInformation('payment_response');

        if (!$antiFraud = $paymentResponseData['fraudAnalysis']) {
            return $this;
        }

        $antiFraudConfigModel = Mage::getModel('braspag_antifraud/config')->getGeneralConfig();
        if (!$fraudAnalysisStatus = $antiFraud['Status']
            || !$antiFraudConfigModel->getGeneralConfig()->isAntifraudActive()
            || !in_array($paymentMethod, ['braspag_cc', 'braspag_justclick'])
        ) {
            return $this;
        }

        if (($fraudAnalysisStatus == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
            || $fraudAnalysisStatus == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
            || $fraudAnalysisStatus == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN)
        ) {

            $status = Mage::getStoreConfig('braspag_antifraud/creditcard_transaction/reject_order_status', $storeId);

            if ($status == 'payment_review') {
                $paymentOrderManager->setOrderStatusPaymentReview($payment);
                return $this;
            }

            if ($status == 'canceled') {
                $paymentOrderManager->setOrderStatusCanceled($payment);
                return $this;
            }

            if ($payment->getIsFraudDetected()) {
                $paymentOrderManager->setOrderStatusFraud($payment);
                return $this;
            }
        }

        if ($fraudAnalysisStatus == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {

            $status = Mage::getStoreConfig('braspag_antifraud/creditcard_transaction/review_order_status', $storeId);

            if ($status == 'fraud') {
                $paymentOrderManager->setOrderStatusFraud($payment);
                return $this;
            }

            if ($status == 'canceled') {
                $paymentOrderManager->setOrderStatusCanceled($payment);
                return $this;
            }

            if ($payment->getIsTransactionPending()) {
                $paymentOrderManager->setOrderStatusPaymentReview($payment);
                return $this;
            }
        }

        return $this;
    }
}