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
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Response_Handler_InfoData
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Response_Handler
{
    /**
     * @param $payment
     * @param $response
     * @return $this
     */
    public function handle($payment, $response)
    {
        $paymentDataResponse = $response->getPayment();

        if ($paymentDataResponse->getStatus() == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_AUTHORIZED
            || $paymentDataResponse->getStatus() == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_PAYMENT_CONFIRMED
            || $paymentDataResponse->getStatus() == Braspag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_PENDING
        ) {
            $info = $payment->getMethodInstance()->getInfoInstance();

            $info->setAdditionalInformation('payment_response', $paymentDataResponse->getDataAsArray());
            $info->setAdditionalInformation('authorized_total_paid', $paymentDataResponse->getAmount());
        }

        return $this;
    }
}