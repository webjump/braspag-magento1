<?php

class Braspag_ProtectedCard_Model_Card extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_protectedcard/card');
    }

    /**
     * @param $customer
     * @return mixed
     * @throws Mage_Core_Model_Store_Exception
     */
    public function loadCardsByCustomer($customer)
    {
        $ccAvailableTypes = Mage::getSingleton('braspag_pagador/method_creditcard')
            ->getCreditCardAvailableTypesCodes();

        return $this->getCollection()
                    ->addFieldToFilter('customer_id', $customer->getId())
                    ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
                    ->addFieldToFilter('provider', array('in' => $ccAvailableTypes))
                    ->addFieldToFilter('is_active', array('eq' => 1))
                    ->load();
    }

    /**
     * @param $order
     * @param array $payment
     * @return bool|Mage_Core_Model_Abstract
     * @throws Mage_Core_Model_Store_Exception
     */
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

    /**
     * @param $token
     * @return mixed
     */
    public function getPaymentMethodIdByToken($token)
    {
        $collection = Mage::getModel('braspag_protectedcard/card')
            ->getCollection()
            ->addFieldToFilter('token', $token)
            ->load();

        return $collection->getFirstItem()->getProvider();
    }

    /**
     * @param $order
     * @param array $payment
     * @return mixed
     */
    protected function loadByOrderAndPayment($order, array $payment)
    {
        $collection = Mage::getModel('braspag_protectedcard/card')
            ->getCollection()
            ->addFieldToFilter('alias', array('like' => '%' . $payment['maskedCreditCardNumber'] . '%'))
            ->addFieldToFilter('customer_id', $order->getCustomerId())
            ->addFieldToFilter('store_id', $order->getStoreId())
            ->addFieldToFilter('provider', $payment['provider'])
            ->load();

        return $collection->getFirstItem();
    }

    /**
     * @param $order
     * @param array $payment
     * @return bool|Mage_Core_Model_Abstract
     * @throws Mage_Core_Model_Store_Exception
     */
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
            'provider' => $payment['provider'],
            'is_active' => $payment['is_active'],
        ));

        return $this->save();
    }
}
