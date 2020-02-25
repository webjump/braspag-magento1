<?php

class Braspag_Lib_Pagador_Transaction_Order_Request_Hydrator extends Braspag_Lib_Core_Hydrator_Abstract
    implements Braspag_Lib_Pagador_Transaction_Order_Request_HydratorInterface
{
    /**
     * @param array $data
     * @param Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
     * @return Braspag_Lib_Pagador_Transaction_Order_RequestInterface
     */
	public function hydrate(array $data, Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request)
	{
		$this->hydrateDefault($data, $request);
		$this->hydrateOrder($data, $request);
		$this->hydratePayment($data, $request);
		$this->hydrateCustomer($data, $request);

		return $request;
	}

    /**
     * @param array $data
     * @param Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
     * @return mixed
     */
	protected function hydrateDefault(
	    array $data, Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
    ) {
       $request->setRequestId((isset($data['requestId'])) ? $data['requestId'] : null);
       return $request->setVersion((isset($data['version'])) ? $data['version'] : null);
	}

    /**
     * @param array $data
     * @param Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
     * @return mixed
     */
	protected function hydrateOrder(
	    array $data, Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
    ) {
        $order = $this->getServiceManager()->get('Pagador\Data\Request\Order');
        $order->populate((isset($data['order'])) ? $data['order'] : array());
        
        return $request->setOrder($order);
	}

    /**
     * @param array $data
     * @param Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
     * @return mixed
     */
	protected function hydratePayment(
	    array $data, Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
    ) {
        $paymentCurrent = $this->getServiceManager()->get('Pagador\Data\Request\Payment');

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
                case Braspag_Lib_Pagador_Data_Request_Payment_CreditCard::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');
                    break;
                case Braspag_Lib_Pagador_Data_Request_Payment_DebitCard::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard');
                    break;
                case Braspag_Lib_Pagador_Data_Request_Payment_Boleto::METHOD:
                    return $this->getServiceManager()->get('Pagador\Data\Request\Payment\Boleto');
                    break;
            }
        }

        return false;
	}

    /**
     * @param array $data
     * @param Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
     * @return mixed
     */
	protected function hydrateCustomer(
	    array $data, Braspag_Lib_Pagador_Transaction_Order_RequestInterface $request
    ) {
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