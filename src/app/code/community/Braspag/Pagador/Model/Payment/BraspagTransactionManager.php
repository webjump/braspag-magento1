<?php
class Braspag_Pagador_Model_Payment_BraspagTransactionManager extends Braspag_Pagador_Model_Payment_AbstractManager
{
    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return bool|Varien_Object
     * @throws Exception
     */
    public function getTransactionByMerchantOrderId(Mage_Sales_Model_Order_Payment $payment)
    {
        $orderIncrementId = $payment->getOrder()->getIncrementId();

        return $this->getPaymentByMerchantOrderId($orderIncrementId);
    }

    /**
     * @param $transactionId
     * @return bool|Varien_Object
     * @throws Exception
     */
    public function getTransactionByPaymentId($transactionId)
    {
        return $this->getPaymentByPaymentId($transactionId);
    }

    /**
     * @param $orderIncrementId
     * @return mixed
     * @throws Exception
     */
    protected function getPaymentByMerchantOrderId($orderIncrementId)
    {
        $requestData = [
            "merchantId" => $this->getBraspagCoreConfigGeneral()->getMerchantId(),
            "merchantKey" => $this->getBraspagCoreConfigGeneral()->getMerchantKey(),
            "merchantOrderId" => $orderIncrementId
        ];

        $getByMerchantOrderIdRequest = $this->getServiceManager()
            ->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Request');

        $request = $getByMerchantOrderIdRequest->populate($requestData);

        $getByMerchantOrderIdTransaction = $this->getServiceManager()
            ->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId');

        $response = $getByMerchantOrderIdTransaction->execute($request);

        return $response->getPayments();
    }

    /**
     * @param $transactionId
     * @return mixed
     * @throws Exception
     */
    protected function getPaymentByPaymentId($transactionId)
    {
        $requestData = [
            "merchantId" => $this->getBraspagCoreConfigGeneral()->getMerchantId(),
            "merchantKey" => $this->getBraspagCoreConfigGeneral()->getMerchantKey(),
            "braspagOrderId" => $transactionId
        ];

        $getByPaymentIdOrderRequest = $this->getServiceManager()
            ->get('Pagador\Data\Request\Order')
            ->populate($requestData);

        $getByPaymentIdRequest = $this->getServiceManager()
            ->get('PagadorQuery\Transaction\GetPayment\ByPaymentId\Request')
            ->populate($requestData);

        $request = $getByPaymentIdRequest->setOrder($getByPaymentIdOrderRequest);

        $getByPaymentIdTransaction = $this->getServiceManager()
            ->get('PagadorQuery\Transaction\GetPayment\ByPaymentId');

        return $getByPaymentIdTransaction->execute($request);
    }

    /**
     * @param mixed|null $payment
     * @param array $transaction
     * @return $this|string
     */
    public function debug($payment, $transaction)
    {
        $payment->getMethodInstance()->debugData(array(
            'request' => $transaction->debug(),
            'response' => $transaction->debugResponse(),
        ));

        return $this;
    }
}