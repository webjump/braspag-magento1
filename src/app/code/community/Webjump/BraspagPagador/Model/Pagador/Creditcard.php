<?php

class Webjump_BraspagPagador_Model_Pagador_Creditcard extends Webjump_BraspagPagador_Model_Pagador
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getAuthorizeCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_command_authorizeCommand');
    }
}
