<?php
class Webjump_BrasPag_Pagadorquery extends \Zend_Http_Client
{
	protected $lastRequest;
	protected $lastResponse;
	protected $url;
	protected $data;
	protected $queryConfig;
	
	public function __construct($queryConfig)
	{
		$this->setQueryConfig($queryConfig);

		parent::__construct();
	}

    /**
     * @return mixed
     */
    public function getQueryConfig()
    {
        return $this->queryConfig;
    }

    /**
     * @param mixed $queryConfig
     */
    public function setQueryConfig($queryConfig)
    {
        $this->queryConfig = $queryConfig;
    }

	public function setData(array $data)
	{
		$this->data = $data;
		return $this;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getOrderIdData()
	{
		try {
            $data = $this->getData();
            $config = $this->getQueryConfig();

            $dataRequest = [
                "Header" => [
                    "MerchantId" => $data['merchant_id'],
                    "MerchantKey" => $data['merchant_key'],
                    "RequestId" => $data['request_id']
                ],
                "OrderIncrementId" => $data['OrderIncrementId']
            ];

            $this->setUri($config['webservice_wsdl']."v2/sales/");
            $this->setMethod("GET");
            $this->setParameterGet("merchantOrderId", $dataRequest['OrderIncrementId']);

            $this->setHeaders('Content-Type', 'application/json');
            $this->setHeaders($dataRequest['Header']);

            $this->request();

			return $this->getProcessResult();
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}

    /**
     * @return mixed
     * @throws Exception
     */
	public function getTransactionData()
	{
		try {
			$data = $this->getData();
			$config = $this->getQueryConfig();

            $dataRequest = [
			    "Header" => [
                    "MerchantId" => $data['merchant_id'],
                    "MerchantKey" => $data['merchant_key'],
                    "RequestId" => $data['request_id']
                ],
                "PaymentId" => $data['braspag_transaction_id']
            ];

            $this->setUri($config['webservice_wsdl']."v2/sales/".$dataRequest['PaymentId']);
            $this->setMethod("GET");

            $this->setHeaders('Content-Type', 'application/json');
            $this->setHeaders($dataRequest['Header']);

            $this->request();

			return $this->getProcessResult();

		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}

    /**
     * @return mixed
     * @throws Exception
     */
	protected function getProcessResult()
	{
		try {
		    $response = $this->getLastResponse();

		    $responseBody = $response->getRawBody();

		    if(!$response || $response->getStatus() != 200) {
                throw new Exception($responseBody);
            }

            $return = json_decode($responseBody, true);
			return $return;
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
}
