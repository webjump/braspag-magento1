<?php
/**
 * Mpi Transaction Authorize
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Mpi_Auth_GetToken
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Mpi_Auth_GetToken
    extends Webjump_BrasPag_Core_Http_Client
    implements Webjump_BrasPag_Mpi_Auth_GetTokenInterface
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

    public function setRequest(Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Webjump_BrasPag_Mpi_Auth_GetToken_ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }

    public function execute()
    {
        try {
            $this->doRequest($this->getRequest(), 'v2/auth/token', 'POST');
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
            $this->requestBuilder = $this->getServiceManager()->get('Mpi\Auth\GetToken\Request\Builder');
        }

        return $this->requestBuilder;
    }

    protected function getResponseHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = $this->getServiceManager()->get('Mpi\Auth\GetToken\Response\Hydrator');
        }

        return $this->hydrator;
    }
}
