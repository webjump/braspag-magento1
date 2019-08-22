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
class Webjump_BraspagPagador_Transaction_Void_ResponseTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->response = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Response');
    }

    public function testTransactionVoidResponseGetValues()
    {
        // test the results
        $this->tester->assertNull($this->response->getCorrelationId());
        $this->tester->assertFalse($this->response->isSuccess());
        $this->tester->assertEquals($this->response->getErrorReport(), $this->tester->getServiceManager()->get('Pagador\Data\Response\ErrorReport'));
        $this->tester->assertEquals($this->response->getTransactions(), $this->tester->getServiceManager()->get('Pagador\Data\Response\Transaction\List'));
    }

    public function testTransactionVoidResponsePopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_ErrorReportInterface'),
            'transactions' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_Transaction_ListInterface'),
        );

        // apply changes
        $this->response->populate($data);

        // test the results
        $this->tester->assertEquals($data['correlationId'], $this->response->getCorrelationId());
        $this->tester->assertEquals($data['success'], $this->response->isSuccess());
        $this->tester->assertEquals($data['errorReport'], $this->response->getErrorReport());
        $this->tester->assertEquals($data['transactions'], $this->response->getTransactions());
    }

    public function testTransactionVoidResponsePopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_ErrorReportInterface'),
            'transactions' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_Transaction_ListInterface'),
        );

        // apply changes
        $this->response->populate($data);
        $this->response->populate(array());

        // test the results
        $this->tester->assertNull($this->response->getCorrelationId());
        $this->tester->assertFalse($this->response->isSuccess());
        $this->tester->assertNull($this->response->getErrorReport());
        $this->tester->assertNull($this->response->getTransactions());
    }

    public function testTransactionVoidResponseGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_ErrorReportInterface'),
            'transactions' => $this->getMockBuilder('Webjump_BrasPag_Pagador_Data_Response_Transaction_ListInterface'),
        );

        // apply changes
        $this->response->populate($data);
        $return = $this->response->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }
}
