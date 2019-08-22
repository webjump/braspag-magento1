<?php
///**
// * Webjump BrasPag Pagador
// *
// * LICENSE
// *
// * This source file is subject to the new BSD license that is bundled
// * with this package in the file LICENSE.txt.
// * It is also available through the world-wide-web at this URL:
// * http://www.webjump.com.br
// * If you did not receive a copy of the license and are unable to
// * obtain it through the world-wide-web, please send an email
// * to license@webjump.com so we can send you a copy immediately.
// *
// * @category  Api
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// */
//
///**
// * Pagador Post
// *
// * @category  Api
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//class Webjump_BraspagPagador_Model_Pagador_Post extends Webjump_BraspagPagador_Model_Pagador_Abstract
//{
//    protected $request;
//    protected $response;
//    protected $cryptRequest;
//
//    protected function setRequest($data)
//    {
//        $this->request = $data;
//
//        return $this;
//    }
//
//    protected function getRequest()
//    {
//        return $this->request;
//    }
//
//    protected function setCryptRequest($data)
//    {
//        $this->cryptRequest = $data;
//
//        return $this;
//    }
//
//    protected function getCryptRequest()
//    {
//        return $this->cryptRequest;
//    }
//
//    public function authorize(Varien_Object $payment, $amount)
//    {
//        $this->setRequest($this->convertPaymentToAuthorizeRequest($payment, $amount));
//
//        $storeId = $this->getStoreId();
//        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);
//
//        if ($sandboxFlag) {
//            $url = Mage::getStoreConfig('webjump_braspag_pagador/post/index/config/sandbox/url', $storeId);
//        } else {
//            $url = Mage::getStoreConfig('webjump_braspag_pagador/post/index/config/production/url', $storeId);
//        }
//
//        if (in_array($payment->getmethod(), array('webjump_braspag_post_passthru_cc', 'webjump_braspag_post_passthru_dc', 'webjump_braspag_post_passthru_boleto'))) {
//            if ($sandboxFlag) {
//                $url = Mage::getStoreConfig('webjump_braspag_pagador/post/passthru/config/sandbox/url', $storeId);
//            } else {
//                $url = Mage::getStoreConfig('webjump_braspag_pagador/post/passthru/config/production/url', $storeId);
//            }
//        }
//
//        $response = array(
//            'sandboxFlag' => $sandboxFlag,
//            'url' => $url,
//            'idLoja' => $this->getMerchantId(),
//            'crypt' => $this->encrypt($this->getRequest()),
//        );
//
//        $payment->getMethodInstance()->debugData(array(
//            'request' => $this->getRequest(),
//            'response' => $response,
//            'cryptRequest' => $this->getCryptRequest(),
//        ));
//
//        return array(
//            'request' => $this->getRequest(),
//            'response' => $response,
//        );
//    }
//
//    protected function convertPaymentToAuthorizeRequest(Varien_Object $payment, $amount)
//    {
//        $this->initPaymentRequest($payment, $amount);
//
//        $data = array(
//            'merchantId' => $this->getMerchantId(),
//            'request' =>
//            $this->getGeneralData()
//             + $this->getOrderData()
//             + $this->getCustomerData()
//             + $this->getPaymentData()
//            ,
//            'extraFields' =>
//            $this->getExtraData()
//            ,
//        );
//
//        //TODO: refactor
//        $paymentRequest = $payment->getAdditionalInformation('payment_request');
//
//        if (isset($paymentRequest['cc_type'])) {
//            $data['request']['CODPAGAMENTO'] = $paymentRequest['cc_type'];
//
//            if ($payment->getmethod() == 'webjump_braspag_post_passthru_cc') {
//                $data['request']['PARCELAS'] = 1;
//                $data['request']['TIPOPARCELADO'] = $payment->getMethodInstance()->getConfigData('installments_plan');
//                // $data['request']['SAVECREDITCARD'] = (isset($paymentRequest['cc_justclick']));
//
//                if (isset($paymentRequest['installments'])) {
//                    $data['request']['PARCELAS'] = $paymentRequest['installments'];
//                }
//            }
//        } elseif (isset($paymentRequest['boleto_type'])) {
//            $data['request']['CODPAGAMENTO'] = $paymentRequest['boleto_type'];
//        }
//
//        return $data;
//    }
//
//    protected function getExtraData()
//    {
//        $storeId = $this->getStoreId();
//
//        $data = array(
//            'EXTRADYNAMICURL' => Mage::getUrl(Mage::getStoreConfig('webjump_braspag_pagador/post/url_notification', $storeId)),
//        );
//
//        return $data;
//    }
//
//    protected function getGeneralData()
//    {
//        $method = $this->getMethod();
//        $storeId = $this->getStoreId();
//
//        $data = array(
//            'LANGUAGE' => $method->getConfigData('transaction_language'),
//        );
//
//        return $data;
//    }
//
//    protected function getOrderData()
//    {
//        $order = $this->getOrder();
//
//        $data = array(
//            'VENDAID' => $order->getIncrementId(),
//        );
//
//        return $data;
//    }
//
//    protected function getCustomerData()
//    {
//        $helper = $this->getHelper();
//        $order = $this->getOrder();
//
//        $data = array(
//            'NOME' => $order->getCustomerName(),
//        );
//
//        if ($taxvat = $order->getCustomerTaxvat()) {
//            $_hlpValidate = Mage::helper('webjump_braspag_pagador/validate');
//
//            $taxvat = preg_replace('/[^0-9]/', '', $taxvat);
//            switch ($_hlpValidate->isCpfOrCnpj($taxvat)) {
//                case $_hlpValidate::CPF:
//                    $data['CPF'] = $taxvat;
//                    break;
//
//                case $_hlpValidate::CNPJ:
//                    $data['RAZAO_PJ'] = $order->getCustomerName();
//                    $data['CNPJ'] = $taxvat;
//                    break;
//
//                default:
//                    throw new Exception($helper->__('Invalid identity type'));
//            }
//        }
//
//        return $data;
//    }
//
//    /**
//     * @deprecated
//     */
//    protected function getPaymentsData()
//    {
//        return $this->getPaymentData();
//    }
//
//    protected function getPaymentData()
//    {
//        $order = $this->getOrder();
//        $method = $this->getMethod();
//        $storeId = $this->getStoreId();
//
//        $currency = $order->getOrderCurrencyCode();
//        $country = Mage::getStoreConfig('webjump_braspag_pagador/general/country', $storeId);
//
//        $data = array(
//            'VALOR' => number_format($this->getAmount(), 2, '', ''),
//            'TRANSACTIONTYPE' => $method->getConfigData('transaction_type'),
//            'TRANSACTIONCURRENCY' => $currency,
//            'TRANSACTIONCOUNTRY' => $country,
//        );
//
//        return $data;
//    }
//
//    protected function getGeneralservice()
//    {
//        return Mage::getModel('webjump_braspag_pagador/generalservice');
//    }
//
//    protected function encrypt($request)
//    {
//        $generalservice = $this->getGeneralservice();
//        $return = $generalservice->encrypt($request);
//        $this->setCryptRequest($generalservice->getLastRequest());
//
//        return $return;
//    }
//}
