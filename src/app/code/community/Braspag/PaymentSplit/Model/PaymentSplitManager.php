<?php

/**
 * Class Braspag_PaymentSplit_Model_Payment_Split
 */
class Braspag_PaymentSplit_Model_PaymentSplitManager extends Mage_Core_Model_Abstract
{
    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param Varien_Object $splitPaymentData
     * @return $this
     * @throws Mage_Core_Model_Store_Exception
     */
    public function createPaymentSplitByQuote(Mage_Sales_Model_Quote $quote, Varien_Object $splitPaymentData)
    {
        $paymentSplitCollection = Mage::getModel('braspag_paymentsplit/split')->getCollection();
        $paymentSplitCollection->addFieldToFilter('sales_quote_id', $quote->getId());
        $paymentSplitCollection->addFieldToFilter('store_merchant_id', $splitPaymentData->getStoreMerchantId());
        $paymentSplit = $paymentSplitCollection->getFirstItem();

        $paymentSplit
            ->setSubordinateMerchantId($splitPaymentData->getStoreMerchantId())
            ->setStoreMerchantId($splitPaymentData->getStoreMerchantId())
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setSalesQuoteId($quote->getId())
            ->save();

        foreach ($splitPaymentData->getSubordinates() as $splitSubordinate) {

            $paymentSplitCollection = Mage::getModel('braspag_paymentsplit/split')->getCollection();
            $paymentSplitCollection->addFieldToFilter('sales_quote_id', $quote->getId());
            $paymentSplitCollection->addFieldToFilter('subordinate_merchant_id', $splitSubordinate->getSubordinateMerchantId());
            $paymentSplit = $paymentSplitCollection->getFirstItem();

            $paymentSplit
                ->setSubordinateMerchantId($splitSubordinate->getSubordinateMerchantId())
                ->setStoreMerchantId($splitPaymentData->getStoreMerchantId())
                ->setTotalAmount($splitSubordinate->getAmount())
                ->setSalesQuoteId($quote->getId())
                ->setStoreId(Mage::app()->getStore()->getId())
                ->setMdrApplied(floatval($splitSubordinate->getFares()->getMdr()))
                ->setTaxApplied(floatval($splitSubordinate->getFares()->getFee()))
                ->save();

            foreach ($splitSubordinate->getItems() as $item) {
                $paymentSplitItemCollection = Mage::getModel('braspag_paymentsplit/split_item')->getCollection();
                $paymentSplitItemCollection->addFieldToFilter('split_id', $paymentSplit->getId());
                $paymentSplitItemCollection->addFieldToFilter('sales_quote_item_id', $item->getId());
                $paymentSplitItem = $paymentSplitItemCollection->getFirstItem();

                $paymentSplitItem->setSplitId($paymentSplit->getId())
                    ->setSalesQuoteItemId($item->getItemId())
                    ->save();
            }
        }

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Varien_Object $splitPaymentData
     * @return $this
     * @throws Exception
     */
    public function createPaymentSplitByOrder(Mage_Sales_Model_Order $order, Varien_Object $splitPaymentData)
    {
        foreach ($splitPaymentData->getSubordinates() as $splitSubordinate) {

            $paymentSplitCollection = Mage::getModel('braspag_paymentsplit/split')->getCollection();
            $paymentSplitCollection->addFieldToFilter('sales_quote_id', $order->getQuoteId());
            $paymentSplitCollection->addFieldToFilter('store_id', $order->getStoreId());
            $paymentSplitCollection->addFieldToFilter('subordinate_merchant_id', $splitSubordinate->getSubordinateMerchantId());
            $paymentSplit = $paymentSplitCollection->getFirstItem();

            $paymentSplit
                ->setTotalAmount($splitSubordinate->getAmount())
                ->setStoreId(Mage::app()->getStore()->getId())
                ->setSalesOrderId($order->getId())
                ->setMdrApplied(floatval($splitSubordinate->getFares()->getMdr()))
                ->setTaxApplied(floatval($splitSubordinate->getFares()->getFee()))
                ->save();

            foreach ($splitSubordinate->getSplits() as $split) {

                $paymentSplitsCollection = Mage::getModel('braspag_paymentsplit/split')->getCollection();
                $paymentSplitsCollection->addFieldToFilter('sales_quote_id', $order->getQuoteId());
                $paymentSplitsCollection->addFieldToFilter('store_id', $order->getStoreId());
                $paymentSplitsCollection->addFieldToFilter('subordinate_merchant_id', $split->getMerchantId());
                $paymentSplits = $paymentSplitsCollection->getFirstItem();

                $paymentSplits->setAmount($paymentSplits->getAmount() + $split->getAmount())
                    ->setSalesOrderId($order->getId())
                    ->save();
            }

            foreach ($order->getAllVisibleItems() as $item) {
                $paymentSplitItemCollection = Mage::getModel('braspag_paymentsplit/split_item')->getCollection();
                $paymentSplitItemCollection->addFieldToFilter('split_id', $paymentSplit->getId());
                $paymentSplitItemCollection->addFieldToFilter('sales_quote_item_id', $item->getQuoteItemId());
                $paymentSplitItem = $paymentSplitItemCollection->getFirstItem();

                if (!empty($paymentSplitItem->getSalesQuoteItemId())) {
                    $paymentSplitItem
                        ->setSalesOrderItemId($item->getItemId())
                        ->save();
                }
            }
        }

        return $this;
    }

    /**
     * @param int $days
     * @param string $paymentMethod
     * @return object
     */
    public function getTransactionPostOrdersToExecuteByDays($days = 20, $paymentMethod = \Braspag_Pagador_Model_Config::METHOD_CREDITCARD)
    {
        $collection = Mage::getModel('sales/order')->getCollection();

        $collection->getSelect()
            ->joinLeft(['bps' => 'braspag_payment_split'], 'main_table.entity_id = bps.sales_order_id', [])
            ->joinInner(['sin' => 'sales_flat_invoice'], 'main_table.entity_id = sin.order_id', [])
            ->joinInner(['sop' => 'sales_flat_order_payment'], 'main_table.entity_id = sop.parent_id', [])
            ->where("bps.sales_order_id IS NULL")
            ->where("sop.method = '".$paymentMethod."'")
            ->where("DATE_FORMAT(DATE_ADD(main_table.created_at, INTERVAL {$days} DAY), \"%Y-%m-%d\") = DATE_FORMAT(NOW(), \"%Y-%m-%d\")")
            ->group("main_table.entity_id")
            ->limit(100);

        return $collection;
    }
}
