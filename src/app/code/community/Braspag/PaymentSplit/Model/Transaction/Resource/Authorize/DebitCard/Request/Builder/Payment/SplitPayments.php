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
class Braspag_PaymentSplit_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment_SplitPayments
    extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment
{
    /**
     * @param $payment
     * @param $amount
     * @param $compositeDataObject
     * @return array|mixed
     * @throws Exception
     */
    public function build($payment, $amount, $compositeDataObject)
    {
        $splitPaymentConfigModel = Mage::getSingleton('braspag_paymentsplit/config_debitCard');

        $quote = $payment->getOrder()->getQuote();

        $paymentSplitData = Mage::getModel('braspag_paymentsplit/paymentSplit')
            ->getPaymentSplitDataFromQuote($quote, $splitPaymentConfigModel);

        if (!$splitPaymentConfigModel->isActive()
            || $splitPaymentConfigModel->getSplitType() == Braspag_PaymentSplit_Model_Source_TransactionType::BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION_POST
        ) {
            return [];
        }

        $paymentSplitsRequestData = [];
        foreach ($paymentSplitData->getSubordinates() as $subordinate) {

            $paymentSplitFareRequestData = $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard\SplitPayment\Fare');
            $paymentSplitFareRequestData->populate($subordinate->getFares()->getData());

            $paymentSplitRequestData = $this->getServiceManager()->get('Pagador\Data\Request\Payment\DebitCard\SplitPayment');
            $paymentSplitRequestData->populate($subordinate->getData());
            $paymentSplitRequestData->setFares($paymentSplitFareRequestData);
            $paymentSplitRequestData->setSubordinateMerchantId($subordinate->getSubordinateMerchantId());

            $paymentSplitsRequestData[] = $paymentSplitRequestData;
        }

        return $paymentSplitsRequestData;
    }
}
