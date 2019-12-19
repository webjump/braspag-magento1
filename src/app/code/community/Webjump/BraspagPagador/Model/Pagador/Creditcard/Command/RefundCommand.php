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
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Method_Transaction_Refund_Creditcard
    extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_refund_creditcard';

    protected $_formBlockType = 'webjump_braspag_pagador/form_creditcard';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_creditcard';

    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        try {
            $result = $this->getPagadorTransaction()->void($payment, $amount);
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
            $this->_importRefundResultToPayment($result, $payment, $amount);
        }

        return $this;

        return $this;
    }

    /**
     * @param $result
     * @param $payment
     * @param $amount
     * @return $this
     */
    protected function _importRefundResultToPayment($result, $payment, $amount)
    {
        $order = $payment->getOrder();
        $resultData = $result->getDataAsArray();

        $refundedAmount = 0;
        $errorMsg = array();

        $this->errorMsg = $resultData['errorReport']['errors'];

        if ($success = (bool) $resultData['success']) {
            $refundedAmount += $payment->getOrder()->getTotalRefunded();
        }

        if (!empty($errorMsg)) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $errorMsg));
            Mage::throwException($errorMsg);
        } elseif ($refundedAmount != $amount) {
            $formatedVoidedAmount = $order->getBaseCurrency()->formatTxt($refundedAmount);
            $formatedAmount = $order->getBaseCurrency()->formatTxt($amount);
            Mage::throwException($this->getHelper()->__('The voided amount (%1$s) differs from requested (%2$s).', $formatedVoidedAmount, $formatedAmount));
        }

        $raw_details['transaction_success'] = true;

        $payment
            ->setTransactionId($payment->getTransactionId())
            ->setIsTransactionClosed(1)
            ->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }
}
