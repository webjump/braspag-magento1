<?php

/**
 * Pagador Transaction Void Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Void
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_Void_RequestTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->request = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Request');
    }

    public function testTransactionVoidRequestGetValues()
    {
        // test the results
        $this->tester->assertNull($this->request->getRequestId());
        $this->tester->assertNull($this->request->getVersion());
        $this->tester->assertNull($this->request->getMerchantId());
        $this->tester->assertEquals($this->request->getTransactionDataCollection(), $this->tester->getServiceManager()->get('Pagador\Data\Request\Transaction\List'));
    }

    public function testTransactionVoidRequestPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' =>  $this->getMockBuilder(Webjump_BrasPag_Pagador_Data_Request_Transaction_ListInterface::class),
        );

        // apply changes
        $this->request->populate($data);

        // test the results
        $this->tester->assertEquals($data['requestId'], $this->request->getRequestId());
        $this->tester->assertEquals($data['version'], $this->request->getVersion());
        $this->tester->assertEquals($data['merchantId'], $this->request->getMerchantId());
        $this->tester->assertEquals($data['transactionDataCollection'], $this->request->getTransactionDataCollection());
    }

    public function testTransactionVoidRequestPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => $this->getMockBuilder(Webjump_BrasPag_Pagador_Data_Request_Transaction_ListInterface::class),
        );

        // apply changes
        $this->request->populate($data);
        $this->request->populate(array());

        // test the results
        $this->tester->assertNull($this->request->getRequestId());
        $this->tester->assertNull($this->request->getVersion());
        $this->tester->assertNull($this->request->getMerchantId());
        $this->tester->assertNull($this->request->getTransactionDataCollection());
    }

    public function testTransactionVoidRequestGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Request_Transaction_ListInterface'),
        );

        // apply changes
        $this->request->populate($data);
        $return = $this->request->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }

    public function testTransactionVoidRequestGetAsArrayShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => $this->tester->getFakeTransactionsRequestList(),
        );

        $expected = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'merchantId' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'transactionDataCollection' => array(
                array(
                    'braspagTransactionId' => '123456',
                    'amount' => 0,
                    'serviceTaxAmount' => 0,
                ),
            ),
        );

        // apply changes
        $this->request->populate($data);
        $this->return = $this->request->getDataAsArray();

        // test the results
        $this->assertEquals($expected, $this->return);
    }
}
