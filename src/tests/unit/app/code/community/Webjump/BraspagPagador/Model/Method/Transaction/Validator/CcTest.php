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
class Webjump_BraspagPagador_Model_Method_Transaction_Validator_CcTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->validator = new \Webjump_BraspagPagador_Model_Method_Transaction_Validator_Cc;

        $this->method_transaction_cc = Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc');

        // prepare the tests

        $this->expected = array(
            'cc_type' => '997',
            'cc_type_label' => 'Simulado',
            'cc_owner' => 'john Doe' ,
            'cc_number' => '0000000000001',
            'cc_number_masked' => '0000********1',
            'cc_exp_month' => '1',
            'cc_exp_year' => '2020',
            'cc_cid' => '123',
            'amount' => 10,
            'installments' => 1,
            'installments_label' => '1x $10.00',
            'cc_justclick' => 'on'
        );
    }

    public function testMethodTransactionValidatorCcValidationShouldReturnTrue()
    {
        // apply changes
        $return = $this->validator->validate($this->expected);

        // test the results
        $this->assertEquals($this->expected, $return);
    }

    public function testMethodTransactionValidatorCcValidationWithMoreFieldsShouldReturnTrue()
    {
        // prepare the tests
        $datawithMoreFields = array(
            'cc_type' => '997',
            'cc_owner' => 'john Doe' ,
            'cc_number' => '0000000000001',
            'cc_exp_month' => '1',
            'cc_exp_year' => '2020',
            'cc_cid' => '123',
            'amount' => '10',
            'installments' => 1,
            'cc_justclick' => 'on',
            'cc_extra_field1' => true,
            'cc_extra_field2' => true,
        );

        // apply changes
        $return = $this->validator->validate($datawithMoreFields);

        // test the results
        $this->assertEquals($this->expected, $return);
    }

    public function testMethodTransactionValidatorCcValidationWithValidTypeShouldReturnInvalidMethodException()
    {
        // prepare the tests
        $datawithInvalidType = array(
            'cc_type' => '1000',
            'cc_owner' => 'john Doe' ,
            'cc_number' => '0000000000001',
            'cc_exp_month' => '1',
            'cc_exp_year' => '2020',
            'cc_cid' => '123',
            'amount' => '10',
            'installments' => 1,
            'cc_justclick' => 'on',
        );

        // apply changes
        $this->expectException(\Mage_Core_Exception::class);

        // test the results
        $this->validator->validate($datawithInvalidType);
    }

    public function testMethodTransactionValidatorCcValidationWithValidInstallmentsShouldReturnInvalidMethodException()
    {
        // prepare the tests
        $datawithInvalidInstallments = array(
            'cc_type' => '997',
            'cc_owner' => 'john Doe' ,
            'cc_number' => '0000000000001',
            'cc_exp_month' => '1',
            'cc_exp_year' => '2020',
            'cc_cid' => '123',
            'amount' => '10',
            'installments' => 10,
            'cc_justclick' => 'on',
        );

        // apply changes
        $this->expectException(\Mage_Core_Exception::class);

        // test the results
        $this->validator->validate($datawithInvalidInstallments);

    }

    public function testMethodTransactionValidatorCcValidationWithoutAmountAttributeShouldReturnTrue()
    {
        // prepare the tests
        $totalForDataWithoutAmountAttribute = 10;
        $datawithoutAmountAttribute = array(
            'cc_type' => '997',
            'cc_owner' => 'john Doe' ,
            'cc_number' => '0000000000001',
            'cc_exp_month' => '1',
            'cc_exp_year' => '2020',
            'cc_cid' => '123',
            'installments' => 1,
            'cc_justclick' => 'on',
        );

        // apply changes
        $return = $this->validator->validate($datawithoutAmountAttribute, $totalForDataWithoutAmountAttribute);

        // apply changes
        $this->assertEquals($this->expected, $return);
    }
}