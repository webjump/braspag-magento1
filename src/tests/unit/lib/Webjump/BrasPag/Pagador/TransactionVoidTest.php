<?php

/**
 * Pagador Transaction Void
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Void
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_TransactionVoidTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->transaction = new \Webjump_BrasPag_Pagador_Transaction($this->tester->getFakeServiceManageConfig());
        $this->authorize = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize');
    }

    public function testPagadorVoidOneCreditCard()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();

        $this->request = array(
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
        );

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
        $result = $this->transaction->void($this->request);

        // test the results
        $this->assertEquals($expected, $result);
    }

    public function testPagadorVoidTwoCreditCard()
    {
        // prepare the tests
        $this->authorize->setRequest($this->tester->getFakeRequest());
        $response = $this->authorize->execute();

        $this->request = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => array(
                array(
                    'braspagTransactionId' => $response->getPayments()->get(0)->getBraspagTransactionId(),
                    'amount' => 0,
                    'serviceTaxAmount' => 0,
                ),
                array(
                    'braspagTransactionId' => $response->getPayments()->get(1)->getBraspagTransactionId(),
                    'amount' => 0,
                    'serviceTaxAmount' => 0,
                ),
            ),
        );

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
                array(
                    'braspagTransactionId' => $response->getPayments()->get(1)->getBraspagTransactionId(),
                    'amount' => 100,
                    'returnCode' => '0',
                    'returnMessage' => 'Operation Successful',
                    'status' => 0,
                    'acquirerTransactionId' => '1',
                    'authorizationCode' => $response->getPayments()->get(1)->getAuthorizationCode(),
                    'proofOfSale' => null,
                    'serviceTaxAmount' => null,
                ),
            ),
        );

        // apply changes
        $result = $this->transaction->void($this->request);

        // test the results
        $this->assertEquals($expected, $result);
    }
}
