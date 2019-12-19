<?php
class Webjump_BraspagPagador_Model_Status_Update extends Mage_Core_Model_Abstract
{
    protected $helper;

    /**
     * @param $paymentId
     * @param $changeType
     * @param $recurrentPaymentId
     * @return bool
     */
    public function process($paymentId, $changeType, $recurrentPaymentId)
    {
        $helper = $this->getHelper();
        $helper->debug($helper->getCurrentRequestInfo());

        $merchantId = $helper->getMerchantId();
        $this->setMerchantId($merchantId);

        $merchantKey = $helper->getMerchantKey();
        $this->setMerchantKey($merchantKey);

        $orderPayment = $this->loadOrderByPaymentId($paymentId);

        if (!$orderPayment || !$orderPayment->getParentId()) {
            Mage::throwException($helper->__('Order %s not found', $this->getParentId()));
        }

        $this->setOrderPayment($orderPayment);

        $transactions = $this->getBraspagTransactionIds($orderPayment);

        $paymentResponse = $orderPayment->getAdditionalInformation('payment_response');

        $order = Mage::getModel('sales/order')->load($orderPayment->getParentId());
        $orderPayment->setOrder($order);

        foreach($transactions AS $transaction) {

            $this->setRequestId($helper->generateGuid($order->getIncrementId()));
            $transactionData = $this->getBraspagTransactionData($this->getData() + $transaction->getData());

            if (!$transactionDataPayment = $transactionData['Payment']) {
                $errorMsg = 'Error: While retrieving transaction data ' . $transaction->getData('braspag_transaction_id');
                Mage::throwException($helper->__($errorMsg));
            }

            // 2 = capture
            if ($transactionDataPayment['Type'] == 'Billet'
                && $transactionDataPayment['Status'] == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED
            ) {
                return $this->createInvoice($paymentResponse, $transactionData, $orderPayment);
            }

            // 2 = capture
            if (($transactionDataPayment['Type'] == 'CreditCard' || $transactionDataPayment['Type'] == 'DebitCard')
                && $transactionDataPayment['Status'] == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED
            ) {
                return $this->createInvoice($paymentResponse, $transactionData, $orderPayment);
            }

            // 3 = Denied/10 = Voided/13 = Aborted
            if (in_array($transactionDataPayment['Status'], [
                Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED,
                Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_VOIDED,
                Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_ABORTED
            ])) {
                return $this->cancelOrder($paymentResponse, $transactionData, $orderPayment);
            }

            // 11 = Refunded
            if ($transactionDataPayment['Status'] == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_REFUNDED) {
                return $this->createCreditMemo($paymentResponse, $transactionData, $orderPayment);
            }
        }

        return false;
    }

    /**
     * @param $paymentResponse
     * @param $transactionData
     * @param $orderPayment
     * @return bool
     */
    protected function createInvoice($paymentResponse, $transactionData, $orderPayment)
    {
        $transactionDataPayment = $transactionData['Payment'];

        $amountPaid = $transactionDataPayment['Amount']/100;

        if ($paymentResponse['paymentId'] == $transactionDataPayment['PaymentId']) {
            $paymentResponse['proofOfSale'] = $transactionDataPayment['ProofOfSale'];
        }

        $orderPayment->setAdditionalInformation('payment_response', $paymentResponse)
            ->setAdditionalInformation('authorized_total_paid', $amountPaid)
            ->save();

        $order = $orderPayment->getOrder();

        if ($amountPaid >= $order->getGrandTotal()) {
            if ($order->canUnhold()) {

                //If order is holded by antifraud...
                if ($order->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::STATUS_PAYMENT_REVIEW
                    ||$order->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::STATUS_FRAUD
                ) {
                    $errorMsg = $this->getHelper()->__('Order updating aborted - order was holded by antifraud');
                    Mage::throwException($errorMsg);
                }
                $order->unhold()->save();
            }

            if (Mage::getStoreConfig('webjump_braspag_pagador/status_update/autoinvoice') && !$order->hasInvoices()) {

                $orderStatusPaid = Mage::getStoreConfig('webjump_braspag_pagador/status_update/order_status_paid');
                $sendEmail = Mage::getStoreConfig('webjump_braspag_pagador/status_update/send_email');

                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                $invoice->register();

                $invoice->getOrder()->setCustomerNoteNotify($sendEmail);
                $invoice->getOrder()->setIsInProcess(true);


                $message = $this->getHelper()->__('Payment Confirmed. Amount of R$'.$amountPaid.'. Transaction ID: "'.$paymentResponse['paymentId'].'".');
                $state = Mage_Sales_Model_Order::STATE_PROCESSING;

                $order->setState($state, $orderStatusPaid, $message);

                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                $transactionSave->save();

                return true;
            }
        }

        return true;
    }

