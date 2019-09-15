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
 * Pagador Abstract
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BraspagPagador_Model_Pagador_Abstract extends Mage_Core_Model_Abstract
{
    protected $_helper;
    protected $_serviceManager;

    protected function getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('webjump_braspag_pagador');
        }

        return $this->_helper;
    }

    protected function initPaymentRequest(Varien_Object $payment, $amount)
    {
        $this->setPayment($payment);
        $this->setAmount($amount);

        $method = $payment->getMethodInstance();
        $configModel = $method->getConfigModel();
        $order = $payment->getOrder();
        $customer = $order->getCustomer();
        $storeId = $order->getStoreId();

        $this->setMethod($method);
        $this->setConfigModel($configModel);
        $this->setOrder($order);
        $this->setCustomer($customer);
        $this->setStoreId($storeId);

        return $this;
    }

    protected function getMerchantId()
    {
        $storeId = $this->getStoreId();
        return $this->getHelper()->getMerchantId($storeId);
    }

    protected function getMerchantKey()
    {
        $storeId = $this->getStoreId();
        return $this->getHelper()->getMerchantKey($storeId);
    }

    protected function convertResponseToArray($response)
    {
        return $response;
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

    protected function convertPaymentToCaptureRequest(Varien_Object $payment, $amount)
    {
        $this->initPaymentRequest($payment, $amount);

        $helper = $this->getHelper();

        $data = array(
            'requestId' => $helper->generateGuid($this->getOrder()->getIncrementId()),
            'merchantId' => $this->getMerchantId(),
            'transactions' => $this->getTransactionsDataToCapture(),
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Capture\Request');
        $request->populate($data);

        return $request;

    }

    protected function getServiceManager()
    {
        if (!$this->_serviceManager) {
            $this->_serviceManager = new Webjump_BrasPag_Pagador_Service_ServiceManager($this->getConfig());
        }

        return $this->_serviceManager;
    }
}