<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Method_Transaction_Cc
    extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_CC;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_creditcard';

    protected $_formBlockType = 'webjump_braspag_pagador/form_cc';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_cc';

    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;

    /**
     * Return cc avaliable types
     *
     * @return array list of cc avaliable types
     */
    public function getCcAvailableTypes()
    {
        $ccTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableCcPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $ccTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $ccTypes;
    }

    public function getCcAvailableTypesCodes()
    {
        return array_keys($this->getCcAvailableTypes());
    }

    public function getCcAvailableTypesLabelByCode($code)
    {
        $ccAvaliabletypes = $this->getCcAvailableTypes();

        if (isset($ccAvaliabletypes[$code])) {
            return $ccAvaliabletypes[$code];
        }

        return false;
    }

//    public function isInstallmentsEnabled()
//    {
//        $configData = $this->getConfigData('installments_plan');
//        if (empty($configData)) {
//            return false;
//        }
//        return (bool) $configData;
//    }

    public function isJustClickActive()
    {
        return (boolean) $this->getConfigData('justclick_active');
    }

    public function getInstallments()
    {

//        if (!$this->isInstallmentsEnabled()) {
//            return false;
//        }

        $installments = $this->getConfigData('installments');

        if (empty($installments)) {
            return false;
        }

        $_hlp = $this->getHelper();
        $_hlpCore = Mage::helper('core');
        $installmentsMinAmount = $this->getConfigData('installments_min_amount');
        $return = array();
        $installments++;

        $paymentInfo = $this->getInfoInstance();
        if ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
            $grandTotal = $paymentInfo->getOrder()->getGrandTotal();
        } else {
            $grandTotal = $paymentInfo->getQuote()->getGrandTotal();
        }

        for ($i = 1; $i < $installments; $i++) {
            $installmentAmount = $grandTotal / $i;

            if ($i > 1 && $installmentAmount < $installmentsMinAmount) {
                break;
            }

//            if (($this->getConfigData('installments_plan') == Webjump_BraspagPagador_Model_Config::PAYMENT_PLAN_ISSUER) && ($i > $this->getConfigData('installments_max_installments_without_interest'))) {
//                $installmentAmount = Mage::helper('webjump_braspag_pagador/installments')->installmentPriceWithInterest($grandTotal, $this->getConfigData('installments_interest_rate')/100, $i);
//                $return[$i] = $_hlp->__('%1$sx %2$s with interest*', $i, $_hlpCore->currency($installmentAmount, true, false));
//            } else {
                $return[$i] = $_hlp->__('%1$sx %2$s without interest', $i, $_hlpCore->currency($installmentAmount, true, false));
//            }

            
        }

        return $return;
    }

    public function capture(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);

        if ($amount <= 0) {
            Mage::throwException($this->getHelper()->__('Invalid amount for capture.'));
        }

        if (!$payment->getTransactionId()) {
            $this->authorize($payment, $amount);
            return $this;
        }

        try {
            $result = $this->getPagador()->capture($payment, $amount);
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
            $this->_importCaptureResultToPayment($result, $payment, $amount);
        }

        return $this;
    }

    protected function _importCaptureResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $resultData = $result->getDataAsArray();

        if (empty($resultData['transactions'])) {
            $errorMsg = $this->getHelper()->__('No transaction response was received');
            Mage::throwException($errorMsg);
        }

        $api = $this->getPagador()->getApi($payment);

        $capturedAmount = 0;
        $raw_details = array(
            'correlationId' => $resultData['correlationId'],
        );

        $errorMsg = array();

        $transactionCount = count($resultData['transactions']);
        foreach ($resultData['transactions'] as $key => $resultTransaction) {
            $status = $resultTransaction['status'];

            //Waiting response
            switch ($status) {
                case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED:
                    $capturedAmount += $resultTransaction['amount'] / 100;
                    break;

                case Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED:
                    switch ((int) $resultTransaction['returnCode']) {
                    default:
                            $errorMsgTmp = $this->getHelper()->__('%1$s (code %2$s) - Braspag transaction id (%3$s).', $resultTransaction['returnMessage'], $resultTransaction['returnCode'], $resultTransaction['braspagTransactionId']);
                            break;
                    }

                    if ($transactionCount > 1) {
                        $formatedAmount = $order->getBaseCurrency()->formatTxt($resultTransaction['amount'] / 100);
                        $errorMsg[] = $this->getHelper()->__('Transaction %1$s (%2$s): %3$s', ($key + 1), $formatedAmount, $errorMsgTmp);
                    } else {
                        $errorMsg[] = $errorMsgTmp;
                    }
                    break;

                default:
                    $errorMsg[] = $this->_wrapGatewayError($this->getHelper()->__('An error occurs before the request was sent to the Acquirer.'));
                    break;
            }

            foreach ($resultTransaction as $r_key => $r_value) {
                $raw_details['transaction_' . $key . '_' . $r_key] = $r_value;
            }
        }

        //If has error and nothing was authorized and payment transaction is not pending
        if (!empty($errorMsg)) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $errorMsg));
            Mage::throwException($errorMsg);
        } elseif ($capturedAmount != $amount) {
            $formatedCapturedAmount = $order->getBaseCurrency()->formatTxt($capturedAmount);
            $formatedAmount = $order->getBaseCurrency()->formatTxt($amount);
            Mage::throwException($this->getHelper()->__('The captured amount (%1$s) differs from requested (%2$s).', $formatedCapturedAmount, $formatedAmount));
        }

        $payment
            ->setTransactionId($resultData['correlationId'])
            ->setIsTransactionClosed(0)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details)
        ;

        return $this;
    }
}
