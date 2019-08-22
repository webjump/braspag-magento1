<?php

class Webjump_BraspagPagador_Service_ServiceManagerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $config = $this->tester->getFakeServiceManageConfig();
        $this->serviceManager = new \Webjump_BrasPag_Pagador_Service_ServiceManager($config);
    }

    public function testServicemanagerGetServiceShouldReceiveValidData()
    {
        // apply changes
        $return = $this->serviceManager->get('Pagador\Transaction\Authorize');

        // test the results
        $this->assertEquals('Webjump_BrasPag_Pagador_Transaction_Authorize', get_class($return));
    }

    public function testServicemanagerGetServiceShouldReceiveInvalidData()
    {
        try {
            $this->serviceManager->get('invalid_class');
        } catch (\Exception $e) {

            // test the results
            $this->assertEquals('Service Invalid: "invalid_class"', $e->getMessage());
            return;
        }

        // apply changes
        $this->fail('Service Manager get invalid class should return fail');
    }

    public function testServicemanagerSetServiceShoudSetClassAsService()
    {

        $class = new \stdClass();
        $this->serviceManager->set('Pagador\Test', $class);
        $return = $this->serviceManager->get('Pagador\Test');

        // test the results
        $this->assertSame($class, $return);
    }

    public function testServicemanagerSetServiceShoudSetFunctionAsService()
    {
        // prepare the tests
        $this->serviceManager->set('Pagador\Test', function () {
            $class = new \stdClass();
            $class->value = 123456;

            return $class;
        });

        // apply changes
        $return = $this->serviceManager->get('Pagador\Test');

        // test the results
        $this->assertEquals(123456, $return->value);
    }

    public function testServicemanagerSetWithMockeryShouldReceiveValidData()
    {
        // prepare the tests
        $authorize = $this->getMockBuilder(Webjump_BrasPag_Pagador_Transaction_Authorize::class)
            ->setMethods(array('authorize'))
            ->getMock();

        $authorize->expects($this->once())
            ->method('authorize')
            ->with('teste')
            ->will(123456);

        $this->serviceManager->set('Pagador\Mockery', $authorize);

        // apply changes
        $return = $this->serviceManager->get('Pagador\Mockery');
        $return->authorize('teste');

        // test the results
        $this->assertEquals(123456, $return->authorize('teste'));
    }
}
