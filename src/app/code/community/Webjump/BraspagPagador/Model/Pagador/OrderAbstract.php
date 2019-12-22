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
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
abstract class Webjump_BraspagPagador_Model_Pagador_OrderAbstract
{
    protected $payment;

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param $payment
     * @param $amount
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function execute($payment, $amount)
    {
        try {

            $this->getRequestValidator()->validate($payment, $amount);

            $request =  $this->getRequestBuilder()->build($payment, $amount);

            $transaction = $this->getServiceManager()->get('Pagador\Transaction\Order');
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
     * @param $paymentDataResponse
     * @param $payment
     * @return $this
     */
    protected function saveInfoData($paymentDataResponse, $payment)
    {
        $info = $payment->getMethodInstance()->getInfoInstance();

        $info->setAdditionalInformation('payment_response', $paymentDataResponse->getDataAsArray());
        $info->setAdditionalInformation('ordered_total_paid', $paymentDataResponse->getAmount());

        return $this;
    }

    /**
     * @param $paymentDataResponse
     * @param $payment
     * @return $this
     */
    protected function saveRawDetails($paymentDataResponse, $payment)
    {
        $raw_details = [];
        foreach ($paymentDataResponse->getDataAsArray() as $r_key => $r_value) {
            $raw_details['payment_order_'. $r_key] = $r_value;
        }

        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }

    /**
     * @param $errors
     * @param $paymentDataResponse
     * @param $payment
     * @return $this
     */
    protected function saveErrors($errors, $paymentDataResponse, $payment)
    {
        if (!empty($errors)) {
            $errors = implode(PHP_EOL, $errors);

            if ($paymentDataResponse->getAmount() == 0 && !$payment->getIsTransactionPending()) {
                \Mage::throwException($errors);
            }

            $payment->getOrder()->addStatusHistoryComment($errors);
        }

        return $this;
    }
}