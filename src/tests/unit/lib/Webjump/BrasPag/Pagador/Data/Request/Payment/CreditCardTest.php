<?php

/**
 * Pagador Payment CreditCard Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Request_Payment_CreditCardTest extends \Codeception\Test\Unit
{

    protected function _before()
    {
        $this->creditCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard();
    }

    public function testCreditCardGetValuesShouldReceiveNonNullData()
    {
        $this->tester->assertNull($this->creditCard->getPaymentMethod());
        $this->tester->assertNull($this->creditCard->getAmount());
        $this->tester->assertNull($this->creditCard->getCurrency());
        $this->tester->assertNull($this->creditCard->getCountry());
        $this->tester->assertNull($this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals(1, $this->creditCard->getNumberOfPayments());
        $this->tester->assertNull($this->creditCard->getPaymentPlan());
        $this->tester->assertNull($this->creditCard->getTransactionType());
        $this->tester->assertNull($this->creditCard->getCardHolder());
        $this->tester->assertNull($this->creditCard->getCardNumber());
        $this->tester->assertNull($this->creditCard->getCardSecurityCode());
        $this->tester->assertNull($this->creditCard->getCardExpirationDate());
        $this->tester->assertNull($this->creditCard->getCreditCardToken());
        $this->tester->assertNull($this->creditCard->getJustClickAlias());
        $this->tester->assertFalse($this->creditCard->isSaveCreditCard());
    }

    public function testCreditCardPopulateWithoutCreditCardTokenShouldReceiveValidSimpleData()
    {
        // prepare the tests
        $data = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000.0000.0000.00010',
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/2020',
            'saveCreditCard' => true,
            'justClickAlias' => 'justclick-alias',
        );

        // apply changes
        $this->creditCard->populate($data);

        // test the results
        $this->tester->assertEquals($data['paymentMethod'], $this->creditCard->getPaymentMethod());
        $this->tester->assertEquals($data['amount'], $this->creditCard->getAmount());
        $this->tester->assertEquals($data['currency'], $this->creditCard->getCurrency());
        $this->tester->assertEquals($data['country'], $this->creditCard->getCountry());
        $this->tester->assertEquals($data['serviceTaxAmount'], $this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals($data['numberOfPayments'], $this->creditCard->getNumberOfPayments());
        $this->tester->assertEquals($data['paymentPlan'], $this->creditCard->getPaymentPlan());
        $this->tester->assertEquals($data['transactionType'], $this->creditCard->getTransactionType());
        $this->tester->assertEquals($data['cardHolder'], $this->creditCard->getCardHolder());
        $this->tester->assertEquals($data['cardNumber'], $this->creditCard->getCardNumber());
        $this->tester->assertEquals($data['cardSecurityCode'], $this->creditCard->getCardSecurityCode());
        $this->tester->assertEquals($data['cardExpirationDate'], $this->creditCard->getCardExpirationDate());
        $this->tester->assertNull($this->creditCard->getCreditCardToken());
        $this->tester->assertEquals($data['justClickAlias'], 'justclick-alias');
        $this->tester->assertEquals($data['saveCreditCard'], $this->creditCard->isSaveCreditCard());
    }

    public function testCreditCardPopulateWithoutCreditCardTokenShouldReceiveNullData()
    {
        // apply changes
        $this->creditCard->populate(array());

        // test the results
        $this->tester->assertNull($this->creditCard->getPaymentMethod());
        $this->tester->assertNull($this->creditCard->getAmount());
        $this->tester->assertNull($this->creditCard->getCurrency());
        $this->tester->assertNull($this->creditCard->getCountry());
        $this->tester->assertNull($this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals(1, $this->creditCard->getNumberOfPayments());
        $this->tester->assertNull($this->creditCard->getPaymentPlan());
        $this->tester->assertNull($this->creditCard->getTransactionType());
        $this->tester->assertNull($this->creditCard->getCardHolder());
        $this->tester->assertNull($this->creditCard->getCardNumber());
        $this->tester->assertNull($this->creditCard->getCardSecurityCode());
        $this->tester->assertNull($this->creditCard->getCardExpirationDate());
        $this->tester->assertNull($this->creditCard->getCreditCardToken());
        $this->tester->assertNull($this->creditCard->getJustClickAlias());
        $this->tester->assertFalse($this->creditCard->isSaveCreditCard());
    }

    public function testCreditCardPopulateWithCreditCardTokenShouldReceiveValidCompleteData()
    {
        // prepare the tests
        $data = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000.0000.0000.00010',
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/2020',
            'saveCreditCard' => true,
            'creditCardToken' => '123',
            'justClickAlias' => '0000********00010',
        );

        // apply changes
        $this->creditCard->populate($data);

        // test the results
        $this->tester->assertEquals($data['paymentMethod'], $this->creditCard->getPaymentMethod());
        $this->tester->assertEquals($data['amount'], $this->creditCard->getAmount());
        $this->tester->assertEquals($data['currency'], $this->creditCard->getCurrency());
        $this->tester->assertEquals($data['country'], $this->creditCard->getCountry());
        $this->tester->assertEquals($data['serviceTaxAmount'], $this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals($data['numberOfPayments'], $this->creditCard->getNumberOfPayments());
        $this->tester->assertEquals($data['paymentPlan'], $this->creditCard->getPaymentPlan());
        $this->tester->assertEquals($data['transactionType'], $this->creditCard->getTransactionType());
        $this->tester->assertNull($this->creditCard->getCardHolder());
        $this->tester->assertNull($this->creditCard->getCardNumber());
        $this->tester->assertEquals($data['cardSecurityCode'], $this->creditCard->getCardSecurityCode());
        $this->tester->assertEquals($data['cardExpirationDate'], $this->creditCard->getCardExpirationDate());
        $this->tester->assertEquals($data['creditCardToken'], '123');
        $this->tester->assertEquals($data['justClickAlias'], '0000********00010');
        $this->tester->assertFalse($this->creditCard->isSaveCreditCard());
    }

    public function testCreditCardPopulateWithCreditCardTokenShouldReceiveValidCompleteWithNullData()
    {
        // prepare the tests
        $data = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000.0000.0000.00010',
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/2020',
            'saveCreditCard' => true,
            'creditCardToken' => '123',
            'justClickAlias' => '0000********00010',
        );

        // apply changes
        $this->creditCard->populate($data);
        $this->creditCard->populate(array());

        // test the results
        $this->tester->assertNull($this->creditCard->getPaymentMethod());
        $this->tester->assertNull($this->creditCard->getAmount());
        $this->tester->assertNull($this->creditCard->getCurrency());
        $this->tester->assertNull($this->creditCard->getCountry());
        $this->tester->assertNull($this->creditCard->getServiceTaxAmount());
        $this->tester->assertEquals(1, $this->creditCard->getNumberOfPayments());
        $this->tester->assertNull($this->creditCard->getPaymentPlan());
        $this->tester->assertNull($this->creditCard->getTransactionType());
        $this->tester->assertNull($this->creditCard->getCardHolder());
        $this->tester->assertNull($this->creditCard->getCardNumber());
        $this->tester->assertNull($this->creditCard->getCardSecurityCode());
        $this->tester->assertNull($this->creditCard->getCardExpirationDate());
        $this->tester->assertNull($this->creditCard->getCreditCardToken());
        $this->tester->assertNull($this->creditCard->getJustClickAlias());
        $this->tester->assertFalse($this->creditCard->isSaveCreditCard());
    }

    public function testCreditCardGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000.0000.0000.00010',
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/01/2020',
            'justClickAlias' => 'alias-name',
            'saveCreditCard' => true,
            'creditCardToken' => null,
        );

        // apply changes
        $this->creditCard->populate($data);
        $return = $this->creditCard->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }

    public function testCreditCardGetArrayCopyShouldReceiveValidDataAndJustClick()
    {
        // prepare the tests
        $expected = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => null,
            'cardNumber' => null,
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/01/2020',
            'creditCardToken' => '123',
            'justClickAlias' => 'alias-name',
            'saveCreditCard' => false,
        );
        $data = array(
            'paymentMethod' => '123',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 6,
            'paymentPlan' => 1,
            'transactionType' => 2,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000.0000.0000.00010',
            'cardSecurityCode' => '123456',
            'cardExpirationDate' => '01/01/2020',
            'creditCardToken' => '123',
            'justClickAlias' => 'alias-name',
            'saveCreditCard' => true,
        );

        // apply changes
        $this->creditCard->populate($data);
        $return = $this->creditCard->getArrayCopy();

        // test the results
        $this->assertEquals($expected, $return);
    }
}
