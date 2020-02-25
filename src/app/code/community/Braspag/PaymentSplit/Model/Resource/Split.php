<?php

class Braspag_PaymentSplit_Model_Resource_Split extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_paymentsplit/payment_split', 'entity_id');
    }
}