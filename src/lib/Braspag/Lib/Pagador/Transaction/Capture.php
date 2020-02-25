<?php
/**
 * Pagador Transaction Capture
 *
 * @category  Transaction
 * @package   Braspag_Lib_Pagador_Transaction_Capture
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Transaction_Capture extends Braspag_Lib_Core_Http_Client
    implements Braspag_Lib_Pagador_TransactionInterface
{
    /**
     * @param Braspag_Lib_Pagador_Transaction_Capture_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_Pagador_Transaction_Capture_RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Braspag_Lib_Pagador_Transaction_Capture_ResponseInterface $response
     * @return $this
     */
    public function setResponse(Braspag_Lib_Pagador_Transaction_Capture_ResponseInterface $response)
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
        $paymentId = $request->getOrder()->getBraspagOrderId();
        $amount = $request->getOrder()->getOrderAmount();
        $serviceTaxAmount = $request->getPayment()->getServiceTaxAmount();

        try {
            $this->setRequest($request);

            $path = "v2/sales/{$paymentId}/capture?amount={$amount}&serviceTaxAmount={$serviceTaxAmount}";

            $this->doRequest($this->getRequest(), $path, 'PUT');
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
            $this->requestBuilder = $this->getServiceManager()->get('Pagador\Transaction\Capture\Request\Builder');
        }

        return $this->requestBuilder;
    }

    /**
     * @return mixed
     */
    protected function getResponseHydrator()
    {
        if (!$this->responseHydrator) {
            $this->responseHydrator = $this->getServiceManager()->get('Pagador\Transaction\Capture\Response\Hydrator');
        }

        return $this->responseHydrator;
    }
}
