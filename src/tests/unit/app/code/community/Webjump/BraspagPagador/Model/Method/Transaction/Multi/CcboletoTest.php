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
class Webjump_BraspagPagador_Model_Method_Transaction_Multi_CcboletoTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->method = new \Webjump_BraspagPagador_Model_Method_Transaction_Multi_Ccboleto;
        $this->method->setData('info_instance', new \Mage_Payment_Model_Info);
    }

    public function testCcboletoMethodAssignDataShouldReceiveValidValues()
    {
        // prepare the tests

        $expected = array(
            'cc' => array(
                'cc_total' => '100',
                'cc_type' => '500',
                'cc_owner' => 'john Doe' ,
                'cc_number' => '0000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => 1,
                'cc_justclick' => 'on',
            ),
            'boleto' => array(
                'cc_total' => '10',
                'boleto_type' => '',
            ),
        );

        $dataValid = new \Varien_Object(array(
            'method' => 'webjump_braspag_multi_ccboleto',
            'payment_request' => array(
                'cc' => array(
                    'cc_total' => '100',
                    'cc_type' => '500',
                    'cc_owner' => 'john Doe' ,
                    'cc_number' => '0000000000001',
                    'cc_exp_month' => '1',
                    'cc_exp_year' => '2020',
                    'cc_cid' => '123',
                    'installments' => 1,
                    'cc_justclick' => 'on',
                ),
                'boleto' => array(
                    'cc_total' => '10',
                    'boleto_type' => '',
                ),
            )
        ));

        // apply changes
        $this->method->assignData($dataValid);
        $result = $this->method->getInfoInstance()->getPaymentRequest();

        // test the results
        $this->tester->assertEquals($expected, $result);

    }

    public function testCcboletoMethodAssignDataShouldNotReceivePaymentRequest()
    {
        // prepare the tests

        $dataWithoutPaymentRequest = new \Varien_Object(array(
            'method' => 'webjump_braspag_multi_ccboleto',
        ));

        // apply changes
        try {
            $result = $this->method->assignData($dataWithoutPaymentRequest);
        } catch (\Mage_Core_Exception $e) {
            // test the results
            $this->assertEquals(new \Mage_Core_Exception, $e);
        }

        $this->assertEquals(true, false);

    }
}