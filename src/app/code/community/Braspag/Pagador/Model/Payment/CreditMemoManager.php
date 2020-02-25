<?php
class Braspag_Pagador_Model_Payment_CreditMemoManager extends Braspag_Pagador_Model_Payment_AbstractManager
{
    /**
     * @param $payment
     * @param $amount
     * @param $transactionDataPayment
     * @param $sendEmail
     * @return mixed
     * @throws Exception
     */
    public function registerRefundNotification($payment, $amount, $transactionDataPayment, $sendEmail)
    {
        $order = $payment->getOrder();

        if (!$order->hasInvoices()) {
            throw new \Exception("Without Invoices.", 400);
        }

        $raw_details = [];
        foreach ($transactionDataPayment->getArrayCopy() as $r_key => $r_value) {
            $raw_details['payment_refund_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        $transactionId = $this->getHelper()->cleanTransactionId($payment->getLastTransId());

        $payment->setParentTransactionId($transactionId)
            ->setTransactionId($transactionId."-refund")
            ->setIsTransactionClosed(1);

        $payment->registerRefundNotification($amount);

        $creditMemo = $payment->getCreatedCreditmemo();

        if (!$creditMemo){
            throw new Exception("Invalid CreditMemo", 400);
        }

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($payment->getOrder())
            ->addObject($payment);
        $transactionSave->save();

        $order->save();

        if ($sendEmail) {
            $creditMemo->sendEmail(true);
        }

        return $payment;
    }
}