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
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Transaction extends Webjump_BraspagPagador_Model_Pagador_Abstract
{
//    const STATUS_CAPTURADO = 0;
//    const STATUS_AUTORIZADO = 1;
//    const STATUS_NAO_AUTORIZADO = 2;
//    const STATUS_ERRO_DESQUALIFICANTE = 3;
//    const STATUS_AGUARDANDO_RESPOSTA = 4;
//    const CAPTURA_STATUS_CAPTURADO = 0;
//    const CAPTURA_STATUS_NEGADO = 2;
//    const CAPTURA_STATUS_ERRO = null;

    protected $_serviceManager;

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

    protected function convertResponseToArray($response)
    {
        return $response;
    }

    protected function getServiceManager()
    {
        if (!$this->_serviceManager) {
            $this->_serviceManager = new Webjump_BrasPag_Pagador_Service_ServiceManager($this->getConfig());
        }

        return $this->_serviceManager;
    }

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

    protected function convertPaymentToCaptureRequest(Varien_Object $payment,$amount)
    {
        $this->initPaymentRequest($payment, $amount);

        $helper = $this->getHelper();

        $data = array(
            'requestId' => $helper->generateGuid($this->getOrder()->getIncrementId()),
            'merchantId'=> $this->getMerchantId(),
            'transactions' => $this->getTransactionsDataToCapture(),
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Capture\Request');
        $request->populate($data);

        return $request;

    }

    protected function getOrderData()
    {
        $order	= $this->getOrder();

        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
            ->setOrderId($order->getIncrementId())
        ;

        return $dataOrder;
    }

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

    protected function getTransactionsDataToCapture()
    {
        $payment = $this->getPayment();
        $amount = $this->getAmount();
        $helper = $this->getHelper();

        $api = Mage::getModel('webjump_braspag_pagador/pagadorold')->getApi($payment);

        $authorizationTransaction = $payment->getAuthorizationTransaction();

        if (!$authorizationTransaction) {
            throw new Exception($helper->__('Transaction not found'));
        }

        $transactionInfo = $authorizationTransaction->getAdditionalInformation(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS);

        //Popular data request
        $dataTransactions = $this->getServiceManager()->get('Pagador\Data\Request\Transaction\List');

        foreach ($transactionInfo AS $key => $value) {
            if(preg_match('/^payment_([0-9]+)_braspagTransactionId$/', $key, $match)){

                $status = $transactionInfo['payment_'.$match[1].'_status'];

                if ($status == $api::STATUS_AUTORIZADO) {

                    $data = array(
                        'braspagTransactionId' => $value,
                        'amount' => $transactionInfo['payment_'.$match[1].'_amount'],
                        //        			'serviceTaxAmount' => '',
                    );

                    $transaction = $this->getServiceManager()->get('Pagador\Data\Request\Transaction\Item');
                    $transaction->populate($data);
                    $dataTransactions->add($transaction);
                }
            }
        }

        return $dataTransactions;
    }

    protected function formatAddress($addressService, $address, $paymentMethod)
    {
        $helper = $this->getHelper();

        if ($paymentMethod == 'webjump_braspag_boleto') {
            require_once("lib/Webjump/Abbreviation.php");

            $abb = new Webjump_Abbreviation();

            $abb->setStreet($address->getStreet1());
            $abb->setNumber($address->getStreet2());
            $abb->setComplement($address->getStreet3());

            $abb->abbreviation();

            $addressService->setStreet($abb->getStreet());
            $addressService->setNumber($abb->getNumber());
            $addressService->setComplement($abb->getComplement());

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