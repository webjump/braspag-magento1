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
class Webjump_BraspagPagador_Model_Method_Justclick
    extends Webjump_BraspagPagador_Model_Method_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_JUSTCLICK;
    protected $_formBlockType = 'webjump_braspag_pagador/form_justclick';

    public function assignData($data)
    {
        parent::assignData($data);

        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();

        $paymentRequest = $paymentRequestInfo = array();

        if ($this->getCode() != Webjump_BraspagPagador_Model_Config::METHOD_JUSTCLICK) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(array('type', 'cc_token', 'cc_cid', 'installments', 'amount'), true);
        $allowedVarsInfo = array_fill_keys(array('type', 'cc_token', 'installments', 'installments_label', 'amount'), true);

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key($paymentRequestData, $allowedVars);

            if (!empty($data)) {
                $paymentRequest = $data;
                $paymentRequestInfo = array_intersect_key($data, $allowedVarsInfo);
            }
        }

        if (!empty($paymentRequest)) {
            $info->setAdditionalInformation('payment_request', $paymentRequestInfo);
            $info->setPaymentRequest($paymentRequest); //Also added fieldset in config.xml
        }

        return $this;
    }

    public function getInstallments()
    {
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

            $return[$i] = $_hlp->__('%1$sx %2$s without interest', $i, $_hlpCore->currency($installmentAmount, true, false));
        }

        return $return;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        Mage::getModel('webjump_braspag_pagador/pagador_justclick')->authorize($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);

        Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_command_captureCommand')->execute();

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment)
    {
        parent::void($payment);

        Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_command_voidCommand')->execute();

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        parent::refund($payment, $amount);

        Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_command_refundCommand')->execute();

        return $this;
    }
}
