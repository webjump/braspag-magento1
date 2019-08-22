<?php
class Webjump_BrasPag_Generalservice
{
    protected $lastRequest;
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

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function encrypt()
    {
        $data = $this->getData();
        $config = $this->getConfig();

        $xml = '
			<Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
				<Body>
					<EncryptRequest xmlns="' . $config['webservice_namespace'] . '">
						<merchantId>' . $data['merchantId'] . '</merchantId>
						<request>
        ';

        foreach ($data['request'] as $key => $value) {
            $xml .= '<string>' . strtoupper($key) . '=' . $value . '</string>' . PHP_EOL;
        }

        $xml .= '
							</request>
					</EncryptRequest>
				</Body>
			</Envelope>'
        ;

        $response = $this->doRequest('EncryptRequest', $xml);
        $result = $response->getElementsByTagName('EncryptRequestResult');
        $encryptRequestResult = $result->item(0)->nodeValue;

        if (preg_match('/^Erro/', $encryptRequestResult)) {
            throw new Exception($encryptRequestResult, 31);
        } else {
            return $encryptRequestResult;
        }
    }

    public function decrypt()
    {
        $data = $this->getData();
        $config = $this->getConfig();

        if (empty($data['merchant_id'])) {
            throw new Exception('MerchantId was not defined.', 11);
        }

        if (empty($data['crypt'])) {
            throw new Exception('Crypt data was not defined.', 12);
        }

        $data = $this->getData();
        $config = $this->getConfig();

        $xml = '
			<Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
				<Body>
					<DecryptRequest xmlns="' . $config['webservice_namespace'] . '">
						<merchantId>' . $data['merchant_id'] . '</merchantId>
						<cryptString>' . trim($data['crypt']) . '</cryptString>
					</DecryptRequest>
				</Body>
			</Envelope>'
        ;

        $response = $this->doRequest('DecryptRequest', $xml);

        return $this->decryptProcessResult($response);

    }

    public function decryptProcessResult($response)
    {
        $result = $response->getElementsByTagName('DecryptRequestResult')->item(0)->getElementsByTagName('string');

        $return = array();
        foreach ($result as $r) {
            $value = $r->nodeValue;
            if (preg_match('/^Erro/', $value)) {
                throw new Exception($value, 21);
            } elseif (!preg_match('/^([^=]+)=(.*)$/', $value, $match)) {
                throw new Exception(sprintf('Error processing data %1$s.', $value), 22);
            } else {
                $return[$match[1]] = $match[2];
            }
        }

        if (empty($return)) {
            throw new Exception('No results were returned', 23);
        }

        return $return;
    }

    public function doRequest($method, $xml)
    {
        $config = $this->getConfig();

        $client = new SoapClient($config['webservice_wsdl']);
        $this->setLastRequest($xml);
        $response = $client->__doRequest($xml, $config['webservice_wsdl'], preg_replace('/\/$/', '', $config['webservice_namespace']) . '/' . $method, SOAP_1_1);

        if (is_soap_fault($response)) {
            throw new Exception($response->faultstring, $response->faultcode);
        } else {
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->loadXML($response);

            return $doc;
        }
    }
}
