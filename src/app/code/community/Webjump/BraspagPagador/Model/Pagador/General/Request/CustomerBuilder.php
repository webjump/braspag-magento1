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
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_General_Request_CustomerBuilder
{
    protected $serviceManager;

    /**
     * @param $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $helper = $this->getHelper();
        $order = $payment->getOrder();

        $dataCustomer = $this->getServiceManager()->get('Pagador\Data\Request\Customer');

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

        $addressBuilder = Mage::getModel('webjump_braspag_pagador/pagador_general_request_customer_addressBuilder');

        if ($billingAddress = $order->getBillingAddress()) {
            $formatedAddressService = $addressBuilder
                ->build($order->getPayment()->getMethod(), $billingAddress);
        }

        if ($deliveryAddress = $order->getShippingAddress()) {
            $formatedDeliveryAddressService = $addressBuilder
                ->build($order->getPayment()->getMethod(), $deliveryAddress);
        }

        $name = $helper->clearSpaces($order->getCustomerName());

        $dataCustomer
            ->setName($name)
            ->setEmail($order->getCustomerEmail())
            ->setAddress($formatedAddressService)
            ->setDeliveryAddress($formatedDeliveryAddressService);

        return $dataCustomer;
    }

    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        if (!$this->serviceManager) {
            $this->serviceManager = new Webjump_BrasPag_Core_Service_Manager([]);
        }

        return $this->serviceManager;
    }
}
