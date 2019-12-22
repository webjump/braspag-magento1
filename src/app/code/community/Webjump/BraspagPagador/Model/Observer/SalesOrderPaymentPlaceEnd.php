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
class Webjump_BraspagPagador_Model_Observer_SalesOrderPaymentPlaceEnd
{
    /**
     * @param Varien_Event_Observer $observer
     * @return $this|null
     */
    public function processAntiFraudAnalysis(Varien_Event_Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();

        $order = $payment->getOrder();

        try {

            $storeId = $order->getStoreId();
            $antiFraudConfigModel = Mage::getModel('webjump_braspag_pagador/config_antifraud');
            $helper = Mage::helper('webjump_braspag_pagador');

            $paymentMethod = $payment->getMethodInstance()->getCode();

            $paymentResponseData = $payment->getAdditionalInformation('payment_response');

            if ($fraudAnalysisStatus = $paymentResponseData['fraudAnalysis']['Status']
                && $antiFraudConfigModel->isAntifraudActive()
                && in_array($paymentMethod, ['webjump_braspag_cc', 'webjump_braspag_justclick'])
            ) {

                if (($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
                    || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
                    || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN)
                ) {

                    $status = Mage::getStoreConfig('antifraud/creditcard_transaction/reject_order_status', $storeId);

                    if ($status == 'payment_review') {
                        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                        $message = $helper->__("Possible Fraud Detected. Analysing.");

                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }

                    if ($status == 'canceled') {
                        $payment->setIsFraudDetected(false);
                        $order->cancel()->save();
                        $state = Mage_Sales_Model_Order::STATE_CANCELED;
                        $message = $helper->__("Canceled After Fraud Detected.");

                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }

                    if ($payment->getIsFraudDetected()) {
                        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                        $message = $helper->__("Anti Fraud - Fraud Detected.");
                        $status = 'fraud';

                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }
                }

                if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {

                    $status = Mage::getStoreConfig('antifraud/creditcard_transaction/review_order_status', $storeId);

                    if ($status == 'fraud') {

                        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                        $message = $helper->__("Fraud Detected.");
                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }

                    if ($status == 'canceled') {
                        $payment->setIsTransactionPending(false);
                        $order->cancel()->save();
                        $state = Mage_Sales_Model_Order::STATE_CANCELED;
                        $message = $helper->__("Canceled After Fraud Detected.");

                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }

                    if ($payment->getIsTransactionPending()) {
                        $status = 'payment_review';

                        $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                        $message = $helper->__('Anti Fraud - Payment Review.');

                        $order->setState($state, $status, $message)
                            ->save();

                        return $this;
                    }
                }
            }

            if (($paymentMethod == 'webjump_braspag_cc' || $paymentMethod == 'webjump_braspag_justclick')
                && !$payment->getIsFraudDetected()
                && !$payment->getIsTransactionPending()
                && $order->canInvoice()
                && $payment->getMethodInstance()->getConfigPaymentAction() == Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE
            ) {

                $sendEmail = Mage::getStoreConfig('webjump_braspag_pagador/status_update/send_email');
                $helper->invoiceOrder($order, $sendEmail);
            }

        } catch (\Exception $e) {

            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
            $order->save();
            return null;
        }

        return $this;
    }
}