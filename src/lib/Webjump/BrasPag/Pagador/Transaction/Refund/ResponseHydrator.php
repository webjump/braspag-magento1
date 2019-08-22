<?php

/* Pagador Transaction Refund Response Hydrator
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund_Response_Hydrator
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Refund_ResponseHydrator implements Webjump_BrasPag_Pagador_Data_HydratorInterface
{
    protected $transactionsList;
    protected $transactionItem;
    protected $errorsList;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setTransactionsList($serviceManager->get('Pagador\Data\Response\Transaction\List'));
        $this->setTransactionItem($serviceManager->get('Pagador\Data\Response\Transaction\Item'));
        $this->setErrorsList($serviceManager->get('Pagador\Data\Response\ErrorReport'));
    }

    public function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Refund_ResponseInterface $response)
    {
        $data = $this->applyLcFirstToAllKeys($data);
        $data = $this->convertTransactions($data);
        $data = $this->convertErrors($data);

        $response->populate($data);

        return $response;
    }

    protected function convertTransactions($transactionsData)
    {
        if (isset($transactionsData['transactionDataCollection'])) {
            $transactionList = $this->getTransactionsList();

            foreach ($this->extractTransactionsData($transactionsData) as $transactionData) {
                $transaction = clone $this->getTransactionItem();
                $transaction->populate($transactionData);
                $transactionList->add($transaction);
            }

            $transactionsData['transactions'] = $transactionList;
            unset($transactionsData['transactionDataCollection']);
        }

        return $transactionsData;
    }

    protected function extractTransactionsData($transactionsData)
    {
        if ((isset($transactionsData['transactionDataCollection']['transactionDataResponse'])) &&
            (is_array(reset($transactionsData['transactionDataCollection']['transactionDataResponse'])))) {
            return $transactionsData['transactionDataCollection']['transactionDataResponse'];
        }

        return $transactionsData['transactionDataCollection'];
    }

    protected function applyLcFirstToAllKeys($data)
    {
        $return = array();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $return[lcfirst($key)] = $this->applyLcFirstToAllKeys($value);
                continue;
            }

            $return[lcfirst($key)] = $value;
        }

        return $return;
    }

    protected function convertErrors($data)
    {
        if (isset($data['errorReportDataCollection'])) {
            $errorsList = $this->getErrorsList();
            $errorsList->setErrors($this->applyLcFirstToAllKeys($data['errorReportDataCollection']));
            $data['errorReport'] = $errorsList;
            unset($data['errorReportDataCollection']);
        }

        return $data;
    }

    protected function getTransactionsList()
    {
        return $this->transactionsList;
    }

    protected function setTransactionsList($transactionsList)
    {
        $this->transactionsList = $transactionsList;

        return $this;
    }

    protected function getTransactionItem()
    {
        return $this->transactionItem;
    }

    protected function setTransactionItem($transactionItem)
    {
        $this->transactionItem = $transactionItem;

        return $this;
    }

    protected function getErrorsList()
    {
        return $this->errorsList;
    }

    protected function setErrorsList($errorsList)
    {
        $this->errorsList = $errorsList;

        return $this;
    }
}
