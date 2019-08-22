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
class Webjump_BraspagPagador_Model_Method_Transaction_Validator_Multi_TotalsTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->validator = new \Webjump_BraspagPagador_Model_Method_Transaction_Validator_Multi_Totals;
    }

    public function testMethodTransactionValidatorMultiTotalsShouldReceiveValidData()
    {
        // prepare the tests
        $this->grandAmount = 110.50;

        $expected = array(
            'cc' => array('amount' => '100.50'),
            'boleto' => array('amount' => '10'),
        );

        // apply changes
        $result = $this->validator->validate($expected, $this->grandAmount);

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testMethodTransactionValidatorMultiTotalsShouldReceiveDifferentAmounts()
    {
        // prepare the tests
        $dataWithDifferentsAmounts = array(
            'cc' => array('amount' => '100.00'),
            'boleto' => array('amount' => '10'),
        );

        // apply changes
        $this->expectException(\Mage_Core_Exception::class);

        // test the results
        $this->validator->validate($dataWithDifferentsAmounts, $this->grandAmount);
    }

    public function testMethodTransactionValidatorMultiTotalsShouldReceiveEmptyAmounts()
    {
        // prepare the tests
        $dataWithZerosAmounts = array(
            'cc' => array('amount' => 0),
            'boleto' => array('amount' => '10'),
        );

        // apply changes
        $this->expectException(\Mage_Core_Exception::class);

        // test the results
        $this->validator->validate($dataWithZerosAmounts, $this->grandAmount);
    }

    public function testMethodTransactionValidatorMultiTotalsShouldReceiveNegativeAmounts()
    {
        // prepare the tests
        $dataWithNegativeAmounts = array(
            'cc' => array('amount' => -1),
            'boleto' => array('amount' => '10'),
        );

        // apply changes
        $this->expectException(\Mage_Core_Exception::class);

        // test the results
        $this->validator->validate($dataWithNegativeAmounts, $this->grandAmount);
    }
}