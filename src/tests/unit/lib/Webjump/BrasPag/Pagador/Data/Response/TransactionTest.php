<?php

/**
 * Pagador Data Response Transaction Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Response_TransactionReportTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->transaction = new \Webjump_BrasPag_Pagador_Data_Response_Transaction;
    }

    public function testTransactionResponseGetValues()
    {
        // test the results
        $this->tester->assertEmpty($this->transaction->getBraspagTransactionId());
        $this->tester->assertEmpty($this->transaction->getAcquirerTransactionId());
        $this->tester->assertEquals(0, $this->transaction->getAmount());
        $this->tester->assertEmpty($this->transaction->getAuthorizationCode());
        $this->tester->assertEmpty($this->transaction->getProofOfSale());
        $this->tester->assertEmpty($this->transaction->getReturnCode());
        $this->tester->assertEmpty($this->transaction->getReturnMessage());
        $this->tester->assertEmpty($this->transaction->getStatus());
        $this->tester->assertEmpty($this->transaction->getServiceTaxAmount());
    }

    public function testTransactionResponsePopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'acquirerTransactionId' => '123456',
            'amount' => 500,
            'authorizationCode' => '123456',
            'proofOfSale' => '123456',
            'returnCode' => '123456',
            'returnMessage' => 'ok',
            'status' => 0,
            'serviceTaxAmount' => 100,
        );

        // apply changes
        $this->transaction->populate($data);

        // test the results
        $this->tester->assertEquals($data['braspagTransactionId'], $this->transaction->getBraspagTransactionId());
        $this->tester->assertEquals($data['acquirerTransactionId'], $this->transaction->getAcquirerTransactionId());
        $this->tester->assertEquals($data['amount'], $this->transaction->getAmount());
        $this->tester->assertEquals($data['authorizationCode'], $this->transaction->getAuthorizationCode());
        $this->tester->assertEquals($data['proofOfSale'], $this->transaction->getProofOfSale());
        $this->tester->assertEquals($data['returnCode'], $this->transaction->getReturnCode());
        $this->tester->assertEquals($data['returnMessage'], $this->transaction->getReturnMessage());
        $this->tester->assertEquals($data['status'], $this->transaction->getStatus());
        $this->tester->assertEquals($data['serviceTaxAmount'], $this->transaction->getServiceTaxAmount());
    }

    public function testTransactionResponsePopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'acquirerTransactionId' => '123456',
            'amount' => 500,
            'authorizationCode' => '123456',
            'proofOfSale' => '123456',
            'returnCode' => '123456',
            'returnMessage' => 'ok',
            'status' => 0,
            'serviceTaxAmount' => 100,
        );

        // apply changes
        $this->transaction->populate($data);
        $this->transaction->populate(array());

        // test the results
        $this->tester->assertEmpty($this->transaction->getBraspagTransactionId());
        $this->tester->assertEmpty($this->transaction->getAcquirerTransactionId());
        $this->tester->assertEquals(0, $this->transaction->getAmount());
        $this->tester->assertEmpty($this->transaction->getAuthorizationCode());
        $this->tester->assertEmpty($this->transaction->getProofOfSale());
        $this->tester->assertEmpty($this->transaction->getReturnCode());
        $this->tester->assertEmpty($this->transaction->getReturnMessage());
        $this->tester->assertEmpty($this->transaction->getStatus());
        $this->tester->assertEmpty($this->transaction->getServiceTaxAmount());
    }

    public function testTransactionResponseGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'acquirerTransactionId' => '123456',
            'amount' => 500,
            'authorizationCode' => '123456',
            'proofOfSale' => '123456',
            'returnCode' => '123456',
            'returnMessage' => 'ok',
            'status' => 0,
            'serviceTaxAmount' => 100,
        );

        // apply changes
        $this->transaction->populate($data);
        $return = $this->transaction->getArrayCopy();

        // test the results
        $this->tester->assertEquals($data, $return);
    }
}
