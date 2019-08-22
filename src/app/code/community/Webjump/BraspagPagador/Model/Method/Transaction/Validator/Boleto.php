<?php

class Webjump_BraspagPagador_Model_Method_Transaction_Validator_Boleto extends Mage_Core_Model_Abstract
{
    protected $validAttributes = array(
        'type',
        'boleto_type',
        'amount',
        'integrationType',
    );

	public function validate($paymentRequest)
    {
        $paymentRequest = $this->filterValidAttributes($paymentRequest);

        return $paymentRequest;
    }

    protected function filterValidAttributes($paymentRequest)
    {
        return array_intersect_key($paymentRequest, array_fill_keys($this->getValidAttributes(), true));
    }

    protected function getValidAttributes()
    {
        return $this->validAttributes;
    }
}