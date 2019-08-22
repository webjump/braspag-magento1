<?php

class Webjump_BraspagPagador_Model_Pagador_Authorize_Response extends varien_Object
{
	protected $response;

	public function import($response)
	{
		$this->response = $response;
		$this->setPaymentResponse($response);
		$this->setTransactionId($response['correlationId']);
		$this->setIsTransactionClose(0);
		$this->importTransactionAdditionalInfo($response);
		$this->setIsAuthorized($response);

		return $this;
	}

	protected function importTransactionAdditionalInfo($response)
	{
		$transaction_info = $response['order'] + array('correlationId' => $response['correlationId']);

		if ($payment = $response['payment']) {
			foreach ($payment as $paymentAttribute => $paymentValue) {
				$transaction_info['payment_'. key($payment ).'_'.$paymentAttribute] = $paymentValue;
			}
		}

		return $this->setTransactionAdditionalInfo($transaction_info);
	}

	public function updatePaymentResponse($currentPaymentResponse)
	{
		return array_merge($currentPaymentResponse, $this->response['payment']);
	}

	protected function setIsAuthorized($response)
	{
		if ($payment = $response['payment']) {
			switch ($payment['integrationType']) {
				case 'TRANSACTION_CC':
					if (($payment['status'] == Webjump_BraspagPagador_Model_Config::STATUS_AUTORIZADO)) {
						return parent::setIsAuthorized(true);
					}
				break;
				case 'TRANSACTION_BOLETO':
					if (!empty($payment['url'])) {
						return parent::setIsAuthorized(true);
					}
				break;
			}
		}

		return parent::setIsAuthorized(false);
	}

	public function updateAuthorizedTotalPaid($currentTotalPaid)
	{
		return $currentTotalPaid + $this->getAuthorizedTotalPaid();
	}

	public function getAuthorizedTotalPaid()
	{
		$total = 0;

		if ($payment = $this->response['payment']) {
			if (($payment['status'] == Webjump_BraspagPagador_Model_Config::STATUS_AUTORIZADO)) {
				$total +=  $payment['amount'];
			}
		}

		return $total/100;
	}

	public function getTransactionType()
	{
		switch (Mage::getStoreConfig('payment/webjump_braspag_cc/payment_action')) {
			case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE: return 'authorization'; break;
			case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE: return 'capture'; break;
		}

		return false;
	}
}