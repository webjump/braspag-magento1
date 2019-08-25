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
    extends Webjump_BrasPag_HttpClientAbstract
    implements Webjump_BrasPag_Mpi_Auth_GetTokenInterface
{
    protected $request;
    protected $response;
    protected $template;
    protected $hydrator;
    protected $serviceManager;

    public function __construct(Webjump_BrasPag_Mpi_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return parent::__construct($this->serviceManager);
    }

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
            $this->__doRequest($this->getRequest(), 'v2/auth/token', 'POST');
            $this->prepareResponse($this->getResponse());
        } catch (Exception $e) {
            $this->getResponse()->getErrorReport()
                ->setErrors(array('ErrorCode' => 'LIB', 'ErrorMessage' => $e->getMessage()));
        }

        return $this->getResponse();
    }

    protected function prepareRequest($request, $path, $action)
    {
        $template = $this->getTemplate();
        $template->setRequest($request);
        return $template->getData();
    }

    protected function prepareResponse($response)
    {
        $this->getResponseHydrator()->hydrate($this->getLastResponse(), $response);
    }

    protected function getTemplate()
    {
        if (!$this->template) {
            $this->template = $this->getServiceManager()->get('Mpi\Auth\GetToken\Template\Default');
        }

        return $this->template;
    }

    protected function getResponseHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = $this->getServiceManager()->get('Mpi\Auth\GetToken\ResponseHydrator');
        }

        return $this->hydrator;
    }

    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
