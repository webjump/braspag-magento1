<?php
class Braspag_Pagador_Model_Payment_TransactionManager extends Braspag_Pagador_Model_Payment_AbstractManager
{
    /**
     * @param $transactionId
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function loadByTxnId($transactionId)
    {
        $braspagCoreHelper = $this->getBraspagCoreHelper();

        if (empty($transactionId)) {
            $errorMsg = 'Error: TransactionID not specified';
            $braspagCoreHelper->debug($errorMsg);
            Mage::logException(new Exception(
                $errorMsg
            ));

            Mage::throwException(
                $braspagCoreHelper->__($errorMsg)
            );
        }

        $salesTransactionCollection = Mage::getModel('sales/order_payment_transaction')->getCollection();
        $salesTransactionCollection->getSelect()->where("txn_id = '{$transactionId}'");
        $transaction = $salesTransactionCollection->getFirstItem();

        if (empty($transaction->getId())) {
            $errorMsg = 'Error: Payment not found';
            $braspagCoreHelper->debug($errorMsg);
            Mage::logException(new Exception($errorMsg));

            Mage::throwException($braspagCoreHelper->__($errorMsg));
        }

        return $transaction;
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getByOrderId($orderId)
    {
        $transaction = Mage::getModel('sales/order_payment_transaction')
            ->getCollection()
            ->addAttributeToFilter('order_id', array('eq' => $orderId))
            ->addAttributeToFilter('txn_type', array('in' => array(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH)))
            ->getFirstItem();

        return $transaction;
    }
}