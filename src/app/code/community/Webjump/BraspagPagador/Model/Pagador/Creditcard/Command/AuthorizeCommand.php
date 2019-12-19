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
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Creditcard_Command_AuthorizeCommand
    extends Webjump_BraspagPagador_Model_Pagador_AuthorizeAbstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_authorize_request_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getResponseValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_authorize_response_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_authorize_requestBuilder');
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager($this->getConfigData());
    }

    /**
     * @return mixed
     */
    protected function getConfigData()
    {
        return Mage::getModel('webjump_braspag_pagador/config')->getConfig();
    }

    /**
     * @param $response
     * @param $payment
     * @return $this
     * @throws Exception
     */
    protected function processResponse($response, $payment)
    {
        if (!$payment) {
            throw new \Exception("Invalid Payment Instance");
        }

        $errors = [];

        $paymentDataResponse = $response->getPayment()->get();

        if ($paymentDataResponse->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED
            || $paymentDataResponse->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED
        ) {
            $this->saveInfoData($paymentDataResponse, $payment)
                ->processFraudAnalysis($paymentDataResponse->getFraudAnalysis(), $payment);
        }

        if ($paymentDataResponse->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING) {
            $payment->setIsTransactionPending(true);
            $this->saveInfoData($paymentDataResponse, $payment);
        }

        if ($paymentDataResponse && (!$paymentDataResponse->getAmount())) {
            $errors[] = $this->getHelper()->__('The payment was unauthorized.');
        }

        $this->saveJustclickCards($paymentDataResponse, $payment)
            ->saveRawDetails($paymentDataResponse, $payment);

        $payment->setTransactionId($response->getOrder()->getBraspagOrderId())
            ->setIsTransactionClosed(0);

        $this->saveErrors($errors, $paymentDataResponse, $payment);

        return $this;
    }

    /**
     * @param $paymentDataResponse
     * @param $payment
     * @return $this
     */
    protected function saveJustclickCards($paymentDataResponse, $payment)
    {
        $info = $payment->getMethodInstance()->getInfoInstance()->getAdditionalInformation('payment_request');

        $paymentData = $paymentDataResponse->getArrayCopy();
        if (!empty($paymentDataResponse->getCardToken())) {
            $paymentData['is_active'] = isset($info[0]['creditcard_justclick']);
            $justClickCard = Mage::getModel('webjump_braspag_pagador/justclick_card');
            $justClickCard->savePaymentResponseLib($payment->getOrder(), $paymentData);
        }

        return $this;
    }

    /**
     * @param $fraudAnalysis
     * @param $payment
     * @return $this
     */
    protected function processFraudAnalysis($fraudAnalysis, $payment)
    {
        $antiFraudConfig = Mage::getModel('webjump_braspag_pagador/config_antifraud');

        if (!$antiFraudConfig->isAntifraudActive() || !isset($fraudAnalysis['Status'])) {
            return $this;
        }

        $fraudAnalysisStatus = $fraudAnalysis['Status'];

//        if ($antiFraudConfig->getOptionsSequence() != 'AnalyseFirst') {
//            return $this;
//        }

        if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REJECT
            || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_ABORTED
            || $fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_UNKNOWN
        ) {
            $payment->setIsFraudDetected(true);
        }

        if ($fraudAnalysisStatus == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_FRAUD_STATUS_REVIEW) {
            $payment->setIsTransactionPending(true);
        }

        return $this;
    }
}
