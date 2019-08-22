<?php

/**
 * Pagador Order Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Order
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Request_Payment_OrderTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->order = new \Webjump_BrasPag_Pagador_Data_Request_Order();
    }

    public function testOrderGetValues()
    {
        // test the results
        $this->tester->assertNull($this->order->getMerchantId());
        $this->tester->assertNull($this->order->getOrderId());
        $this->tester->assertNull($this->order->getBraspagOrderId());
    }

    public function testOrderPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'merchantId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'orderId' => 1,
            'braspagOrderId' => '123',
        );

        // apply changes
        $this->order->populate($data);

        // test the results
        $this->tester->assertEquals($data['merchantId'], $this->order->getMerchantId());
        $this->tester->assertEquals($data['orderId'], $this->order->getOrderId());
        $this->tester->assertEquals($data['braspagOrderId'], $this->order->getBraspagOrderId());
    }

    public function testOrderPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'merchantId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'orderId' => 1,
            'braspagOrderId' => '123',
        );

        // apply changes
        $this->order->populate($data);
        $this->order->populate(array());

        // test the results
        $this->tester->assertNull($this->order->getMerchantId());
        $this->tester->assertNull($this->order->getOrderId());
        $this->tester->assertNull($this->order->getBraspagOrderId());
    }

    public function testOrderGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'merchantId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'orderId' => 1,
            'braspagOrderId' => '123',
        );

        // apply changes
        $this->order->populate($data);
        $return = $this->order->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }
}
