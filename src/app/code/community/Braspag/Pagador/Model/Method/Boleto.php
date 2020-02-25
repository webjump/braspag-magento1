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
class Braspag_Pagador_Model_Method_Boleto
    extends Braspag_Pagador_Model_Method_Abstract
{
    protected $_code = Braspag_Pagador_Model_Config::METHOD_BOLETO;

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

        if (!$data->getPaymentRequest()) {
            return false;
        }

        $info = $this->getInfoInstance();

        $paymentRequest = $paymentRequestInfo = array();

        if ($this->getCode() != Braspag_Pagador_Model_Config::METHOD_BOLETO) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(array('type', 'boleto_type', 'amount'), true);
        $allowedVarsInfo = array_fill_keys(array('type', 'boleto_type', 'amount'), true);

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

    /**
     * @return mixed
     */
    public function getPaymentInstructions()
    {
        return $this->getConfigData('payment_instructions');
    }

    /**
     * @return mixed
     */
    public function getBoletoType()
    {
        return $this->getConfigData('boleto_type');
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function order(Varien_Object $payment, $amount)
    {
        parent::order($payment, $amount);

        Mage::getModel('braspag_pagador/transaction_command_order_boleto')->execute($payment, $amount);

        return $this;
    }

    /**
     * @return bool
     */
    public function getDebugFlag()
    {
        return Mage::getSingleton('braspag_core/config_general')->isDebugEnabled();
    }
}
