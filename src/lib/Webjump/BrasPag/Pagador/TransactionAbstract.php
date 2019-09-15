<?php
/**
 * Pagador Transaction Pagador
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BrasPag_Pagador_TransactionAbstract implements Webjump_BrasPag_Pagador_TransactionInterface
{
    protected $config = array();
    protected $authorize;
    protected $authorizeRequestHydrator;
    protected $void;
    protected $voidRequestHydrator;
    protected $refund;
    protected $refundRequestHydrator;
    protected $query;
    protected $serviceManager;

    public function __construct(array $config, Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager = null)
    {
        $this->setConfig($config);

        if ($serviceManager) {
            $this->setServiceManager($serviceManager);
        }

        $this->setAuthorize($this->getServiceManager()->get('Pagador\Transaction\Authorize'));
        $this->setAuthorizeRequestHydrator($this->getServiceManager()->get('Pagador\Transaction\Authorize\Request\Hydrator'));
        $this->setVoid($this->getServiceManager()->get('Pagador\Transaction\Void'));
        $this->setVoidRequestHydrator($this->getServiceManager()->get('Pagador\Transaction\Void\Request\Hydrator'));
        $this->setRefund($this->getServiceManager()->get('Pagador\Transaction\Refund'));
        $this->setRefundRequestHydrator($this->getServiceManager()->get('Pagador\Transaction\Refund\Request\Hydrator'));
        $this->setQuery($this->getServiceManager()->get('Pagador\Transaction\Query'));
    }

    public function authorize($data)
    {
        $this->getAuthorizeRequestHydrator()->hydrate($data, $this->getAuthorize()->getRequest());
        return $this->getAuthorize()->execute()->getDataAsArray();
    }

    public function void($data)
    {
        $this->getVoidRequestHydrator()->hydrate($data, $this->getVoid()->getRequest());
        return $this->getVoid()->execute()->getDataAsArray();
    }

    public function refund($data)
    {
        $this->getRefundRequestHydrator()->hydrate($data, $this->getRefund()->getRequest());
        return $this->getRefund()->execute()->getDataAsArray();
    }

    public function getTransactionData(array $data)
    {
        return $this->getQuery()->getTransactionData($data);
    }

    public function getCredicardData(array $data)
    {
        return $this->getQuery()->getCredicardData($data);
    }

    public function getBoletoData(array $data)
    {
        return $this->getQuery()->getBoletoData($data);
    }

    protected function getQuery()
    {
        return $this->query;
    }

    protected function getAuthorize()
    {
        return $this->authorize;
    }

    protected function setAuthorize($authorize)
    {
        $this->authorize = $authorize;

        return $this;
    }

    protected function getAuthorizeRequestHydrator()
    {
        return $this->authorizeRequestHydrator;
    }

    protected function setAuthorizeRequestHydrator($authorizeRequestHydrator)
    {
        $this->authorizeRequestHydrator = $authorizeRequestHydrator;

        return $this;
    }

    protected function getVoid()
    {
        return $this->void;
    }

    protected function setVoid($void)
    {
        $this->void = $void;

        return $this;
    }

    protected function getVoidRequestHydrator()
    {
        return $this->voidRequestHydrator;
    }

    protected function setVoidRequestHydrator($voidRequestHydrator)
    {
        $this->voidRequestHydrator = $voidRequestHydrator;

        return $this;
    }

    protected function getRefund()
    {
        return $this->refund;
    }

    protected function setRefund($refund)
    {
        $this->refund = $refund;

        return $this;
    }

    protected function getRefundRequestHydrator()
    {
        return $this->refundRequestHydrator;
    }

    protected function setRefundRequestHydrator($refundRequestHydrator)
    {
        $this->refundRequestHydrator = $refundRequestHydrator;

        return $this;
    }

    protected function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    protected function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    protected function getServiceManager()
    {
        if (!$this->serviceManager) {
            $this->serviceManager = new Webjump_BrasPag_Pagador_Service_ServiceManager($this->getConfig());
        }

        return $this->serviceManager;
    }
}
