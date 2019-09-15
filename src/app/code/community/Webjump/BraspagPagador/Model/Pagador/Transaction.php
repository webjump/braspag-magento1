<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction extends Webjump_BraspagPagador_Model_Pagador_Abstract
{
    protected $_serviceManager;

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return mixed
     * @throws Exception
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $request = $this->convertPaymentToAuthorizeRequest($payment, $amount);

        $transaction = $this->getServiceManager()->get('Pagador\Transaction\Authorize');
        $transaction->setRequest($request);
        $response = $this->convertResponseToArray($transaction->execute());

        $payment->getMethodInstance()->debugData(array(
            'request' => $transaction->debug(),
            'response' => $response->debug(),
        ));

        return $response;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    protected function convertPaymentToAuthorizeRequest(Varien_Object $payment,$amount)
    {
        $this->initPaymentRequest($payment, $amount);

        $helper = $this->getHelper();

        $data = array(
            'requestId' => $helper->generateGuid($this->getOrder()->getIncrementId()),
            'merchantId' => $this->getMerchantId(),
            'merchantKey' => $this->getMerchantKey(),
            'order' 	=> $this->getOrderData(),
            'payment' 	=> $this->getPaymentData(),
            'customer' 	=> $this->getCustomerData()
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Authorize\Request');
        $request->populate($data);

        return $request;

    }

    /**
     * @param $response
     * @return mixed
     */
    protected function convertResponseToArray($response)
    {
        return $response;
    }

    /**
     * @return Webjump_BrasPag_Pagador_Service_ServiceManager
     */
    protected function getServiceManager()
    {
        if (!$this->_serviceManager) {
            $this->_serviceManager = new Webjump_BrasPag_Pagador_Service_ServiceManager($this->getConfig());
        }

        return $this->_serviceManager;
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        $storeId = $this->getStoreId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/sandbox', $storeId);
        } else {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/production', $storeId);
        }

        return $wsConfig;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return mixed
     * @throws Exception
     */
    public function capture(Varien_Object $payment, $amount)
    {
        $request = $this->convertPaymentToCaptureRequest($payment, $amount);

        $transaction = $this->getServiceManager()->get('Pagador\Transaction\Capture');
        $transaction->setRequest($request);
        $response = $this->convertResponseToArray($transaction->execute());

        $payment->getMethodInstance()->debugData(array(
            'request' => $transaction->debug(),
            'response' => $response->debug(),
        ));

        return $response;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return mixed
     * @throws Exception
     */
    public function void(Varien_Object $payment, $amount = 0)
    {
        $request = $this->convertPaymentToVoidRequest($payment, $amount);

        $transaction = $this->getServiceManager()->get('Pagador\Transaction\Void');
        $transaction->setRequest($request);
        $response = $this->convertResponseToArray($transaction->execute());

        $payment->getMethodInstance()->debugData(array(
            'request' => $transaction->debug(),
            'response' => $response->debug(),
        ));

        return $response;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    protected function convertPaymentToCaptureRequest(Varien_Object $payment,$amount)
    {
        $this->initPaymentRequest($payment, $amount);

        $helper = $this->getHelper();

        $data = array(
            'requestId' => $helper->generateGuid($this->getOrder()->getIncrementId()),
            'merchantId' => $this->getMerchantId(),
            'merchantKey' => $this->getMerchantKey(),
            'order' 	=> $this->getOrderData(),
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Capture\Request');
        $request->populate($data);

        return $request;

    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    protected function convertPaymentToVoidRequest(Varien_Object $payment, $amount = 0)
    {
        $this->initPaymentRequest($payment, $payment->getOrder()->getGrandTotal());

        $helper = $this->getHelper();

        $data = array(
            'requestId' => $helper->generateGuid($this->getOrder()->getIncrementId()),
            'merchantId' => $this->getMerchantId(),
            'merchantKey' => $this->getMerchantKey(),
            'order' 	=> $this->getOrderData($amount),
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Void\Request');
        $request->populate($data);

        return $request;

    }

    /**
     * @param int $amount
     * @return mixed
     * @throws Exception
     */
    protected function getOrderData($amount = 0)
    {
        $order	= $this->getOrder();
        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
            ->setOrderId($order->getIncrementId())
            ->setBraspagOrderId($this->getPaymentTransactionId())
            ->setOrderAmount(($amount == 0 ? $order->getGrandTotal() : $amount));

        return $dataOrder;
    }

    /**
     * @return bool|mixed
     * @throws Exception
     */
    protected function getCustomerData()
    {
        $helper = $this->getHelper();
        $order = $this->getOrder();

        $dataCustomer = $this->getServiceManager()->get('Pagador\Data\Request\Customer');
        $addressService = $this->getServiceManager()->get('Pagador\Data\Request\Address');

        if ($taxvat = $order->getCustomerTaxvat()) {
            $_hlpValidate = Mage::helper('webjump_braspag_pagador/validate');

            switch ($_hlpValidate->isCpfOrCnpj($taxvat)) {
                case $_hlpValidate::CPF: $identityType = 'CPF';break;
                case $_hlpValidate::CNPJ: $identityType = 'CNPJ';break;
                default:
                    throw new Exception($helper->__('Invalid identity type'));
            }

            $taxvat = $helper->clearTaxVat($taxvat);

            $dataCustomer
                ->setIdentity($taxvat)
                ->setIdentityType($identityType)
            ;
        }

        $formatedAddressService = null;
        $formatedDeliveryAddressService = null;

        if ($billingAddress = $order->getBillingAddress()) {
            $formatedAddressService = $this->formatAddress($addressService, $billingAddress, $order->getPayment()->getMethod());
        }

        if ($deliveryAddress = $order->getShippingAddress()) {
            $formatedDeliveryAddressService = $this->formatAddress($addressService, $deliveryAddress, $order->getPayment()->getMethod());
        }

        $name = $helper->clearSpaces($order->getCustomerName());

        $dataCustomer
            ->setName($name)
            ->setEmail($order->getCustomerEmail())
            ->setAddress($formatedAddressService)
            ->setDeliveryAddress($formatedDeliveryAddressService);

        return $dataCustomer;
    }

    /**
     * @param bool $isCapture
     * @return bool|mixed|string
     * @throws Exception
     */
    protected function getPaymentTransactionId()
    {
        $payment = $this->getPayment();

        if (!$authorizationTransaction = $payment->getAdditionalInformation('payment_response')) {
            return '';
        }

        if(!$braspagTransactionId = $authorizationTransaction['paymentId']) {
            return '';
        }

        $status = $authorizationTransaction['status'];

        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED) {

            return $braspagTransactionId;
        }

        return '';
    }

    /**
     * @param $addressService
     * @param $address
     * @param $paymentMethod
     * @return mixed
     */
    protected function formatAddress($addressService, $address, $paymentMethod)
    {
        $helper = $this->getHelper();

        if ($paymentMethod == 'webjump_braspag_boleto') {

            $abbreviationHelper = Mage::helper('webjump_braspag_pagador/addressAbbreviation');

            $abbreviationHelper->setStreet($address->getStreet1());
            $abbreviationHelper->setNumber($address->getStreet2());
            $abbreviationHelper->setComplement($address->getStreet3());

            $abbreviationHelper->abbreviation();

            $addressService->setStreet($abbreviationHelper->getStreet());
            $addressService->setNumber($abbreviationHelper->getNumber());
            $addressService->setComplement($abbreviationHelper->getComplement());

        } else {
            $addressService->setStreet($address->getStreet1());
            $addressService->setNumber($address->getStreet2());
            $addressService->setComplement($address->getStreet3());
        }

        $city = $helper->removeAccents($address->getCity());

        $district = $address->getStreet4();
        if (empty($district)) {
            $district = '--';
        }
        $addressService->setDistrict($district);

        $addressService->setZipCode($address->getPostcode());
        $addressService->setCity($city);

        $state = $address->getRegionCode();
        if (empty($state)) {
            $state = '--';
        }
        $addressService->setState($state);

        $addressService->setCountry($address->getCountryId());

        return $addressService;
    }
}