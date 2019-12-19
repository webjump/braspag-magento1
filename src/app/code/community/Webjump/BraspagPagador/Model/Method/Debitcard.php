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
class Webjump_BraspagPagador_Model_Method_Debitcard
    extends Webjump_BraspagPagador_Model_Method_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_DEBITCARD;

    protected $_formBlockType = 'webjump_braspag_pagador/form_debitcard';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_debitcard';

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

        if ($this->getCode() != Webjump_BraspagPagador_Model_Config::METHOD_DEBITCARD) {
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
                if ($this->getCode() == Webjump_BraspagPagador_Model_Config::METHOD_DEBITCARD) {
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

        Mage::getModel('webjump_braspag_pagador/pagador_debitcard')->authorize($payment, $amount);

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

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableDebitCardPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $debitcardTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
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
}
