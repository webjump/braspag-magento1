<?php

/* Pagador Transaction Refund Response
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund_Response
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Refund_Response extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Transaction_Refund_ResponseInterface
{
    protected $correlationId;
    protected $success;
    protected $errorReport;
    protected $transactions;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setErrorReport($serviceManager->get('Pagador\Data\Response\ErrorReport'));
        $this->setTransactions($serviceManager->get('Pagador\Data\Response\Transaction\List'));
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

    public function setErrorReport(Webjump_BrasPag_Pagador_Data_Response_ErrorReportInterface $errorReport = null)
    {
        $this->errorReport = $errorReport;

        return $this;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function setTransactions(Webjump_BrasPag_Pagador_Data_Response_Transaction_ListInterface $transactions = null)
    {
        $this->transactions = $transactions;

        return $this;
    }
}
