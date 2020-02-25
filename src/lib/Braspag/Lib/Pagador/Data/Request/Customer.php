<?php
/**
 * Pagador Data Customer
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Request_Customer extends Braspag_Lib_Core_Data_Abstract implements Braspag_Lib_Pagador_Data_Request_CustomerInterface
{
    protected $identity;
    protected $identityType;
    protected $name;
    protected $email;
    protected $birthDate;
    protected $address;
    protected $deliveryAddress;

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    public function getIdentityType()
    {
        return $this->identityType;
    }

    public function setIdentityType($identityType)
    {
        $this->identityType = $identityType;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(Braspag_Lib_Pagador_Data_Request_Customer_AddressInterface $address = null)
    {
        $this->address = $address;

        return $this;
    }

    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(Braspag_Lib_Pagador_Data_Request_Customer_AddressInterface $deliveryAddress = null)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }
}
