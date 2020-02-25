<?php
 
class Braspag_PaymentSplit_Model_Resource_Split_Item_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
       $this->_init('braspag_paymentsplit/split_item');
    }
}