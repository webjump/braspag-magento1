<?php

/**
 * Pagador Customer Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Customer
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Request_Payment_CustomerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->customer = new \Webjump_BrasPag_Pagador_Data_Request_Customer();
    }

    public function testCustomerGetValues()
    {
        // test the results
        $this->tester->assertNull($this->customer->getIdentity());
        $this->tester->assertNull($this->customer->getIdentityType());
        $this->tester->assertNull($this->customer->getName());
        $this->tester->assertNull($this->customer->getEmail());
        $this->tester->assertNull($this->customer->getAddress());
    }

    public function testCustomerPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'identity' => 1,
            'identityType' => 'basic',
            'name' => 'John Doe',
            'email' => 'johndoe@johndoe.com.br',
            'address' => $this->tester->getFakeAddress(),
        );

        // apply changes
        $this->customer->populate($data);

        // test the results
        $this->tester->assertEquals($data['identity'], $this->customer->getIdentity());
        $this->tester->assertEquals($data['identityType'], $this->customer->getIdentityType());
        $this->tester->assertEquals($data['name'], $this->customer->getName());
        $this->tester->assertEquals($data['email'], $this->customer->getEmail());
        $this->tester->assertEquals($data['address'], $this->customer->getAddress());
    }

    public function testCustomerPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'identity' => 1,
            'identityType' => 'basic',
            'name' => 'John Doe',
            'email' => 'johndoe@johndoe.com.br',
            'address' => $this->tester->getFakeAddress(),
        );

        // apply changes
        $this->customer->populate($data);
        $this->customer->populate(array());

        // test the results
        $this->tester->assertNull($this->customer->getIdentity());
        $this->tester->assertNull($this->customer->getIdentityType());
        $this->tester->assertNull($this->customer->getName());
        $this->tester->assertNull($this->customer->getEmail());
        $this->tester->assertNull($this->customer->getAddress());
    }

    public function testCustomerGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'identity' => 1,
            'identityType' => 'basic',
            'name' => 'John Doe',
            'email' => 'johndoe@johndoe.com.br',
            'address' => $this->tester->getFakeAddress(),
        );

        // apply changes
        $this->customer->populate($data);
        $return = $this->customer->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }
}
