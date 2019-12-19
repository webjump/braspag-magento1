<?php

class Webjump_BraspagPagador_Model_Pagador_Debitcard_Resource_Authorize_InstallmentsBuilder
{
    /**
     * @param $total
     * @param bool $installments
     * @param bool $installmentsMinAmount
     * @return array
     */
	public function calculate($total, $installments = false, $installmentsMinAmount = false)
	{
		$return = array();

		if (!$installments) {
			$installments = $this->getDebitcardPaymentMethod()->getConfigData('installments');
		}

		if (!$installmentsMinAmount) {
			$installmentsMinAmount = $this->getDebitcardPaymentMethod()->getConfigData('installments_min_amount');
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

    /**
     * @param $installment
     * @param $amount
     * @return bool|string
     */
	public function getInstallmentLabel($installment, $amount)
	{
		$installments = $this->calculate($amount);

		if (isset($installments[$installment])) {
			return sprintf('%sx %s', $installment, $installments[$installment]);
		}

		return false;
	}

    protected function getDebitcardPaymentMethod()
    {
        return Mage::getSingleton('webjump_braspag_pagador/method_Debitcard');
    }
}