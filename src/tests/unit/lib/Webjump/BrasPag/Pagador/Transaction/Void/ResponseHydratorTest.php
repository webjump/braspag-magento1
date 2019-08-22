<?php

/**
 * Pagador Transaction Void Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Void
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_Void_ResponseHydratorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->hydrator = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Response\Hydrator');
        $this->response = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Response');
    }

    public function testTransactionVoidResponseHydrateWithOneCreditCardShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'CorrelationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'Success' => true,
            'ErrorReportDataCollection' => array(
                array(
                    'ErrorCode' => '100',
                    'ErrorMessage' => 'RequestId is a mandatory parameter',
                ),
            ),
            'TransactionDataCollection' => array(
                'TransactionDataResponse' => array(
                    'BraspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'Amount' => 100,
                    'ReturnCode' => '0',
                    'ReturnMessage' => 'Operation Successful',
                    'Status' => 0,
                    'AcquirerTransactionId' => '1',
                    'AuthorizationCode' => '123456789',
                ),
            ),
        );

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array(
                'errors' => array(
                    array('errorCode' => '100', 'errorMessage' => 'RequestId is a mandatory parameter'),
                ),
            ),
            'transactions' => array(
                array(
                    'braspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => '123456789',
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($data, $this->response);
        $result = $this->response->getDataAsArray();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }

    public function testTransactionVoidResponseHydrateWithTwoCreditCardsShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'CorrelationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'Success' => true,
            'ErrorReportDataCollection' => array(
                array(
                    'ErrorCode' => '100',
                    'ErrorMessage' => 'RequestId is a mandatory parameter',
                ),
            ),
            'TransactionDataCollection' => array(
                'TransactionDataResponse' => array(
                    array(
                        'BraspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                        'Amount' => 100,
                        'ReturnCode' => '0',
                        'ReturnMessage' => 'Operation Successful',
                        'Status' => 0,
                        'AcquirerTransactionId' => '1',
                        'AuthorizationCode' => '123456789',
                    ),
                    array(
                        'BraspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                        'Amount' => 100,
                        'ReturnCode' => '0',
                        'ReturnMessage' => 'Operation Successful',
                        'Status' => 0,
                        'AcquirerTransactionId' => '1',
                        'AuthorizationCode' => '123456789',
                    ),
                ),
            ),
        );

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => array(
                'errors' => array(
                    array('errorCode' => '100', 'errorMessage' => 'RequestId is a mandatory parameter'),
                ),
            ),
            'transactions' => array(
                array(
                    'braspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => '123456789',
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
                array(
                    'braspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => '123456789',
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $this->hydrator->hydrate($data, $this->response);
        $result = $this->response->getDataAsArray();

        // test the results
        $this->tester->assertEquals($expected, $result);
    }
}
