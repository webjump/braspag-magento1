<?php

/**
 * Pagador Transaction Refund Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_RefundTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->refund = $this->tester->getServiceManager()->get('Pagador\Transaction\Refund');
        $this->authorize = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize');
        $this->capture = $this->tester->getServiceManager()->get('Pagador\Transaction\Capture');
    }

    public function testTransactionRefundOneCreditCardShouldReceiveValidData()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();
        $braspagId = $response->getPayments()->get(0)->getBraspagTransactionId();

        $this->capture->setRequest($this->tester->getFakeCaptureRequest($braspagId));
        $captureResponse = $this->capture->execute();

        $this->request = $this->getMockBuilder('Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface')
        ->shouldReceive('getDataAsArray')->andReturn(
            array(
                'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                'version' => '1.2',
                'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
                'transactionDataCollection' => array(
                    array(
                        'braspagTransactionId' => $braspagId,
                        'amount' => 0,
                        'serviceTaxAmount' => 0,
                    ),
                ),

            )
        )->getMock();

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array('errors' => array()),
            'transactions' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful - 1.00',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => $captureResponse->getTransactions()->get(0)->getAuthorizationCode(),
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->refund->setRequest($this->request);
        $result = $this->refund->execute();

        // test the results
        $this->assertEquals($expected, $result->getDataAsArray());
    }

    public function testTransactionRefundOneCreditCardShouldReturnsTransactionIsNotAbleToRefund()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();

        $this->request = $this->getMockBuilder('Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface')
            ->shouldReceive('getDataAsArray')->andReturn(
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
            )->getMock();

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array('errors' => array()),
            'transactions' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 0,
                    'returnCode' => '141',
                    'returnMessage' => 'Transaction is not able to refund. Verify the transaction status.',
                    'status' => 2,
                    'acquirerTransactionId' => null,
                    'authorizationCode' => null,
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->refund->setRequest($this->request);
        $this->refund->execute();

        $this->refund = $this->tester->getServiceManager()->get('Pagador\Transaction\Refund');
        $this->refund->setRequest($this->request);
        $result = $this->refund->execute();

        // test the results
        $this->assertEquals($expected, $result->getDataAsArray());
    }
}
