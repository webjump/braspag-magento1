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
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Mpi
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_AntiFraud_Model_Antifraud extends Mage_Core_Model_Abstract
{
    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $quote;

    protected $paymentRequest;

    /**
     * Webjump_BraspagPagador_Model_Antifraud constructor.
     */
    public function __construct()
    {
        $this->quote = Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * @param $paymentRequest
     */
    public function setPaymentRequest($paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getFingerPrintId()
    {
    	try{
	        return Mage::getSingleton("core/session")->getEncryptedSessionId();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getBrowserData()
    {
        $data = new Varien_Object();
    	try{
            $data->addData([
                "CookiesAccepted" => Mage::getSingleton('core/cookie')->getLifetime() != '' ? true : false,
                "HostName" => Mage::getBaseUrl(),
                "IpAddress" => Mage::helper('core/http')->getRemoteAddr()
            ]);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getCartData()
    {
        $data = new Varien_Object();
    	try{
            $data->addData([
                "Items" => $this->getCartItemsData()
            ]);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    protected function getCartItemsData()
    {
        try{
            $cartItems = $this->quote->getAllItems();

            $items = [];
            foreach ($cartItems as $key => $cartItem) {

                $items[] =[
                    "Name" => $cartItem->getName(),
                    "Quantity" => $cartItem->getQty(),
                    "Sku" => $cartItem->getSku(),
                    "UnitPrice" => intval($cartItem->getPrice()*100),
                ];
            };

            return $items;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getMerchantDefinedFieldsData()
    {
        $data = new Varien_Object();
    	try{
            $mddCollection = [];

            $result = [];
            foreach ($mddCollection as $mdd) {
                if ($mdd['Value']) {
                    $result[] = $mdd;
                }
            }

            $data->addData($result);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getShippingData()
    {
        $shippingAddress = $this->quote->getShippingAddress();

        $data = new Varien_Object();
    	try{
            $data->addData([
                "Addressee" => $this->quote->getCustomer()->getName(),
                "Phone" => $shippingAddress->getTelephone()
            ]);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getTravelData()
    {
        $data = new Varien_Object();
    	try{

            $data->addData([
                'JourneyType' => '',
                'DepartureTime' => '',
                'Passengers' => $this->getTravelPassengersData(),
            ]);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return array
     */
    public function getTravelPassengersData()
    {
        return [];
    }

    /**
     * @return Braspag_Lib_Core_Service_Manager
     */
    public function getServiceManager()
    {
        return new Braspag_Lib_Core_Service_Manager([]);
    }
}