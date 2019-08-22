<?php
/**
 * Pagador Data Customer
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Request_Customer extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Request_CustomerInterface
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

    public function setAddress(Webjump_BrasPag_Pagador_Data_Request_AddressInterface $address = null)
    {
        $this->address = $address;

        return $this;
    }

    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(Webjump_BrasPag_Pagador_Data_Request_AddressInterface $deliveryAddress = null)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }
}
