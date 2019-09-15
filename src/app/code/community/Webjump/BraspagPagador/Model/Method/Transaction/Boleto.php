<?php
class Webjump_BraspagPagador_Model_Method_Transaction_Boleto
    extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_BOLETO;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_boleto';

    protected $_formBlockType = 'webjump_braspag_pagador/form_boleto';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_boleto';

    public function getPaymentInstructions()
    {
    	return $this->getConfigData('payment_instructions');
    }

    public function getBoletoType()
    {
        return $this->getConfigData('boleto_type');
    }

    /**
     * @param $result
     * @param $payment
     * @param $resultPayment
     */
    protected function _importAuthorizeResultToPayment($result, $payment, $resultPayment)
    {
        $resultData = $result->getDataAsArray();

        if (empty($resultPayment->getUrl())) {
            if (!empty($resultPayment->getMessage())) {
                $this->errorMsg[] = $this->getHelper()->__($resultPayment->getMessage());
            } else {
                $this->errorMsg[] = $this->getHelper()->__('An error occurs while generating the boleto.');
            }
        }

        $this->processAuthorizeRawDetails($resultData['payment'], $payment);
        $this->processAuthorizeInfoData($resultData['payment']);

        $payment
            ->setTransactionId($resultData['order']['braspagOrderId'])
            ->setIsTransactionClosed(0);

        $this->processAuthorizeErrors($payment);
    }
}
