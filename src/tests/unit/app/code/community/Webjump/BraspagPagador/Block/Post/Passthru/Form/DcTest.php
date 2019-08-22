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
class Webjump_BraspagPagador_Block_Post_Passthru_Form_DcTest extends \Codeception\Test\Unit
{
//

    public function _before()
    {
        $this->block = new \Webjump_BraspagPagador_Block_Post_Passthru_Form_Dc;
        $this->block->setMethod(Mage::getSingleton('webjump_braspag_pagador/method_post_passthru_dc'));
    }

    public function testPostPassThruDebitCardFormGetTemplate()
    {
        // prepare the tests
        $expected = 'webjump/braspag_pagador/payment/post/passthru/form/dc.phtml';

        // apply changes
        $result = $this->block->getTemplate();

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
        $result = $this->block->getAvailableTypes();

        // test the results
        $this->assertEquals($expected, $result);
    }
}
