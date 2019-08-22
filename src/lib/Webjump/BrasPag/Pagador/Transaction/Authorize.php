<?php
/**
 * Pagador Transaction Authorize
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Authorize extends Webjump_BrasPag_Pagador_Transaction_Abstract
    implements Webjump_BrasPag_Pagador_Transaction_AuthorizeInterface
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

    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Authorize_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function execute()
    {
        try {
            $this->__doRequest($this->getRequest(), 'v2/sales/', 'POST');
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
        $data = $this->getLastResponse();

//        $mockedData = new Varien_Object();
//        $mockedData->setStatus(200);
//        $mockedData->setBody('{"MerchantOrderId":"000000035","Customer":{"Name":"jose accept","Identity":"67328470000150","IdentityType":"CNPJ","Email":"joseaccept@webjumpbraspag.com","Phone":"11948937178","Address":{"Street":"Rua Professor Artur Ramos","Number":"123","Complement":"Cj 41","ZipCode":"01454011","City":"São Paulo","State":"SP","Country":"BRA","District":"Jardim Paulistano"},"DeliveryAddress":{"Street":"","Number":"","Complement":"","Country":"","District":""}},"Payment":{"ServiceTaxAmount":0,"Installments":1,"Interest":"ByMerchant","Capture":true,"Authenticate":false,"Recurrent":false,"CreditCard":{"CardNumber":"554581******4411","Holder":"jose accept","ExpirationDate":"04/2020","SaveCard":false,"Brand":"Visa"},"ProofOfSale":"20190813060450377","AcquirerTransactionId":"0813060450377","AuthorizationCode":"680586","FraudAnalysis":{"Sequence":"AnalyseFirst","SequenceCriteria":"OnSuccess","FingerPrintId":"k8ie2od8g5d7vu5u5ekqhmfp4f","Provider":"Cybersource","CaptureOnLowRisk":false,"VoidOnHighRisk":false,"TotalOrderAmount":0,"IsRetryTransaction":false,"Cart":{"Items":[{"Type":"Undefined","Name":"Advanced Pilates & Yoga (Strength)","Risk":"Undefined","Sku":"240-LV08","UnitPrice":1800,"Quantity":1,"HostHedge":"Undefined","NonSensicalHedge":"Undefined","ObscenitiesHedge":"Undefined","PhoneHedge":"Undefined","TimeHedge":"Undefined","VelocityHedge":"Undefined","GiftCategory":"Undefined","Passenger":{"Rating":"Undefined"},"OriginalPrice":0,"Weight":0,"CartType":0}]},"Browser":{"CookiesAccepted":false,"IpAddress":"177.94.213.118"},"Shipping":{"Addressee":"","Phone":"","Method":"Undefined"},"Id":"4813e9fb-0dbe-e911-bcd0-00155dff22e9","Status":1,"StatusDescription":"Accept","FraudAnalysisReasonCode":100,"ReplyData":{"FactorCode":"N","Score":76,"HostSeverity":1,"IpCity":"sao paulo","IpCountry":"br","IpRoutingMethod":"fixed","IpState":"sao paulo","ScoreModelUsed":"default_lac","VelocityInfoCode":"VEL-NAME","CasePriority":3,"ProviderTransactionId":"5657302895226385404012"}},"VelocityAnalysis":{"Id":"0e0ed0f1-3fd5-4205-b082-181e96240b12","ResultMessage":"Accept","Score":0},"PaymentId":"0e0ed0f1-3fd5-4205-b082-181e96240b12","Type":"CreditCard","Amount":1800,"ReceivedDate":"2019-08-13 18:04:48","CapturedAmount":1800,"CapturedDate":"2019-08-13 18:04:50","Currency":"BRL","Country":"BRA","Provider":"Simulado","ReasonCode":0,"ReasonMessage":"Successful","Status":2,"ProviderReturnCode":"6","ProviderReturnMessage":"Operation Successful","Links":[{"Method":"GET","Rel":"self","Href":"https://apiquerysandbox.braspag.com.br/v2/sales/0e0ed0f1-3fd5-4205-b082-181e96240b12"},{"Method":"PUT","Rel":"void","Href":"https://apisandbox.braspag.com.br/v2/sales/0e0ed0f1-3fd5-4205-b082-181e96240b12/void"}]}}');
        
        $hydrator = $this->getResponseHydrator();

        $hydrator->hydrate($data, $response);
    }

    protected function getTemplate()
    {
        if (!$this->template) {
            $this->template = $this->getServiceManager()->get('Pagador\Transaction\Authorize\Template\Default');
        }

        return $this->template;
    }

    protected function getResponseHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = $this->getServiceManager()->get('Pagador\Transaction\Authorize\ResponseHydrator');
        }

        return $this->hydrator;
    }

    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
