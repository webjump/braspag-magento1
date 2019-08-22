<?php

class Webjump_BraspagPagador_Transaction_Void_RequestHydratorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->hydrator = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Request\Hydrator');
        $this->request = $this->tester->getServiceManager()->get('Pagador\Transaction\Void\Request');
    }

    public function testTransactionVoidRequestHydrateWithOneCreditCard()
    {
        // prepare the tests
        $data = array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.2',
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
        $this->hydrator->hydrate($data, $this->request);
        $result = $this->request->getDataAsArray();

        // test the results
        $this->tester->assertEquals($data, $result);
    }
}
