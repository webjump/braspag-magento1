<?php
/**
 * Pagador Transaction Capture Template Default
 *
 * @category  Template
 * @package   Webjump_BrasPag_Pagador_Transaction_Capture_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture_Template_Default
{
    protected $request;

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function toXml()
    {
    	try{
			$request  = $this->getRequest();
			$xml = $this->getHeader();
			$xml .= $this->getTransactionDataCollection();
			$xml .= $this->getFooter();

	        return $xml;
	    } catch (Exception $e) {
	    	throw new Exception($e->getMessage());
	    }
    }

    protected function getHeader()
    {
        $request  = $this->getRequest();

        $xml = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="https://www.pagador.com.br/webservice/pagador" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml .= "<SOAP-ENV:Body>\n";
        $xml .= "<ns1:CaptureCreditCardTransaction>\n";
        $xml .= "<ns1:request>\n";
        $xml .= "<ns1:RequestId>{$request->getRequestId()}</ns1:RequestId>\n";
        $xml .= "<ns1:Version>{$request->getVersion()}</ns1:Version>\n";
		$xml .= "<ns1:MerchantId>{$request->getMerchantId()}</ns1:MerchantId>\n";

        return $xml;
    }

    public function getTransactionDataCollection()
    {
        $request = $this->getRequest();

        $xml = "<ns1:TransactionDataCollection>\n";

        if (!$transactions = $this->getTransactionsIterator()) {
        	throw new Exception('No transaction data sent');
        }
        
		while ($transactions->valid()) {
			$transaction = $transactions->current();

			$xml .= "<ns1:TransactionDataRequest>\n";
			$xml .= "<ns1:BraspagTransactionId>{$transaction->getBraspagTransactionId()}</ns1:BraspagTransactionId>\n";
			$xml .= "<ns1:Amount>{$transaction->getAmount()}</ns1:Amount>\n";
			$serviceTaxAmount = $transaction->getServiceTaxAmount();
			if (!empty($serviceTaxAmount)) {
				$xml .= "<ns1:ServiceTaxAmount>{$transaction->getServiceTaxAmount()}</ns1:ServiceTaxAmount>\n";
			}
			$xml .= "</ns1:TransactionDataRequest>\n";
			$transactions->next();
		}
        $xml .= "</ns1:TransactionDataCollection>\n";

        return $xml;
    }

    protected function getFooter()
    {
        $xml = "</ns1:request>\n";
        $xml .= "</ns1:CaptureCreditCardTransaction>\n";
        $xml .= "</SOAP-ENV:Body>\n";
        $xml .= "</SOAP-ENV:Envelope>\n";

        return $xml;
    }

    protected function getTransactionsIterator()
    {
		if ($transaction = $this->getRequest()->getTransactions()) {
			return $transaction->getIterator();
		}
    }
}
