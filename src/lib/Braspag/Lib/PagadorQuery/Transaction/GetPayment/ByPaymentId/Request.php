<?php
/**
 * Pagador Transaction Authorize request
 *
 * @category  Method
 * @package   Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Request
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_RequestInterface
{
    protected $merchantId;
    protected $merchantKey;
    protected $requestId;
    protected $order;

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

    public function setOrder(Braspag_Lib_Pagador_Data_Request_OrderInterface $order = null)
    {
        $this->order = $order;

        return $this;
    }
}
