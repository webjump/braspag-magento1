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
class Webjump_BraspagPagador_Helper_Installments_InstallemtnsTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->helper = new \Webjump_BraspagPagador_Helper_Installments;
    }

    public function testHelperInstallmentsSimpleCaculate()
    {
        // prepare the tests
        $this->amount = 40.9;
        $expected = array(1 => 40.9, 2 => 20.45);

        $data = array(
            'amount' => $this->amount,
            'installments' => 2,
            'installments_min_amount' => 10,
        );

        // apply changes
        $result = $this->helper->caculate($data);

        // test the results
        $this->assertEquals($expected, $result);
        foreach ($result as $key => $value) {
            $this->assertEquals($this->amount, ($key * $value));
        }
    }

    public function testHelperInstallmentsSimpleCaculateRounded()
    {
        // prepare the tests
        $this->amount = 100;
        $data = array(
            'amount' => $this->amount,
            'installments' => 3,
        );

        $expected = array(1 => 100, 2 => 50, 3 => 33.333333333333336);

        // apply changes
        $result = $this->helper->caculate($data);

        // test the results
        $this->assertEquals($expected, $result);
        foreach ($result as $key => $value) {
            $this->assertEquals($this->amount, ($key * $value));
        }
    }

    public function testHelperInstallmentsSimpleCaculateWithMinInstallmentAmount()
    {
        // prepare the tests
        $this->amount = 40.90;
        $data = array(
            'amount' => $this->amount,
            'installments' => 3,
            'installments_min_amount' => 14,
        );

        $expected = array(1 => 40.9, 2 => 20.45);

        // apply changes
        $result = $this->helper->caculate($data);

        // test the results
        $this->assertEquals($expected, $result);
        foreach ($result as $key => $value) {
            $this->assertEquals($this->amount, ($key * $value));
        }
    }

    public function testPOstPassthruGetInstallmentLabel()
    {
        // prepare the tests
        $expected = '2x R$50.00';

        // apply changes
        $result = $this->helper->getInstallmentLabel(2, 100.00);

        // test the results
        $this->assertEquals($expected, $result);
    }
}
