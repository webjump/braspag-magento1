<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Api
 * @package   Braspag_Auth3ds20_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Auth3ds20_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Auth3ds20_Model_Mpi_Data extends Mage_Core_Model_Abstract
{
    protected $_serviceManager;

    /**
     * @var Braspag_Auth3ds20_Helper_Mpi
     */
    protected $mpiHelper;

    /**
     * @var Braspag_Auth3ds20_Model_Config_Mpi
     */
    protected $mpiConfig;

    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $quote;

    /**
     * Braspag_Auth3ds20_Model_Mpi_Data constructor.
     */
    public function __construct()
    {
        $this->mpiConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi');
        $this->mpiHelper = Mage::helper('braspag_auth3ds20/mpi');
        $this->quote = Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * @return array
     */
    public function getDataContent($paymentType)
    {
        $billingAddress = $this->getBillingAddressData();
        $shippingAddress = $this->getShippingAddressData();
        $cartItems = $this->getCartItemsData();
        $userAccount = $this->getUserAccountData();
        $mdd = $this->getMddData($paymentType);

        return [
            "bpmpi_merchant_url" => Mage::getUrl("/"),
            "bpmpi_device_ipaddress" => $this->quote->getRemoteIp(),
            "addresses" => [
                "billingAddress" => $billingAddress,
                "shippingAddress" => $shippingAddress,
            ],
            "cart" => [
                "items" => $cartItems
            ],
            "user_account" => $userAccount,
            "mdd" => $mdd
        ];

    }

    /**
     * @return array
     */
    protected function getBillingAddressData()
    {
        $customer = $this->quote->getCustomer();
        $billingAddress = $this->quote->getBillingAddress();

        $street = $billingAddress->getStreet();
        $formatedStreet = $this->formatStreet($street);

        return [
            "bpmpi_billto_phonenumber" => $billingAddress->getTelephone(),
            "bpmpi_billto_customerid" => preg_replace('/[^0-9]/', '', $customer->getTaxvat()),
            "bpmpi_billto_email" => $billingAddress->getEmail(),
            "bpmpi_billto_street1" => $formatedStreet[0],
            "bpmpi_billto_street2" => $formatedStreet[1],
            "bpmpi_billto_city" => $billingAddress->getCity(),
            "bpmpi_billto_state" => $billingAddress->getRegion(),
            "bpmpi_billto_zipcode" => $billingAddress->getPostcode(),
            "bpmpi_billto_country" => $billingAddress->getCountry()
        ];
    }

    /**
     * @return array
     */
    protected function getShippingAddressData()
    {
        $shippingAddress = $this->quote->getShippingAddress();

        $street = $shippingAddress->getStreet();
        $formatedStreet = $this->formatStreet($street);

        return [
            "bpmpi_shipto_sameasbillto" => (bool) $shippingAddress->getSameAsBilling(),
            "bpmpi_shipto_addressee" => $this->quote->getCustomer()->getName(),
            "bpmpi_shipto_phonenumber" => $shippingAddress->getTelephone(),
            "bpmpi_shipto_email" => $shippingAddress->getEmail(),
            "bpmpi_shipto_street1" => $formatedStreet[0],
            "bpmpi_shipto_street2" => $formatedStreet[1],
            "bpmpi_shipto_city" => $shippingAddress->getCity(),
            "bpmpi_shipto_state" => $shippingAddress->getRegion(),
            "bpmpi_shipto_zipcode" => $shippingAddress->getPostcode(),
            "bpmpi_shipto_country" => $shippingAddress->getCountry()
        ];
    }

    /**
     * @return array
     */
    protected function getCartItemsData()
    {
        $cartItems = $this->quote->getAllItems();

        $items = [];
        foreach ($cartItems as $key => $cartItem) {

            $items[] = [
                "bpmpi_cart_description" => $cartItem->getDescription(),
                "bpmpi_cart_name" => $cartItem->getName(),
                "bpmpi_cart_sku" => $cartItem->getSku(),
                "bpmpi_cart_quantity" => $cartItem->getQty(),
                "bpmpi_cart_unitprice" => intval($cartItem->getPrice()*100)
            ];
        }

        return $items;
    }

    /**
     * @return array
     */
    protected function getUserAccountData()
    {
        $customer = $this->quote->getCustomer();

        $customerCreatedAt = \DateTime::createFromFormat(\DateTime::ATOM, $customer->getCreatedAt());
        $customerUpdatedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $customer->getUpdatedAt());

        return [
            "bpmpi_useraccount_guest" => $customer->getId() ? false : true,
            "bpmpi_useraccount_createddate" => $customerCreatedAt ? $customerCreatedAt->format('Y-m-d') : '',
            "bpmpi_useraccount_changeddate" => $customerUpdatedAt ? $customerUpdatedAt->format('Y-m-d') : '',
            "bpmpi_useraccount_authenticationmethod" => 2,
            "bpmpi_useraccount_authenticationprotocol" => "HTTP"
        ];
    }

    /**
     * @param null $paymentType
     * @return array
     */
    protected function getMddData($paymentType = null)
    {
        $mdds = new Varien_Object();

        if ($paymentType == "credicard") {
            $mdds->addData($this->mpiConfig->getMpiCreditCardMdds());
        }

        if ($paymentType == "debitcard") {
            $mdds->addData($this->mpiConfig->getMpiDebitCardMdds());
        }

        return[
            "bpmpi_mdd1" => $mdds->getMdd1(),
            "bpmpi_mdd2" => $mdds->getMdd2(),
            "bpmpi_mdd3" => $mdds->getMdd3(),
            "bpmpi_mdd4" => $mdds->getMdd4(),
            "bpmpi_mdd5" => $mdds->getMdd5(),
        ];
    }

    /**
     * @param $street
     * @return array
     */
    public function formatStreet($street)
    {
        $street1 = $street2 = '';

        if (count($street) == 2) {
            $street1 = $street[0];
            $street2 = $street[1];
        }

        if (count($street) == 4) {
            $street1 = $street[0].", ".$street[1];
            $street2 = $street[2]." - ".$street[3];
        }

        return [
            $street1,
            $street2
        ];
    }
}