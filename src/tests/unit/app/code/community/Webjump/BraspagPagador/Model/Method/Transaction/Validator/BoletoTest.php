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
class Webjump_BraspagPagador_Model_Method_Transaction_Validator_BoletoTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->validator = new \Webjump_BraspagPagador_Model_Method_Transaction_Validator_Boleto;
    }

    public function testMethodTransactionValidatorCcValidation()
    {
        // prepare the tests

        $expected = array(
            'amount' => '100',
            'boleto_type' => '1',
        );

        // apply changes
        $return = $this->validator->validate($expected);

        // test the results
        $this->tester->assertEquals($return, $expected);
    }

    public function testMethodTransactionValidatorCcValidationShoulReceiveMoreFields()
    {
        // prepare the tests

        $dataWithMoreFields = array(
            'amount' => '100',
            'boleto_type' => '1',
            'extra_field' => true,
        );

        $expected = array(
            'amount' => '100',
            'boleto_type' => '1',
        );

        // apply changes
        $return = $this->validator->validate($dataWithMoreFields);

        // test the results
        $this->tester->assertEquals($return, $expected);
    }
}