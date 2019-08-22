<?php

/**
 * Pagador Transaction Authorize Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_Authorize_RequestTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $serviceManager = $this->tester->getServiceManager();
        $this->request = new \Webjump_BrasPag_Pagador_Transaction_Authorize_Request($serviceManager);
        $this->hydrator = new \Webjump_BrasPag_Pagador_Transaction_Authorize_RequestHydrator($serviceManager);
    }

    public function testTransactionAuthorizeRequestGetValues()
    {
        // test the results
        $this->tester->assertNull($this->request->getRequestId());
        $this->tester->assertNull($this->request->getVersion());
        $this->tester->assertNull($this->request->getOrder());
        $this->tester->assertNull($this->request->getPayments());
        $this->tester->assertNull($this->request->getCustomer());
    }

    public function testTransactionAuthorizeRequestPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'order' => $this->tester->getFakeOrder(),
            'payments' => $this->tester->getFakePaymentsList(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        // apply changes
        $this->request->populate($data);

        // test the results
        $this->tester->assertEquals($data['requestId'], $this->request->getRequestId());
        $this->tester->assertEquals($data['version'], $this->request->getVersion());
        $this->tester->assertEquals($data['order'], $this->request->getOrder());
        $this->tester->assertEquals($data['payments'], $this->request->getPayments());
        $this->tester->assertEquals($data['customer'], $this->request->getCustomer());
    }

    public function testTransactionAuthorizeRequestPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'order' => $this->tester->getFakeOrder(),
            'payments' => $this->tester->getFakePaymentsList(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        // apply changes
        $this->request->populate($data);
        $this->request->populate(array());

        // test the results
        $this->tester->assertNull($this->request->getRequestId());
        $this->tester->assertNull($this->request->getVersion());
        $this->tester->assertNull($this->request->getOrder());
        $this->tester->assertNull($this->request->getPayments());
        $this->tester->assertNull($this->request->getCustomer());
    }

    public function testTransactionAuthorizeRequestGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'order' => $this->tester->getFakeOrder(),
            'payments' => $this->tester->getFakePaymentsList(),
            'customer' => $this->tester->getFakeCustomer(),
        );

        // apply changes
        $this->request->populate($data);
        $return = $this->request->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }
}