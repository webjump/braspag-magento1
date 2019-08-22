<?php
class Webjump_BraspagPagador_Model_Status_Update extends Mage_Core_Model_Abstract
{
    protected $helper;

    public function process($orderIncrementId)
    {
        $helper = $this->getHelper();
        $helper->debug($helper->getCurrentRequestInfo());

        $merchantId = $helper->getMerchantId();
        $this->setMerchantId($merchantId);

        if ($orderIncrementId instanceof Mage_Sales_Model_Order) {
            $order = $orderIncrementId;
        } else {
            $order = $this->loadOrderByIncrementId($orderIncrementId);
        }

        if (!$order || !$order->getId()) {
            Mage::throwException($helper->__('Order %s not found', $this->getIncrementId()));
        }

        $this->setOrder($order);

        $transactions = $this->getBraspagTransactionIds($order);

        $amountPaid = 0;

        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        $paymentResponse = $payment->getAdditionalInformation('payment_response');

        foreach($transactions AS $transaction) {
            $this->setRequestId($helper->generateGuid($order->getIncrementId()));
            $transactionData = $this->getBraspagTransactionData($this->getData() + $transaction->getData());

            if (!$transactionData['Success']) {
                $errorMsg = 'Error: While retrieving transaction data ' . $transaction->getData('braspag_transaction_id');
                Mage::throwException($helper->__($errorMsg));
            }

            if ($transactionData['Status'] == Webjump_BraspagPagador_Model_Config::STATUS_AUTORIZADO) {
                $amountPaid+= $transactionData['Amount'];
            }

            if (!empty($transactionData['ProofOfSale'])) {
                foreach ($paymentResponse AS $key => $value) {
                    if ($value['braspagTransactionId'] == $transactionData['BraspagTransactionId']) {
                        $paymentResponseUpdated = true;
                        $paymentResponse[$key]['proofOfSale'] = $transactionData['ProofOfSale'];
                        break;
                    }
                }
            }
        }

        $amountPaid = $amountPaid/100;

        if (!empty($paymentResponseUpdated)) {
            $payment->setAdditionalInformation('payment_response', $paymentResponse);
        }

        $payment
            ->setAdditionalInformation('authorized_total_paid', $amountPaid)
            ->save()
        ;

        if ($amountPaid >= $order->getGrandTotal()) {
            if ($order->canUnhold()) {
                
                //If order is holded by antifraud...
                if ($order->getStatus() == Webjump_BraspagAntifraud_Model_Config::STATUS_REJECTED || 
                    $order->getStatus() == Webjump_BraspagAntifraud_Model_Config::STATUS_ERROR ||
                    $order->getStatus() == Webjump_BraspagAntifraud_Model_Config::STATUS_REVIEW
                ) {
                    $errorMsg = 'Order updating aborted - order was holded by antifraud';
                    $helper->debug($errorMsg);
                    Mage::throwException($errorMsg);
                }
                $order->unhold()->save();
            }

            $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, Mage::getStoreConfig('webjump_braspag_pagador/status_update/order_status_paid'), $helper->__('Order status updated (2º Post). Total paid %s', Mage::helper('core')->currency($amountPaid, true, false)));
            $order->save();

            if (Mage::getStoreConfig('webjump_braspag_pagador/status_update/autoinvoice')) {
                $sendEmail = Mage::getStoreConfig('webjump_braspag_pagador/status_update/send_email');
                $helper->invoiceOrder($order, $sendEmail);
            }
        }

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

    protected function loadOrderByIncrementId($incrementId)
    {
        $helper = $this->getHelper();

        if (empty($incrementId)) {
            $errorMsg = 'Error: Order not specified';
            $helper->debug($errorMsg);
            Mage::logException(new Exception(
                $errorMsg
            ));

            Mage::throwException(
                $helper->__($errorMsg)
            );
        }

        $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
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
