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

            $helper = Mage::helper('webjump_braspag_pagador');

//            if ($payment->getMethodInstance()->getConfigData('payment_action') == 'authorize_capture'
//                && $order->getId()
//            ) {
//                if ($payment->getIsFraudDetected()
//                    && $payment->getMethodInstance()->getConfigData('reject_order_status') === 'canceled'
//                ){
//                    $order
//                        ->cancel()
//                        ->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)
//                        ->save();
//
//                    return $this;
//                }
//
//                if (!$payment->getIsFraudDetected()
//                    && !$payment->getIsTransactionPending()
//                    && $order->canInvoice()
//                ) {
//                    $sendEmail = Mage::getStoreConfig('webjump_braspag_pagador/status_update/send_email');
//                    $helper->invoiceOrder($order, $sendEmail);
//                }
//            }

            $storeId = $order->getStoreId();
            $antiFraudConfigModel = Mage::getModel('webjump_braspag_pagador/config_antifraud');
            $helper = Mage::helper('webjump_braspag_pagador');

            $paymentMethod = $payment->getMethodInstance()->getCode();

            if (!$antiFraudConfigModel->isAntifraudActive($storeId)
                || ($paymentMethod != 'webjump_braspag_cc' && $paymentMethod != 'webjump_braspag_justclick')
            ) {
                return $this;
            }

            $paymentResponseData = $payment->getInfoInstance()->getAdditionalInformation('payment_response');

            if (!$fraudAnalisysStatus = $paymentResponseData['fraudAnalysis']['Status']) {
                return $this;
            }

            if (($fraudAnalisysStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
                || $fraudAnalisysStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
                || $fraudAnalisysStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN)
                && $payment->getIsFraudDetected()
            ) {
                $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                $status = Mage::getStoreConfig('antifraud/creditcard_transation/reject_order_status', $storeId);
                $message = $helper->__('Fraud detected.');

                if ($status == 'canceled') {
                    $order->cancel()->save();
                    $state = Mage_Sales_Model_Order::STATE_CANCELED;
                    $message = "Canceled After Fraud Detected.";
                }

                $order
                    ->setState($state, $status, $message)
                    ->save();
            }

            if ($fraudAnalisysStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW
                && $payment->getIsTransactionPending()
            ) {
                $message = $this->getHelper()->__('Payment Review.');
                $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                $status = Mage::getStoreConfig('antifraud/creditcard_transation/review_order_status', $storeId);

                $order
                    ->setState($state, $status, $message)
                    ->save();
            }

        } catch (\Exception $e) {

            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
            $order->save();
            return null;
        }

        return $this;
    }
}