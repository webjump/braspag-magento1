<?php
 
class Webjump_BraspagPagador_Model_Resource_Justclick_Card extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('webjump_braspag_pagador/justclick_card', 'entity_id');
    }
}