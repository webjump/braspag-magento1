<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Cc_Installments extends Mage_Core_Helper_Abstract
{
	public function caculate($total, $installments = false, $installmentsMinAmount = false)
	{
		$return = array();
		
		if (!$this->getCreditCardPaymentMethod()->isInstallmentsEnabled()) {
			return false;
		}

		if (!$installments) {
			$installments = $this->getCreditCardPaymentMethod()->getConfigData('installments');
		}

		if (!$installmentsMinAmount) {
			$installmentsMinAmount = $this->getCreditCardPaymentMethod()->getConfigData('installments_min_amount');
		}

		for ($i = 1; $i < $installments; $i++) {
			$installmentAmount = $total / $i;
			
			if ($i > 1 && $installmentAmount < $installmentsMinAmount) {
				break;
			}

			$return[$i] = Mage::helper('core')->currency($installmentAmount, true, false);
		}
		
		return $return;
	}

	public function getInstallmentLabel($installment, $amount)
	{
		$installments = $this->caculate($amount);

		if (isset($installments[$installment])) {
			return sprintf('%sx %s', $installment, $installments[$installment]);
		}

		return false;
	}

    protected function getCreditCardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_transaction_cc');
    }
}