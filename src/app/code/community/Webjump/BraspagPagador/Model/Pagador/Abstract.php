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
//    const STATUS_CAPTURADO = 0;
//    const STATUS_AUTORIZADO = 1;
//    const STATUS_NAO_AUTORIZADO = 2;
//    const STATUS_ERRO_DESQUALIFICANTE = 3;
//    const STATUS_AGUARDANDO_RESPOSTA = 4;
//
//    const CAPTURA_STATUS_CAPTURADO = 0;
//    const CAPTURA_STATUS_NEGADO = 2;
//    const CAPTURA_STATUS_ERRO = null;

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

//    protected function getWebServiceVersion()
//    {
//        $config = $this->getConfig();
//
//        return $config['webservice_version'];
//    }

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
            'version' => $this->getWebServiceVersion(),
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

//class Webjump_Checksum
//{
//    // Used used binaray in Hex format
//    private $privateKey = "ec340029d65c7125783d8a8b27b77c8a0fcdc6ff23cf04b576063fd9d1273257"; // default
//    private $keySize = 32;
//    private $profile;
//    private $hash = "sha1";
//
//    public function __construct($option, $key = null, $hash = "sha1")
//    {
//        $this->profile = $this->_normalizeOption($option);
//        $this->hash = $hash;
//
//        // Use Default Binary Key or generate yours
//        $this->privateKey = ($key === null) ? pack('H*', $this->privateKey) : $key;
//        $this->keySize = strlen($this->privateKey);
//    }
//
//    //Normalize new lines since Magento Cache system modify them
//    private function _normalizeOption($value)
//    {
//        foreach ($value AS $k => $v) {
//            $value[$k] = preg_replace('/[\r\n]{1,}/', PHP_EOL, $v);
//        }
//        return $value;
//    }
//
//    private function randString($length)
//    {
//        $r = 0;
//        switch (true) {
//            case function_exists("openssl_random_pseudo_bytes"):
//                $r = bin2hex(openssl_random_pseudo_bytes($length));
//                break;
//            case function_exists("mcrypt_create_ivc"):
//            default:
//                $r = bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
//                break;
//        }
//
//        return strtoupper(substr($r, 0, $length));
//    }
//
//    public function generate($keys = false)
//    {
//        // 10 ramdom char
//        $keys = $keys ?: $this->randString(10);
//        $keys = strrev($keys); // reverse string
//
//        // Add keys to options
//        if (is_array($this->profile)) {
//            $this->profile['keys'] = $keys;
//        } else {
//            $this->profile->keys = $keys;
//        }
//
//        // Serialise to convert to string
//        $data = json_encode($this->profile);
//
//        // Simple Random Chr authentication
//        $hash = hash_hmac($this->hash, $data, $this->privateKey);
//        $hash = str_split($hash);
//
//        $step = floor(count($hash) / 15);
//        $i = 0;
//
//        $key = array();
//        foreach (array_chunk(str_split($keys), 2) as $v) {
//            $i = $step + $i;
//            $key[] = sprintf("%s%s%s%s%s", $hash[$i++], $v[1], $hash[$i++], $v[0], $hash[$i++]);
//            $i++; // increment position
//        }
//
//        return strtoupper(implode("-", $key));
//    }
//
//    public function check($key)
//    {
//        $key = trim($key);
//        if (strlen($key) != 29) {
//            return false;
//        }
//        // Exatact ramdom keys
//        $keys = implode(array_map(function ($v) {
//            return $v[3] . $v[1];
//        }, array_map("str_split", explode("-", $key))));
//
//        $keys = strrev($keys); // very important
//
//        return $key === $this->generate($keys);
//    }
//}
