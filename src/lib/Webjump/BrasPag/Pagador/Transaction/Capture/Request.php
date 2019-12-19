<?php
/**
 * Pagador Transaction Capture request
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Transaction_Capture
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture_Request
    extends Webjump_BrasPag_Core_Data_Abstract
    implements Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface
{
    protected $merchantId;
    protected $merchantKey;
    protected $requestId;
    protected $order;
    protected $payment;
    protected $customer;

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantKey()
    {
        return $this->merchantKey;
    }

    /**
     * @param mixed $merchantKey
     */
    public function setMerchantKey($merchantKey)
    {
        $this->merchantKey = $merchantKey;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Webjump_BrasPag_Pagador_Data_Request_OrderInterface $order = null)
    {
        $this->order = $order;

        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment(Webjump_BrasPag_Pagador_Data_Request_Payment_CurrentInterface $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(Webjump_BrasPag_Pagador_Data_Request_CustomerInterface $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }
}
