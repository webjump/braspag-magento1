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
class Braspag_Pagador_Model_Method_Debitcard
    extends Braspag_Pagador_Model_Method_Abstract
{
    protected $_code = Braspag_Pagador_Model_Config::METHOD_DEBITCARD;

    protected $_canOrder                    = false;
    protected $_canVoid                     = true;

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

        if ($this->getCode() != Braspag_Pagador_Model_Config::METHOD_DEBITCARD) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(
            array(
                'type',
                'dc_type',
                'dc_owner',
                'dc_number',
                'dc_exp_month',
                'dc_exp_year',
                'dc_cid',
                'amount',
                'creditcard_justclick',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'), true);
        $allowedVarsInfo = array_fill_keys(
            array(
                'type',
                'dc_type',
                'dc_type_label',
                'dc_owner',
                'dc_number_masked',
                'dc_exp_month',
                'dc_exp_year',
                'amount',
                'authentication_failure_type',
                'authentication_cavv',
                'authentication_xid',
                'authentication_eci',
                'authentication_version',
                'authentication_reference_id'
            ), true);
        $debitCardTypes = $this->getDebitCardAvailableTypes();

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key($paymentRequestData, $allowedVars);

            if (!empty($data)) {
                if ($this->getCode() == Braspag_Pagador_Model_Config::METHOD_DEBITCARD) {
                    if (!empty($data['dc_number'])) {
                        $data['dc_number_masked'] = substr_replace(preg_replace('/[^0-9]+/', '', $data['dc_number']), str_repeat('*', 8), 4, 8);
                    }

                    if (!empty($data['dc_type'])) {
                        if (isset($debitCardTypes[$data['dc_type']])) {
                            $data['dc_type_label'] = $debitCardTypes[$data['dc_type']];
                        } else {
                            Mage::throwException($this->getHelper()->__('Selected debit card type is not allowed.'));
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
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        Mage::getModel('braspag_pagador/transaction_command_authorize_debitCard')->execute($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment, $amount)
    {
        parent::void($payment, $amount);

        Mage::getModel('braspag_pagador/debitcard')->void($payment, $amount);

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param string $transactionId
     * @return $this|array
     */
    public function fetchTransactionInfo(Varien_Object $payment, $transactionId)
    {
        $response = Mage::getModel('braspag_pagador/status_update')
            ->process($transactionId);

        if ($response->getIsTransactionApproved()) {
            $payment->setIsTransactionApproved(true);
        }

        return $this;
    }

    /**
     * Retrieve availables debit card types
     *
     * @return array
     */
    public function getDebitCardAvailableTypes()
    {
        $debitcardTypes = array();

        $config = $this->getConfigModel();
        $debitcardConfig = Mage::getModel('braspag_pagador/config_transaction_debitCard');

        $acquirers = $config->getAcquirers();
        $availableTypes = $debitcardConfig->getAvailableDebitCardPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = isset($availableTypeExploded[1]) ? $availableTypeExploded[1] : "";

            $debitcardTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($acquirers[$acquirerCode]) ? $acquirerCode : $acquirers[$acquirerCode]." - ").$brand;
        }

        return $debitcardTypes;
    }

    /**
     * @param $code
     * @return bool
     */
    public function getDebitCardAvailableTypesLabelByCode($code)
    {
        $debitcardAvaliabletypes = $this->getDebitCardAvailableTypes();

        if (isset($debitcardAvaliabletypes[$code])) {
            return $debitcardAvaliabletypes[$code];
        }

        return false;
    }

    public function getDebugFlag()
    {
        return Mage::getSingleton('braspag_core/config_general')->isDebugEnabled();
    }
}
