<?php

class Webjump_BraspagPagador_Model_Pagador_Billet extends Webjump_BraspagPagador_Model_Pagador
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getOrderCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_billet_command_orderCommand');
    }
}
