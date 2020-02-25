<?php
/**
 * Pagador Transaction Authorize
 *
 * @category  Transaction
 * @package   Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId extends Braspag_Lib_Core_Http_Client
    implements Braspag_Lib_PagadorQuery_TransactionInterface
{
    /**
     * @param Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface $response
     * @return $this
     */
    public function setResponse(Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute($request)
    {
        $merchantOrderId = $request->getMerchantOrderId();

        try {
            $this->setRequest($request);

            $this->doRequest($this->getRequest(), "v2/sales?merchantOrderId={$merchantOrderId}", 'GET');
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
            $this->requestBuilder = $this->getServiceManager()
                ->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Request\Builder');
        }

        return $this->requestBuilder;
    }

    /**
     * @return mixed
     */
    protected function getResponseHydrator()
    {
        if (!$this->responseHydrator) {
            $this->responseHydrator = $this->getServiceManager()
                ->get('PagadorQuery\Transaction\GetPayment\ByMerchantOrderId\Response\Hydrator');
        }

        return $this->responseHydrator;
    }
}
