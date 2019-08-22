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
// * @category  Api
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// */
//
///**
// * Pagador Transaction
// *
// * @category  Api
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//class Webjump_BraspagPagador_Model_Pagador_Post_Index extends Webjump_BraspagPagador_Model_Pagador_Post
//{
//    protected function getTransactionsDataToCapture()
//    {
//        $payment = $this->getPayment();
//        $amount = $this->getAmount();
//        $helper = $this->getHelper();
//
//        $api = Mage::getModel('webjump_braspag_pagador/pagadorold')->getApi($payment);
//
//        $authorizationTransaction = $payment->getAuthorizationTransaction();
//
//        if (!$authorizationTransaction) {
//            throw new Exception($helper->__('Transaction not found'));
//        }
//
//        $transactionInfo = $authorizationTransaction->getAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS);
//
//        $data = array(
//            'braspagTransactionId' => $transactionInfo['payment_TRANSACTIONID'],
//            'amount' => $transactionInfo['payment_VALOR'],
//        );
//
//        $transaction = $this->getServiceManager()->get('Pagador\Data\Request\Transaction\Item');
//        $transaction->populate($data);
//
//        $dataTransactions = $this->getServiceManager()->get('Pagador\Data\Request\Transaction\List');
//        $dataTransactions->add($transaction);
//
//        return $dataTransactions;
//    }
//}
