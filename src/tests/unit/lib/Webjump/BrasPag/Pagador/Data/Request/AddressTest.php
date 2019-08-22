<?php

/**
 * Pagador Address Unit Test
 *
 * @category  UnitTest
 * @package    Webjump_BraspagPagador_Pagador_Address
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Data_Request_Payment_AddressTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->address = new \Webjump_BrasPag_Pagador_Data_Request_Address();
    }

    public function testAddressGetValues()
    {
        // test the results
        $this->tester->assertNull($this->address->getStreet());
        $this->tester->assertNull($this->address->getNumber());
        $this->tester->assertNull($this->address->getComplement());
        $this->tester->assertNull($this->address->getDistrict());
        $this->tester->assertNull($this->address->getZipCode());
        $this->tester->assertNull($this->address->getCity());
        $this->tester->assertNull($this->address->getState());
        $this->tester->assertNull($this->address->getCountry());
    }

    public function testAddressPopulateShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'street' => 'Rua dos Bobos',
            'number' => 0,
            'complement' => 'apartamento 1',
            'district' => 'centro',
            'zipCode' => '09180001',
            'city' => 'santo andré',
            'state' => 'SP',
            'country' => 'Brasil'
        );

        // apply changes
        $this->address->populate($data);

        // test the results
        $this->tester->assertEquals($data['street'], $this->address->getStreet());
        $this->tester->assertEquals($data['number'], $this->address->getNumber());
        $this->tester->assertEquals($data['complement'], $this->address->getComplement());
        $this->tester->assertEquals($data['district'], $this->address->getDistrict());
        $this->tester->assertEquals($data['zipCode'], $this->address->getZipCode());
        $this->tester->assertEquals($data['city'], $this->address->getCity());
        $this->tester->assertEquals($data['state'], $this->address->getState());
        $this->tester->assertEquals($data['country'], $this->address->getCountry());
    }

    public function testAddressPopulateShouldReceiveEmptyData()
    {
        // prepare the tests
        $data = array(
            'street' => 'Rua dos Bobos',
            'number' => 0,
            'complement' => 'apartamento 1',
            'district' => 'centro',
            'zipCode' => '09180001',
            'city' => 'santo andré',
            'state' => 'SP',
            'country' => 'Brasil'
        );

        // apply changes
        $this->address->populate($data);
        $this->address->populate(array());

        // test the results
        $this->tester->assertNull($this->address->getStreet());
        $this->tester->assertNull($this->address->getNumber());
        $this->tester->assertNull($this->address->getComplement());
        $this->tester->assertNull($this->address->getDistrict());
        $this->tester->assertNull($this->address->getZipCode());
        $this->tester->assertNull($this->address->getCity());
        $this->tester->assertNull($this->address->getState());
        $this->tester->assertNull($this->address->getCountry());
    }

    public function testAddressGetArrayCopyShouldReceiveValidData()
    {
        // prepare the tests
        $data = array(
            'street' => 'Rua dos Bobos',
            'number' => 0,
            'complement' => 'apartamento 1',
            'district' => 'centro',
            'zipCode' => '09180001',
            'city' => 'santo andré',
            'state' => 'SP',
            'country' => 'Brasil'
        );

        // apply changes
        $this->address->populate($data);
        $return = $this->address->getArrayCopy();

        // test the results
        $this->assertEquals($return, $data);
    }
}
