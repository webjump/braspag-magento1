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
 * Credit Card Payment
 *
 * @category  Info
 * @package   Braspag_Pagador_Block_Info
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Block_Payment_Info_Boleto extends Braspag_Pagador_Block_Payment_Info
{
    public function __construct(array $args = array())
    {
        parent::__construct($args);

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_boleto/block/info');

        $this->processInfoChildren($formChildren);
    }

    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        $additionalData = $this->getInfo()->getAdditionalInformation();
        $paymentRequest = (isset($additionalData['payment_request'])) ? $additionalData['payment_request'] : array();

        $_hlp = Mage::helper('braspag_pagador');

        $order = $this->getInfo()->getOrder();

        if ($order) {
            $payment = $order->getPayment();
            $paymentTotalDue = ($order->getGrandTotal() - $payment->getAdditionalInformation('authorized_total_paid'));

            if (!isset($paymentRequest['type'])) {
                $paymentTotalDue = 0;
            } elseif (($paymentRequest['type'] == 'braspag_boleto') || (
                    $this->getLayout() &&
                    (in_array('braspag_payment_reorder', $this->getLayout()->getUpdate()->getHandles())))) {
                $paymentTotalDue = 0;
            }
        }

        if (!empty($paymentRequest)) {
            $paymentCount = count($additionalData['payment_request']);

            $tmp = array();

            if (!empty($paymentRequest['installments']) && !empty($paymentRequest['installments_label'])) {
                $tmp[$_hlp->__('Installments')] = $paymentRequest['installments_label'];
            }

            if ($paymentCount > 1 && !empty($paymentRequest['boleto_type'])) {
                $tmp[] = $paymentRequest['boleto_type'];
            }

            if (!empty($paymentRequest['amount'])) {
                $tmp[$_hlp->__('Amount')] = Mage::helper('core')->currency($paymentRequest['amount'], true, false);
            }

            if (
                $order &&
                $order->getBaseTotalDue() &&
                !empty($additionalData['payment_response']) &&
                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_BOLETO'
            ) {

                $paymentResponse = $additionalData['payment_response'];

                $tmp[$_hlp->__('Barcode representation')] = $paymentResponse['barCodeNumber'];
                $tmp[$_hlp->__('Expiration Date')] = \DateTime::createFromFormat('Y-m-d', $paymentResponse['expirationDate'])->format('d/m/Y');
                $tmp[] = '<a href="' . $paymentResponse['url'] . '" target="_blank" class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Print Boleto') . '</a>';
            }

            if (!empty($paymentTotalDue)) {
                $tmp[] = '<a href="' . $this->getUrl('braspag/payment/reorder') . '?order=' . $order->getIncrementId() . '"class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Now') . '</a>';
            }

            $data += $tmp;
        }

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}