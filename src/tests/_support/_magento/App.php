<?php

namespace Codeception\Module\Magento;

use \Mage;

class App
{
    private static $instance;
    protected $config = array(
        'folder' => '',
    );

    public static function init(array $config = array())
    {
        if (null === static::$instance) {
            static::$instance = new static;
            static::$instance->initMagento($config);
        }

        return static::$instance;
    }

    protected function initMagento(array $config = array())
    {
        $this->config = array_merge($this->config, $config);

        if ($file = $this->getBaseDir('app/Mage.php')) {
            Mage::app(null, null, array('config_model' => 'Codeception\Module\Magento\Config'));
            Mage::getConfig()->reinit();
        };

        return false;
    }

    protected function getBaseDir($file = null)
    {
        $baseDir = getcwd();
        $mageDir = $this->config['folder'];
        $file = "{$baseDir}/{$mageDir}/{$file}";

        if (file_exists($file)) {
            require_once $file;
            return $this;
        }

        return false;
    }

    public function createExampleCustomer()
    {
        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();

        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail('webjump@webjump.com');

        if ($customer->getId()) {
            return false;
        }

        $customer->setWebsiteId($websiteId)
                 ->setStore($store)
                 ->setFirstname('webjump')
                 ->setLastname('webjump')
                 ->setEmail('webjump@webjump.com')
                 ->setPassword('123456789');

        try {
            $customer->save();
        } catch (Exception $e) {}

        $address = Mage::getModel("customer/address");
        $address->setCustomerId($customer->getId())
                ->setFirstname($customer->getFirstname())
                ->setMiddleName($customer->getMiddlename())
                ->setLastname($customer->getLastname())
                ->setCountryId('BR')
                ->setPostcode('01454-011')
                ->setCity('São Paulo')
                ->setRegion('SP')
                ->setTAxVat('354.846.973-60')
                ->setTelephone('2339-6880')
                ->setCompany('Webjump')
                ->setStreet('Rua Professor Artur Ramos, 241 5º andar Jardim Paulistano')
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

        try {
            $address->save();
        } catch (Exception $e) {}
    }

    /**
     * is not allowed to call from outside: private!
     *
     */
    private function __construct()
    {
    }
    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone()
    {
    }
    /**
     * prevent from being unserialized
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
