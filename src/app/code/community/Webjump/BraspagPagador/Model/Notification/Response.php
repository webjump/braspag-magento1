<?php

class Webjump_BraspagPagador_Model_Notification_Response extends Mage_Core_Model_Abstract
{
    protected $response;

    public function import($response)
    {
        $this->response = $response;
        $this->setTransactionId($response['TRANSID']);
        $this->setIsTransactionClose(0);
        $this->importTransactionAdditionalInfo($response);
        $this->setIsAuthorized($response);
        $this->preparePaymentInfo();

        return $this;
    }

    protected function setIsAuthorized($response)
    {
        if (isset($response['DESRETORNO'])) {
            $this->setErrorMessage($response['DESRETORNO']);

            return parent::setIsAuthorized(false);
        }

        return parent::setIsAuthorized(true);
    }

    public function getTransactionType()
    {
        return 'authorization';
    }

    protected function importTransactionAdditionalInfo($response)
    {
        foreach ($response as $paymentAttribute => $paymentValue) {
            $transaction_info['payment_' . $paymentAttribute] = $paymentValue;
        }

        return $this->setTransactionAdditionalInfo($transaction_info);
    }

    public function preparePaymentInfo()
    {
        if (!$this->getIsAuthorized()) {
            return false;
        }

        $request = array(
            'requestId' => Mage::helper('webjump_braspag_pagador')->generateGuid($response['0213104507111']),
            'version' => '1.2',
            'merchantId' => $this->response['MERCHANTID'],
            'braspagTransactionId' => $this->response['TRANSACTIONID'],
        );

        $defaultResponse = Mage::getSingleton('webjump_braspag_pagador/pagador')->getTransactiondata($request);
        $creditCardResponse = Mage::getSingleton('webjump_braspag_pagador/pagador')->getCredicardData($request);
        $boletoResponse = Mage::getSingleton('webjump_braspag_pagador/pagador')->getBoletoData($request);

        if ($creditCardResponse['success']) {
            $response = array_merge($defaultResponse, $creditCardResponse);
            $return = array(
                'type' => 'webjump_braspag_cc',
                'cc_type' => $response['paymentMethod'],
                'cc_type_label' => $response['paymentMethodName'],
                'cc_owner' => $response['cardHolder'],
                'cc_number_masked' => $response['cardNumber'],
                'cc_exp_month' => substr($response['cardExpirationDate'], 0, 1),
                'cc_exp_year' => substr($response['cardExpirationDate'], 7, 11),
                'installments' => $response['numberOfPayment'],
                'installments_label' => Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments')->getInstallmentLabel($response['numberOfPayment'], $response['amount'] / 100),
                'amount' => $response['amount'] / 100,
                'integrationType' => 'TRANSACTION_CC',
            );
        } elseif (isset($boletoResponse['url'])) {
            $response = array_merge($defaultResponse, $boletoResponse);
            $return = array(
                'type' => 'webjump_braspag_cc',
                'boleto_type' => $response['type'],
                'amount' => $response['amount'] / 100,
                'barCodeNumber' => $response['barCodeNumber'],
                'url' => $response['url'],
                'expirationDate' => $response['expirationDate'],
                'integrationType' => 'TRANSACTION_BOLETO',
            );
        }

        return $this->setData('payment_response', array($return));
    }
}
