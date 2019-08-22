<?php

/**
 * Pagador Transaction Authorize Template Default Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Transaction_Authorize_Template_Default
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Transaction_Authorize_Template_DefaultTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->template = new \Webjump_BrasPag_Pagador_Transaction_Authorize_Template_Default();
    }

    public function testTransactionAuthorizeTemplateDefaultGenerateXmlShouldReceiveValidData()
    {
        // prepare the tests
        $request = $this->tester->getFakeRequest();

        $expected = $this->tester->getValidTemplateXml();

        // apply changes
        $this->template->setRequest($request);
        $return = $this->template->toXml();

        // test the results
        $this->assertEquals($expected, $return);
    }
}