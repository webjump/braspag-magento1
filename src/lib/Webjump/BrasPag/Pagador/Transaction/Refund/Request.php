<?php
/**
 * Pagador Transaction Refund Request
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund_Request
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Refund_Request extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface
{
    protected $requestId;
    protected $version;
    protected $merchantId;
    protected $transactionDataCollection;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setTransactionDataCollection($serviceManager->get('Pagador\Data\Request\Transaction\List'));
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

    public function getTransactionDataCollection()
    {
        return $this->transactionDataCollection;
    }

    public function setTransactionDataCollection(Webjump_BrasPag_Pagador_Data_Request_Transaction_ListInterface $transactionDataCollection = null)
    {
        $this->transactionDataCollection = $transactionDataCollection;

        return $this;
    }
}
