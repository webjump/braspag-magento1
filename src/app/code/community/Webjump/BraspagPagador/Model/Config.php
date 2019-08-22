<?php
class Webjump_BraspagPagador_Model_Config extends Mage_Core_Model_Abstract
{
    const MERCHANT_ID_DEMO_ACCOUNT = 'E42B3806-D36F-4B8D-8B66-227B60FC3D22';
    const MERCHANT_KEY_DEMO_ACCOUNT = '3uZkdtNRS1xbedqv0VtlcDwtEgONbL9KWTrFkvgm';

    const METHOD_CC = 'webjump_braspag_cc';
    const METHOD_JUSTCLICK = 'webjump_braspag_justclick';
    const METHOD_DC = 'webjump_braspag_dc';
    const METHOD_BOLETO = 'webjump_braspag_boleto';
//    const METHOD_POST_INDEX = 'webjump_braspag_postindex';
//    const METHOD_POST_PASSTHRU_CC = 'webjump_braspag_postpassthru_cc';

    const INTEGRATION_TRANSACTION = 'transaction';
//    const INTEGRATION_POST = 'post';

//    const INTEGRATION_POST_METHOD_INDEX = 'index';
//    const INTEGRATION_POST_METHOD_PASSTHRU = 'passthru';

    const PAYMENT_PLAN_CASH = 0;
    const PAYMENT_PLAN_ESTABLISHMENT = 1;
    const PAYMENT_PLAN_ISSUER = 2;

    const POST_AUTHORIZE = 1;
    const POST_AUTHORIZE_CAPTURE = 2;
    const POST_AUTHORIZE_AUTH = 3;
    const POST_AUTHORIZE_CAPTURE_AUTH = 4;

//    const STATUS_CAPTURADO = 0;
//    const STATUS_AUTORIZADO = 1;
//    const STATUS_NAO_AUTORIZADO = 2;
//    const STATUS_ERRO_DESQUALIFICANTE = 3;
//    const STATUS_AGUARDANDO_RESPOSTA = 4;

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

    public function getPostTransactionTypes()
    {
        $return = array(
            self::POST_AUTHORIZE => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize Only'),
            self::POST_AUTHORIZE_CAPTURE => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize and Capture'),
            self::POST_AUTHORIZE_AUTH => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize Only with Authentication'),
            self::POST_AUTHORIZE_CAPTURE_AUTH => Mage::helper('webjump_braspag_pagador')
                ->__('Authorize and Capture with Authentication'),
        );

        return $return;
    }

    public function getTransactionLanguage()
    {
        $return = array(
            'pt-BR' => Mage::helper('webjump_braspag_pagador')->__('Portuguese (Brazil)'),
            'en-US' => Mage::helper('webjump_braspag_pagador')->__('English (United States)'),
            'es-ES' => Mage::helper('webjump_braspag_pagador')->__('Spanish'),
        );

        return $return;
    }

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


//            'cielo' => array('500', '501', '502', '503', '504', '543', '544', '545', '997', '998', '999', '995', '996'),
//            'redecard' => array('509', '510', '511', '548', '608'),
//            'banorte' => array('505', '506', '507', '508', '520', '521'),
//            'sitef' => array('524', '525', '526', '527', '528', '529', '530', '531', '532', '539'),
//            'pagosonline' => array('512', '513', '514', '515'),
//            'payvision' => array('516', '517', '518', '519'),
//            'sub1' => array('535', '536', '537', '538', '540', '541', '542'),
        );

        return $return;
    }

    public function getAvailableCcPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/webjump_braspag_cc/acquirers', $storeId));
    }

    public function getAvailableDcPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/webjump_braspag_dc/dctypes', $storeId));
    }

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

//        $paymentMethods = array('', '06', '07', '08', '09', '10', '13', '14', '124', '551', '568', '583', '585', '589', '593', '594', '1002');
//        $return = array_intersect_key($this->getPaymentMethods(), array_fill_keys($paymentMethods, true));
//        asort($return);

        return $return;
    }

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

//        return self::getDcTypesPost() + self::getDcTypesTransaction();

        return $return;
    }

