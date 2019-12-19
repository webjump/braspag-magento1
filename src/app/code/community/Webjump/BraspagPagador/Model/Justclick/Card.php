<?php

class Webjump_BraspagPagador_Model_Justclick_Card extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('webjump_braspag_pagador/justclick_card');
    }

    public function loadCardsByCustomer($customer)
    {
        $ccAvaliableTypes = Mage::getSingleton('webjump_braspag_pagador/method_creditcard')->getCreditCardAvailableTypesCodes();

        return $this->getCollection()
                    ->addFieldToFilter('customer_id', $customer->getId())
                    ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
                    ->addFieldToFilter('method_id', array('in' => $ccAvaliableTypes))
                    ->addFieldToFilter('is_active', array('eq' => 1))
                    ->load();
    }

    public function savePaymentResponseLib($order, array $payment)
    {
        $paymentResponse = $this->loadByOrderAndPayment($order, $payment);

        if ($paymentResponse->getId()) {
            $paymentResponse->setToken($payment['cardToken']);
            $paymentResponse->setIsActive($payment['is_active']);
            return $paymentResponse->save();
        }

        return $this->saveToken($order, $payment);
    }

    public function getPaymentMethodIdByToken($token)
    {
        $collection = Mage::getModel('webjump_braspag_pagador/justclick_card')
            ->getCollection()
            ->addFieldToFilter('token', $token)
            ->load();

        return $collection->getFirstItem()->getMethodId();
    }

    protected function loadByOrderAndPayment($order, array $payment)
    {
        $collection = Mage::getModel('webjump_braspag_pagador/justclick_card')
            ->getCollection()
            ->addFieldToFilter('alias', array('like' => '%' . $payment['maskedCreditCardNumber'] . '%'))
            ->addFieldToFilter('customer_id', $order->getCustomerId())
            ->addFieldToFilter('store_id', $order->getStoreId())
            ->addFieldToFilter('method_id', $payment['paymentMethod'])
            ->load();

        return $collection->getFirstItem();
    }

    protected function saveToken($order, array $payment)
    {
        if (empty($payment['cardToken'])) {
            return false;
        }

        $this->setData(array(
            'alias' => $payment['maskedCreditCardNumber'],
            'token' => $payment['cardToken'],
            'customer_id' => $order->getCustomerId(),
            'store_id' => Mage::app()->getStore()->getId(),
            'method_id' => $payment['paymentMethod'],
            'is_active' => $payment['is_active'],
        ));

        return $this->save();
    }
}
