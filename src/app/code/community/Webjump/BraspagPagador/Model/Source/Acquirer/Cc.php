<?php

class Webjump_BraspagPagador_Model_Source_Acquirer_Cc extends Webjump_BraspagPagador_Model_Source_Acquirer_Abstract
{
    protected function getPaymentMethods()
    {
        return $this->getConfig()->getAcquirersCcPaymentMethods();
    }
}