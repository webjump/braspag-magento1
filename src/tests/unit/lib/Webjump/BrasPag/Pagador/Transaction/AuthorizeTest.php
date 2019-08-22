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
class Webjump_BraspagPagador_Transaction_AuthorizeTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->transaction = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize');
    }

    public function testTransactionAuthorizeGetValues()
    {
        // apply changes
        $response = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize\Response');
        $request = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize\Request');

        // test the results
        $this->tester->assertEquals($request, $this->transaction->getRequest());
        $this->tester->assertEquals($response, $this->transaction->getResponse());
    }

    public function testTransactionAuthorizeExecuteShouldReceiveValidData()
    {
        // apply changes
        $request = $this->tester->getFakeRequest();

        $this->transaction->setRequest($request);
        $response = $this->transaction->execute();

        // test the results
        $this->assertTrue($response->isSuccess());
    }

    public function testTransactionAuthorizeExecuteShouldNotReceiveData()
    {
        // apply changes
        $response = $this->transaction->execute();

        // test the results
        $this->assertFalse($response->isSuccess());
    }
}