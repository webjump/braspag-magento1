<?php
class Webjump_BraspagPagador_Model_Config extends Mage_Core_Model_Abstract
{
    const MERCHANT_ID_DEMO_ACCOUNT = 'E42B3806-D36F-4B8D-8B66-227B60FC3D22';
    const MERCHANT_KEY_DEMO_ACCOUNT = '3uZkdtNRS1xbedqv0VtlcDwtEgONbL9KWTrFkvgm';

    const METHOD_CC = 'webjump_braspag_cc';
    const METHOD_JUSTCLICK = 'webjump_braspag_justclick';
    const METHOD_DC = 'webjump_braspag_dc';
    const METHOD_BOLETO = 'webjump_braspag_boleto';

    const INTEGRATION_TRANSACTION = 'transaction';

    const PAYMENT_PLAN_CASH = 0;
    const PAYMENT_PLAN_ESTABLISHMENT = 1;
    const PAYMENT_PLAN_ISSUER = 2;

    const POST_AUTHORIZE = 1;
    const POST_AUTHORIZE_CAPTURE = 2;
    const POST_AUTHORIZE_AUTH = 3;
    const POST_AUTHORIZE_CAPTURE_AUTH = 4;

    /**
     * @param null $storeId
     * @return bool
     */
    public function isTestEnvironmentEnabled($storeId = null)
    {
        return (bool) Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);
    }

    /**
     * @return array
     */
    public function getPaymentActions()
    {
        $return = array(
            Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize Only'),
            Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize and Capture'),
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getTransactionLanguage()
    {
        $return = array(
            'pt-BR' => Mage::helper('webjump_braspag_pagador')->__('Portuguese (Brazil)'),
            'en-US' => Mage::helper('webjump_braspag_pagador')->__('English (United States)'),
            'es-ES' => Mage::helper('webjump_braspag_pagador')->__('Spanish'),
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getAcquirers()
    {
        $return = array(
            'Cielo' => 'Cielo',
            'Cielo30' => 'Cielo 3.0',
            'Redecard' => 'Redecard',
//            'Rede' => 'Rede',
            'Rede2' => 'Rede 2',
            'Getnet' => 'Getnet',
            'GlobalPayments' => 'Global Payments',
            'Stone' => 'Stone',
            'FirstData' => 'First Data',
            'Sub1' => 'Sub 1',
            'Banorte' => 'Banorte',
            'Credibanco' => 'Credibanco',
            'Transbank' => 'Transbank',
            'RedeSitef' => 'Rede Sitef',
            'CieloSitef' => 'Cielo Sitef',
            'SantanderSitef' => 'SantanderSitef',
            'DMCard' => 'DMCard',
            'Simulado' => '',
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getAcquirersCcPaymentMethods()
    {
        $return = array(
            'Cielo' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover"),
            'Cielo30' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover", "Hipercard", "Hiper"),
            'Redecard' => array("Visa", "Master", "Hipercard", "Hiper", "Diners"),
//            'Rede' => array(Visa, Master, Hipercard, Hiper, Diners, Elo, Amex),
            'Rede2' => array("Visa", "Master", "Hipercard", "Hiper", "Diners", "Elo", "Amex"),
            'Getnet' => array("Visa", "Master", "Elo", "Amex"),
            'GlobalPayments' => array("Visa", "Master"),
            'Stone' => array("Visa", "Master", "Hipercard", "Elo"),
            'FirstData' => array("Visa", "Master", "Cabal"),
            'Sub1' => array("Visa", "Master", "Diners", "Amex", "Discover", "Cabal", "Naranja e Nevada"),
            'Banorte' => array("Visa", "Master", "Carnet"),
            'Credibanco' => array("Visa", "Master", "Diners", "Amex", "Credential"),
            'Transbank' => array("Visa", "Master", "Diners", "Amex"),
            'RedeSitef' => array("Visa", "Master", "Hipercard", "Diners"),
            'CieloSitef' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover"),
            'SantanderSitef' => array("Visa", "Master"),
            'DMCard' => array(),
            'Simulado' => array('Simulado')
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getAvailableCcPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/webjump_braspag_cc/acquirers', $storeId));
    }

    /**
     * @return array
     */
    public function getAvailableDcPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/webjump_braspag_dc/dctypes', $storeId));
    }

    /**
     * @return array
     */
    public function getBoletoTypes()
    {

        $return = array(
            "Bradesco2" => "Boleto Registrado Bradesco",
            "BancoDoBrasil2" => "Boleto Registrado Banco do Brasil",
            "ItauShopline" => "Boleto Registrado Itau Shopline",
            "Itau2" => "Boleto Registrado Itau",
            "Santander2" => "Boleto Registrado Santander",
            "Caixa2" => "Boleto Registrado Caixa",
            "CitiBank2" => "Boleto Registrado Citi Bank",
            "BankOfAmerica" => "Boleto Registrado Bank Of America",
            "Simulado" => "Simulado"
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getAcquirersDcPaymentMethods()
    {
        $return = array(
            'Cielo' => array("Visa", "Master"),
            'Cielo30' => array("Visa", "Master"),
            'Getnet' => array("Visa", "Master"),
            'FirstData' => array("Visa", "Master"),
            'GlobalPayments' => array("Visa", "Master"),
            'Simulado' => array('Simulado')
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getIntegrations()
    {
        $_hlp = Mage::helper('webjump_braspag_pagador');

        $return = array(
            self::INTEGRATION_TRANSACTION => $_hlp->__('Transaction')
        );

        return $return;
    }

    /**
     * @return mixed
     */
    public function getConfig($storeId = null)
    {
        $sandboxFlag = $this->isTestEnvironmentEnabled($storeId);

        if ($sandboxFlag) {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/sandbox', $storeId);
        } else {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/production', $storeId);
        }

        return $wsConfig;
    }

    /**
     * @param $incrementId
     * @return string
     */
    public function generateGuid($incrementId)
    {
        $incrementId = preg_replace('/[^0-9]/', '0', $incrementId);
        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . str_pad($incrementId, 12, '0', STR_PAD_LEFT);

        return $guid;
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDcReturnUrl($storeId = null)
    {
        $storeId = Mage::app()->getStore()->getId();
        return Mage::getStoreConfig('payment/webjump_braspag_dc/return_url', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantId($storeId = null)
    {
        $merchantId = Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid merchant id in production environment. Please check configuration.'));
        }

        return $merchantId;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantKey($storeId = null)
    {
        $merchantKey = Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_key', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid merchant key in production environment. Please check configuration.'));
        }

        return $merchantKey;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getMerchantName($storeId = null)
    {
        $merchantName = Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_name', $storeId);
        if (empty($merchantName)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid merchant Name in production environment. Please check configuration.'));
        }

        return $merchantName;
    }

    /**
     * @param null $storeId
     * @return mixed
     * @throws Exception
     */
    public function getEstablishmentCode($storeId = null)
    {
        $establishmentCode = Mage::getStoreConfig('webjump_braspag_pagador/general/establishment_code', $storeId);
        if (empty($establishmentCode)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid Establishment Code in production environment. Please check configuration.'));
        }

        return $establishmentCode;
    }

    public function getMcc($storeId = null)
    {
        $mcc = Mage::getStoreConfig('webjump_braspag_pagador/general/mcc', $storeId);
        if (empty($mcc)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid MCC in production environment. Plean getTokense check configuration.'));
        }

        return $mcc;
    }
}