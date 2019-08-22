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
class Webjump_BraspagPagador_Data_Request_Payment_ListTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
    }

    public function testPaymentListAddPaymentsShouldAddTwoPaymentMethods()
    {
        // prepare the tests

        // apply changes
        $this->payments->add($this->tester->getFakeCreditCard());
        $this->payments->add($this->tester->getFakeCreditCard());

        // test the results
        $this->tester->assertEquals(2, $this->payments->count());
    }
}