    /**
     * @param $orderPayment
     * @return bool
     */
    protected function cancelOrder($orderPayment)
    {
        $order = $orderPayment->getOrder();
        $order->cancel();

        return true;
    }

    /**
     * @param $orderPayment
     * @return bool
     */
    protected function createCreditMemo($orderPayment)
    {
        $order = $orderPayment->getOrder();

        $invoices = array();
        foreach ($order->getInvoiceCollection() as $invoice) {
            if ($invoice->canRefund()) {
                $invoices[] = $invoice;
            }
        }

        $service = Mage::getModel('sales/service_order', $order);
        foreach ($invoices as $invoice) {
            $creditmemo = $service->prepareInvoiceCreditmemo($invoice);
            $creditmemo->refund();
        }

        return true;
    }

    /**                    ->debug($errorMsg);

     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        if (!$this->helper) {
            $this->helper = Mage::helper('webjump_braspag_pagador');
        }

        return $this->helper;
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getPagadorquery()
    {
        return Mage::getModel('webjump_braspag_pagador/pagadorquery');
    }

    protected function getBraspagOrderIdData(array $data)
    {
        $pagadorquery = $this->getPagadorquery();
        $pagadorquery->setData($data);

        $return = $pagadorquery->getOrderIdData();

        return $return;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function getBraspagTransactionData(array $data)
    {
        $pagadorquery = $this->getPagadorquery();
        $pagadorquery->setData($data);

        $return = $pagadorquery->getTransactionData();

        return $return;
    }

    /**
     * @param $paymentId
     * @return mixed
     */
    protected function loadOrderByPaymentId($paymentId)
    {
        $helper = $this->getHelper();

        if (empty($paymentId)) {
            $errorMsg = 'Error: Order not specified';
            $helper->debug($errorMsg);
            Mage::logException(new Exception(
                $errorMsg
            ));

            Mage::throwException(
                $helper->__($errorMsg)
            );
        }

        $order = Mage::getModel('sales/order_payment')->getCollection()
            ->addAttributeToFilter('last_trans_id', ['eq' => $paymentId])
            ->getFirstItem();

        if (!$order->getParentId()) {
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

    /**
     * @param Mage_Sales_Model_Order_Payment $orderPayment
     * @return array
     */
    protected function getBraspagTransactionIds(Mage_Sales_Model_Order_Payment $orderPayment)
    {
        $helper = $this->getHelper();
        $braspagTransactionIds = [];

        $transaction = Mage::getModel('sales/order_payment_transaction')
            ->getCollection()
            ->addAttributeToFilter('order_id', array('eq' => $orderPayment->getParentId()))
            ->addAttributeToFilter('txn_type', array('in' => array(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH)))
            ->getFirstItem();

        $transactionData = $transaction->getData();

        if ($transactionId = $transactionData['additional_information']['raw_details_info']['braspagOrderId']) {
            $data = new Varien_Object();
            $data->setData(array(
                'braspag_transaction_id' => $transactionId,
                'type' => $transactionData['txn_type'],
                'transaction_id' => $transactionData['transaction_id'],
                'is_closed' => $transactionData['is_closed'],
                'braspag_order_id' => $transactionData['additional_information']['raw_details_info']['braspagOrderId'],
            ));
            $braspagTransactionIds[] = $data;
        }

        //If order payment transactions didn't return any transaction...
        if (empty($braspagTransactionId)) {

            $order = Mage::getModel('sales/order')->load($orderPayment->getParentId());

            $data = array_merge($this->getData(), ["OrderIncrementId" => $order->getIncrementId()]);

            $this->setOrderIncrementId($order->getIncrementId());
            $this->setRequestId($helper->generateGuid($order->getIncrementId()));
            $braspagOrderIdData = $this->getBraspagOrderIdData($data);

            if ($payments = $braspagOrderIdData['Payments']) {

                foreach ($payments as $payment) {
                    $dataObject = new Varien_Object();
                    $dataObject->setData(array(
                        'braspag_transaction_id' => $payment['PaymentId'],
                        'braspag_order_id' => $order->getIncrementId(),
                    ));
                    $braspagTransactionIds[] = $dataObject;
                }
            }
        }

        return $braspagTransactionIds;
    }
}
