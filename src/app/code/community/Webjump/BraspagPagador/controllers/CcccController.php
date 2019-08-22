<?php
//class Webjump_BraspagPagador_CcccController extends Mage_Core_Controller_Front_Action
//{
//    public function apportionmentAction()
//    {
//        $response = array(
//            'cc0' => array(
//                'installments' => $this->getInstallmentsSelectOptions($this->getCc0Total()),
//            ),
//            'cc1' => array(
//                'value' => $this->getCc1Total(),
//                'currency' => Mage::helper('core')->currency($this->getCc1Total(), true, false),
//                'installments' => $this->getInstallmentsSelectOptions($this->getCc1Total()),
//            ),
//        );
//
//        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
//        $this->getResponse()->setBody(json_encode($response));
//    }
//
//    protected function getInstallmentsSelectOptions($total)
//    {
//        $options = null;
//
//        foreach ($this->getInstallments($total) as $id => $value) {
//            $options .= Mage::helper('webjump_braspag_pagador')->__('<option value="%1$s">%1$sx %2$s without interest</option>', $id, $value);
//        }
//
//        return $options;
//    }
//
//    protected function getInstallments($total)
//    {
//        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments')
//            ->caculate($total);
//    }
//
//    protected function getCc1Total()
//    {
//        return Mage::getModel('checkout/session')->getQuote()->getGrandTotal() - $this->getCc0Total();
//    }
//
//    protected function getCc0Total()
//    {
//        return floatval(str_replace(',', '.', $this->getRequest()->getPost('cc0')));
//    }
//}
