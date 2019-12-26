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
class Webjump_BraspagPagador_Model_Method_Creditcard
    extends Webjump_BraspagPagador_Model_Method_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD;

    protected $_formBlockType = 'webjump_braspag_pagador/form_creditcard';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_creditcard';

    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;

    /**
     * @param mixed $data
     * @return $this|bool|Mage_Payment_Model_Info
     * @throws Mage_Core_Exception
     */
    public function assignData($data)
    {
        parent::assignData($data);

        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();

        $paymentRequest = $paymentRequestInfo = array();

        if ($this->getCode() != Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(
            array(
                'type',
                'cc_type',
                'cc_owner',
                'cc_number',
                'cc_exp_month',
                'cc_exp_year',
                'cc_cid',
                'installments',
                'amount',
                'cc_justclick',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'
            ), true);
        $allowedVarsInfo = array_fill_keys(
            array(
                'type',
                'cc_type',
                'cc_type_label',
                'cc_owner',
                'cc_number_masked',
                'cc_exp_month',
                'cc_exp_year',
                'installments',
                'installments_label',
                'amount',
                'cc_justclick',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'
            ), true);
        $installments = $this->getInstallments();
        $creditCardTypes = $this->getCreditCardAvailableTypes();
        ($this->isJustClickActive()) ? $allowedVars['cc_justclick'] = true : null;

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key($paymentRequestData, $allowedVars);

            if (!empty($data)) {
                if ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_CREDITCARD) {
                    if (!empty($data['cc_number'])) {
                        $data['cc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $data['cc_number']), str_repeat('*', 8), 4, 8);
                    }

                    if (!empty($data['cc_type'])) {
                        if (isset($creditCardTypes[$data['cc_type']])) {
                            $data['cc_type_label'] = $creditCardTypes[$data['cc_type']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected credit card type is not allowed.'));
                        }
                    }

                    if (!empty($data['installments'])) {
                        if (isset($installments[$data['installments']])) {
                            $data['installments_label'] = $installments[$data['installments']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected installments is not allowed.'));
                        }
                    }

                }

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

    /**
     * @return bool
     */
    public function isInitializeNeeded()
    {
        return true;
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * @return Mage_Payment_Model_Abstract|void
     */
    public function initialize($paymentAction, $stateObject)
    {
        $payment = $this->getInfoInstance();

        $payment->authorize(true, $payment->getOrder()->getTotalDue());

        $stateObject->setData('is_notified', false);
    }

    /**
     * @return array|bool
     */
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
     * Return creditcard avaliable types
     *
     * @return array list of creditcard avaliable types
     */
    public function getCreditCardAvailableTypes()
    {
        $creditCardTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableCreditCardPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);

            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = isset($availableTypeExploded[1]) ? $availableTypeExploded[1] : "";

            $creditCardTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $creditCardTypes;
    }

    /**
     * @return array
     */
    public function getCreditCardAvailableTypesCodes()
    {
        return array_keys($this->getCreditCardAvailableTypes());
    }

    /**
     * @param $code
     * @return bool|mixed
     */
    public function getCreditCardAvailableTypesLabelByCode($code)
    {
        $creditCardAvaliabletypes = $this->getCreditCardAvailableTypes();

        if (isset($creditCardAvaliabletypes[$code])) {
            return $creditCardAvaliabletypes[$code];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isJustClickActive()
    {
        return (boolean) $this->getConfigData('justclick_active');
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        Mage::getModel('webjump_braspag_pagador/pagador_creditcard')->authorize($payment, $amount);

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

        Mage::getSingleton('webjump_braspag_pagador/pagador_creditcard')->capture($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment)
    {
        parent::void($payment);

        Mage::getSingleton('webjump_braspag_pagador/pagador_creditcard')->void($payment);

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

        Mage::getSingleton('webjump_braspag_pagador/pagador_creditcard')->refund($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function cancel(Varien_Object $payment)
    {
        parent::void($payment);

        Mage::getSingleton('webjump_braspag_pagador/pagador_creditcard')->void($payment);
        
        return $this;
    }
}
