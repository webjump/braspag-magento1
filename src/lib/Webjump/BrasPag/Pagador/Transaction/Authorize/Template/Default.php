<?php
/**
 * Pagador Transaction Authorize Template Default
 *
 * @category  Template
 * @package   Webjump_BrasPag_Pagador_Transaction_Authorize_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Authorize_Template_Default
{
    protected $request;
    protected $data;

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Webjump_BrasPag_Pagador_Transaction_Authorize_RequestInterface $request
     * @return $this
     */
    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Authorize_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getData()
    {
        try{
            $this->data["Header"] = [];
            $this->data["Body"] = [];

            $this->prepareHeader();
            $this->prepareBody();

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $this->data;
    }

    /**
     * @return mixed
     */
    public function prepareHeader()
    {
        $this->data["Header"] = [
            'MerchantId' => $this->getRequest()->getMerchantId(),
            'MerchantKey' => $this->getRequest()->getMerchantKey(),
            'RequestId' => $this->getRequest()->getRequestId()
        ];

        return $this->data;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function prepareBody()
    {
        $this->getMerchantOrderId();

        $this->data["Body"]["Customer"] = $this->getCustomerData();
        $this->data["Body"]["Payment"] = $this->getPaymentData();

        return $this->data;
    }

    /**
     * @return mixed
     */
    protected function getMerchantOrderId()
    {
        $this->data["Body"]["MerchantOrderId"] = $this->getRequest()->getOrder()->getOrderId();

        return $this->data;
    }

    /**
     * @return array
     */
    protected function getCustomerData()
    {
        $request  = $this->getRequest();

        $customerData = [];

        if ($customer = $request->getCustomer()) {

            $customerData = [
                'Name' => $customer->getName(),
                'Identity' => $customer->getIdentity(),
                'IdentityType' => $customer->getIdentityType(),
                'Email' => $customer->getEmail(),
                'Birthdate' => $customer->getBirthdate(),
                'Address' => [],
                'DeliveryAddress' => [],
            ];


            
            if ($address = $customer->getAddress()) {
                $customerAddress = [
                    "Street" => $address->getStreet(),
                    "Number" => $address->getNumber(),
                    "Complement" => $address->getComplement(),
                    "ZipCode" => $address->getZipCode(),
                    "City" => $address->getCity(),
                    "State" => $address->getState(),
                    "Country" => $address->getCountry(),
                    "District" => $address->getDistrict()
                ];

                $customerData["Address"] = $customerAddress;
            }

            if ($deliveryAddress = $customer->getDeliveryAddress()) {
                
                $customerDeliveryAddress = [
                    "Street" => $deliveryAddress->getStreet(),
                    "Number" => $deliveryAddress->getNumber(),
                    "Complement" => $deliveryAddress->getComplement(),
                    "ZipCode" => $deliveryAddress->getZipCode(),
                    "City" => $deliveryAddress->getCity(),
                    "State" => $deliveryAddress->getState(),
                    "Country" => $deliveryAddress->getCountry(),
                    "District" => $deliveryAddress->getDistrict()
                ];

                $customerData["DeliveryAddress"] = $customerDeliveryAddress;
			}

            if (!$deliveryAddress) {
                unset($customerData["DeliveryAddress"]);
            }
        }

        return $customerData;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPaymentData()
    {
        if (!$payment = $this->request->getPayment()->get()) {
        	throw new Exception('No payment data sent');
        }

        if ($payment instanceof Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCardInterface) {

            return $this->getCreditCardData($payment);
        } elseif ($payment instanceof Webjump_BrasPag_Pagador_Data_Request_Payment_DebitCardInterface) {

            return $this->getDebitCardData($payment);
        } elseif ($payment instanceof Webjump_BrasPag_Pagador_Data_Request_Payment_BoletoInterface) {

            return $this->getBoletoData($payment);
        } else {

            throw new Exception('Request payment collection not recognized');
        }
    }

    /**
     * @param $payment
     * @return array
     */
    protected function getCreditCardData($payment)
    {
        $creditCardData = [
            "CardNumber" => $payment->getCardNumber(),
            "Holder" => $payment->getCardHolder(),
            "ExpirationDate" => $payment->getCardExpirationDate(),
            "SecurityCode" => $payment->getCardSecurityCode(),
            "Brand" => $payment->getCardBrand(),
            "SaveCard" => $payment->getSaveCard(),
            "Alias" => $payment->getCardAlias()
        ];

        $paymentData = [
            "Provider" => $payment->getProvider(),
            "Type" => $payment->getType(),
            "Amount" => $payment->getAmount(),
            "Currency" => $payment->getCurrency(),
            "Country" => $payment->getCountry(),
            "Installments" => $payment->getInstallments(),
            "Interest" => $payment->getInterest(),
            "Capture" => $payment->getCapture(),
            "Authenticate" => $payment->getAuthenticate(),
            "Recurrent" => $payment->getRecurrent(),
            "SoftDescriptor" => $payment->getSoftDescriptor(),
            "DoSplit" => $payment->getDoSplit(),
            "CreditCard" => $creditCardData
        ];

        return $paymentData;
    }

    /**
     * @param $payment
     * @return array
     */
    protected function getDebitCardData($payment)
    {
        $debitCardData = [
            "CardNumber" => $payment->getCardNumber(),
            "Holder" => $payment->getCardHolder(),
            "ExpirationDate" => $payment->getCardExpirationDate(),
            "SecurityCode" => $payment->getCardSecurityCode(),
            "Brand" => $payment->getCardBrand()
        ];

        $paymentData = [
            "Provider" => $payment->getProvider(),
            "Type" => $payment->getType(),
            "Amount" => $payment->getAmount(),
            "Currency" => $payment->getCurrency(),
            "Country" => $payment->getCountry(),
            "Interest" => $payment->getInterest(),
            "Capture" => $payment->getCapture(),
            "Authenticate" => $payment->getAuthenticate(),
            "Recurrent" => $payment->getRecurrent(),
            "SoftDescriptor" => $payment->getSoftDescriptor(),
            "DebitCard" => $debitCardData,
            "ReturnUrl" => $payment->getReturnUrl(),
        ];

        return $paymentData;
    }

    /**
     * @param $payment
     * @return array
     */
    protected function getBoletoData($payment)
    {
        $paymentData = [
            "Provider" => $payment->getProvider(),
            "Type" => $payment->getType(),
            "Amount" => $payment->getAmount(),
            "BoletoNumber" => $payment->getBoletoNumber(),
            "Assignor" => $payment->getAssignor(),
            "Demonstrative" => $payment->getDemonstrative(),
            "ExpirationDate" => $payment->getExpirationDate(),
            "Identification" => $payment->getIdentification(),
            "Instructions" => $payment->getInstructions(),
            "DaysToFine" => $payment->getDaysToFine(),
            "FineRate" => $payment->getFineRate(),
            "FineAmount" => $payment->getFineAmount(),
            "DaysToInterest" => $payment->getDaysToInterest(),
            "InterestRate" => $payment->getInterestRate(),
            "InterestAmount" => $payment->getInterestAmount()
        ];

        return $paymentData;
    }
}
