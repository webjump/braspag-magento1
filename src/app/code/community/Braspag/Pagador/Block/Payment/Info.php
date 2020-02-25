<?php
class Braspag_Pagador_Block_Payment_Info extends Mage_Payment_Block_Info
{
    protected $specificInformation = [];
    protected $compositeChildren = [];
    protected $_installments = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/pagador/payment/info/default.phtml');
    }

    /**
     * @param array $blockChildren
     * @return $this
     */
    protected function processInfoChildren(array $blockChildren)
    {
        $blockComposite = Mage::getModel('braspag_pagador/block_composite');

        foreach ($blockChildren as $child) {
            $blockComposite->addChild($child);
        }

        $blockComposite->processBlock($this);

        return $blockComposite;
    }

    /**
     * @param $child
     */
    public function addCompositeChild($child)
    {
        $this->compositeChildren[] = $child;
    }

    /**
     * @return array
     */
    public function getCompositeChildren()
    {
        return $this->compositeChildren;
    }

    /**
     * @param string $child
     * @return Mage_Core_Block_Abstract|void
     */
    public function setChild($child)
    {
        parent::setChild($child->getBlockAlias(), $child->getBlockInstance());

        $this->addCompositeChild($child);
    }

    /**
     * @param $paymentRequest
     * @return array
     */
    public function getCompositeChildrenSpecificInfo($paymentRequest)
    {
        $specificInformation = [];
        foreach($this->getCompositeChildren() as $compositeChild) {
            $specificInformation = array_merge($specificInformation, $this->getCompositeChildSpecificInfo($compositeChild, $paymentRequest));
        }

        return $specificInformation;
    }

    protected function getCompositeChildSpecificInfo($compositeChild, $paymentRequest)
    {
        $specificInformation = [];
        foreach ($compositeChild->getSpecificInformation($paymentRequest) as $information) {
            $specificInformation[] = $information;
        }
        return $specificInformation;
    }

    /**
     * @param null $transport
     * @return Varien_Object
     * @throws Mage_Core_Exception
     */
//    protected function _prepareSpecificInformation($transport = null)
//    {
//        if (null !== $this->_paymentSpecificInformation) {
//            return $this->_paymentSpecificInformation;
//        }
//        $transport = parent::_prepareSpecificInformation($transport);
//        $data = array();
//
//        $additionalData = $this->getInfo()->getAdditionalInformation();
//        $paymentRequest = (isset($additionalData['payment_request'])) ? $additionalData['payment_request'] : array();
//
//        $_hlp = Mage::helper('braspag_pagador');
//
//        $order = $this->getInfo()->getOrder();
//
//        if ($order) {
//			$payment = $order->getPayment();
//			$paymentTotalDue = ($order->getGrandTotal() - $payment->getAdditionalInformation('authorized_total_paid'));
//
//            if (!isset($paymentRequest['type'])) {
//                $paymentTotalDue = 0;
//            } elseif (($paymentRequest['type'] == 'braspag_boleto') || (
//                $this->getLayout() &&
//                (in_array('braspag_payment_reorder', $this->getLayout()->getUpdate()->getHandles())))) {
//                $paymentTotalDue = 0;
//            }
//		}
//
//        if (!empty($paymentRequest)) {
//            $paymentCount = count($additionalData['payment_request']);
//
//            $tmp = array();
//
//            if (
//                $order &&
//                $order->getBaseTotalDue() &&
//                !empty($additionalData['payment_response']) &&
//                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_DEBITCARD'
//            ) {
//                $paymentResponse = $additionalData['payment_response'];
//                $tmp[] = '<a href="' . $paymentResponse['authenticationUrl'] . '" class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Order') . '</a>';
//            }
//
//            if (!empty($paymentRequest['cc_type']) && !empty($paymentRequest['cc_type_label'])) {
//                $tmp[$_hlp->__('Credit Card Type')] = $paymentRequest['cc_type_label'];
//            }
//
//            if (!empty($paymentRequest['cc_owner'])) {
//                $tmp[$_hlp->__('Name on the Card')] = $paymentRequest['cc_owner'];
//            }
//
//            if (!empty($paymentRequest['cc_number_masked'])) {
//                $tmp[$_hlp->__('Credit Card Number')] = $paymentRequest['cc_number_masked'];
//            }
//
//            if (!empty($paymentRequest['cc_exp_month']) && !empty($paymentRequest['cc_exp_year'])) {
//                $tmp[$_hlp->__('Expiration Date')] = $_hlp->__('%1$02s/%2$s', $paymentRequest['cc_exp_month'], $paymentRequest['cc_exp_year']);
//            }
//
//            if (!empty($paymentRequest['installments']) && !empty($paymentRequest['installments_label'])) {
//                $tmp[$_hlp->__('Installments')] = $paymentRequest['installments_label'];
//            }
//
////            if ((!empty($paymentRequest['cc_justclick']) && (!empty($paymentRequest['cc_number_masked'])))) {
////                $tmp[$_hlp->__('Add to braspag justclick as')] = $paymentRequest['cc_number_masked'];
////            }
//
//            if (!empty($paymentRequest['dc_type']) && !empty($paymentRequest['dc_type_label'])) {
//                $tmp[$_hlp->__('Debit Card Type')] = $paymentRequest['dc_type_label'];
//            }
//
//            if (!empty($paymentRequest['dc_owner'])) {
//                $tmp[$_hlp->__('Name on the Card')] = $paymentRequest['dc_owner'];
//            }
//
//            if (!empty($paymentRequest['dc_number_masked'])) {
//                $tmp[$_hlp->__('Debit Card Number')] = $paymentRequest['dc_number_masked'];
//            }
//
//            if (!empty($paymentRequest['dc_exp_month']) && !empty($paymentRequest['dc_exp_year'])) {
//                $tmp[$_hlp->__('Expiration Date')] = $_hlp->__('%1$02s/%2$s', $paymentRequest['dc_exp_month'], $paymentRequest['dc_exp_year']);
//            }
//
//            if ($paymentCount > 1 && !empty($paymentRequest['boleto_type'])) {
//                $tmp[] = $paymentRequest['boleto_type'];
//            }
//
//            if (!empty($paymentRequest['amount'])) {
//                $tmp[$_hlp->__('Amount')] = Mage::helper('core')->currency($paymentRequest['amount'], true, false);
//            }
//
//            if (
//                $order &&
//                $order->getBaseTotalDue() &&
//                !empty($additionalData['payment_response']) &&
//                $additionalData['payment_response']['integrationType'] == 'TRANSACTION_BOLETO'
//            ) {
//
//                $paymentResponse = $additionalData['payment_response'];
//
//                $tmp[$_hlp->__('Barcode representation')] = $paymentResponse['barCodeNumber'];
//                $tmp[$_hlp->__('Expiration Date')] = \DateTime::createFromFormat('Y-m-d', $paymentResponse['expirationDate'])->format('d/m/Y');
//                $tmp[] = '<a href="' . $paymentResponse['url'] . '" target="_blank" class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Print Boleto') . '</a>';
//            }
//
//            if (!empty($paymentTotalDue)) {
//                $tmp[] = '<a href="' . $this->getUrl('braspag/payment/reorder') . '?order=' . $order->getIncrementId() . '"class="button braspag-pagador payment-button payment-code-' . $this->getInfo()->getMethodInstance()->getCode() . '">' . $_hlp->__('Pay Now') . '</a>';
//            }
//
//            $data += $tmp;
//        }
//
//        return $transport->setData(array_merge($data, $transport->getData()));
//    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }
}
