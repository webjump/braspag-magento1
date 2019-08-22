<?php

/**
 * Pagador Data Payment
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Order
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Response_Payment_ListTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->payments = new \Webjump_BrasPag_Pagador_Data_Response_Payment_List();
    }

    public function testPaymentResponseListAddPaymentsShouldReceiveTwoPaymentMethods()
    {
        // apply changes
        $this->payments->add($this->tester->getFakeCreditCardResponse());
        $this->payments->add($this->tester->getFakeCreditCardResponse());

        // test the results
        $this->tester->assertEquals(2, $this->payments->count());
    }

    public function testPaymentResponseListGetPaymentShouldReceiveAValidPayment()
    {
        // apply changes
        $this->payments->add($this->tester->getFakeCreditCardResponse());
        $this->payments->add($this->tester->getFakeCreditCardResponse());

        $payment = $this->payments->get(0);

        // test the results
        $this->tester->assertEquals($this->tester->getFakeCreditCardResponse(), $payment);
    }

    public function testPaymentResponseListGetPaymentShouldReceiveAInvalidPayment()
    {
        // apply changes
        $payment = $this->payments->get(5);

        // test the results
        $this->tester->assertFalse($payment);
    }

    public function testPaymentResponseListGetIteratorShoudReceiveAValidPayment()
    {
        // apply changes
        $this->payments->add($this->tester->getFakeCreditCardResponse());
        $this->payments->add($this->tester->getFakeCreditCardResponse());

        $iterator = $this->payments->getIterator();

        // test the results
        $this->tester->assertEquals(2, $iterator->count());
    }
}
