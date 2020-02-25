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
class Braspag_ProtectedCard_Model_Transaction_Resource_Authorize_CreditCard_Response_Processor_ProtectedCard
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Response_Processor
{
    /**
     * @param $payment
     * @param $response
     * @return $this
     */
    public function process($payment, $response)
    {
        $paymentDataResponse = $response->getPayment();
        $info = $payment->getMethodInstance()->getInfoInstance()->getAdditionalInformation('payment_request');

        $paymentData = $paymentDataResponse->getArrayCopy();

        if (!empty($paymentDataResponse->getCardToken())) {
            $paymentData['is_active'] = (isset($info['cc_justclick']) && $info['cc_justclick'] == 'on');
            $justClickCard = Mage::getModel('braspag_protectedcard/card');
            $justClickCard->savePaymentResponseLib($payment->getOrder(), $paymentData);
        }

        return $this;
    }
}