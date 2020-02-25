<?php
 
class Braspag_ProtectedCard_Model_Resource_Card extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_protectedcard/card', 'entity_id');
    }
}