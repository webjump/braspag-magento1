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
class Webjump_BraspagPagador_Model_Pagador_Billet_Resource_Order_Request_Validator
{
    protected $validAttributes = array(
        'type',
        'billet_type',
        'amount'
    );

    protected $total;

    /**
     * @param $paymentRequest
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validate($paymentRequest, $amount)
    {
//        $this->addEncryptedNumber($paymentRequest);
//        $this->validateTotal($paymentRequest);
        $this->filterValidAttributes($paymentRequest);
        $this->validateType($paymentRequest);

        return $this;
    }

    /**
     * @param $paymentRequest
     * @return array
     */
    protected function filterValidAttributes($paymentRequest)
    {
        return array_intersect_key($paymentRequest->getData(), array_fill_keys($this->getValidAttributes(), true));
    }

    /**
     * @param $paymentRequest
     * @return mixed
     * @throws Mage_Core_Exception
     */
    protected function validateType($paymentRequest)
    {
        if ((isset($paymentRequest['cc_type'])) &&
            (!$paymentRequest['cc_type_label'] = $this->getCreditCardPaymentMethod()->getCreditCardAvailableTypesLabelByCode($paymentRequest['cc_type']))) {
            Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Selected credit card type is not allowed.'));
        }

        return $paymentRequest;
    }

    /**
     * @param $paymentRequest
     * @return bool]
     */
    protected function isValidInstallment($paymentRequest)
    {
        return (($paymentRequest['installments'] > 0) && ($paymentRequest['installments'] <= $this->getCreditCardPaymentMethod()->getConfigData('installments')));
    }

    /**
     * @return array
     */
    protected function getValidAttributes()
    {
        return $this->validAttributes;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getCreditCardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_creditcard');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getCreditCardInstallments()
    {
        return Mage::getSingleton('webjump_braspag_pagador/pagador_creditcard_resource_authorize_installmentsBuilder');
    }
}
