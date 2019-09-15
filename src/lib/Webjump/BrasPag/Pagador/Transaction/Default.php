<?php
/**
 * Pagador Transaction Abstract
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BrasPag_Pagador_Transaction_Default
{
    const TRANSACTION_RESULT_KEY = null;

    protected $client;
    protected $request;
    protected $response;
    protected $responseHydrator;

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

    public function getRequest()
    {
        return $this->request;
    }

    protected function prepareResponse($data)
    {
        $data = $this->convertObjectToArray($data);
        $data = $this->hydrateResponse($data[self::TRANSACTION_RESULT_KEY]);

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

    public function getResponse()
    {
        return $this->response;
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
