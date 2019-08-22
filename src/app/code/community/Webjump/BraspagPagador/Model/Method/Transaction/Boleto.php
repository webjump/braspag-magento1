<?php
class Webjump_BraspagPagador_Model_Method_Transaction_Boleto
    extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_BOLETO;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_boleto';

    protected $_formBlockType = 'webjump_braspag_pagador/form_boleto';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_boleto';

    public function getPaymentInstructions()
    {
    	return $this->getConfigData('payment_instructions');
    }

    public function getBoletoType()
    {
        return $this->getConfigData('boleto_type');
    }
}
