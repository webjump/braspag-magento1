<?php

class Webjump_BraspagPagador_Model_Pagador_Debitcard extends Webjump_BraspagPagador_Model_Pagador
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getAuthorizeCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_debitcard_command_authorizeCommand');
    }
}
