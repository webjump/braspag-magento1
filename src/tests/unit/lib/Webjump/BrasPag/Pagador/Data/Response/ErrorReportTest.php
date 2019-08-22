<?php

/**
 * Pagador Data Response ErrorReport Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Data_Response
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Response_ErrorReport extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->report = new \Webjump_BrasPag_Pagador_Data_Response_ErrorReport;
    }

    public function testErrorReportGetValues()
    {
        $this->tester->assertEmpty($this->report->getErrors());
    }

    public function testErrorReportPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            array(
                'ErrorCode' => '100',
                'ErrorMessage' => 'RequestId is a mandatory parameter',
            ),
        );

        // apply changes
        $this->report->setErrors($data);

        // test the results
        $this->tester->assertEquals($data, $this->report->getErrors());
    }

    public function testErrorReportPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            array(
                'ErrorCode' => '100',
                'ErrorMessage' => 'RequestId is a mandatory parameter',
            ),
        );

        // apply changes
        $this->report->setErrors($data);
        $this->report->setErrors(array());

        // test the results
        $this->tester->assertEmpty($this->report->getErrors());
    }
}
