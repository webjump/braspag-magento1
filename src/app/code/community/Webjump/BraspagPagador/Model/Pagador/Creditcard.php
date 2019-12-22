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

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getCaptureCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_command_captureCommand');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getVoidCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_command_voidCommand');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getRefundCommand()
    {
        return Mage::getModel('webjump_braspag_pagador/pagador_creditcard_command_refundCommand');
    }
}
