<?php

/**
 * Pagador Void
 *
 * @category  Unit_Test
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_VoidTest extends \Codeception\Test\Unit
{
    private $void;
    private $authorize;
    private $payment;
    private $order;
    private $config;

    public function _before()
    {
        $this->void = new \Webjump_BraspagPagador_Model_Pagador_Transaction_Void;
        $this->authorize = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize');
    }

    public function testMagentoModelPagadorVoidGenerateLibPagadorVoidRequestData()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();

        $this->payment = $this->getMockBuilder(Mage_Sales_Model_Order_Payment::class)
            ->setMethods(array('getAdditionalInformation'))
            ->getMock();

        $this->payment->expects($this->once())
            ->method('getAdditionalInformation')
            ->with('payment_response')
            ->will(array(array('braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId())));

        $this->order = $this->getMockBuilder(Mage_Sales_Model_Order::class)
            ->setMethods(array('getId', 'getPayment'))
            ->getMock();

        $this->order->expects($this->once())
            ->method('getId')
            ->will(123);

        $this->order->expects($this->once())
            ->method('getPayment')
            ->will($this->payment);

        $this->config = $this->getMockBuilder(Webjump_BraspagPagador_Model_Config::class)
            ->setMethods(array('generateGuid', 'getWebserviceVersion', 'getMerchantId'))
            ->getMock();

        $this->config->expects($this->once())
            ->method('generateGuid')
            ->with(123)
            ->will('2978D7D5-B6E1-99F4-A93B-000000000123');

        $this->config->expects($this->once())
            ->method('getWebserviceVersion')
            ->will('1.2');

        $this->config->expects($this->once())
            ->method('getMerchantId')
            ->will('7AEA051A-1D01-E411-9406-0026B939D54B');

        $this->tester->replaceModelByMock('webjump_braspag_pagador/config', $this->config);

        $expected = array(
            'requestId' => '2978D7D5-B6E1-99F4-A93B-000000000123',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 0,
                ),
            ),
        );

        // apply changes
        $this->void->setOrder($this->order);
        $result = $this->void->getRequest();

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testMagentoModelPagadorVoidGenerateLibPagadorVoidRequestDataShouldReceiveOrderWithMultiplePayments()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();

        $this->payment = $this->getMockBuilder(Mage_Sales_Model_Order_Payment::class)
            ->setMethods(array('getAdditionalInformation'))
            ->getMock();

        $this->payment->expects($this->once())
            ->method('getAdditionalInformation')
            ->with('payment_response')
            ->will(array(
                array('braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId()),
                array('braspagTransactionId' => $response->getPayments()->get(1)->getBraspagTransactionId()),
            ));

        $this->order = $this->getMockBuilder(Mage_Sales_Model_Order::class)
            ->setMethods(array('getId', 'getPayment'))
            ->getMock();

        $this->order->expects($this->once())
            ->method('getId')
            ->will(123);

        $this->order->expects($this->once())
            ->method('getPayment')
            ->will($this->payment);

        $this->config = $this->getMockBuilder(Webjump_BraspagPagador_Model_Config::class)
            ->setMethods(array('generateGuid', 'getWebserviceVersion', 'getMerchantId'))
            ->getMock();

        $this->config->expects($this->once())
            ->method('generateGuid')
            ->with(123);

        $this->config->expects($this->once())
            ->will('2978D7D5-B6E1-99F4-A93B-000000000123');

        $this->config->expects($this->once())
            ->method('getWebserviceVersion')
            ->will('1.2');

        $this->config->expects($this->once())
            ->method('getMerchantId')
            ->will('7AEA051A-1D01-E411-9406-0026B939D54B');

        $this->tester->replaceModelByMock('webjump_braspag_pagador/config', $this->config);

        $expected = array(
            'requestId' => '2978D7D5-B6E1-99F4-A93B-000000000123',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 0,
                ),
                array(
                    'braspagTransactionId' => $response->getPayments()->get(1)->getBraspagTransactionId(),
                    'amount' => 0,
                ),
            ),
        );

        // apply changes
        $this->void->setOrder($this->order);
        $result = $this->void->getRequest();

        //verify the results
        $this->assertEquals($expected, $result);
    }

    public function testMagentoModelPagadorVoidGenerateLibPagadorVoidRequestDataShouldNotReceiveOrderData()
    {
        //setup the test

        // apply changes
        $result = $this->void->getRequest();

        // test the results
        $this->assertEquals(new \Exception, $result);
    }

    public function testMagentoModelPagadorVoidProcessLibPagadorVoidResponsetDataShoudReceiveValidData()
    {
        // prepare the tests
        $this->order = $this->getMockBuilder(\Mage_Sales_Model_Order::class)
            ->setMethods(array('setState', 'save'))
            ->getMock();

        $this->order
            ->expects($this->once())
            ->method('setState')
            ->with('canceled', true)
            ->will($this->returnSelf());

        $this->order
            ->expects($this->once())
            ->method('save')
            ->will($this->returnSelf());

        $this->response = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array('errors' => array()),
            'transactions' => array(
                array(
                    'braspagTransactionId' => '123456',
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => '123456',
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->void->setOrder($this->order);
        $this->void->processResponse($this->response);

        // test the results
        //No verify the results
    }
}
