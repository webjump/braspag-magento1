<?php
class Webjump_BraspagPagador_Model_Method_Transaction_Abstract extends Webjump_BraspagPagador_Model_Method_Abstract
{

    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
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
            $allowedVars = array_fill_keys(array('type', 'cc_type', 'cc_owner', 'cc_number', 'cc_exp_month', 'cc_exp_year', 'cc_cid', 'installments', 'amount', 'cc_justclick'), true);
            $allowedVarsInfo = array_fill_keys(array('type', 'cc_type', 'cc_type_label', 'cc_owner', 'cc_number_masked', 'cc_exp_month', 'cc_exp_year', 'installments', 'installments_label', 'amount', 'cc_justclick'), true);
            $installments = $this->getInstallments();
            $cc_types = $this->getCcAvailableTypes();
            ($this->isJustClickActive()) ? $allowedVars['cc_justclick'] = true : null;
        } elseif ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_DC) {
            $allowedVars = array_fill_keys(array('type', 'dc_type', 'dc_owner', 'dc_number', 'dc_exp_month', 'dc_exp_year', 'dc_cid', 'amount'), true);
            $allowedVarsInfo = array_fill_keys(array('type', 'dc_type', 'dc_type_label', 'dc_owner', 'dc_number_masked', 'dc_exp_month', 'dc_exp_year', 'amount'), true);
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
     * Instantiate state and set it to state object
     *
     * @param string $paymentAction
     * @param Varien_Object
     */
/*    public function initialize($paymentAction, $stateObject)
{
switch ($paymentAction) {
case self::ACTION_ORDER:
case self::ACTION_AUTHORIZE:
case self::ACTION_AUTHORIZE_CAPTURE:
$payment = $this->getInfoInstance();
$order = $payment->getOrder();
$order->setCanSendNewEmailFlag(false);
$payment->authorize(true, $order->getBaseTotalDue()); // base amount will be set inside
$payment->setAmountAuthorized($order->getTotalDue());

//                $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, 'pending_payment', '', false);

//                $stateObject->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
//                $stateObject->setStatus('pending_payment');
//                $stateObject->setIsNotified(false);
break;
default:
break;
}
}
 */

    /**
     * Order Transaction
     *
     * @param Varien_Object $payment payment order data
     * @param Double        $amount  total   amount
     *
     * @return same
     */
    public function order(Varien_Object $payment, $amount)
    {
        parent::order($payment, $amount);
        $this->processPayment($payment, $amount);
    }

    /**
     * Authorize Transaction
     *
     * @param Varien_Object $payment payment order data
     * @param Double        $amount  total   amount
     *
     * @return same
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);
        $this->processPayment($payment, $amount);
    }

    public function processPayment(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
        $orderTransactionId = $payment->getTransactionId();

        try {
            $result = $this->getPagador()->authorize($payment, $amount);
            if ($result === false) {
                $errorMsg = $this->getHelper()->__('Error processing the request.');
                Mage::throwException($errorMsg);
            }
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        if (!$result->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $result->getErrorReport()->getErrors()));
            Mage::throwException($errorMsg);
        } else {
            $this->_importAuthorizeResultToPayment($result, $payment, $amount);
        }

        $this->saveJustClickCards($order, $result);

        if ($payment->getIsTransactionPending()) {
            $message = $this->getHelper()->__('Waiting response from acquirer.');
            $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
            $status = true;
        }

        if (!empty($state)) {
            if (!empty($message)) {
                $payment->getOrder()->setState($state, $status, $message);
            } else {
                $payment->getOrder()->setState($state, $status);
            }
        }

        if (!empty($errorMsg)) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    protected function saveJustClickCards($order, $result)
    {
        $info = $this->getInfoInstance()->getAdditionalInformation('payment_request');
        $payment = $result->getPayment();

//        foreach ($result->getPayment()->getIterator() as $key => $payment) {
            $paymentData = $payment->getArrayCopy();

            if (!empty($paymentData['cardToken'])) {
                $paymentData['is_active'] = isset($info[0]['cc_justclick']);
                $justClickCard = Mage::getModel('webjump_braspag_pagador/justclick_card');
                $justClickCard->savePaymentResponseLib($order, $paymentData);
            }
//        }
    }

    protected function _importAuthorizeResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $info = $this->getInfoInstance();
        $resultData = $result->getDataAsArray();

        if (empty($resultData['payment'])) {
            $errorMsg = $this->getHelper()->__('No payment response was received');
            Mage::throwException($errorMsg);
        }

        $api = $this->getPagador()->getApi($payment);

        $totalPaid = 0;
        $totalDue = 0;
        $raw_details = $resultData['order'] + array(
            'correlationId' => $resultData['correlationId'],
        );

        $errorMsg = array();
//        $paymentsPending = array();
//        $paymentCount = count($resultData['payments']);

        $paymentResponse = $this->getAdditionalInformation('payment_response');
        if (!$paymentResponse) {
            $paymentResponse = array();
        }

        $paymentResponseAuthorized = array();

//        $i = 0;
        $paymentRequest = $info->getAdditionalInformation('payment_request');
        $paymentRequestAuthorized = array();
        
        if ($resultPayment = $resultData['payment']) {
//            $paymentResponse[] = $resultPayment;
            switch ($resultPayment['integrationType']) {
                case 'TRANSACTION_CC':
                case 'TRANSACTION_DC':
                    $status = $resultPayment['status'];

                    //Waiting response
                    switch ($status) {
                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_NOT_FINISHED:
                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED:
                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED:
                            $totalPaid += $resultPayment['amount'] / 100;
                            $paymentRequestAuthorized = $paymentRequest;
                            $paymentResponseAuthorized = $resultPayment;
                            break;

                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED:
                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_ABORTED:

                        switch ((int) $resultPayment['returnCode']) {
                            case 5:
                                $returnMessage = $resultPayment['returnMessage'];
                                $errors = array();

                                if (strstr($returnMessage, 'numero element in DadosCartao')) {
                                    $errors[] = 'credit card number';
                                }
                                if (strstr($returnMessage, 'codigo-seguranca element in DadosCartao')) {
                                    $errors[] = 'card verification number';
                                }

                                if (!empty($errors)) {
                                    $errorMsgTmp = $this->getHelper()->__('Please verify %1$s (code %2$s).', (count($errors) == 1 ? current($errors) : implode(', ', array_slice($errors, 0, -1)) . ' ' . $this->getHelper()->__('and') . ' ' . end($errors)), $resultPayment['ReturnCode']);
                                } else {
                                    $errorMsgTmp = $this->getHelper()->__($returnMessage);
                                }

                                break;

                            default:
                                $errorMsgTmp = $this->getHelper()->__('%1$s (code %2$s).', $resultPayment['returnMessage'], $resultPayment['returnCode']);
                                break;
                        }

//                        if ($paymentCount > 1) {
//                            $formatedAmount = $order->getBaseCurrency()->formatTxt($resultPayment['amount'] / 100);
//                            $errorMsg[] = $this->getHelper()->__('Payment %1$s (%2$s): %3$s', ($key + 1), $formatedAmount, $errorMsgTmp);
//
//                            $totalDue += $resultPayment['amount'] / 100;
//
//                            $paymentsPending[] = $resultPayment;
//
//                        } else {
                            $errorMsg[] = $errorMsgTmp;
//                        }
                        break;

                    case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING:
                            if ($resultPayment['integrationType'] == 'TRANSACTION_CC') {
                                $payment->setIsTransactionPending(true);
                            }
                            
                            $paymentRequestAuthorized = $paymentRequest;
                            $paymentResponseAuthorized = $resultPayment;

                            break;
                    }

                    if ((!$totalPaid) && $resultData['payment']) {
                        $errorMsg[] = $this->getHelper()->__('The payment was unauthorized');
                    }

                    if ($resultPayment['integrationType'] == 'TRANSACTION_DC' && empty($resultPayment['authenticationUrl'])) {
                        $errorMsg[] = $this->getHelper()->__('The authentication url of payment was not received.');
                    }

                    break;

                case 'TRANSACTION_BOLETO':
                    $paymentRequestAuthorized = $paymentRequest;
                    $paymentResponseAuthorized = $resultPayment;
                    if (empty($resultPayment['url'])) {
                        if (!empty($resultPayment['message'])) {
                            $errorMsg[] = $this->getHelper()->__($resultPayment['message']);
                        } else {
                            $errorMsg[] = $this->getHelper()->__('An error occurs while generating the boleto.');
                        }
                    }

                    break;
            }

            foreach ($resultPayment as $r_key => $r_value) {
                $raw_details['payment_'. $r_key] = $r_value;
            }
//            $i++;
        }

        if (!empty($paymentRequestAuthorized)) {
            $info->setAdditionalInformation('payment_request', $paymentRequestAuthorized);
        }

        $info->setAdditionalInformation('payment_response', $paymentResponseAuthorized);
        $info->setAdditionalInformation('authorized_total_paid', $totalPaid);

//        if (!empty($paymentsPending)) {
//            Mage::getSingleton('webjump_braspag_pagador/session')->setOrderId($order->getIncrementId());
//            $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true);
//            $order->save();
//        }

        $payment
            ->setTransactionId($resultData['order']['braspagOrderId'])
            ->setIsTransactionClosed(0)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details)
        ;

        //If has error...
        if (!empty($errorMsg)) {
            $errorMsg = implode(PHP_EOL, $errorMsg);

            // if nothing was authorized and payment transaction is not pending
            if ($totalPaid == 0 && !$payment->getIsTransactionPending()) {
                Mage::throwException($errorMsg);
            }

            $order->addStatusHistoryComment($errorMsg);
        }

//        $formatedAmount = $order->getBaseCurrency()->formatTxt($totalPaid);
        //        $message =  $this->getHelper()->__('Authorized amount of %s.', $formatedAmount);
    }

    public function void(Varien_Object $payment)
    {
        $this->getPagadorTransaction()->void($payment->getOrder());

        return $this;
    }

    public function refund(Varien_Object $payment, $amount)
    {
        $this->getPagadorTransaction()->refund($payment->getOrder());

        return $this;
    }

    protected function getPagadorTransaction()
    {
        return Mage::getSingleton('webjump_braspag_pagador/pagador');
    }
}
