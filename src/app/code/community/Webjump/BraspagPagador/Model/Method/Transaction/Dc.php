<?php
class Webjump_BraspagPagador_Model_Method_Transaction_Dc extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_DC;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_debitcard';

    protected $_validator = 'webjump_braspag_pagador/method_transaction_validator_dc';

    protected $_formBlockType = 'webjump_braspag_pagador/form_dc';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_dc';

    /**
     * Retrieve availables dedit card types
     *
     * @return array
     */
    public function getDcAvailableTypes()
    {
        $dcTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableDcPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $dcTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $dcTypes;
    }

    /**
     * @param $code
     * @return bool
     */
    public function getDcAvailableTypesLabelByCode($code)
    {
        $dcAvaliabletypes = $this->getDcAvailableTypes();

        if (isset($dcAvaliabletypes[$code])) {
            return $dcAvaliabletypes[$code];
        }

        return false;
    }

    protected function _importAuthorizeResultToPayment($result, $payment, $resultPayment)
    {
        $resultData = $result->getDataAsArray();

        $status = $resultPayment->getStatus();

        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED
            || $status = Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED) {

            $this->totalPaid += $resultPayment->getAmount() / 100;

            $this->processAuthorizeInfoData($resultData['payment']);
        }

        if ($status == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING) {

            $payment->setIsTransactionPending(true);
            $this->processAuthorizeInfoData($resultData['payment']);
        }

        if ((!$this->totalPaid) && $resultData['payment']) {
            $this->errorMsg[] = $this->getHelper()->__('The payment was unauthorized.');
        }

        $payment
            ->setTransactionId($resultData['order']['braspagOrderId'])
            ->setIsTransactionClosed(0);

        $this->processAuthorizeRawDetails($resultData['payment'], $payment);
        $this->processAuthorizeErrors($payment);

        return $this;
    }
}
