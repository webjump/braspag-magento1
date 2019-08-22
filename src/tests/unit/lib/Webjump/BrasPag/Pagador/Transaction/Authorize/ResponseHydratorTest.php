<?php

class Webjump_BraspagPagador_Transaction_Authorize_ResponseHydratorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->hydrator = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize\ResponseHydrator');
        $this->response = $this->tester->getServiceManager()->get('Pagador\Transaction\Authorize\Response');
    }

    public function testHydrateResponseBySoapClientResultValidShouldReceiveValidData()
    {
        // prepare the tests
        $xml = $this->tester->getFakeAuthorizeResponseXml();
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => true,
            'errorReport' => $this->tester->getFakeErrorReportEmpty(),
            'order' => $this->tester->getFakeOrderResponse(),
            'payments' => $this->tester->getFakePaymentsListresponse(),
        );

        // apply changes
        $this->hydrator->hydrate($doc, $this->response);
        $return = $this->response->getArrayCopy();

        // test the results
        $this->assertEquals($expected, $return);
    }

    public function testHydrateResponseBySoapClientResultValidShouldReceiveInvalidData()
    {
        // prepare the tests
        $xml = $this->tester->getFakeAuthorizeResponseXmlWithErrors();
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        $expected = array(
            'correlationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'success' => false,
            'errorReport' => $this->tester->getFakeErrorReport(),
            'order' => $this->tester->getFakeOrderResponseEmpty(),
            'payments' => $this->tester->getFakePaymentsListresponseEmpty(),
        );

        // apply changes
        $this->hydrator->hydrate($doc, $this->response);
        $return = $this->response->getArrayCopy();

        // test the results
        $this->assertEquals($expected, $return);
    }

}