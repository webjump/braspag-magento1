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
class Webjump_BraspagPagador_Block_Post_Passthru_Form_BoletoTest extends \Codeception\Test\Unit
{
//

    public function _before()
    {
        $this->block = new Webjump_BraspagPagador_Block_Post_Passthru_Form_Boleto;
        $this->block->setMethod(Mage::getSingleton('webjump_braspag_pagador/method_post_passthru_boleto'));
    }

    public function testPostPassThruBoletoFormGetTemplate()
    {
        // prepare the tests
        $expected = 'webjump/braspag_pagador/payment/post/passthru/form/boleto.phtml';

        // apply changes
        $result = $this->block->getTemplate();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassthruBoletoPaymentmethodgetType()
    {
        // prepare the tests
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/active', 1, 'default', 0);
        Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/boleto_type', '10', 'default', 0);
        Mage::getConfig()->reinit();

        $expected = 10;

        // apply changes
        $result = $this->block->getType();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPostPassthruBoletoPaymentmethodgetPaymentInstructions()
    {
        // prepare the tests
            Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/active', 1, 'default', 0);
            Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_boleto/payment_instructions', 'Pagável em qualquer agência bancária ou bankline até a data do vencimento.', 'default', 0);
            Mage::getConfig()->reinit();

            $expected = 'Pagável em qualquer agência bancária ou bankline até a data do vencimento.';

        // apply changes
            $result = $this->block->getPaymentInstructions();

        // test the results
            $this->assertEquals($expected, $result);
    }

}
