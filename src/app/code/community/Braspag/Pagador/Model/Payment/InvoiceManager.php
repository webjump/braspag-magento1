<?php

class Braspag_Pagador_Model_Payment_InvoiceManager extends Braspag_Pagador_Model_Payment_AbstractManager
{
    /**
     * @param $paymentMethod
     * @param $payment
     * @return bool
     */
    public function canInvoice($payment, $paymentMethod)
    {
        $order = $payment->getOrder();

        if ($payment->getIsFraudDetected() || $payment->getIsTransactionPending() || !$order->canInvoice()) {
            return false;
        }

        if (($paymentMethod == Braspag_Pagador_Model_Config::METHOD_CREDITCARD
            || $paymentMethod == Braspag_Pagador_Model_Config::METHOD_JUSTCLICK)
            && $payment->getMethodInstance()->getConfigPaymentAction() != Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE) {
            return false;
        }

        if ($paymentMethod == Braspag_Pagador_Model_Config::METHOD_DEBITCARD) {
            return true;
        }

        return false;
    }

    /**
     * @param $payment
     * @param $amount
     * @param $sendEmail
     * @param bool $captureOnline
     * @return mixed
     */
    public function create($payment, $amount, $sendEmail, $captureOnline = true)
    {
        $paymentDataResponse = $payment->getAdditionalInformation('payment_response');

        $method = $payment->getMethodInstance();

        $order = $payment->getOrder();

        $invoice = $order->prepareInvoice();

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);

        if (!$method->canCapture() || !$captureOnline) {

            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);

            $transactionId = $this->getHelper()->cleanTransactionId($payment->getLastTransId());

            $payment->setParentTransactionId($transactionId)
                ->setTransactionId($transactionId."-capture")
                ->setIsTransactionClosed(1);

            $raw_details = [];
            foreach ($paymentDataResponse as $r_key => $r_value) {
                $raw_details['payment_capture_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
            }

            $payment->resetTransactionAdditionalInfo();
            $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);
        }

        if ($sendEmail) {
            $invoice->setEmailSent(true);
            $invoice->getOrder()->setCustomerNoteNotify(true);
        }

        $invoice->getOrder()->setStatus(Mage::getModel('braspag_pagador/config_statusUpdate')->getOrderStatusPaid());
        $invoice->getOrder()->setIsInProcess(true);

        $invoice->register();

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder());

        $transactionSave->save();

        $payment->setIsTransactionApproved(true);

        $payment->save();

        if ($sendEmail) {
            $invoice->sendEmail(true);
        }

        return $payment;
    }

    /**
     * @param $payment
     * @param $amount
     * @param $transactionDataPayment
     * @param $sendEmail
     * @return mixed
     */
    public function registerCaptureNotification($payment, $amount, $transactionDataPayment, $sendEmail)
    {
        $raw_details = [];
        foreach ($transactionDataPayment->getArrayCopy() as $r_key => $r_value) {
            $raw_details['payment_capture_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $payment->setParentTransactionId($transactionDataPayment->getPaymentId())
            ->setTransactionId($transactionDataPayment->getPaymentId()."-capture");

        $payment->registerCaptureNotification($amount, true);

        $invoice = $payment->getCreatedInvoice();

        $payment->setIsTransactionApproved(true);

        $payment->getOrder()->save();

        if ($sendEmail) {
            $invoice->sendEmail(true);
        }

        return $payment;
    }
}