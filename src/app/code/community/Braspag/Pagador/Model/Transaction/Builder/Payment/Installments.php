<?php

class Braspag_Pagador_Model_Transaction_Builder_Payment_Installments
    extends Braspag_Pagador_Model_Transaction_Builder_Payment
{
    /**
     * @param $payment
     * @param $total
     * @return array|mixed
     */
	public function build($payment, $total)
	{
		$return = array();

		$methodInstance = $payment->getMethodInstance();
        $installments = $methodInstance->getConfigData('installments');
        $installmentsMinAmount = $methodInstance->getConfigData('installments_min_amount');

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
}