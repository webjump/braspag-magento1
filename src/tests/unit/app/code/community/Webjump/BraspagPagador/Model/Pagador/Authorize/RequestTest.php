<?php

/**
 * Pagador Unit Test
 *
 * @category  Unit_Test
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Authorize_RequestTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->request = new \Webjump_BraspagPagador_Model_Pagador_Authorize_Request;
    }

    public function testPagadorTransactionAuthorizeRequestImportOneCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'numberOfPayments' => '1',
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'saveCreditCard' => false,
                    'creditCardToken' => null,
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000011',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '1.00',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }

    public function testPagadorTransactionAuthorizeRequestImportTwoCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'numberOfPayments' => '1',
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'saveCreditCard' => false,
                    'creditCardToken' => null,
                ),
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => '200',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'numberOfPayments' => '1',
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'Bill Gates',
                    'cardNumber' => '00000000000000000012',
                    'cardSecurityCode' => '456',
                    'cardExpirationDate' => '01/2025',
                    'saveCreditCard' => false,
                    'creditCardToken' => null,
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000011',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '1.00',
            ),
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'Bill Gates',
                'cc_number' => '00000000000000000012',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2025',
                'cc_cid' => '456',
                'installments' => '1',
                'amount' => '2.00',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }

    public function testPagadorTransactionAuthorizeRequestImportOneSavedJustClickCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'numberOfPayments' => '1',
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000000001',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'creditCardToken' => '22524580-0465-48ce-a3d8-c416efb696d9',
                    'saveCreditCard' => false,
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '1.00',
                'cc_token' => '22524580-0465-48ce-a3d8-c416efb696d9',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }

    public function testPagadorTransactionAuthorizeRequestImportOneDebitCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_dc',
                    'paymentMethod' => '10',
                    'amount' => 100,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '0000000000000001',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '02/2020',
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_dc',
                'dc_type' => '10',
                'dc_owner' => 'John Doe',
                'dc_number' => '0000000000000001',
                'dc_exp_month' => '2',
                'dc_exp_year' => '2020',
                'dc_cid' => '123',
                'amount' => '1.00',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }

    public function testPagadorTransactionAuthorizeRequestImportOneBoleto()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_boleto',
                    'paymentMethod' => '10',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'boletoNumber' => 1,
                    'boletoInstructions' => "Sr. Caixa, não conceder desconto Não receber após o vencimento",
                    'boletoExpirationDate' => $this->tester->getTomorrowDate(),
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_boleto',
                'boleto_type' => '10',
                'amount' => '1.00',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }

    public function testPagadorTransactionAuthorizeRequestImportOneBoletoAndOneCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'numberOfPayments' => '1',
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'saveCreditCard' => false,
                    'creditCardToken' => null,
                ),
                array(
                    'type' => 'webjump_braspag_boleto',
                    'paymentMethod' => '10',
                    'amount' => '100',
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'boletoNumber' => 1,
                    'boletoInstructions' => "Sr. Caixa, não conceder desconto Não receber após o vencimento",
                    'boletoExpirationDate' => $this->tester->getTomorrowDate(),
                ),
            ),
            'customer' => array(
                'identity' => '128.551.323-12',
                'identityType' => 'CPF',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    // 'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000011',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '1.00',
            ),
            array(
                'type' => 'webjump_braspag_boleto',
                'boleto_type' => '10',
                'amount' => '1.00',
            ),
        );

        // apply changes
        $this->response = $this->request->import(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['requestId']));
        $this->tester->assertEquals('1.2', $this->response['version']);
        $this->tester->assertEquals($expected['order'], $this->response['order']);
        $this->tester->assertEquals($expected['payments'], $this->response['payments']);
        $this->tester->assertEquals($expected['customer'], $this->response['customer']);
    }
}
