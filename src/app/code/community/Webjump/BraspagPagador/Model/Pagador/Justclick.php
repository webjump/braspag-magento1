<?php

class Webjump_BraspagPagador_Model_Pagador_Justclick
{
    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getAuthorizeCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_justclick_command_authorizeCommand');
    }
}
