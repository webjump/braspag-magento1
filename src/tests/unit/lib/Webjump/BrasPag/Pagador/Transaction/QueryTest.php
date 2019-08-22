<?php

/**
 * Pagador Transaction Query Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Query
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_QueryTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->query = new \Webjump_BrasPag_Pagador_Transaction_Query($this->tester->getServiceManager());
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
        $this->response = $this->query->getTransactionData($this->requestData);

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
        $this->response = $this->query->getCredicardData($this->requestData);

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
        $this->response = $this->query->getCredicardData($this->requestData);

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
        $this->response = $this->query->getBoletoData($this->requestData);

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
        $this->response = $this->query->getBoletoData($this->requestData);

        // test the results
        $this->assertEquals($expected, $this->response);
    }

}
