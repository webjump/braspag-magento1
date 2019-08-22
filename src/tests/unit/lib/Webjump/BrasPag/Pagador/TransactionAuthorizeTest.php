<?php

/**
 * Pagador Transaction Authorize
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_TransactionTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->transaction = new \Webjump_BrasPag_Pagador_Transaction($this->tester->getFakeServiceManageConfig());
    }

    public function testPagadorAuthorizeOneCreditCard()
    {
        // prepare the tests
        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
                'braspagOrderId' => '67db310d-79a4-4f71-ad73-2b121bcb8e6f',
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'justClickAlias' => '0000*********0011',
                    'saveCreditCard' => false,
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_CC', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['authorizationCode']));
        $this->tester->assertEquals(4, $this->response['payments'][0]['returnCode']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][0]['returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['proofOfSale']));
        $this->tester->assertEquals(1, $this->response['payments'][0]['status']);
        $this->tester->assertEmpty($this->response['payments'][0]['creditCardToken']);
        $this->tester->assertEmpty($this->response['payments'][0]['serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $this->response['payments'][0]['maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('997', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][0]['amount']);
    }

    public function testPagadorAuthorizeTwoCreditCards()
    {
        // prepare the tests
        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
                'braspagOrderId' => '67db310d-79a4-4f71-ad73-2b121bcb8e6f',
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                ),
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 3,
                    'paymentPlan' => 1,
                    'transactionType' => 1,
                    'cardHolder' => 'Bill Gates',
                    'cardNumber' => '00000000000000011',
                    'cardSecurityCode' => '456',
                    'cardExpirationDate' => '01/2030',
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_CC', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['authorizationCode']));
        $this->tester->assertEquals(4, $this->response['payments'][0]['returnCode']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][0]['returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['proofOfSale']));
        $this->tester->assertEquals(1, $this->response['payments'][0]['status']);
        $this->tester->assertEmpty($this->response['payments'][0]['creditCardToken']);
        $this->tester->assertEmpty($this->response['payments'][0]['serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $this->response['payments'][0]['maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('997', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][0]['amount']);

        $this->tester->assertEquals('TRANSACTION_CC', $this->response['payments'][1]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][1]['acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][1]['authorizationCode']));
        $this->tester->assertEquals(4, $this->response['payments'][1]['returnCode']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][1]['returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][1]['proofOfSale']));
        $this->tester->assertEquals(1, $this->response['payments'][1]['status']);
        $this->tester->assertEmpty($this->response['payments'][1]['creditCardToken']);
        $this->tester->assertEmpty($this->response['payments'][1]['serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $this->response['payments'][1]['maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][1]['braspagTransactionId']));
        $this->tester->assertEquals('997', $this->response['payments'][1]['paymentMethod']);
        $this->tester->assertEquals('100', $this->response['payments'][1]['amount']);
    }

    public function testPagadorAuthorizeOneSavedJustClickCreditCard()
    {
        // prepare the tests
        $this->requestData = array(
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
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'creditCardToken' => '22524580-0465-48ce-a3d8-c416efb696d9',
                    'saveCreditCard' => true,
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_CC', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['authorizationCode']));
        $this->tester->assertEquals(4, $this->response['payments'][0]['returnCode']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][0]['returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['proofOfSale']));
        $this->tester->assertEquals(1, $this->response['payments'][0]['status']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['creditCardToken']));
        $this->tester->assertEmpty($this->response['payments'][0]['serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $this->response['payments'][0]['maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('997', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][0]['amount']);
    }

    public function testPagadorAuthorizeOneDebitCard()
    {
        // prepare the tests
        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
                'braspagOrderId' => '67db310d-79a4-4f71-ad73-2b121bcb8e6f',
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_dc',
                    'paymentMethod' => '123',
                    'amount' => 3000,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000012',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '02/2020',
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_DC', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['acquirerTransactionId']));
        $this->tester->assertEquals(0, $this->response['payments'][0]['returnCode']);
        $this->tester->assertEquals('', $this->response['payments'][0]['returnMessage']);
        $this->tester->assertEquals(4, $this->response['payments'][0]['status']);
        $this->tester->assertNotEmpty($this->response['payments'][0]['authenticationUrl']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('123', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('3000', $this->response['payments'][0]['amount']);
    }

    public function testPagadorAuthorizeOneBoleto()
    {
        // prepare the tests
        $this->requestData = array(
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
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'boletoNumber' => '123',
                    'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
                    'boletoExpirationDate' => '01/01/2020',
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_BOLETO', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals('123', $this->response['payments'][0]['boletoNumber']);
        $this->tester->assertEquals('01/01/2020', $this->response['payments'][0]['boletoExpirationDate']);
        $this->tester->assertNotEmpty($this->response['payments'][0]['boletoUrl']);
        $this->tester->assertEquals(1, preg_match('/([0-9]).{4}/', $this->response['payments'][0]['barCodeNumber']));
        $this->tester->assertEquals('Webjump - Plugin Magento', $this->response['payments'][0]['assignor']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][0]['message']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('10', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][0]['amount']);
    }

    public function testPagadorAuthorizeOneCreditCardAndBoleto()
    {
        // prepare the tests
        $this->requestData = array(
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
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '00000000000000011',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                ),
                array(
                    'type' => 'webjump_braspag_boleto',
                    'paymentMethod' => '10',
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'boletoNumber' => '123',
                    'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
                    'boletoExpirationDate' => '01/01/2020',
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );

        // apply changes
        $this->response = $this->transaction->authorize($this->requestData);

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['correlationId']));
        $this->tester->assertEquals(1, $this->response['success']);
        $this->tester->assertEmpty($this->response['errorReport']['errors']);
        $this->tester->assertEquals(1, $this->response['order']['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['order']['braspagOrderId']));

        $this->tester->assertEquals('TRANSACTION_CC', $this->response['payments'][0]['integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['authorizationCode']));
        $this->tester->assertEquals(4, $this->response['payments'][0]['returnCode']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][0]['returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['proofOfSale']));
        $this->tester->assertEquals(1, $this->response['payments'][0]['status']);
        $this->tester->assertEmpty($this->response['payments'][0]['creditCardToken']);
        $this->tester->assertEmpty($this->response['payments'][0]['serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $this->response['payments'][0]['maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][0]['braspagTransactionId']));
        $this->tester->assertEquals('997', $this->response['payments'][0]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][0]['amount']);

        $this->tester->assertEquals('TRANSACTION_BOLETO', $this->response['payments'][1]['integrationType']);
        $this->tester->assertEquals('123', $this->response['payments'][1]['boletoNumber']);
        $this->tester->assertEquals('01/01/2020', $this->response['payments'][1]['boletoExpirationDate']);
        $this->tester->assertNotEmpty($this->response['payments'][1]['boletoUrl']);
        $this->tester->assertEquals(1, preg_match('/([0-9]).{4}/', $this->response['payments'][1]['barCodeNumber']));
        $this->tester->assertEquals('Webjump - Plugin Magento', $this->response['payments'][1]['assignor']);
        $this->tester->assertEquals('Operation Successful', $this->response['payments'][1]['message']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response['payments'][1]['braspagTransactionId']));
        $this->tester->assertEquals('10', $this->response['payments'][1]['paymentMethod']);
        $this->tester->assertEquals('300', $this->response['payments'][1]['amount']);
    }

    public function testGetTransactionData()
    {
        // prepare the tests
        $expected = array(
            'braspagTransactionId' => '91d56829-721c-4ab8-8173-8a6d9133b3d2',
            'orderId' => '1',
            'acquirerTransactionId' => '0215031717771',
            'paymentMethod' => 997,
            'paymentMethodName' => 'Simulado',
            'errorReportDataCollection' => array(),
            'amount' => 100,
            'authorizationCode' => '516159',
            'numberOfPayments' => 3,
            'currency' => 'BRL',
            'country' => 'BRA',
            'transactionType' => 1,
            'status' => 2,
            'receivedDate' => '02/15/2015 03:17:16 PM',
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'creditCardToken' => null,
            'proofOfSale' => '1717771',
            'maskedCardNumber' => '000000*******0011',

        );
        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'braspagTransactionId' => '91d56829-721c-4ab8-8173-8a6d9133b3d2',
        );

        // apply changes
        $this->response = $this->transaction->getTransactionData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }

    public function testGetTransactionDataCreditCard()
    {
        // prepare the tests
        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReportDataCollection' => array(),
            'cardHolder' => 'JOHN DOE',
            'cardNumber' => '000000*******0011',
            'cardExpirationDate' => '01/01/2020 12:00:00 AM',

        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'braspagTransactionId' => 'D1F20FAC-6084-4682-91C8-9385BD819348',
        );

        // apply changes
        $this->response = $this->transaction->getCredicardData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }

    public function testGetTransactionDataCreditCardInvalid()
    {
        // prepare the tests
        $expected = array(
            'success' => false,
            'errorReportDataCollection' => array('ErrorReportDataResponse' => array('ErrorCode' => '199', 'ErrorMessage' => 'Undefined error')),
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'braspagTransactionId' => 'dda4649f-d6c1-46f8-9ae5-dae8eb3490b0',
        );

        // apply changes
        $this->response = $this->transaction->getCredicardData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }

    public function testGetTransactionDataBoleto()
    {
        // prepare the tests
        $expected = array(
            'documentNumber' => '1',
            'documentDate' => '02/16/2015 12:00:00 AM',
            'customerName' => 'John Doe',
            'boletoNumber' => '00001234',
            'barCodeNumber' => '35691.11101 00111.140000 00000.001230 4 81210000000300',
            'boletoExpirationDate' => '01/01/2020 12:00:00 AM',
            'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
            'boletoType' => 'CSR',
            'boletoUrl' => 'https://homologacao.pagador.com.br/pagador/reenvia.asp?Id_Transacao=dda4649f-d6c1-46f8-9ae5-dae8eb3490b0',
            'paidAmount' => 0,
            'bankNumber' => '356-5',
            'agency' => '1111',
            'account' => '0001111',
            'assignor' => 'Webjump - Plugin Magento',
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReportDataCollection' => array(),
            'braspagTransactionId' => 'dda4649f-d6c1-46f8-9ae5-dae8eb3490b0',
            'paymentMethod' => 10,
            'amount' => 300,
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'braspagTransactionId' => 'DDA4649F-D6C1-46F8-9AE5-DAE8EB3490B0',
        );

        // apply changes
        $this->response = $this->transaction->getBoletoData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }

    public function testGetTransactionDataBoletoInvalid()
    {
        // prepare the tests
        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReportDataCollection' => array(),
            'braspagTransactionId' => 'd1f20fac-6084-4682-91c8-9385bd819348',
            'paymentMethod' => 997,
            'amount' => 0,
            'paidAmount' => 0,
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'braspagTransactionId' => 'D1F20FAC-6084-4682-91C8-9385BD819348',
        );

        // apply changes
        $this->response = $this->transaction->getBoletoData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }
}
