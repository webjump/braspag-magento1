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
class Webjump_BraspagPagador_Model_Method_Transaction_Authorize_JustClick
    extends Webjump_BraspagPagador_Model_Method_Transaction_Authorize
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_JUSTCLICK;
    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_authorize_justclick';
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

        $allowedVars = array_fill_keys(array('type', 'creditcard_token', 'creditcard_cid', 'installments', 'amount'), true);
        $allowedVarsInfo = array_fill_keys(array('type', 'creditcard_token', 'installments', 'installments_label', 'amount'), true);

        if (!$data->getPaymentRequest()) {
            return false;
        }

        if ($paymentRequestData = $data->getPaymentRequest()) {

            $data = array_intersect_key(reset($paymentRequestData), $allowedVars);

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
}