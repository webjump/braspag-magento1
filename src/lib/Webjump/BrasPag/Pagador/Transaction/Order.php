<?php
/**
 * Pagador Transaction Order
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Order
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Order extends Webjump_BrasPag_Core_Http_Client
    implements Webjump_BrasPag_Pagador_TransactionInterface
{
    /**
     * @param Webjump_BrasPag_Pagador_Transaction_Order_RequestInterface $request
     * @return $this
     */
    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Order_RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Webjump_BrasPag_Pagador_Transaction_Order_ResponseInterface $response
     * @return $this
     */
    public function setResponse(Webjump_BrasPag_Pagador_Transaction_Order_ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $this->doRequest($this->getRequest(), 'v2/sales/', 'POST');
            $this->prepareResponse($this->getResponse());

        } catch (Exception $e) {
            $this->getResponse()->getErrorReport()
                ->setErrors(array('ErrorCode' => 'LIB', 'ErrorMessage' => $e->getMessage()));
        }

        return $this->getResponse();
    }

    /**
     * @return mixed
     */
    protected function getRequestBuilder()
    {
        if (!$this->requestBuilder) {
            $this->requestBuilder = $this->getServiceManager()->get('Pagador\Transaction\Order\Request\Builder');
        }

        return $this->requestBuilder;
    }

    /**
     * @return mixed
     */
    protected function getResponseHydrator()
    {
        if (!$this->responseHydrator) {
            $this->responseHydrator = $this->getServiceManager()->get('Pagador\Transaction\Order\Response\Hydrator');
        }

        return $this->responseHydrator;
    }
}
