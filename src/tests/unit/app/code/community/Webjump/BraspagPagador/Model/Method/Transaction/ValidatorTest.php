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
class Webjump_BraspagPagador_Model_Method_Transaction_ValidatorTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->validator = new \Webjump_BraspagPagador_Model_Method_Transaction_Validator;
    }

    public function testMethodTransactionValidatorOneCreditCardOnASingleArrayData()
    {
        // prepare the tests
        $expected = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_type_label' => 'Simulado',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_number_masked' => '0000********1',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10.0',
                'installments' => 1,
                'installments_label' => '1x R$10.00',
                'cc_justclick' => 'on',
            )
        );

        $data = array(
            'type' => 'webjump_braspag_cc',
            'cc_type' => '997',
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
        $this->return = $this->validator->validate($data);

        // test the results
        $this->tester->assertEquals($expected, $this->return);
    }

    public function testMethodTransactionValidatorOneCreditCardOnAMultiArrayData()
    {
        // prepare the tests
        $expected = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_type_label' => 'Simulado',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_number_masked' => '0000********1',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10.0',
                'installments' => 1,
                'installments_label' => '1x R$10.00',
                'cc_justclick' => 'on',
            )
        );

        $data = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10',
                'installments' => 1,
                'cc_justclick' => 'on',
            ),
        );

        // apply changes
        $this->return = $this->validator->validate($data);

        // test the results
        $this->tester->assertEquals($expected, $this->return);
    }

    public function testMethodTransactionValidatorTwoCreditCards()
    {
        // prepare the tests
        $expected = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_type_label' => 'Simulado',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_number_masked' => '0000********1',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10.0',
                'installments' => 1,
                'installments_label' => '1x R$10.00',
                'cc_justclick' => 'on',
            ),
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_type_label' => 'Simulado',
                'cc_owner' => 'Bill Gates' ,
                'cc_number' => '0000000000002',
                'cc_number_masked' => '0000********2',
                'cc_exp_month' => '5',
                'cc_exp_year' => '2025',
                'cc_cid' => '123',
                'amount' => '20.0',
                'installments' => 1,
                'installments_label' => '1x R$20.00',
            )
        );

        $data = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10',
                'installments' => 1,
                'cc_justclick' => 'on',
            ),
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'Bill Gates' ,
                'cc_number' => '0000000000002',
                'cc_exp_month' => '5',
                'cc_exp_year' => '2025',
                'cc_cid' => '123',
                'amount' => '20',
                'installments' => 1,
            ),
        );

        // apply changes
        $this->return = $this->validator->validate($data);

        // test the results
        $this->tester->assertEquals($expected, $this->return);
    }

    public function testMethodTransactionValidatorSavedJustClickCreditCard()
    {
        // prepare the tests
        $expected = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_type_label' => 'Simulado',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_number_masked' => '0000********1',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10.0',
                'installments' => 1,
                'installments_label' => '1x R$10.00',
                'cc_token' => '22524580-0465-48ce-a3d8-c416efb696d9',
            ),
        );

        $data = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'amount' => '10',
                'installments' => 1,
                'cc_token' => '22524580-0465-48ce-a3d8-c416efb696d9',
            ),
        );

        // apply changes
        $this->return = $this->validator->validate($data);

        // test the results
        $this->tester->assertEquals($expected, $this->return);
    }
}