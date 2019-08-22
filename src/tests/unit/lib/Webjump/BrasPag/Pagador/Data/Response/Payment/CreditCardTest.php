<?php

/**
 * Pagador Data Response Payment CreditCard Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Data_Response_Payment_CreditCard
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class  Webjump_BraspagPagador_Data_Response_Payment_CreditCardTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->creditCard = new \Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard;
    }

    public function testCreditCardResponseGetValues()
    {
        // test the results
        $this->tester->assertEmpty($this->creditCard->getBraspagTransactionId());
        $this->tester->assertEmpty($this->creditCard->getAmount());
        $this->tester->assertEmpty($this->creditCard->getPaymentMethod());
        $this->tester->assertEmpty($this->creditCard->getAcquirerTransactionId());
        $this->tester->assertEmpty($this->creditCard->getAuthorizationCode());
        $this->tester->assertEmpty($this->creditCard->getReturnCode());
        $this->tester->assertEmpty($this->creditCard->getReturnMessage());
        $this->tester->assertEmpty($this->creditCard->getProofOfSale());
        $this->tester->assertEmpty($this->creditCard->getStatus());
        $this->tester->assertEmpty($this->creditCard->getCreditCardToken());
        $this->tester->assertEmpty($this->creditCard->getServiceTaxAmount());
        $this->tester->assertEmpty($this->creditCard->getMaskedCreditCardNumber());
    }

    public function testCreditCardResponsePopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'amount' => 100,
            'paymentMethod' => 0,
            'acquirerTransactionId' => '123',
            'authorizationCode' => '123',
            'returnCode' => '123',
            'returnMessage' => '123',
            'proofOfSale' => '123',
            'status' => 0,
            'creditCardToken' => '123456',
            'serviceTaxAmount' => 80,
            'maskedCreditCardNumber' => '0000********0001',
        );

        // apply changes
        $this->creditCard->populate($data);

        // test the results
        $this->tester->assertEquals($data['braspagTransactionId'], $this->creditCard->getBraspagTransactionId());
        $this->tester->assertEquals($data['amount'], $this->creditCard->getAmount());
        $this->tester->assertEquals($data['paymentMethod'], $this->creditCard->getPaymentMethod());
        $this->tester->assertEquals($data['acquirerTransactionId'], $this->creditCard->getAcquirerTransactionId());
        $this->tester->assertEquals($data['authorizationCode'], $this->creditCard->getAuthorizationCode());
        $this->tester->assertEquals($data['returnCode'], $this->creditCard->getReturnCode());
        $this->tester->assertEquals($data['returnMessage'], $this->creditCard->getReturnMessage());
        $this->tester->assertEquals($data['proofOfSale'], $this->creditCard->getProofOfSale());
        $this->tester->assertEquals($data['status'], $this->creditCard->getStatus());
        $this->tester->assertEquals($data['creditCardToken'], $this->creditCard->getCreditCardToken());
        $this->tester->assertEquals($data['serviceTaxAmount'], $this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals($data['maskedCreditCardNumber'], $this->creditCard->getMaskedCreditCardNumber());
    }

    public function testCreditCardResponsePopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'amount' => 100,
            'paymentMethod' => 0,
            'acquirerTransactionId' => '123',
            'authorizationCode' => '123',
            'returnCode' => '123',
            'returnMessage' => '123',
            'proofOfSale' => '123',
            'status' => 0,
            'creditCardToken' => '123456',
            'serviceTaxAmount' => 80,
            'maskedCreditCardNumber' => '0000********0001',
        );

        // apply changes
        $this->creditCard->populate($data);
        $this->creditCard->populate(array());

        // test the results
        $this->tester->assertEmpty($this->creditCard->getBraspagTransactionId());
        $this->tester->assertEmpty($this->creditCard->getAmount());
        $this->tester->assertEmpty($this->creditCard->getPaymentMethod());
        $this->tester->assertEmpty($this->creditCard->getAcquirerTransactionId());
        $this->tester->assertEmpty($this->creditCard->getAuthorizationCode());
        $this->tester->assertEmpty($this->creditCard->getReturnCode());
        $this->tester->assertEmpty($this->creditCard->getReturnMessage());
        $this->tester->assertEmpty($this->creditCard->getProofOfSale());
        $this->tester->assertEmpty($this->creditCard->getStatus());
        $this->tester->assertEmpty($this->creditCard->getCreditCardToken());
        $this->tester->assertEmpty($this->creditCard->getServiceTaxAmount());
        $this->tester->assertEmpty($this->creditCard->getMaskedCreditCardNumber());

    }

    public function testCreditCardGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'amount' => 100,
            'paymentMethod' => 0,
            'acquirerTransactionId' => '123',
            'authorizationCode' => '123',
            'returnCode' => '123',
            'returnMessage' => '123',
            'proofOfSale' => '123',
            'status' => 0,
            'creditCardToken' => '123456',
            'serviceTaxAmount' => 80,
            'maskedCreditCardNumber' => '0000********0001',
            'integrationType' => 'TRANSACTION_CC',
        );


        // apply changes
        $this->creditCard->populate($data);
        $return = $this->creditCard->getArrayCopy();

        // test the results
        $this->tester->assertEquals($data, $return);
    }
}
