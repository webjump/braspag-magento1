<?php
/**
 * Pagador Transaction Capture request
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Transaction_Capture
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture_Request extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface
{
    protected $requestId;
    protected $version;
    protected $merchantId;
    protected $transactions;

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function setTransactions(Webjump_BrasPag_Pagador_Data_Request_Transaction_ListInterface $transactions = null)
    {
        $this->transactions = $transactions;

        return $this;
    }
}
