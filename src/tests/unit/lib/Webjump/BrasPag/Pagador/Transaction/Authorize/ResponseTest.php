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
class Webjump_BraspagPagador_Transaction_Authorize_ResponseTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->response = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize\Response');
    }

    public function testTransactionAuthorizeResponseGetValues()
    {
        // test the results
        $this->tester->assertNull($this->response->getCorrelationId());
        $this->tester->assertFalse($this->response->isSuccess());
        $this->tester->assertEquals($this->response->getErrorReport(), $this->tester->getServiceManager()->get('Pagador\Data\Response\ErrorReport'));
        $this->tester->assertEquals($this->response->getOrder(), $this->tester->getServiceManager()->get('Pagador\Data\Response\Order'));
        $this->tester->assertEquals($this->response->getPayments(), $this->tester->getServiceManager()->get('Pagador\Data\Response\Payment\List'));
    }

    public function testTransactionAuthorizeResponsePopulateShoudReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->tester->getFakeErrorReport(),
            'order' => $this->tester->getFakeOrderResponse(),
            'payments' => $this->tester->getFakePaymentsListresponse(),
        );

        // apply changes
        $this->response->populate($data);

        // test the results
        $this->tester->assertEquals($data['correlationId'], $this->response->getCorrelationId());
        $this->tester->assertEquals($data['success'], $this->response->isSuccess());
        $this->tester->assertEquals($data['errorReport'], $this->response->getErrorReport());
        $this->tester->assertEquals($data['order'], $this->response->getOrder());
        $this->tester->assertEquals($data['payments'], $this->response->getPayments());
    }

    public function testTransactionAuthorizeResponsePopulateShoudReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->tester->getFakeErrorReport(),
            'order' => $this->tester->getFakeOrderResponse(),
            'payments' => $this->tester->getFakePaymentsListresponse(),
        );

        // apply changes
        $this->response->populate($data);
        $this->response->populate(array());

        // test the results
        $this->tester->assertNull($this->response->getCorrelationId());
        $this->tester->assertFalse($this->response->isSuccess());
        $this->tester->assertNull($this->response->getErrorReport());
        $this->tester->assertNull($this->response->getOrder());
        $this->tester->assertNull($this->response->getPayments());
    }

    public function testTransactionAuthorizeResponseGetArrayCopyShoudReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->tester->getFakeErrorReport(),
            'order' => $this->tester->getFakeOrderResponse(),
            'payments' => $this->tester->getFakePaymentsListresponse(),
        );

        // apply changes
        $this->response->populate($data);
        $return = $this->response->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }

    public function testSuccessAttributeShouldReceiveTrueBooleanValue()
    {
        // apply changes
        $this->response->setSuccess(true);
        $return = $this->response->isSuccess();

        // test the results
        $this->assertTrue($return);
    }

    public function testSuccessAttributeShouldReceiveFalseBooleanValue()
    {
        // apply changes
        $this->response->setSuccess(false);
        $return = $this->response->isSuccess();

        // test the results
        $this->assertFalse($return);
    }

    public function testSuccessAttributeShouldReceiveTrueStringValue()
    {
        // apply changes
        $this->response->setSuccess('true');
        $return = $this->response->isSuccess();

        // test the results
        $this->assertTrue($return);
    }

    public function testSuccessAttributeShouldReceiveFalseStringValue()
    {
        // apply changes
        $this->response->setSuccess('false');
        $return = $this->response->isSuccess();

        // test the results
        $this->assertFalse($return);
    }
}