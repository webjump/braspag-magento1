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
class Webjump_BraspagPagador_Block_Post_Passthru_Form_CcTest extends \Codeception\Test\Unit
{
//

    public function _before()
    {
        $this->block = new \Webjump_BraspagPagador_Block_Post_Passthru_Form_Cc;
        $this->block->setMethod(Mage::getSingleton('webjump_braspag_pagador/method_post_passthru_cc'));
    }

    public function testPostPassThruCreditCardFormGetTemplate()
    {
        // prepare the tests
        $expected = 'webjump/braspag_pagador/payment/post/passthru/form/cc.phtml';

        // apply changes
        $result = $this->block->getTemplate();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardFormGetAvaliableTypesAsSelectOptions()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo_flag', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo', '997, 06', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = array(997 => 'Simulado', 6 => 'Boleto Bradesco');

        // apply changes
        $result = $this->block->getAvailableTypes();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardFormGetInstallments()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo_flag', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo', '997, 06', 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/installments_plan', 1, 'default', 0);
        Mage::getConfig()->reinit();

        $this->amount = 100;
        $expected = array(1 => 'R$100.00', 2 => 'R$50.00', 3 => 'R$33.33');

        // apply changes
        $this->orderFake = new \Mage_Sales_Model_Order;
        $this->orderFake->setData('grand_total', 100);
        $this->block->getMethod()->setData('info_instance', new \Mage_Sales_Model_Order_Payment);
        $this->block->getMethod()->getInfoInstance()->setOrder($this->orderFake);
        $result = $this->block->getInstallments();

        // test the results
        $this->assertTrue($this->block->isInstallmentsEnabled());
        $this->assertEquals($expected, $result);
    }

    public function testPostPassThruCreditCardFormGetJustClickActive()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/justclick_active', 1, 'default', 0);
        Mage::getConfig()->reinit();

        // apply changes
        $result = $this->block->isJustclickEnabled();

        // test the results
        $this->assertTrue($result);
    }
}
