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
class Webjump_BraspagPagador_Model_Method_Billet
    extends Webjump_BraspagPagador_Model_Method_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_BILLET;

    protected $_formBlockType = 'webjump_braspag_pagador/form_billet';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_billet';

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

        if ($this->getCode() != Webjump_BraspagPagador_Model_Config::METHOD_BILLET) {
            Mage::throwException($this->getHelper()->__('Payment method allowed vars not defined.'));
        }

        $allowedVars = array_fill_keys(array('type', 'billet_type', 'amount'), true);
        $allowedVarsInfo = array_fill_keys(array('type', 'billet_type', 'amount'), true);

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
    public function getBilletType()
    {
        return $this->getConfigData('billet_type');
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     */
    public function order(Varien_Object $payment, $amount)
    {
        parent::order($payment, $amount);

        Mage::getModel('webjump_braspag_pagador/pagador_billet')->order($payment, $amount);

        return $this;
    }
}
