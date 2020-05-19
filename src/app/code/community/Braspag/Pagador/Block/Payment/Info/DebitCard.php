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
 * @category  Info
 * @package   Braspag_Pagador_Block_Info
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Debit Card Payment
 *
 * @category  Info
 * @package   Braspag_Pagador_Block_Info
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Block_Payment_Info_DebitCard extends Braspag_Pagador_Block_Payment_Info
{
    public function __construct(array $args = array())
    {
        parent::__construct($args);

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_debitcard/block/info');

        $this->processInfoChildren($formChildren);
    }

    /**
     * @param null $transport
     * @return Varien_Object
     * @throws Mage_Core_Exception
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (!empty($this->_paymentSpecificInformation)) {
            return $this->_paymentSpecificInformation;
        }
        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        $additionalData = $this->getInfo()->getAdditionalInformation();
        $paymentRequest = (isset($additionalData['payment_request'])) ? $additionalData['payment_request'] : array();
        $paymentResponse = (isset($additionalData['payment_response'])) ? $additionalData['payment_response'] : array();

        $_hlp = Mage::helper('braspag_pagador');

        $order = $this->getInfo()->getOrder();

        if ($order) {
            $payment = $order->getPayment();
            $paymentTotalDue = ($order->getGrandTotal() - $payment->getAdditionalInformation('authorized_total_paid'));

            if (!isset($paymentRequest['type'])) {
                $paymentTotalDue = 0;
            } elseif ($this->getLayout()
                && (in_array('braspag_payment_reorder', $this->getLayout()->getUpdate()->getHandles()))
            ) {
                $paymentTotalDue = 0;
            }
        }

        if (!empty($paymentRequest)) {

            $tmp = array();

            if (
                $order &&
                $order->getBaseTotalDue() &&
                !empty($additionalData['payment_response']) &&
                !empty($paymentResponse['authenticationUrl']) &&
                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_DEBITCARD'
            ) {
                $paymentResponse = $additionalData['payment_response'];
                $tmp[] = '<a href="' . $paymentResponse['authenticationUrl'] . '" class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Order') . '</a>';
            }

            if (!empty($paymentRequest['installments']) && !empty($paymentRequest['installments_label'])) {
                $tmp[$_hlp->__('Installments')] = $paymentRequest['installments_label'];
            }

            if (!empty($paymentRequest['dc_type']) && !empty($paymentRequest['dc_type_label'])) {
                $tmp[$_hlp->__('Debit Card Type')] = $paymentRequest['dc_type_label'];
            }

            if (!empty($paymentRequest['dc_owner'])) {
                $tmp[$_hlp->__('Name on the Card')] = $paymentRequest['dc_owner'];
            }

            if (!empty($paymentRequest['dc_number_masked'])) {
                $tmp[$_hlp->__('Debit Card Number')] = $paymentRequest['dc_number_masked'];
            }

            if (!empty($paymentRequest['dc_exp_month']) && !empty($paymentRequest['dc_exp_year'])) {
                $tmp[$_hlp->__('Expiration Date')] = $_hlp->__('%1$02s/%2$s', $paymentRequest['dc_exp_month'], $paymentRequest['dc_exp_year']);
            }

            if (!empty($paymentRequest['amount'])) {
                $tmp[$_hlp->__('Amount')] = Mage::helper('core')->currency($paymentRequest['amount'], true, false);
            }

            if (!empty($paymentTotalDue)) {
                $tmp[] = '<a href="' . $this->getUrl('braspag/payment/reorder') . '?order=' . $order->getIncrementId() . '"class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Now') . '</a>';
            }

            foreach($this->getCompositeChildrenSpecificInfo($paymentRequest) as $information) {
                $tmp[$information['info_label']] = $information['info_content'];
            }

            $data += $tmp;
        }

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}