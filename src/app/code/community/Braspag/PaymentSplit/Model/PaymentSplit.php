<?php

/**
 * Class Braspag_PaymentSplit_Model_Payment_SplitAdapter
 */
class Braspag_PaymentSplit_Model_PaymentSplit extends Mage_Core_Model_Abstract
{
    /**
     * @param $quote
     * @param $splitPaymentConfigModel
     * @return array|Varien_Object
     */
    public function getPaymentSplitDataFromQuote($quote, $splitPaymentConfigModel)
    {
        $dataCard = [];
        if (!$splitPaymentConfigModel->isActive()) {
            $dataSplitPayment = new Varien_Object();
            $dataSplitPayment->setIsActive($splitPaymentConfigModel->isActive());
            return $dataSplitPayment;
        }

        $subordinates = [];

        foreach ($quote->getAllVisibleItems() as $item) {

            $product = $item->getProduct();

            $braspagSubordinateMerchantId = Mage::getResourceModel('catalog/product')
                ->getAttributeRawValue($product->getId(), 'braspag_subordinate_merchantid', $quote->getStoreId());

            if (empty($braspagSubordinateMerchantId)) {
                return $dataCard;
            }

            if (!isset($subordinates[$braspagSubordinateMerchantId])) {
                $subordinates[$braspagSubordinateMerchantId] = [];
                $subordinates[$braspagSubordinateMerchantId]['amount'] = 0;
                $subordinates[$braspagSubordinateMerchantId]['fares'] = [
                    "mdr" => floatval($splitPaymentConfigModel->getDefaultMdr()),
                    "fee" => intval($splitPaymentConfigModel->getDefaultFee())
                ];
                $subordinates[$braspagSubordinateMerchantId]['skus'] = [];
            }

            $subordinates[$braspagSubordinateMerchantId]['amount'] +=  floatval($item->getRowTotalInclTax()) * 100;

            $itemsObject = new Varien_Object();
            $items = [
                "item_id" => $item->getId(),
                "sku" => $product->getSku()
            ];
            $itemsObject->addData($items);

            $subordinates[$braspagSubordinateMerchantId]['items'][] =  $itemsObject;
        }

        $paymentSplitAdapter = Mage::getSingleton('braspag_paymentsplit/paymentSplitAdapter');
        $dataSplitPayment = $paymentSplitAdapter->adapt($subordinates);
        $dataSplitPayment->setIsActive($splitPaymentConfigModel->isActive());

        return $dataSplitPayment;
    }

    /**
     * @param $order
     * @param $splitPaymentConfigModel
     * @return array|Varien_Object
     */
    public function getPaymentSplitDataFromOrder($order, $splitPaymentConfigModel)
    {
        $dataCard = [];
        if (!$splitPaymentConfigModel->isActive()) {
            $dataSplitPayment = new Varien_Object();
            $dataSplitPayment->setIsActive($splitPaymentConfigModel->isActive());
            return $dataSplitPayment;
        }

        $subordinates = [];

        foreach ($order->getAllVisibleItems() as $item) {

            $product = $item->getProduct();

            $braspagSubordinateMerchantId = Mage::getResourceModel('catalog/product')
                ->getAttributeRawValue($product->getId(), 'braspag_subordinate_merchantid', $order->getStoreId());

            if (empty($braspagSubordinateMerchantId)) {
                return $dataCard;
            }

            if (!isset($subordinates[$braspagSubordinateMerchantId])) {
                $subordinates[$braspagSubordinateMerchantId] = [];
                $subordinates[$braspagSubordinateMerchantId]['amount'] = 0;
                $subordinates[$braspagSubordinateMerchantId]['fares'] = [
                    "mdr" => floatval($splitPaymentConfigModel->getDefaultMdr()),
                    "fee" => intval($splitPaymentConfigModel->getDefaultFee())
                ];
                $subordinates[$braspagSubordinateMerchantId]['skus'] = [];
            }

            $subordinates[$braspagSubordinateMerchantId]['amount'] +=  floatval($item->getRowTotalInclTax()) * 100;

            $itemsObject = new Varien_Object();
            $items = [
                "item_id" => $item->getId(),
                "sku" => $product->getSku()
            ];
            $itemsObject->addData($items);

            $subordinates[$braspagSubordinateMerchantId]['items'][] =  $itemsObject;
        }

        $paymentSplitAdapter = Mage::getSingleton('braspag_paymentsplit/paymentSplitAdapter');
        $dataSplitPayment = $paymentSplitAdapter->adapt($subordinates);
        $dataSplitPayment->setIsActive($splitPaymentConfigModel->isActive());

        return $dataSplitPayment;
    }
}
