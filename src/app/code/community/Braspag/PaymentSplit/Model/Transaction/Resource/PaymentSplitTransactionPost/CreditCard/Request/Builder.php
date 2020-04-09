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
class Braspag_PaymentSplit_Model_Transaction_Resource_PaymentSplitTransactionPost_CreditCard_Request_Builder
    extends Braspag_Pagador_Model_Transaction_AbstractBuilder
{
    /**
     * @param $order
     * @param $payment
     * @return bool|mixed
     * @throws Exception
     */
    public function build($order, $payment)
    {
        $authorizationAuthModel = Mage::getModel('braspag_core/auth');
        $splitPaymentsBuilder = Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_request_builder_splitPayments');

        $paymentInfo = $payment->getMethodInstance()->getInfoInstance()->getAdditionalInformation('payment_response');

        $paymentId = '';

        if ($payment->getMethodInstance()->getInfoInstance()->getAuthorizationTransaction()) {
            $paymentId = $payment->getMethodInstance()->getInfoInstance()->getAuthorizationTransaction()->getTxnId();
        }

        if (empty($paymentId) && isset($paymentInfo['braspagOrderId']) && !empty($paymentInfo['braspagOrderId'])) {
            $paymentId = $paymentInfo['braspagOrderId'];
        }

        $requestData = array(
            'AuthorizationToken' => $authorizationAuthModel->getAuthToken()->getAccessToken(),
            'SplitPayments' => $splitPaymentsBuilder->build($order, $payment),
            'PaymentId' => $paymentId,
        );

        $request = $this->getServiceManager()->get('Split\TransactionPost\Send\Request');
        $request->populate($requestData);

        return $request;
    }
}
