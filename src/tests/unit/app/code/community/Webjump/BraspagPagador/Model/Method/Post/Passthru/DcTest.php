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
class Webjump_BraspagPagador_Model_Method_Post_Passthru_DcTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->method = new \Webjump_BraspagPagador_Model_Method_Post_Passthru_Dc;
        $this->method->setData('info_instance', new \Mage_Sales_Model_Order_Payment);
    }

    public function testPostPassThruDebiCardPaymentMethodGetCode()
    {
        // prepare the tests
        $expected = 'webjump_braspag_post_passthru_dc';

        // apply changes
        $result = $this->method->getCode();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruDebitCardPaymentMethodGetFormBlockType()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_form_dc';

        // apply changes
        $result = $this->method->getFormBlockType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruDebitCardPaymentMethodGetFormBlockInfo()
    {
        // prepare the tests
        $expected = 'webjump_braspag_pagador/post_passthru_info_dc';

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
            'payment_button_label',
            'dctypes',
            'allowspecific',
            'sort_order',
        );

        // apply changes
        $result = array_keys(Mage::getStoreConfig('payment/webjump_braspag_post_passthru_dc'));

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruDebitCardPaymentMethodGetTypesAvaliables()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_dc/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_dc/dctypes', '11, 552', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = array(552 => 'Cielo Mastercard Débito', 11 => 'Débito Bradesco (SPS) ');

        // apply changes
        $result = $this->method->getAvailableTypes();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassPathruAssignData($value = '')
    {
        // prepare the tests
        $data = array(
            'payment_request' => array(
                array(
                    'cc_type' => 997,
                ),
            ),
        );
        $expected = array(
            'cc_type' => 997,
        );

        // apply changes
        $this->method->assignData($data);
        $result = $this->method->getInfoInstance()->getAdditionalInformation('payment_request');

        // test the results
        $this->assertEquals($expected, $result);
    }
}
