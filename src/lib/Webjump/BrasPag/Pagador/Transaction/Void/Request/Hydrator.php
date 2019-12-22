<?php

class Webjump_BrasPag_Pagador_Transaction_Void_Request_Hydrator
    extends Webjump_BrasPag_Core_Hydrator_Abstract
    implements Webjump_BrasPag_Pagador_Transaction_Void_Request_HydratorInterface
{
    /**
     * @param array $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request
     * @return Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface
     */
    public function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        $this->hydrateDefault($data, $request);
        $this->hydrateOrder($data, $request);
        $this->hydratePayment($data, $request);
        $this->hydrateCustomer($data, $request);

        return $request;
    }

    /**
     * @param array $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request
     * @return mixed
     */
    protected function hydrateDefault(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        $request->setRequestId((isset($data['requestId'])) ? $data['requestId'] : null);
        return $request->setVersion((isset($data['version'])) ? $data['version'] : null);
    }

    /**
     * @param array $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request
     * @return mixed
     */
    protected function hydrateOrder(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        $order = $this->getServiceManager()->get('Pagador\Data\Request\Order');
        $order->populate((isset($data['order'])) ? $data['order'] : array());

        return $request->setOrder($order);
    }

    /**
     * @param array $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request
     * @return mixed
     */
    protected function hydratePayment(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        $paymentCurrent = $this->getServiceManager()->get('Pagador\Data\Request\Payment\Current');

        $paymentData = (isset($data['payment'])) ? $data['payment'] : array();

        if ($payment = $this->getPaymentByType($paymentData)) {
            $paymentCurrent->populate($payment);
            $paymentCurrent->add($payment);
        }

        return $request->setPayment($paymentCurrent);
    }

    /**
     * @param $paymentData
     * @return bool
     */
    protected function getPaymentByType($paymentData)
    {
        if (isset($paymentData['type'])) {
            switch ($paymentData['type']) {
                case Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');
                    break;
                case Webjump_BrasPag_Pagador_Data_Request_Payment_DebitCard::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard');
                    break;
                case Webjump_BrasPag_Pagador_Data_Request_Payment_Billet::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\Billet');
                    break;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request
     * @return mixed
     */
    protected function hydrateCustomer(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        if (isset($data['customer']['address'])) {
            $address = $this->getServiceManager()->get('Pagador\Data\Request\Address');
            $address->populate($data['customer']['address']);
            $data['customer']['address'] = $address;
        }

        $customer = $this->getServiceManager()->get('Pagador\Data\Request\Customer');
        $customer->populate((isset($data['customer'])) ? $data['customer'] : array());

        return $request->setCustomer($customer);
    }
}