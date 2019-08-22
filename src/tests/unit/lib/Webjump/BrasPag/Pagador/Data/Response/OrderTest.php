<?php

/**
 * Pagador Data Response Order Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Data_Response_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Response_Order extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->order = new \Webjump_BrasPag_Pagador_Data_Response_Order();
    }

    public function testOrderGetValues()
    {
        $this->tester->assertEmpty($this->order->getOrderId());
        $this->tester->assertEmpty($this->order->getBraspagOrderId());
    }

    public function testOrderPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'orderId' => '123456',
            'braspagOrderId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
        );

        // apply changes
        $this->order->populate($data);

        // test the results
        $this->tester->assertEquals($data['orderId'], $this->order->getOrderId());
        $this->tester->assertEquals($data['braspagOrderId'], $this->order->getBraspagOrderId());
    }

    public function testOrderPopulateShouldReceiveDataWithUppercase()
    {
        // prepare the tests
        $data = array(
            'OrderId' => '123456',
            'BraspagOrderId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
        );

        // apply changes
        $this->order->populate($data);

        // test the results
        $this->tester->assertEquals($data['OrderId'], $this->order->getOrderId());
        $this->tester->assertEquals($data['BraspagOrderId'], $this->order->getBraspagOrderId());
    }

    public function testOrderPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'orderId' => '123456',
            'braspagOrderId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
        );

        // apply changes
        $this->order->populate($data);
        $this->order->populate(array());

        // test the results
        $this->tester->assertEmpty($this->order->getOrderId());
        $this->tester->assertEmpty($this->order->getBraspagOrderId());
    }

    public function testOrderGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'orderId' => '123456',
            'braspagOrderId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
        );

        // apply changes
        $this->order->populate($data);
        $return = $this->order->getArrayCopy();

        // test the results
        $this->tester->assertEquals($data, $return);
    }
}
