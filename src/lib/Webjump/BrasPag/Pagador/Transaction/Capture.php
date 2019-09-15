<?php
/**
 * Pagador Transaction Capture
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Capture
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture extends Webjump_BrasPag_Pagador_Transaction_Abstract
    implements Webjump_BrasPag_Pagador_Transaction_CaptureInterface
{
    protected $request;
    protected $response;
    protected $template;
    protected $hydrator;
    protected $serviceManager;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return parent::__construct($this->serviceManager);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function execute()
    {
        $paymentId = $this->getRequest()->getOrder()->getBraspagOrderId();
        $amount = $this->getRequest()->getOrder()->getOrderAmount();

        try {
            $this->__doRequest($this->getRequest(), 'v2/sales/'.$paymentId."/capture?amount=".$amount, 'PUT');
            $this->prepareResponse($this->getResponse());
        } catch (Exception $e) {
            $this->getResponse()->getErrorReport()
                ->setErrors(array('ErrorCode' => 'LIB', 'ErrorMessage' => $e->getMessage()));
        }

        return $this->getResponse();
    }

    protected function prepareRequest($request, $location, $action)
    {
        $template = $this->getTemplate();
        $template->setRequest($this->getRequest());
        return $template->getData();
    }

    protected function prepareResponse($response)
    {
        $this->getResponseHydrator()->hydrate($this->getLastResponse(), $response);
    }

    protected function getTemplate()
    {
        if (!$this->template) {
            $this->template = $this->getServiceManager()->get('Pagador\Transaction\Capture\Template\Default');
        }

        return $this->template;
    }

    protected function getResponseHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = $this->getServiceManager()->get('Pagador\Transaction\Capture\ResponseHydrator');
        }

        return $this->hydrator;
    }

    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
