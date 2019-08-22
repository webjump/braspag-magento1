<?php

/**
 * Pagador Unit Test
 *
 * @category  Unit_Test
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_PagadorTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->pagador = Mage::getSingleton('webjump_braspag_pagador/pagador');
    }

    public function testMagentoPagadorAuthorizeOneValidCreditCard()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '100.00',
                'cc_justclick' => 'on',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_CC', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_authorizationCode']));
        $this->tester->assertEquals('4', $transaction['payment_0_returnCode']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_0_returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_proofOfSale']));
        $this->tester->assertEquals('1', $transaction['payment_0_status']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_creditCardToken']));
        $this->tester->assertEquals('', $transaction['payment_0_serviceTaxAmount']);
        $this->tester->assertEquals('0000************0001', $transaction['payment_0_maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('10000', $transaction['payment_0_amount']);
        $this->tester->assertEquals('997', $transaction['payment_0_paymentMethod']);
    }

    public function testMagentoPagadorAuthorizeTwoValidsCreditCards()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '000000000000000000011',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '100.00',
                'cc_justclick' => 'on',
            ),
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'Bill Gates',
                'cc_number' => '000000000000000000012',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2025',
                'cc_cid' => '456',
                'installments' => '1',
                'amount' => '200.00',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_CC', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_authorizationCode']));
        $this->tester->assertEquals('4', $transaction['payment_0_returnCode']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_0_returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_proofOfSale']));
        $this->tester->assertEquals('1', $transaction['payment_0_status']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_creditCardToken']));
        $this->tester->assertEquals('', $transaction['payment_0_serviceTaxAmount']);
        $this->tester->assertEquals('0000*************0011', $transaction['payment_0_maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('10000', $transaction['payment_0_amount']);
        $this->tester->assertEquals('997', $transaction['payment_0_paymentMethod']);
        $this->tester->assertEquals('TRANSACTION_CC', $transaction['payment_1_integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_1_acquirerTransactionId']));
        $this->tester->assertEquals('', $transaction['payment_1_authorizationCode']);
        $this->tester->assertEquals('2', $transaction['payment_1_returnCode']);
        $this->tester->assertEquals('Not Authorized', $transaction['payment_1_returnMessage']);
        $this->tester->assertEquals('', $transaction['payment_1_proofOfSale']);
        $this->tester->assertEquals('2', $transaction['payment_1_status']);
        $this->tester->assertEquals('', $transaction['payment_1_serviceTaxAmount']);
        $this->tester->assertEquals('0000*************0012', $transaction['payment_1_maskedCreditCardNumber']);
        $this->tester->assertEquals(1, 1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_1_braspagTransactionId']));
        $this->tester->assertEquals('20000', $transaction['payment_1_amount']);
        $this->tester->assertEquals('997', $transaction['payment_1_paymentMethod']);
    }

    public function testMagentoPagadorAuthorizeOneSavedJustClickCreditCard()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '00000000000000000001',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '100.00',
                'cc_token' => '22524580-0465-48ce-a3d8-c416efb696d9',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_CC', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_authorizationCode']));
        $this->tester->assertEquals('4', $transaction['payment_0_returnCode']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_0_returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_proofOfSale']));
        $this->tester->assertEquals('1', $transaction['payment_0_status']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_creditCardToken']));
        $this->tester->assertEquals('', $transaction['payment_0_serviceTaxAmount']);
        $this->tester->assertEquals('0000*********0011', $transaction['payment_0_maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('10000', $transaction['payment_0_amount']);
        $this->tester->assertEquals('997', $transaction['payment_0_paymentMethod']);
    }

    public function testMagentoPagadorAuthorizeOneDebitCard()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_dc',
                'dc_type' => '123',
                'dc_owner' => 'John Doe',
                'dc_number' => '0000000000000001',
                'dc_exp_month' => '2',
                'dc_exp_year' => '2020',
                'dc_cid' => '123',
                'amount' => '100.00',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_DC', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals('0', $transaction['payment_0_returnCode']);
        $this->tester->assertEquals('', $transaction['payment_0_returnMessage']);
        $this->tester->assertEquals('4', $transaction['payment_0_status']);
        $this->tester->assertNotEmpty($transaction['payment_0_authenticationUrl']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('10000', $transaction['payment_0_amount']);
        $this->tester->assertEquals('123', $transaction['payment_0_paymentMethod']);
    }

    public function testMagentoPagadorAuthorizeOneBoleto()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_boleto',
                'boleto_type' => '10',
                'amount' => '1.00',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_BOLETO', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals('1', $transaction['payment_0_boletoNumber']);
        $this->tester->assertTrue($this->tester->validateDate($transaction['payment_0_boletoExpirationDate']));
        $this->tester->assertNotEmpty($transaction['payment_0_boletoUrl']);
        $this->tester->assertEquals('Webjump - Plugin Magento', $transaction['payment_0_assignor']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_0_message']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('100', $transaction['payment_0_amount']);
        $this->tester->assertEquals('10', $transaction['payment_0_paymentMethod']);
    }

    public function testMagentoPagadorAuthorizeOneBoletoAndOneCreditCard()
    {
        // prepare the tests
        $this->paymentsFake = array(
            array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => '997',
                'cc_owner' => 'John Doe',
                'cc_number' => '000000000000000000011',
                'cc_exp_month' => '1',
                'cc_exp_year' => '2020',
                'cc_cid' => '123',
                'installments' => '1',
                'amount' => '1.00',
            ),
            array(
                'type' => 'webjump_braspag_boleto',
                'boleto_type' => '10',
                'amount' => '2.00',
            ),
        );

        // apply changes
        $this->response = $this->pagador->authorize(array('order' => $this->tester->getMageFakeOrder(), 'payments' => $this->paymentsFake));

        // test the results
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $this->response->getTransactionId()));
        $this->tester->assertEquals(0, $this->response->getIsTransactionClosed());

        $paymentResponse = $this->response->getPaymentResponse();
        $this->tester->assertTrue($paymentResponse['success']);

        $transaction = $this->response->getTransactionAdditionalInfo();
        $this->tester->assertEquals('1', $transaction['orderId']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['correlationId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_authorizationCode']));

        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['braspagOrderId']));
        $this->tester->assertEquals('TRANSACTION_CC', $transaction['payment_0_integrationType']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_acquirerTransactionId']));
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_authorizationCode']));
        $this->tester->assertEquals('4', $transaction['payment_0_returnCode']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_0_returnMessage']);
        $this->tester->assertEquals(1, preg_match('/[0-9]*/', $transaction['payment_0_proofOfSale']));
        $this->tester->assertEquals('1', $transaction['payment_0_status']);
        $this->tester->assertEquals('', $transaction['payment_0_serviceTaxAmount']);
        $this->tester->assertEquals('0000*************0011', $transaction['payment_0_maskedCreditCardNumber']);
        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('100', $transaction['payment_0_amount']);
        $this->tester->assertEquals('997', $transaction['payment_0_paymentMethod']);

        $this->tester->assertEquals('TRANSACTION_BOLETO', $transaction['payment_1_integrationType']);
        $this->tester->assertEquals('1', $transaction['payment_1_boletoNumber']);
        $this->tester->assertTrue($this->tester->validateDate($transaction['payment_1_boletoExpirationDate']));
        $this->tester->assertNotEmpty($transaction['payment_1_boletoUrl']);
        $this->tester->assertEquals('Webjump - Plugin Magento', $transaction['payment_1_assignor']);
        $this->tester->assertEquals('Operation Successful', $transaction['payment_1_message']);

        $this->tester->assertEquals(1, preg_match('/([a-z0-9]*)-([a-z0-9]*)/', $transaction['payment_0_braspagTransactionId']));
        $this->tester->assertEquals('200', $transaction['payment_1_amount']);
        $this->tester->assertEquals('10', $transaction['payment_1_paymentMethod']);
    }

    public function testVoidOrder()
    {
        
    }
}