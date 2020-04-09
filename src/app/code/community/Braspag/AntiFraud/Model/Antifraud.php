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
    	try {
            $result = [];
            foreach ($this->getMerchantDefinedFieldsCollectionData() as $key => $mdd) {
                if (empty($mdd)) {
                    continue;
                }

                $result[] = ['id' => $key, 'value' => $mdd];
            }

            $data->addData($result);

            return $data;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

    /**
     * @return array
     */
    public function getMerchantDefinedFieldsCollectionData()
    {
        $helper = Mage::helper('braspag_antifraud');
        $helperIdentityValidation = Mage::helper('braspag_core/identityValidation');
        $antiFraudMddConfig = Mage::getSingleton('braspag_antifraud/config')->getMddConfig();

        $customerSession = Mage::getSingleton('customer/session');
        $customerSessionData = $customerSession->getCustomer();

        $customerPaymentAdditionalInfo = new Varien_Object();
        if (!empty($this->quote->getPayment()->getAdditionalInformation('payment_request'))) {
            $customerPaymentAdditionalInfo
                ->addData($this->quote->getPayment()->getAdditionalInformation('payment_request'));
        }

        preg_match('#\d{4}$#is', $customerPaymentAdditionalInfo->getCcNumberMasked(), $customerCreditCardSufixResult);
        preg_match('#^\d{4}#is', $customerPaymentAdditionalInfo->getCcNumberMasked(), $customerCreditCardPrefixResult);

        return [
            1 => $customerSession->isLoggedIn() ? $customerSessionData->getEmail() : 'Guest',
            2 => $helper->getDaysQtyUntilNowFromDate($customerSessionData->getCreatedAt()),
            3 => intval($this->quote->getPayment()->getAdditionalInformation('payment_request')['installments']),
            4 => $antiFraudMddConfig->getMddSalesChannel(),
            5 => $this->quote->getCouponCode(),
            21 => ($this->quote->getShippingAddress()) ? $this->quote->getShippingAddress()->getShippingAmount() : null,
            23 => reset($customerCreditCardSufixResult),
            25 => $customerSessionData->getCustomerGender(),
            26 => reset($customerCreditCardPrefixResult),
            34 => $customerSessionData->getConfirmation(),
            36 => floatval($this->quote->getGiftCardsAmount()) > 0 ? "SIM" : 'NÃO',
            41 => $helperIdentityValidation->isCpfOrCnpj($customerSessionData->getTaxvat()),
            42 => $helper->getYearsQtyUntilNowFromDate($customerSessionData->getCustomerDob()),
            46 => $this->quote->getPayment()->getAdditionalInformation('payment_request')['cc_owner'],
            48 => 1,
            52 => $antiFraudMddConfig->getMddMerchantCategory(),
            60 => $helper->getDaysQtyUntilNowFromDate($customerSessionData->getUpdatedAt()),
            83 => $antiFraudMddConfig->getMddMerchantSegment(),
            84 => 'Magento',
            85 => $antiFraudMddConfig->getMddExtraData1(),
            86 => $antiFraudMddConfig->getMddExtraData2(),
            87 => $antiFraudMddConfig->getMddExtraData3(),
            88 => $antiFraudMddConfig->getMddExtraData4(),
            89 => $antiFraudMddConfig->getMddExtraData5(),
        ];
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