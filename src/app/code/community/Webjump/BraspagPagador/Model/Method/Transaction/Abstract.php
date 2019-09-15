<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Abstract extends Webjump_BraspagPagador_Model_Method_Abstract
{
    protected $totalPaid = 0;
    protected $errorMsg = [];

    /**
     * @param mixed $data
     * @return $this|Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        parent::assignData($data);

        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();

        $paymentRequest = $paymentRequestInfo = array();

        if ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_CC) {

            $allowedVars = array_fill_keys(
                array(
                    'type',
                    'cc_type',
                    'cc_owner',
                    'cc_number',
                    'cc_exp_month',
                    'cc_exp_year',
                    'cc_cid',
                    'installments',
                    'amount',
                    'cc_justclick',
                    'authentication_failure_type',
                    'authentication_cavv',
                    'authentication_xid',
                    'authentication_eci',
                    'authentication_version',
                    'authentication_reference_id'
                ), true);
            $allowedVarsInfo = array_fill_keys(
                array(
                    'type',
                    'cc_type',
                    'cc_type_label',
                    'cc_owner',
                    'cc_number_masked',
                    'cc_exp_month',
                    'cc_exp_year',
                    'installments',
                    'installments_label',
                    'amount',
                    'cc_justclick',
                    'authentication_failure_type',
                    'authentication_cavv',
                    'authentication_xid',
                    'authentication_eci',
                    'authentication_version',
                    'authentication_reference_id'
                ), true);
            $installments = $this->getInstallments();
            $cc_types = $this->getCcAvailableTypes();
            ($this->isJustClickActive()) ? $allowedVars['cc_justclick'] = true : null;

        } elseif ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_DC) {

            $allowedVars = array_fill_keys(
                array(
                    'type',
                    'dc_type',
                    'dc_owner',
                    'dc_number',
                    'dc_exp_month',
                    'dc_exp_year',
                    'dc_cid',
                    'amount',
                    'cc_justclick',
                    'authentication_failure_type',
                    'authentication_cavv',
                    'authentication_xid',
                    'authentication_eci',
                    'authentication_version',
                    'authentication_reference_id'), true);
            $allowedVarsInfo = array_fill_keys(
                array(
                    'type',
                    'dc_type',
                    'dc_type_label',
                    'dc_owner',
                    'dc_number_masked',
                    'dc_exp_month',
                    'dc_exp_year',
                    'amount',
                    'authentication_failure_type',
                    'authentication_cavv',
                    'authentication_xid',
                    'authentication_eci',
                    'authentication_version',
                    'authentication_reference_id'
                ), true);
            $dc_types = $this->getDcAvailableTypes();

        } elseif ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_BOLETO) {

            $allowedVars = array_fill_keys(array('type', 'boleto_type', 'amount'), true);
            $allowedVarsInfo = array_fill_keys(array('type', 'boleto_type', 'amount'), true);
            
        } elseif ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_JUSTCLICK) {

            $allowedVars = array_fill_keys(array('type', 'cc_token', 'cc_cid', 'installments', 'amount'), true);
            $allowedVarsInfo = array_fill_keys(array('type', 'cc_token', 'installments', 'installments_label', 'amount'), true);
            $installments = $this->getInstallments();

        } else {

            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key(reset($paymentRequestData), $allowedVars);

            if (!empty($data)) {
                if ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_CC) {
                    if (!empty($data['cc_number'])) {
                        $data['cc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $data['cc_number']), str_repeat('*', 8), 4, 8);
                    }

                    if (!empty($data['cc_type'])) {
                        if (isset($cc_types[$data['cc_type']])) {
                            $data['cc_type_label'] = $cc_types[$data['cc_type']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected credit card type is not allowed.'));
                        }
                    }

                    if (!empty($data['installments'])) {
                        if (isset($installments[$data['installments']])) {
                            $data['installments_label'] = $installments[$data['installments']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected installments is not allowed.'));
                        }
                    }

                } elseif ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_DC) {
                    if (!empty($data['dc_number'])) {
                        $data['dc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $data['dc_number']), str_repeat('*', 8), 4, 8);
                    }

                    if (!empty($data['dc_type'])) {
                        if (isset($dc_types[$data['dc_type']])) {
                            $data['dc_type_label'] = $dc_types[$data['dc_type']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected debit card type is not allowed.'));
                        }
                    }
                }

                $paymentRequest = $data;
                $paymentRequestInfo = array_intersect_key($data, $allowedVarsInfo);
            }
        }

        if (!empty($paymentRequest)) {
            $info->setAdditionalInformation('payment_request', $paymentRequestInfo);
            $info->setPaymentRequest($paymentRequest); //Also added fieldset in config.xml
        }

        return $this;
    }

    /**
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function validate()
    {
        parent::validate();

        $paymentRequest = $this->getInfoInstance()->getPaymentRequest();

        if ($this->getValidator()
            && preg_match("#saveOrder#is", Mage::app()->getRequest()->getOriginalPathInfo())
        ) {
            $this->getValidator()->validate($paymentRequest);
        }

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return Mage_Payment_Model_Abstract|void
     */
    public function order(Varien_Object $payment, $amount)
    {
        parent::order($payment, $amount);
        $this->processOrder($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return Webjump_BraspagPagador_Model_Method_Transaction_Abstract
     */
    public function processOrder(Varien_Object $payment, $amount)
    {
        return $this->processAuthorize($payment, $amount);
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return Mage_Payment_Model_Abstract|void
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);
        $this->processAuthorize($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return $this
     */
    public function processAuthorize(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();

        $result = $this->getPagador()->authorize($payment, $amount);

        if (!$result->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $result->getErrorReport()->getErrors()));
            Mage::throwException($errorMsg);
        }

        if ($result === false) {
            Mage::throwException($this->getHelper()->__('Error processing the request.'));
        }

        $resultData = $result->getDataAsArray();
        $resultPayment = $result->getPayment()->get();

        if (empty($resultData['payment']) || !$resultPayment) {
            Mage::throwException($this->getHelper()->__('No payment response was received'));
        }

        $this->_importAuthorizeResultToPayment($result, $payment, $resultPayment);

        $this->saveJustClickCards($order, $result);

        if ($payment->getIsTransactionPending()) {
            $message = $this->getHelper()->__('Waiting response from acquirer.');
            $payment->getOrder()->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true, $message);
        }

        if (!empty($errorMsg)) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    /**
     * @param $responseDataPayment
     * @return $this
     */
    protected function processAuthorizeInfoData($responseDataPayment)
    {
        $info = $this->getInfoInstance();

        $info->setAdditionalInformation('payment_response', $responseDataPayment);
        $info->setAdditionalInformation('authorized_total_paid', $this->totalPaid);

        return $this;
    }

    /**
     * @param $responseDataPayment
     * @param $payment
     * @return $this
     */
    protected function processAuthorizeRawDetails($responseDataPayment, $payment)
    {
        $raw_details = [];
        foreach ($responseDataPayment as $r_key => $r_value) {
            $raw_details['payment_'. $r_key] = $r_value;
        }

        $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }

    /**
     * @param $payment
     */
    protected function processAuthorizeErrors($payment)
    {
        if (!empty($this->errorMsg)) {
            $errorMsg = implode(PHP_EOL, $this->errorMsg);

            // if nothing was authorized and payment transaction is not pending
            if ($this->totalPaid == 0 && !$payment->getIsTransactionPending()) {
                Mage::throwException($errorMsg);
            }

            $payment->getOrder()->addStatusHistoryComment($errorMsg);
        }
    }

    /**
     * @param $order
     * @param $result
     */
    protected function saveJustClickCards($order, $result)
    {
        $info = $this->getInfoInstance()->getAdditionalInformation('payment_request');
        $payment = $result->getPayment();

        $paymentData = $payment->getArrayCopy();

        if (!empty($paymentData['cardToken'])) {
            $paymentData['is_active'] = isset($info[0]['cc_justclick']);
            $justClickCard = Mage::getModel('webjump_braspag_pagador/justclick_card');
            $justClickCard->savePaymentResponseLib($order, $paymentData);
        }
    }

    /**
     * @param Varien_Object $payment
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment)
    {
        try {
            $result = $this->getPagadorTransaction()->void($payment);
            if ($result === false) {
                $errorMsg = $this->getHelper()->__('Error processing the request.');
                throw new Exception($errorMsg);
            }
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        if (!$result->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $result->getErrorReport()->getErrors()));
            Mage::throwException($errorMsg);
        } else {
            $this->_importVoidResultToPayment($result, $payment, $payment->getOrder()->getGrandTotal());
        }

        return $this;
    }

    protected function _importVoidResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $resultData = $result->getDataAsArray();

        $voidedAmount = 0;
        $errorMsg = array();

        $this->errorMsg = $resultData['errorReport']['errors'];

        if ($success = (bool) $resultData['success']) {
            $voidedAmount += $payment->getOrder()->getGrandTotal();
        }

        if (!empty($errorMsg)) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $errorMsg));
            Mage::throwException($errorMsg);
        } elseif ($voidedAmount != $amount) {
            $formatedVoidedAmount = $order->getBaseCurrency()->formatTxt($voidedAmount);
            $formatedAmount = $order->getBaseCurrency()->formatTxt($amount);
            Mage::throwException($this->getHelper()->__('The voided amount (%1$s) differs from requested (%2$s).', $formatedVoidedAmount, $formatedAmount));
        }

        $raw_details['transaction_success'] = true;

        $payment
            ->setTransactionId($payment->getTransactionId())
            ->setIsTransactionClosed(1)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getPagadorTransaction()
    {
        return Mage::getSingleton('webjump_braspag_pagador/pagador');
    }
}