//    public function getDcTypesPost()
//    {
//        $paymentMethods = array(11, 12, 15, 30, 31, 92, 123, 552);
//        $return = array_intersect_key($this->getPaymentMethods(), array_fill_keys($paymentMethods, true));
//        asort($return);
//
//        return $return;
//    }

    public function getDcTypesTransaction()
    {
//        $paymentMethods = array(123, 552, 609);
//        $return = array_intersect_key($this->getPaymentMethods(), array_fill_keys($paymentMethods, true));
//        asort($return);

        return $this->getDcTypes();
    }


//    public function getPaymentMethods()
//    {
//        $return = array(
////            '06' => 'Boleto Bradesco',
////            '07' => 'Boleto Caixa Econômica Federal',
////            '08' => 'Boleto HSBC',
////            '09' => 'Boleto Banco do Brasil',
////            '10' => 'Boleto Real ABN AMRO',
//            '11' => 'Débito Bradesco (SPS) ',
//            '12' => 'Itaú Shopline',
//            '13' => 'Boleto Citibank',
//            '14' => 'Boleto Itaú',
//            '15' => 'BBPag',
//            '16' => 'RealPague',
//            '28' => 'Real FLV',
//            '30' => 'Débito Banrisul',
//            '31' => 'Débito Unibanco',
//            '32' => 'Financiamento BBPag ',
//            '34' => 'Financiamento Eletrônico Bradesco',
//            '35' => 'Paypal Express Checkout',
//            '92' => 'Débito HSBC',
//            '111' => 'SafetyPay',
//            '123' => 'Cielo Visa Electron',
//            '124' => 'Boleto Santander',
//            '500' => 'Cielo VISA',
//            '501' => 'Cielo MASTERCARD',
//            '502' => 'Cielo AMEX',
//            '503' => 'Cielo DINERS',
//            '504' => 'Cielo ELO',
//            '505' => 'Banorte VISA',
//            '506' => 'Banorte MASTERCARD',
//            '507' => 'Banorte DINERS',
//            '508' => 'Banorte AMEX',
//            '509' => 'Redecard Webservice VISA',
//            '510' => 'Redecard Webservice MASTERCARD',
//            '511' => 'Redecard Webservice DINERS',
//            '512' => 'PagosOnLine VISA',
//            '513' => 'PagosOnLine MASTERCARD',
//            '514' => 'PagosOnLine AMEX',
//            '515' => 'PagosOnLine DINERS',
//            '516' => 'Payvision VISA',
//            '517' => 'Payvision MASTERCARD',
//            '518' => 'Payvision DINERS',
//            '519' => 'Payvision AMEX',
//            '520' => 'Banorte Cargos Automaticos VISA',
//            '521' => 'Banorte Cargos Automaticos MASTERCARD',
//            '523' => 'AMEX 2P',
//            '524' => 'SITEF VISA',
//            '525' => 'SITEF MASTERCARD',
//            '526' => 'SITEF AMEX',
//            '527' => 'DITEF DINERS',
//            '528' => 'SITEF HIPERCARD',
//            '529' => 'SITEF LEADER',
//            '530' => 'SITEF AURA',
//            '531' => 'SITEF SANTANDER VISA',
//            '532' => 'SITEF SANTANDER MASTERCARD',
//            '535' => 'SUB1 – VISA',
//            '536' => 'SUB1 – MASTERCARD	',
//            '537' => 'SUB1 - AMEX',
//            '538' => 'SUB1 – DINERS',
//            '539' => 'SITEF SONDA',
//            '540' => 'SUB1 – NARANJA',
//            '541' => 'SUB1 – NEVADA',
//            '542' => 'SUB1 – CABAL',
//            '543' => 'Cielo DISCOVER',
//            '544' => 'Cielo JCB',
//            '545' => 'Cielo AURA',
//            '546' => 'Cartão Presente Alelo',
//            '547' => 'Cielo Pagamento por Celular – Modalidade Pós Pago',
//            '548' => 'Redecard Hipercard',
//            '549' => 'Cielo Pagamento por Celular – Modalidade Pré Pago',
//            '550' => 'CredSystem',
//            '551' => 'Boleto Caixa - SIGCB',
//            '552' => 'Cielo Mastercard Débito',
//            '565' => 'CREDZ',
////            '568' => 'Boleto Bradesco - SPS',
//            '583' => 'Boleto Registrado Banco do Brasil',
//            '585' => 'Boleto Registrado Bradesco',
//            '589' => 'Boleto Registrado Itaú Shopline',//TODO: Existe um limite de 8 digitos para o campo MerchantOrderId
//            '593' => 'Boleto Registrado Santander',
//            '594' => 'Boleto Caixa Econômica Federal Registrado',
//            '608' => 'Redecard Webservice VISA/MASTERCARD - eRede2',
//            '609' => 'Cielo VISA/MASTERCARD - eRede2',
////            '995' => 'Simulado Captura Automática USD',
////            '996' => 'Simulado Captura Automática EUR',
////            '997' => 'Simulado',
////            '998' => 'Simulado USD',
////            '999' => 'Simulado EUR',
////            '1002' => 'Boleto Simulado',
//            'Simulado' => 'Simulado',
//
//        );
//
//        return $return;
//    }

