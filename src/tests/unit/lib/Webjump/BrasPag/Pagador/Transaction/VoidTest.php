<?php

/**
 * Pagador Transaction Void Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_VoidTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->void = $this->tester->getServiceManager()->get('Pagador\Transaction\Void');
        $this->transaction = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize');
    }

    public function testTransactionVoidOneCreditCardShouldReceiveValidData()
    {
        // prepare the tests
        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array('errors' => array()),
            'transactions' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => $response->getPayments()->get(0)->getAuthorizationCode(),
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->transaction->setRequest($this->tester->getFakeRequest());
        $response = $this->transaction->execute();

        $this->request = $this->getMockBuilder(Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface::class)
            ->setMethods(array('getDataAsArray'))
            ->getMock();

        $this->request->expects($this->once())
            ->method('getDataAsArray')
            ->will(
                array(
                    'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'version' => '1.2',
                    'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                    'transactionDataCollection' => array(
                        array(
                            'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                            'amount' => 0,
                            'serviceTaxAmount' => 0,
                        )
                    )
                )
            );

        $this->void->setRequest($this->request);
        $result = $this->void->execute();

        // test the results
        $this->assertEquals($expected, $result->getDataAsArray());
    }

    public function testTransactionVoidOneCreditCardShouldReceiveDataToCancelAndReturnNotValidToCancelResponse()
    {
        // prepare the tests
        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array('errors' => array()),
            'transactions' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 0,
                    'returnCode' => 'BP001',
                    'returnMessage' => 'Transaction is not available for cancellation after capture day',
                    'status' => 1,
                    'acquirerTransactionId' => null,
                    'authorizationCode' => null,
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->transaction->setRequest($this->tester->getFakeRequest());
        $response = $this->transaction->execute();

        $this->request = $this->getMockBuilder(Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface::class)
            ->setMethods(array('getDataAsArray'))
            ->getMock();

        $this->request->expects($this->once())
            ->method('getDataAsArray')
            ->will(
                array(
                    'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'version' => '1.2',
                    'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                    'transactionDataCollection' => array(
                        array(
                            'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                            'amount' => 0,
                            'serviceTaxAmount' => 0,
                        ),
                    ),

                )
            );

        $this->void->setRequest($this->request);
        $this->void->execute();

        $this->void = $this->tester->getServiceManager()->get('Pagador\Transaction\Void');
        $this->void->setRequest($this->request);
        $result = $this->void->execute();

        // test the results
        $this->assertEquals($expected, $result->getDataAsArray());
    }
}
