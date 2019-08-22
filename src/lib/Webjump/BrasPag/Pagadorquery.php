<?php
class Webjump_BrasPag_Pagadorquery
{
	protected $lastRequest;
	protected $lastResponse;
	protected $url;
	protected $data;
	protected $config;
	
	public function __construct($config)
	{
		$this->setConfig($config);
	}
	
	public function getConfig()
	{
		return $this->config;
	}
	
	public function setConfig($config)
	{
		$this->config = $config;
		return $this;
	}
	
	public function getLastRequest()
	{
		return $this->lastRequest;
	}
	
	protected function setLastRequest($xml)
	{
		$this->lastRequest = $xml;
		return $this;
	}

	public function getLastResponse()
	{
		return $this->lastResponse;
	}
	
	protected function setLastResponse($xml)
	{
		$this->lastResponse = $xml;
		return $this;
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
			$config = $this->getConfig();
	
			$xml = '
				<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
					<Body>
						<GetOrderIdData xmlns="' . $config['webservice_namespace']. '">
							<orderIdDataRequest>
								<RequestId>' . $data['request_id'] . '</RequestId>
								<Version>' . $config['webservice_version'] . '</Version>
								<MerchantId>' . $data['merchant_id'] . '</MerchantId>
								<OrderId>' . $data['order_increment_id'] . '</OrderId>
							</orderIdDataRequest>
						</GetOrderIdData>
					</Body>
				</Envelope>'
			;
			
			$response = $this->doRequest('GetOrderIdData', $xml);
			return $this->getOrderIdDataProcessResult($response);
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	function getOrderIdDataProcessResult($response)
	{
		try {
			$error = $response->getElementsByTagName('ErrorReportDataResponse');
			if ($error->length) {
				throw new Exception($error->item(0)->getElementsByTagName('ErrorMessage')->item(0)->nodeValue, $error->item(0)->getElementsByTagName('ErrorCode')->item(0)->nodeValue);
			}
	
			$result = $response->getElementsByTagName('OrderIdTransactionResponse');
			
			$return = array();
			foreach ($result AS $key => $r) {
				if (!$r->hasChildNodes()) {
					$return[$r->nodeName] = $r->nodeValue;
				}
				else {
					foreach ($r->childNodes AS $child) {
						$data = array();
						foreach ($child->childNodes AS $child_child) {
							$data[] = $child_child->nodeValue;
						}
						if (count($data) == 1) {
							$return[$key][$child->nodeName] = $data[0];
						} else {
							$return[$key][$child->nodeName] = $data;
						}
					}
				}
			}
			
			return $return;
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	public function getTransactionData()
	{
		try {
			$data = $this->getData();
			$config = $this->getConfig();
	
			$xml = '
				<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
					<Body>
						<GetTransactionData xmlns="' . $config['webservice_namespace']. '">
							<transactionDataRequest>
								<RequestId>' . $data['request_id'] . '</RequestId>
								<Version>' . $config['webservice_version'] . '</Version>
								<MerchantId>' . $data['merchant_id'] . '</MerchantId>
				               <BraspagTransactionId>' . $data['braspag_transaction_id'] . '</BraspagTransactionId>
							</transactionDataRequest>
						</GetTransactionData>
					</Body>
				</Envelope>'
			;
			
			$response = $this->doRequest('GetTransactionData', $xml);
			return $this->getTransactionDataProcessResult($response);
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	function getTransactionDataProcessResult($response)
	{
		try {
			$error = $response->getElementsByTagName('ErrorReportDataResponse');
			if ($error->length) {
				throw new Exception($error->item(0)->getElementsByTagName('ErrorMessage')->item(0)->nodeValue, $error->item(0)->getElementsByTagName('ErrorCode')->item(0)->nodeValue);
			}
	
			$result = $response->getElementsByTagName('GetTransactionDataResponse')->item(0)->getElementsByTagName('GetTransactionDataResult');
			
			$return = array();
			foreach ($result->item(0)->childNodes AS $key => $r) {
				$return[$r->nodeName] = $r->nodeValue;
			}
			return $return;
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	public function doRequest($method, $xml)
	{
		$config = $this->getConfig();

		$client = new SoapClient($config['webservice_wsdl']);
		$this->setLastRequest($xml);
		$response = $client->__doRequest($xml, $config['webservice_wsdl'], preg_replace('/\/$/', '', $config['webservice_namespace']) . '/' . $method, SOAP_1_1);

		$this->setLastResponse($response);
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->loadXML( $response );

		$error = $doc->getElementsByTagName('Fault');
		if ($error->length) {
			throw new Exception($error->item(0)->nodeValue );
		}
		return $doc;
	}
}