//    public function getPaymentMethodTypeLabel($id)
//    {
//        $types = $this->getPaymentMethods();
//
//        return (isset($types[$id])) ? $types[$id] : false;
//    }

    public function getIntegrations()
    {
        $_hlp = Mage::helper('webjump_braspag_pagador');

        $return = array(
            self::INTEGRATION_TRANSACTION => $_hlp->__('Transaction'),
//            self::INTEGRATION_POST => $_hlp->__('Post'),
        );

        return $return;
    }

//    public function getIntegrationPostMethods()
//    {
//        $_hlp = Mage::helper('webjump_braspag_pagador');
//
//        $return = array(
//            self::INTEGRATION_POST_METHOD_INDEX => $_hlp->__('Index - Customer chooses payment in Braspag'),
//            self::INTEGRATION_POST_METHOD_PASSTHRU => $_hlp->__('Passthru - Customer chooses payment in store'),
//        );
//
//        return $return;
//    }

    public function getPaymentPlans()
    {
        $_hlp = Mage::helper('webjump_braspag_pagador');

        $return = array(
            self::PAYMENT_PLAN_CASH => $_hlp->__('Only Cash Payment'),
            self::PAYMENT_PLAN_ESTABLISHMENT => $_hlp->__('Installments Through Establishment'),
            self::PAYMENT_PLAN_ISSUER => $_hlp->__('Installments Through Card Issuer'),
        );

        return $return;
    }

    public function getConfig()
    {
        $storeId = Mage::app()->getStore()->getId();
        $sandboxFlag = Mage::getStoreConfig('webjump_braspag_pagador/general/sandbox_flag', $storeId);

        if ($sandboxFlag) {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/sandbox', $storeId);
        } else {
            $wsConfig = Mage::getStoreConfig('webjump_braspag_pagador/transaction/config/production', $storeId);
        }

        return $wsConfig;
    }

    public function getMerchantId($storeId = null)
    {
        $merchantId = Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_id', $storeId);
        if (empty($merchantId)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid merchant id in production environment. Please check configuration.'));
        }

        return $merchantId;
    }

    public function getMerchantKey($storeId = null)
    {
        $merchantKey = Mage::getStoreConfig('webjump_braspag_pagador/general/merchant_key', $storeId);
        if (empty($merchantKey)) {
            throw new Exception(Mage::helper('webjump_braspag_pagador')
                ->__('Invalid merchant key in production environment. Please check configuration.'));
        }

        return $merchantKey;
    }

    public function generateGuid($incrementId)
    {
        $incrementId = preg_replace('/[^0-9]/', '0', $incrementId);
        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . str_pad($incrementId, 12, '0', STR_PAD_LEFT);

        return $guid;
    }

    public function getDcReturnUrl($storeId = null)
    {
        $storeId = Mage::app()->getStore()->getId();
        return Mage::getStoreConfig('payment/webjump_braspag_dc/return_url', $storeId);
    }
}