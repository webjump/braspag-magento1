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
class Webjump_BraspagPagador_Model_Method_Post_Passthru_CcTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->method = new \Webjump_BraspagPagador_Model_Method_Post_Passthru_Cc;
        $this->method->setData('info_instance', new \Mage_Sales_Model_Order_Payment);
    }

    public function testPostPassThruCreditCardPaymentMethodGetCode()
    {
        // prepare the tests
        $expected = 'webjump_braspag_post_passthru_cc';

        // apply changes
        $result = $this->method->getCode();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardPaymentMethodGetTypesAvaliables()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo_flag', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo', '997, 06', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = array(997 => 'Simulado', 6 => 'Boleto Bradesco');

        // apply changes
        $result = $this->method->getAvailableTypes();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruGetFormBlockType()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_form_cc';

        // apply changes
        $result = $this->method->getFormBlockType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruGetFormBlockInfo()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_info_cc';

        // apply changes
        $result = $this->method->getInfoBlockType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardFormGetInstallments()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo_flag', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo', '997, 06', 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/installments', 3, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/installments_min_amount', 10, 'default', 0);
        Mage::getConfig()->reinit();

        $this->orderFake = new \Mage_Sales_Model_Order;
        $this->orderFake->setData('grand_total', 100);

        $expected = array(1 => 100.00, 2 => 50.00, 3 => 33.333333333333336);

        // apply changes
        $this->method->getInfoInstance()->setOrder($this->orderFake);
        $result = $this->method->getInstallments();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardFormGetJustClickActive()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/justclick_active', 1, 'default', 0);
        Mage::getConfig()->reinit();

        // apply changes
        $result = $this->method->isJustclickEnabled();

        // test the results
        $this->assertTrue($result);
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
            'installments_plan',
            'installments_min_amount',
            'justclick_active',
            'acquirer_cielo_flag',
            'acquirer_cielo',
            'installments',
            // 'acquirer_redecard_flag',
            // 'acquirer_sitef_flag',
            // 'acquirer_banorte_flag',
            // 'acquirer_pagosonline_flag',
            // 'acquirer_payvision_flag',
            // 'acquirer_sub1_flag',
            // 'allowspecific',
            // 'sort_order',
        );

        // apply changes
        $result = array_keys(Mage::getStoreConfig('payment/webjump_braspag_post_passthru_cc'));

        // test the results
        $this->assertEquals($expected, $result);
    }

    //dando problema com session analisar depois
    // public function testPostPassPathruProcessOrderPaymentMethod()
    // {
    //     // prepare the tests
    //         $this->amount = 100;
    //         $expected = 1234;

    //         $this->orderFake = $this->getMockBuilder('Mage_Sales_Model_Order')
    //         ->shouldReceive('setState')
    //         ->getMock();

    //         $this->orderFake
    //         ->shouldReceive('save')
    //         ->getMock();

    //         $this->orderFake
    //         ->shouldReceive('getId')
    //         ->andReturn($expected)
    //         ->getMock();

    //         $this->paymentFake = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
    //         ->shouldReceive('getOrder')
    //         ->andReturn($this->orderFake)
    //         ->getMock();

    //     // apply changes
    //         $this->method->order($this->paymentFake, $this->amount);
    //         $result = Mage::registry('braspag_current_order')->getId();

    //     // test the results
    //         $this->assertEquals($expected, $result);
    // }

    // public function testPostPassThruGetOrderPlaceRedirectUrl()
    // {
    //     // prepare the tests
    //         $this->amount = 100;
    //         $this->orderFake = $this->getMockBuilder('Mage_Sales_Model_Order')
    //         ->shouldReceive('setState')
    //         ->getMock();

    //         $this->orderFake
    //         ->shouldReceive('save')
    //         ->getMock();

    //         $this->orderFake
    //         ->shouldReceive('getId')
    //         ->andReturn(1234)
    //         ->getMock();

    //         $this->paymentFake = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
    //         ->shouldReceive('getOrder')
    //         ->andReturn($this->orderFake)
    //         ->getMock();

    //         $expected = Mage::getUrl('braspag/post/pay/order_id/1234/');

    //     // apply changes
    //         $this->method->order($this->paymentFake, $this->amount);
    //         $result = $this->method->getOrderPlaceRedirectUrl();

    //     // test the results
    //         $this->assertEquals($expected, $result);
    // }

    public function testPostPassPathruAssignData($value = '')
    {
        // prepare the tests
        $data = array(
            'payment_request' => array(
                array(
                    'cc_type' => 997,
                    'installments' => 2,
                    'cc_justclick' => 'on',
                ),
            ),
        );
        $expected = array(
            'cc_type' => 997,
            'installments' => 2,
            'cc_justclick' => 'on',
        );

        // apply changes
        $this->method->assignData($data);
        $result = $this->method->getInfoInstance()->getAdditionalInformation('payment_request');

        // test the results
        $this->assertEquals($expected, $result);
    }
}
