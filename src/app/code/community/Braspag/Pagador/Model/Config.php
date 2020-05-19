<?php
class Braspag_Pagador_Model_Config extends Mage_Core_Model_Abstract
{
    const MERCHANT_ID_DEMO_ACCOUNT = 'E42B3806-D36F-4B8D-8B66-227B60FC3D22';
    const MERCHANT_KEY_DEMO_ACCOUNT = '3uZkdtNRS1xbedqv0VtlcDwtEgONbL9KWTrFkvgm';

    const METHOD_CREDITCARD = 'braspag_creditcard';
    const METHOD_JUSTCLICK = 'braspag_justclick';
    const METHOD_DEBITCARD = 'braspag_debitcard';
    const METHOD_BOLETO = 'braspag_boleto';

    const OLD_METHOD_CREDITCARD = 'webjump_braspag_cc';
    const OLD_METHOD_DEBITCARD = 'webjump_braspag_dc';
    const OLD_METHOD_BOLETO = 'webjump_braspag_boleto';

    const INTEGRATION_TRANSACTION = 'transaction';

    const PAYMENT_PLAN_CASH = 0;
    const PAYMENT_PLAN_ESTABLISHMENT = 1;
    const PAYMENT_PLAN_ISSUER = 2;

    const POST_AUTHORIZE = 1;
    const POST_AUTHORIZE_CAPTURE = 2;
    const POST_AUTHORIZE_AUTH = 3;
    const POST_AUTHORIZE_CAPTURE_AUTH = 4;

    /**
     * @return array
     */
    public function getPaymentActions()
    {
        $return = array(
            Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE => Mage::helper('braspag_pagador')
                ->__('Authorize Only'),
            Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE => Mage::helper('braspag_pagador')
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
            'pt-BR' => Mage::helper('braspag_pagador')->__('Portuguese (Brazil)'),
            'en-US' => Mage::helper('braspag_pagador')->__('English (United States)'),
            'es-ES' => Mage::helper('braspag_pagador')->__('Spanish'),
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getAcquirers()
    {
        $return = array(
            'Simulado' => '',
            'Cielo' => 'Cielo',
            'Cielo30' => 'Cielo 30',
            'Getnet' => 'Getnet',
            'Rede' => 'Rede',
            'Rede2' => 'Rede 2',
            'GlobalPayments' => 'Global Payments',
            'Stone' => 'Stone',
            'Safra' => 'Safra',
            'FirstData' => 'First Data',
        );

        return $return;
    }

    /**
     * @return array
     */
    public function getIntegrations()
    {
        $_hlp = Mage::helper('braspag_pagador');

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
        $sandboxFlag = Mage::getSingleton('braspag_core/config_general')
            ->isTestEnvironmentEnabled($storeId);

        if ($sandboxFlag) {
            $config = Mage::getStoreConfig('braspag_pagador/api/config/sandbox', $storeId);
        } else {
            $config = Mage::getStoreConfig('braspag_pagador/api/config/production', $storeId);
        }

        return $config;
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
}