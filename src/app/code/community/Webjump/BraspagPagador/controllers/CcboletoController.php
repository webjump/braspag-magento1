<?php
//class Webjump_BraspagPagador_CcboletoController extends Mage_Core_Controller_Front_Action
//{
//	public function apportionmentAction()
//	{
//		$response = array(
//			'boleto' => array(
//				'value' => $this->getBoletoValue(),
//				'currency' => Mage::helper('core')->currency($this->getBoletoValue(), true, false),
//			),
//			'installments' => $this->getInstallmentsSelectOptions(),
//		);
//
// 		$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
//        $this->getResponse()->setBody(json_encode($response));
//	}
//
//	protected function getInstallmentsSelectOptions()
//	{
//		foreach ($this->getInstallments() as $id => $value) {
//			$options .= Mage::helper('webjump_braspag_pagador')->__('<option value="%1$s">%1$sx %2$s without interest</option>', $id, $value);
//		}
//
//		return $options;
//	}
//
//	protected function getInstallments()
//	{
//		return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc_installments')
//			->caculate($this->getCcTotal());
//	}
//
//	protected function getBoletoValue()
//	{
//		return Mage::getModel('checkout/session')->getQuote()->getGrandTotal() - $this->getCcTotal();
//	}
//
//	protected function getCcTotal()
//	{
//		return floatval(str_replace(',', '.', $this->getRequest()->getPost('cc')));
//	}
//}