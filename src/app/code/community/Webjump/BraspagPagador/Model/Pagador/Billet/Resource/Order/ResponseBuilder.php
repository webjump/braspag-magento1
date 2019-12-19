<?php
///**
// * Webjump BrasPag Pagador
// *
// * LICENSE
// *
// * This source file is subject to the new BSD license that is bundled
// * with this package in the file LICENSE.txt.
// * It is also available through the world-wide-web at this URL:
// * http://www.webjump.com.br
// * If you did not receive a copy of the license and are unable to
// * obtain it through the world-wide-web, please send an email
// * to license@webjump.com so we can send you a copy immediately.
// *
// * @category  Model
// * @package   Webjump_BraspagPagador_Model_Method
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2019 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// */
//
///**
// * BrasPag Pagador Model
// *
// * @category  Model
// * @package   Webjump_BraspagPagador_Model_Method
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2019 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//class Webjump_BraspagPagador_Model_Pagador_Transaction_Billet_Resource_Order_ResponseBuilder
//{
//    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_authorize_creditcard';
//    protected $request;
//
//    protected $payment;
//    protected $amount;
//
//    /**
//     * @return mixed
//     */
//    public function getPayment()
//    {
//        return $this->payment;
//    }
//
//    /**
//     * @param mixed $payment
//     */
//    public function setPayment($payment)
//    {
//        $this->payment = $payment;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAmount()
//    {
//        return $this->amount;
//    }
//
//    /**
//     * @param mixed $amount
//     */
//    public function setAmount($amount)
//    {
//        $this->amount = $amount;
//    }
//
//    public function build()
//    {
//        $helper = Mage::helper('webjump_braspag_pagador');
//        $generalConfigModel = Mage::getSingleton('webjump_braspag_pagador/config');
//
//        $order = $this->getPayment()->getOrder();
//        $amount = $this->getAmount();
//
//        $merchantId = $generalConfigModel->getMerchantId();
//        $merchantKey = $generalConfigModel->getMerchantKey();
//
//        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
//            ->setOrderId($order->getIncrementId())
//            ->setBraspagOrderId($this->getPaymentTransactionId())
//            ->setOrderAmount(($amount == 0 ? $order->getGrandTotal() : $amount));
//
//        $paymentData = Mage::getModel('webjump_braspag_pagador/pagador_data_request_paymentBuilder')
//            ->setPayment($this->getPayment())
//            ->setAmount($this->getAmount())
//            ->build();
//
//        $customerData = Mage::getModel('webjump_braspag_pagador/pagador_data_request_customerBuilder')
//            ->setOrder($this->getPayment()->getOrder())
//            ->build();
//
//        $dataRequest = array(
//            'requestId' => $helper->generateGuid($order->getIncrementId()),
//            'merchantId' => $merchantId,
//            'merchantKey' => $merchantKey,
//            'order' 	=> $dataOrder,
//            'payment' 	=> $paymentData,
//            'customer' 	=> $customerData
//        );
//
//        $request = $this->getServiceManager()->get('Pagador\Transaction\Authorize\Request');
//        $request->populate($dataRequest);
//
//        return $request;
//
//    }
//
////    public function processAuthorize(Varien_Object $payment, $amount)
////    {
////        $result = $this->getPagador()->authorize($payment, $amount);
////
////        $this->_importAuthorizeResultToPayment($result, $payment, $result->getPayment()->get());
////
////        if ($payment->getIsTransactionPending()){
////            $message = $this->getHelper()->__('Waiting response from acquirer.');
////            $payment->getOrder()->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true, $message);
////        }
////
////        return $this;
////    }
//
////    /**
////     * @param $result
////     * @param $payment
////     * @param $amount
////     * @return $this
////     */
////    protected function _importAuthorizeResultToPayment($result, $payment, $resultPayment)
////    {
////        $antiFraudConfig = Mage::getModel('webjump_braspag_pagador/config_antifraud');
////
////        $resultData = $result->getDataAsArray();
////
////        $status = $resultPayment->getStatus();
////
////        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED
////            || $status = Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED) {
////
////            $this->totalPaid += $resultPayment->getAmount() / 100;
////
////            $this->processAuthorizeInfoData($resultData['payment']);
////
////            if ($antiFraudConfig->isAntifraudActive()
////                && $fraudAnalysisStatus = $resultPayment->getFraudAnalysis()['Status']
////            ) {
////                $payment = $this->getAuthorizeFraudAnalysis($fraudAnalysisStatus, $payment);
////            }
////        }
////
////        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING) {
////
////            $payment->setIsTransactionPending(true);
////            $this->processAuthorizeInfoData($resultData['payment']);
////        }
////
////        if ((!$this->totalPaid) && $resultData['payment']) {
////            $this->errorMsg[] = $this->getHelper()->__('The payment was unauthorized.');
////        }
////
////        $payment
////            ->setTransactionId($resultData['order']['braspagOrderId'])
////            ->setIsTransactionClosed(0);
////
////        $this->processAuthorizeRawDetails($resultData['payment'], $payment);
////        $this->processAuthorizeErrors($payment);
////
////        return $this;
////    }
////
////    /**
////     * @param $fraudAnalysisStatus
////     * @param $payment
////     * @return mixed
////     */
////    protected function getAuthorizeFraudAnalysis($fraudAnalysisStatus, $payment)
////    {
////        $antiFraudConfig = Mage::getModel('webjump_braspag_pagador/config_antifraud');
////
////        if ($antiFraudConfig->getOptionsSequence() === 'AnalyseFirst') {
////
////            if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
////                || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
////                || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN
////            ) {
////                $payment->setIsFraudDetected(true);
////            }
////
////            if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {
////                $payment->setIsTransactionPending(true);
////            }
////        }
////
////        return $payment;
////    }
//}
