<?php
class Webjump_BraspagPagador_Model_Notification extends Mage_Core_Model_Abstract
{
    protected $helper;

    public function process()
    {
        $helper = $this->getHelper();
        $helper->debug($helper->getCurrentRequestInfo());

        $merchantId = $helper->getMerchantId();
        $this->setMerchantId($merchantId);
        $decryptedData = $this->decrypt();

        $order = $this->getOrderFromBraspagPostData($decryptedData);
        $storeId = $order->getStoreId();
        $this->setOrderId($order->getEntityId());

        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();

        $response = Mage::getSingleton('webjump_braspag_pagador/notification_response')->import($decryptedData);

        if (!$response->getIsAuthorized()) {
            return Mage::throwException(Mage::helper('webjump_braspag_pagador')->__($response->getErrorMessage()));
        }

        $payment->setAdditionalInformation('payment_request', $response->getPaymentResponse());
        $payment->setAdditionalInformation('payment_response', $response->getPaymentResponse());
        $payment->save();

        $transaction = Mage::getModel('sales/order_payment_transaction')
            ->setOrderPaymentObject($payment)
            ->setOrder($order)
            ->setTxnId($response->getTransactionId())
            ->setTxnType($response->getTransactionType())
            ->setIsClosed($response->getIsTransactionClose())
            ->setAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $response->getTransactionAdditionalInfo())
            ->save();

        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
        $order->save();

        Mage::getSingleton('checkout/type_onepage')->getCheckout()
                                                   ->setLastOrderId($order->getId())
                                                   ->setLastSuccessQuoteId($order->getQuoteId())
                                                   ->setLastQuoteId($order->getQuoteId());

        return true;
    }

    protected function getHelper()
    {
        if (!$this->helper) {
            $this->helper = Mage::helper('webjump_braspag_pagador');
        }

        return $this->helper;
    }

    protected function getGeneralservice()
    {
        return Mage::getModel('webjump_braspag_pagador/generalservice');
    }

    protected function getPagadorquery()
    {
        return Mage::getModel('webjump_braspag_pagador/pagadorquery');
    }

    public function decrypt()
    {
        $helper = $this->getHelper();
        $data = $this->getData();
        $data = array_change_key_case($data);

        $helper->debug('--Decrypt-start-');
        $helper->debug($data);

        $generalservice = $this->getGeneralservice();

        $return = $generalservice->decrypt($data);

        $helper->debug($generalservice->getLastRequest());
        $helper->debug($generalservice->getLastResponse());
        $helper->debug($return);
        $helper->debug('--Decrypt-end-');

        return $return;
    }

    protected function getBraspagOrderIdData(array $data)
    {
        $helper = $this->getHelper();
        $pagadorquery = $this->getPagadorquery();
        $pagadorquery->setData($data);
        $return = $pagadorquery->getOrderIdData();

        return $return;
    }

    protected function getBraspagTransactionData(array $data)
    {
        $helper = $this->getHelper();
        $pagadorquery = $this->getPagadorquery();
        $pagadorquery->setData($data);
        $return = $pagadorquery->getTransactionData();

        return $return;
    }

    protected function getOrderFromBraspagPostData($data)
    {
        $helper = $this->getHelper();

        if (empty($data['VENDAID'])) {
            $errorMsg = 'Error: Order not specified';
            $helper->debug($errorMsg);
            Mage::logException(new Exception(
                $errorMsg
            ));

            Mage::throwException(
                $helper->__($errorMsg)
            );
        }

        $order = Mage::getModel('sales/order')->loadByIncrementId($data['VENDAID']);
        if (!$order->getId()) {
            $errorMsg = 'Error: Order not found';
            $helper->debug($errorMsg);
            Mage::logException(new Exception(
                $errorMsg
            ));

            Mage::throwException(
                $helper->__($errorMsg)
            );
        }

        return $order;
    }

    protected function getCaptureBraspagTransaction(Mage_Sales_Model_Order $order, $braspagTransactionId)
    {
        $helper = $this->getHelper();

        $transactions = Mage::getModel('sales/order_payment_transaction')->getCollection()
                                                                         ->addAttributeToFilter('order_id', array('eq' => $order->getId()))
                                                                         ->addAttributeToFilter('txn_type', array('eq' => Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE))
        ;

        $return = array();
        foreach ($transactions as $transaction) {
            $transactionData = $transaction->getData();

            foreach ($transactionData['additional_information']['raw_details_info'] as $key => $value) {
                if (preg_match('/_braspagTransactionId$/', $key) && $value == $braspagTransactionId) {
                    $return[] = $transaction;
                }
            }
        }

        return $return;
    }

    protected function getBraspagTransactionIds(Mage_Sales_Model_Order $order)
    {
        $helper = $this->getHelper();

        $transactions = Mage::getModel('sales/order_payment_transaction')->getCollection()
                                                                         ->addAttributeToFilter('order_id', array('eq' => $order->getId()))
                                                                         ->addAttributeToFilter('txn_type', array('in' => array(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH)))
        ;

        $braspagTransactionIds = array();
        foreach ($transactions as $transaction) {
            $transactionData = $transaction->getData();

            foreach ($transactionData['additional_information']['raw_details_info'] as $key => $value) {
                if (preg_match('/_braspagTransactionId$/', $key)) {
                    $data = new Varien_Object();
                    $data->setData(array(
                        'braspag_transaction_id' => $value,
                        'type' => $transactionData['txn_type'],
                        'transaction_id' => $transactionData['transaction_id'],
                        'is_closed' => $transactionData['is_closed'],
                        'braspag_order_id' => $transactionData['additional_information']['raw_details_info']['braspagOrderId'],
                    ));
                    $braspagTransactionIds[] = $data;
                }
            }
        }

        //If order payment transactions didn't return any transaction...
        if (empty($braspagTransactionIds)) {
            $this->setOrderIncrementId($order->getIncrementId());
            $this->setRequestId($helper->generateGuid($order->getIncrementId()));
            $braspagOrderIdData = $this->getBraspagOrderIdData($this->getData());

            foreach ($braspagOrderIdData as $data) {
                if (is_array($data['BraspagTransactionId'])) {
                    foreach ($data['BraspagTransactionId'] as $r) {
                        $dataObject = new Varien_Object();
                        $dataObject->setData(array(
                            'braspag_transaction_id' => $r,
                        ));
                        $braspagTransactionIds[] = $dataObject;
                    }
                } else {
                    $dataObject = new Varien_Object();
                    $dataObject->setData(array(
                        'braspag_transaction_id' => $data['BraspagTransactionId'],
                        'braspag_order_id' => $data['BraspagOrderId'],
                    ));
                    $braspagTransactionIds[] = $dataObject;
                }
            }
        }

        return $braspagTransactionIds;
    }
}
