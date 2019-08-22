<?php

/* Pagador Transaction Refund
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Refund extends Webjump_BrasPag_Pagador_Transaction_Default implements Webjump_BrasPag_Pagador_Transaction_RefundInterface
{
    const TRANSACTION_RESULT_KEY = 'RefundCreditCardTransactionResult';

    protected $soapMethod = 'RefundCreditCardTransaction';

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setClient($serviceManager->get('Pagador\Transaction\Client'));
        $this->setRequest($serviceManager->get('Pagador\Transaction\Refund\Request'));
        $this->setResponse($serviceManager->get('Pagador\Transaction\Refund\Response'));
        $this->setResponseHydrator($serviceManager->get('Pagador\Transaction\Refund\Response\Hydrator'));
    }

    public function execute()
    {
        $soapResponse = $this->getClient()->RefundCreditCardTransaction (
            array('request' => $this->getSoapRequest())
        );

        return $this->prepareResponse($soapResponse);
    }

    protected function prepareResponse($data)
    {
        $data = $this->convertObjectToArray($data);
        $data = $this->hydrateResponse($data['RefundCreditCardTransactionResult']);

        return $data;
    }

    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function setResponse(Webjump_BrasPag_Pagador_Transaction_Refund_ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }
}
