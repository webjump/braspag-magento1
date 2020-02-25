<?php

/**
 * Class Braspag_PaymentSplit_Model_Payment_Split_Item
 */
class Braspag_PaymentSplit_Model_Split_Item extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_paymentsplit/split_item', 'split_item_id');
    }
}
