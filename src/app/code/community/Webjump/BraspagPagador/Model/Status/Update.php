<?php
class Webjump_BraspagPagador_Model_Status_Update extends Mage_Core_Model_Abstract
{
    protected $helper;

    /**
     * @param $paymentId
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function process($paymentId)
    {
        $helper = $this->getHelper();
        $helper->debug($helper->getCurrentRequestInfo());

        $this->setMerchantId($helper->getMerchantId());
        $this->setMerchantKey($helper->getMerchantKey());

        $orderPayment = $this->loadOrderByPaymentId($paymentId);

        if (!$orderPayment || !$orderPayment->getParentId()) {
            Mage::throwException($helper->__('Order %s not found', $this->getParentId()));
        }

        $this->setOrderPayment($orderPayment);

        $transactions = $this->getBraspagTransactionIds($orderPayment);

        $paymentResponse = $orderPayment->getAdditionalInformation('payment_response');

        $order = Mage::getModel('sales/order')->load($orderPayment->getParentId());
        $orderPayment->setOrder($order);

        if (empty($transactions)) {
            throw new Exception('Data Error.');
        }

        $transaction = $transactions[0];

        $this->setRequestId($helper->generateGuid($order->getIncrementId()));
        $transactionData = $this->getBraspagTransactionData(array_merge($this->getData(), $transaction->getData()));

        if (!$transactionDataPayment = $transactionData['Payment']) {
            $errorMsg = 'Error: While retrieving transaction data ' . $transaction->getData('braspag_transaction_id');
            Mage::throwException($helper->__($errorMsg));
        }

        // 2 = capture
        if ($transactionDataPayment['Type'] == 'Boleto'
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
            return $this->cancelOrder($paymentResponse, $orderPayment);
        }

        // 11 = Refunded
        if ($transactionDataPayment['Status'] == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_REFUNDED) {
            return $this->createCreditMemo($paymentResponse, $transactionData, $orderPayment);
        }

        return $orderPayment;
    }

    /**
     * @param $paymentResponse
     * @param $transactionData
     * @param $payment
     * @return bool
     * @throws Exception
     */
    protected function createInvoice($paymentResponse, $transactionData, $payment)
    {
        $order = $payment->getOrder();

        $transactionDataPayment = $transactionData['Payment'];

        $amountOrdered = floatval($order->getGrandTotal());
        $amountPaid = floatval($transactionDataPayment['Amount']/100);

        if ($paymentResponse['paymentId'] == $transactionDataPayment['PaymentId']) {
            $paymentResponse['proofOfSale'] = $transactionDataPayment['ProofOfSale'];
        }

        $payment->setAdditionalInformation('payment_response', $paymentResponse)
            ->setAdditionalInformation('authorized_total_paid', $amountPaid)
            ->save();
        
        if ($order->hasInvoices()) {
            throw new \Exception('The Order already has Invoices.', 400);
        }

        if ($amountPaid < $amountOrdered) {
            throw new \Exception('Invalid Amount for Invoice', 400);
        }

        if ($order->canUnhold()) {
            $order->unhold()->save();
        }

        if (!Mage::getStoreConfig('webjump_braspag_pagador/status_update/autoinvoice')){
            throw new \Exception('Invoice creation is disabled.', 400);
        }

        $payment->setParentTransactionId($payment->getLastTransId())
            ->setTransactionId($payment->getLastTransId()."-capture")
            ->setIsTransactionClosed(0);

        $raw_details = [];
        foreach ($transactionDataPayment as $r_key => $r_value) {
            $raw_details['payment_capture_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->resetTransactionAdditionalInfo();
        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $payment->registerCaptureNotification($amountPaid, true);

        $invoice = $payment->getCreatedInvoice();
        
        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder());
        $transactionSave->save();

        $invoice->sendEmail(true);
        $order->save();

        $payment->setIsTransactionApproved(true);

        return $payment;
    }

    /**
     * @param $paymentResponse
     * @param $payment
     * @return bool
     * @throws Exception
     */
    protected function cancelOrder($paymentResponse, $payment)
    {
        $order = $payment->getOrder();

        $payment->setParentTransactionId($payment->getLastTransId())
            ->setTransactionId($payment->getLastTransId()."-void");

        $raw_details = [];
        foreach ($paymentResponse as $r_key => $r_value) {
            $raw_details['payment_void_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->resetTransactionAdditionalInfo();
        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $payment->registerVoidNotification();

        $order->registerCancellation();

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($order);

        $transactionSave->save();
        $order->save();

        return $payment;
    }

    /**
     * @param $paymentResponse
     * @param $transactionData
     * @param $payment
     * @return bool
     * @throws Exception
     */
    protected function createCreditMemo($paymentResponse, $transactionData, $payment)
    {
        $order = $payment->getOrder();

        if (!$order->hasInvoices()) {
            throw new \Exception("There aren't invoices to credit memo creation.", 400);
        }

        $transactionDataPayment = $transactionData['Payment'];

        $raw_details = [];
        foreach ($transactionDataPayment as $r_key => $r_value) {
            $raw_details['payment_refund_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $amountRefunded = floatval($transactionDataPayment['Amount']/100);

        $transactionId = str_replace("-capture", "", $payment->getLastTransId());

        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $payment->setParentTransactionId($transactionId)
            ->setTransactionId($transactionId."-refund");

        $payment->registerRefundNotification($amountRefunded);

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($payment->getOrder())
            ->addObject($payment);
        $transactionSave->save();

        $order->save();

        return $payment;
    }

    /**
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
     * @throws Mage_Core_Exception
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

        $paymentOrderCollection = Mage::getModel('sales/order_payment')->getCollection();
        $paymentOrderCollection->getSelect()->where("last_trans_id like '{$paymentId}%'");
        $order = $paymentOrderCollection->getFirstItem();

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
