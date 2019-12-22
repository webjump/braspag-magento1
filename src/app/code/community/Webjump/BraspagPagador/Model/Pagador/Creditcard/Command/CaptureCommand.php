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
class Webjump_BraspagPagador_Model_Pagador_Creditcard_Command_CaptureCommand
    extends Webjump_BraspagPagador_Model_Pagador_CaptureAbstract
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_capture_request_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getResponseValidator()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_capture_response_validator');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_resource_capture_requestBuilder');
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
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

    /**
     * @param $response
     * @param $payment
     * @return $this
     * @throws Exception
     */
    public function execute($payment, $amount)
    {
        try {

            $this->getRequestValidator()->validate($payment, $amount);

            $request =  $this->getRequestBuilder()->build($payment, $amount);

            $transaction = $this->getServiceManager()->get('Pagador\Transaction\Capture');
            $transaction->setRequest($request);

            $response = $transaction->execute();

            $payment->getMethodInstance()->debugData(array(
                'request' => json_encode($transaction->debug()),
                'response' => json_encode($transaction->debugResponse()),
            ));

            $this->getResponseValidator()->validate($response);

            $this->setPayment($payment);

            $this->processResponse($response, $payment);

        } catch (\Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $response
     * @param $payment
     * @return $this
     */
    protected function processResponse($response, $payment)
    {
        $errors = [];
        $paymentDataResponse = $response->getPayment()->get();

        if ($paymentDataResponse->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED) {

            $this->saveInfoData($paymentDataResponse, $payment);
        }

        if (
            $paymentDataResponse->getStatus() == Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING
        ) {
            $payment->setIsTransactionPending(true);
            $this->saveInfoData($paymentDataResponse, $payment);
        }

        $parentTransactionId = str_replace('-capture', '', $payment->getTransactionId());

        $payment->setParentTransactionId($parentTransactionId)
            ->setTransactionId($parentTransactionId."-capture")
            ->setIsTransactionClosed(0);

        $this->saveRawDetails($paymentDataResponse, $payment);
        $this->saveErrors($errors, $paymentDataResponse, $payment);

        return $this;
    }
}
