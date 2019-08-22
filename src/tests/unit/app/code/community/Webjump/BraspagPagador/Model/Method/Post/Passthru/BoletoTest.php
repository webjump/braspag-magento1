<?php

/**
 * TRansaction Unit Test
 *
 * @category  Unit_Test
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Method_Post_Passthru_BoletoTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->method = new \Webjump_BraspagPagador_Model_Method_Post_Passthru_Boleto;
        $this->method->setData('info_instance', new \Mage_Sales_Model_Order_Payment);
    }

    public function testPostPassThruBoletoPaymentMethodGetCode()
    {
        // prepare the tests
        $expected = 'webjump_braspag_post_passthru_boleto';

        // apply changes
        $result = $this->method->getCode();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruBoletoPaymentMethodGetFormBlockType()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_form_boleto';

        // apply changes
        $result = $this->method->getFormBlockType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruBoletoPaymentMethodGetFormBlockInfo()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_info_boleto';

        // apply changes
        $result = $this->method->getInfoBlockType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruGetDefaultConfigAttributes()
    {
        // prepare the tests
        $expected = array(
            'active',
            'model',
            'title',
            'group',
            'payment_action',
            'order_status',
            'boleto_types',
            'payment_button_label',
            'allowspecific',
            'boleto_expiration_date',
            'boleto_instructions',
            'sort_order',
            'boleto_type',
            'payment_instructions',
        );

        // apply changes
        $result = array_keys(Mage::getStoreConfig('payment/webjump_braspag_post_passthru_boleto'));

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPOstPassthruBoletoPaymentmethodgetType()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/boleto_type', '10', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = 10;

        // apply changes
        $result = $this->method->getType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPOstPassthruBoletoPaymentmethodgetPaymentInstructions()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/payment_instructions', 'Pagável em qualquer agência bancária ou bankline até a data do vencimento.', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = 'Pagável em qualquer agência bancária ou bankline até a data do vencimento.';

        // apply changes
        $result = $this->method->getPaymentInstructions();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassPathruAssignData($value = '')
    {
        // prepare the tests
        $data = array(
            'payment_request' => array(
                array(
                    'boleto_type' => 10,
                ),
            ),
        );
        $expected = array(
            'boleto_type' => 10,
        );

        // apply changes
        $this->method->assignData($data);
        $result = $this->method->getInfoInstance()->getAdditionalInformation('payment_request');

        // test the results
        $this->assertEquals($expected, $result);
    }
}
