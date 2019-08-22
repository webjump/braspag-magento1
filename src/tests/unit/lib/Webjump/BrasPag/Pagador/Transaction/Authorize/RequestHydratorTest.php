<?php

class Webjump_BraspagPagador_Transaction_Authorize_RequestHydratorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $serviceManager = $this->tester->getServiceManager();
        $this->request = new \Webjump_BrasPag_Pagador_Transaction_Authorize_Request($serviceManager);
        $this->hydrator = new \Webjump_BrasPag_Pagador_Transaction_Authorize_RequestHydrator($serviceManager);
    }

    public function testTransactionAuthorizeRequestHydrateWithOneCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithOneValidCreditCards(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
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
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithOneSavedJustClickCreditCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithOneValidsavedJustClickCreditCard(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => null,
                    'cardNumber' => null,
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'creditCardToken' => '123',
                    'saveCreditCard' => false,
                ),
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithTwoCreditCards()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithTwoValidCreditCards(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
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
                    'amount' => 300,
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
                )
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithOneDebitCard()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithOneValidDebitCard(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_dc',
                    'paymentMethod' => '997',
                    'amount' => 300,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '0000000000000001',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '02/2020',
                ),
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithBoleto()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsLisWithBoletoValid(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_boleto',
                    'boletoNumber' => '123',
                    'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
                    'boletoExpirationDate' => '01/01/2020',
                ),
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithOneCreditCardAndBoleto()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithOneValidCreditCardAndBoleto(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
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
                    'boletoNumber' => '123',
                    'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
                    'boletoExpirationDate' => '01/01/2020',
                ),
            ),
            'customer' =>  array(
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
                    'country' => 'Brasil'
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionAuthorizeRequestHydrateWithCustomerWithoutAddress()
    {
        // prepare the tests
        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' => $this->tester->getFakeOrderWithoutBraspagOrderId(),
            'payments' => $this->tester->getFakePaymentsListWithOneValidCreditCards(),
            'customer' => $this->tester->getFakeCustomerWithoutAddress(),
        );

        $this->requestData = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'order' =>  array(
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'type' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
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
            ),
            'customer' =>  array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
            ),
        );

        // apply changes
        $this->hydrator->hydrate($this->requestData, $this->request);
        $result = $this->request->getArrayCopy();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }
}