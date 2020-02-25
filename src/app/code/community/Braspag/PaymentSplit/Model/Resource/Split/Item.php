<?php

class Braspag_PaymentSplit_Model_Resource_Split_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_paymentsplit/payment_split_item', 'split_item_id');
    }
}