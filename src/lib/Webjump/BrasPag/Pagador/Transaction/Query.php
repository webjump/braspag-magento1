<?php
/**
 * Pagador Transaction Authorize
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Query implements Webjump_BrasPag_Pagador_Transaction_QueryInterface
{
    protected $client;
    protected $serviceManager;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getAdditionalData(array $request)
    {
        return false;
    }

    public function getBoletoData(array $request)
    {
        $request = $this->prepareRequest($request);
        $response = $this->getClient()->getBoletoData(array('boletoDataRequest' => $request));

        return $this->prepareResponse($response->GetBoletoDataResult);
    }

    public function getBraspagOrderId(array $request)
    {
        return false;
    }

    public function getCredicardData(array $request)
    {
        $request = $this->prepareRequest($request);
        $response = $this->getClient()->getCreditCardData(array('creditCardDataRequest' => $request));

        return $this->prepareResponse($response->GetCreditCardDataResult);
    }

    public function getCustomerData(array $request)
    {
        return false;
    }

    public function getDeliveryAddressData(array $request)
    {
        return false;
    }

    public function getOrderData(array $request)
    {
        return false;
    }

    public function getTransactionData(array $request)
    {
        $request = $this->prepareRequest($request);
        $response = $this->getClient()->getTransactionData(array('transactionDataRequest' => $request));

        return $this->prepareResponse($response->GetTransactionDataResult);
    }

    public function getOrderIdData(array $request)
    {
        return false;
    }

    protected function getClient()
    {
        if (!$this->client) {
            $this->client = $this->getServiceManager()->get('Pagador\Transaction\Query\Client');
        }

        return $this->client;
    }

    protected function preparerequest($request)
    {
        return array_combine(
            array_map('ucfirst', array_keys($request)),
            array_values($request)
        );
    }

    protected function prepareResponse($response)
    {
        $response = json_decode(json_encode($response), true);

        return array_combine(
            array_map('lcfirst', array_keys($response)),
            array_values($response)
        );
    }

    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
