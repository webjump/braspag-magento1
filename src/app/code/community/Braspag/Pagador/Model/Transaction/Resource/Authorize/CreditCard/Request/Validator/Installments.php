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
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Request_Validator_Installments
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Request_Validator
{
    /**
     * @param $dataObject
     * @return bool
     */
    public function isValid($dataObject)
    {
        $payment = $dataObject->getPayment();
        $request = $dataObject->getRequest();

        $paymentRequestData = $payment->getPaymentRequest();

        if(($paymentRequestData['installments'] < 0)
            || ($paymentRequestData['installments'] > $payment->getMethodInstance()->getConfigData('installments'))
        ){
            $this->validatorMessages[] = $this->getBraspagPagadorHelper()->__("Invalid installments.");
            return false;
        }

        $installmentsBuilder = Mage::getModel('braspag_pagador/transaction_builder_payment_installments')
            ->build($payment, $request->getOrder()->getOrderAmount());

        $paymentRequestInstallments = $paymentRequestData['installments'];

        $installmentsBuildered = false;
        if (isset($installmentsBuilder[$paymentRequestInstallments])) {
            $installmentsBuildered = sprintf('%sx %s', $paymentRequestInstallments, $installmentsBuilder[$paymentRequestInstallments]);
            $installmentsBuildered = base64_encode($installmentsBuildered);
        }

        if (!isset($paymentRequestData['installments'])
            || !preg_match("#{$installmentsBuildered}#is", $installmentsBuildered)
        ) {
            $this->validatorMessages[] = "Selected installments is not allowed.";

            return false;
        }

        return true;
    }
}
