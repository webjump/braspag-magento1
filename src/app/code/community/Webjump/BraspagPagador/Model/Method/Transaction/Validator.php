<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Validator extends Mage_Core_Model_Abstract
{
	public function validate(array $payment)
	{
		if (empty($payment)) {
			return Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('Payment data empty'));
		}

		if (!$this->isMultiDimensional($payment)) {
			$payment = array($payment);
		}

        $payment = $this->validatePaymentByType($payment);

		return $payment;
	}

	protected function validatePaymentByType($payment)
	{
		if (!isset($payment['type'])) {
			return Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('"type" attribute not found.'));
		}

		switch ($payment['type']) {
			case 'webjump_braspag_cc':
				return Mage::getSingleton('webjump_braspag_pagador/method_transaction_validator_cc')->validate($payment);
				break;
			case 'webjump_braspag_dc':
				return Mage::getSingleton('webjump_braspag_pagador/method_transaction_validator_dc')->validate($payment);
				break;
			case 'webjump_braspag_boleto':
				return Mage::getSingleton('webjump_braspag_pagador/method_transaction_validator_boleto')->validate($payment);
				break;
			default:
				return Mage::throwException(Mage::helper('webjump_braspag_pagador')->__('"type" is not valid.'));
				break;
		}
	}

	protected function isMultiDimensional($payment)
	{
		return is_array(reset($payment));
	}
}