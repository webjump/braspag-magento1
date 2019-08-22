<?php
/**
 * Pagador Method Capture response
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture_Response extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface
{
    protected $correlationId;
    protected $success;
    protected $errorReport;
    protected $transactions;
    private $serviceManager;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    public function setCorrelationId($correlationId)
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    public function isSuccess()
    {
        return (boolean) $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = (boolean) $success;

        return $this;
    }

    public function getErrorReport()
    {
        return $this->errorReport;
    }

    public function setErrorReport($errorReport)
    {
        $this->errorReport = $errorReport;

        return $this;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function setTransactions(Webjump_BrasPag_Pagador_Data_Response_Transaction_List $transactions = null)
    {
        $this->transactions = $transactions;

        return $this;
    }

    public function importBySoapClientResult($data, $xml)
    {
        $data = $this->objToArray($data);

        $result = $data['CaptureCreditCardTransactionResult'];

        $this->setCorrelationId($result['CorrelationId']);
        $this->setSuccess($result['Success']);
        $this->getErrorReport()->setErrors($result['ErrorReportDataCollection']);

		//Normalize Braspag return for one or multiples Transactions request
		if (!array_key_exists(0, $result['TransactionDataCollection']['TransactionDataResponse'])) {
			$result['TransactionDataCollection']['TransactionDataResponse'] = array($result['TransactionDataCollection']['TransactionDataResponse']);
		}

        foreach ($result['TransactionDataCollection']['TransactionDataResponse'] as $r) {
            $transaction = $this->getServiceManager()->get('Pagador\Data\Response\Transaction\Item');
            $transaction->populate($r);
            $this->getTransactions()->add($transaction);
        }
    }

    protected function objToArray($obj)
    {
        if (!is_array($obj) && !is_object($obj)) {
            return $obj;
        }

        if (is_object($obj)) {
             $obj = get_object_vars($obj);
        }

        return array_map(array($this, __FUNCTION__),  $obj);
    }

	public function getDataAsArray()
    {
    	$data = parent::getDataAsArray();
    	
    	if (!empty($data['transactions']['transactions'])) {
    		$data['transactions'] = $data['transactions']['transactions'];
    	}
    	return $data;
    }

    protected function getServiceManager()
    {
        return $this->serviceManager;
    }
}
