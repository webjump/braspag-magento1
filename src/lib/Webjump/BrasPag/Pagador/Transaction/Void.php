<?php

/* Pagador Transaction Void
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Void
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Void implements Webjump_BrasPag_Pagador_Transaction_VoidInterface
{
    protected $client;
    protected $request;
    protected $response;
    protected $responseHydrator;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setClient($serviceManager->get('Pagador\Transaction\Client'));
        $this->setRequest($serviceManager->get('Pagador\Transaction\Void\Request'));
        $this->setResponse($serviceManager->get('Pagador\Transaction\Void\Response'));
        $this->setResponseHydrator($serviceManager->get('Pagador\Transaction\Void\Response\Hydrator'));
    }

    public function execute()
    {
        $soapRequest = $this->prepareSoapRequest($this->getRequest()->getDataAsArray());

        $soapResponse = $this->getClient()->VoidCreditCardTransaction(
            array('request' => $soapRequest)
        );

        return $this->prepareResponse($soapResponse);
    }

    protected function prepareSoapRequest($data)
    {
        return $this->applyUcFirstToAllKeys($data);
    }

    protected function applyUcFirstToAllKeys($data)
    {
        $return = array();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->applyUcFirstToAllKeys($value);
            }

            $return[ucfirst($key)] = $value;
        }

        return $return;
    }

    protected function prepareResponse($data)
    {
        $data = $this->convertObjectToArray($data);
        $data = $this->hydrateResponse($data['VoidCreditCardTransactionResult']);

        return $data;
    }

    protected function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function hydrateResponse($data)
    {
        $this->getResponseHydrator()->hydrate($data, $this->getResponse());

        return $this->getResponse();
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Webjump_BrasPag_Pagador_Service_ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Webjump_BrasPag_Pagador_Transaction_Void_ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function getResponseHydrator()
    {
        return $this->responseHydrator;
    }

    public function setResponseHydrator($responseHydrator)
    {
        $this->responseHydrator = $responseHydrator;

        return $this;
    }
}
