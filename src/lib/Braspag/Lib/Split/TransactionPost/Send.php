<?php
/**
 * Split Transaction TransactionPostorize
 *
 * @category  Transaction
 * @package   Braspag_Lib_Split_TransactionPost_Send
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Split_TransactionPost_Send
    extends Braspag_Lib_Core_Http_Client
    implements Braspag_Lib_Split_TransactionPost_SendInterface
{
    protected $request;
    protected $response;
    protected $template;
    protected $hydrator;
    protected $serviceManager;

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Braspag_Lib_Split_TransactionPost_Send_ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }

    public function execute($request)
    {
        $paymentId = $request->getPaymentId();

        try {
            $this->setRequest($request);

            $this->doRequest($this->getRequest(), "api/transactions/{$paymentId}/split/", 'PUT');

            $this->prepareResponse($this->getResponse());
        } catch (Exception $e) {
            $this->getResponse()->getErrorReport()
                ->setErrors(array('ErrorCode' => 'LIB', 'ErrorMessage' => $e->getMessage()));
        }

        return $this->getResponse();
    }

    protected function getRequestBuilder()
    {
        if (!$this->requestBuilder) {
            $this->requestBuilder = $this->getServiceManager()->get('Split\TransactionPost\Send\Request\Builder');
        }

        return $this->requestBuilder;
    }

    protected function getResponseHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = $this->getServiceManager()->get('Split\TransactionPost\Send\Response\Hydrator');
        }

        return $this->hydrator;
    }
}
