<?php
/**
 * Pagador Transaction Refund Request hydrator
 *
 * @category  Method
 * @package   Webjump_BrasPag_Pagador_Transaction_Refund_Request_Hydrator
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Refund_RequestHydrator implements Webjump_BrasPag_Pagador_Data_HydratorInterface
{
    protected $transactionsList;
    protected $transactionItem;

    public function __construct(Webjump_BrasPag_Pagador_Service_ServiceManagerInterface $serviceManager)
    {
        $this->setTransactionsList($serviceManager->get('Pagador\Data\Request\Transaction\List'));
        $this->setTransactionItem($serviceManager->get('Pagador\Data\Request\Transaction\Item'));
    }

    public function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface $request)
    {
        $data = $this->convertTransactionDataToTransactionList($data, $request);
        $this->hydrateDefault($data, $request);

        return $request;
    }

    protected function hydrateDefault($data, Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface $request)
    {
        $request->populate($data);
    }

    protected function convertTransactionDataToTransactionList($transactionsData, Webjump_BrasPag_Pagador_Transaction_Refund_RequestInterface $request)
    {
        if (isset($transactionsData['transactionDataCollection'])) {
            $transactionList = $this->getTransactionsList();

            foreach ($transactionsData['transactionDataCollection'] as $transactionData) {
                $transaction = clone $this->getTransactionItem();
                $transaction->populate($transactionData);
                $transactionList->add($transaction);
            }

            $transactionsData['transactionDataCollection'] = $transactionList;
        }

        return $transactionsData;
    }

    public function getTransactionsList()
    {
        return $this->transactionsList;
    }

    protected function setTransactionsList($transactionsList)
    {
        $this->transactionsList = $transactionsList;

        return $this;
    }

    public function getTransactionItem()
    {
        return $this->transactionItem;
    }

    protected function setTransactionItem($transactionItem)
    {
        $this->transactionItem = $transactionItem;

        return $this;
    }
}
