<?php
class Webjump_BraspagPagador_Model_Method_Transaction_Dc extends Webjump_BraspagPagador_Model_Method_Transaction_Abstract
{
    protected $_code = Webjump_BraspagPagador_Model_Config::METHOD_DC;

    protected $_apiType = 'webjump_braspag_pagador/pagador_transaction_debitcard';

    protected $_formBlockType = 'webjump_braspag_pagador/form_dc';
    protected $_infoBlockType = 'webjump_braspag_pagador/info_dc';

    /**
     * Retrieve availables dedit card types
     *
     * @return array
     */
    public function getDcAvailableTypes()
    {
        $dcTypes = array();

        $_config = $this->getConfigModel();
        $_acquirers = $_config->getAcquirers();
        $availableTypes = $_config->getAvailableDcPaymentMethods();

        foreach ($availableTypes as $availableType) {
            $availableTypeExploded = explode("-", $availableType);
            if (!isset($availableTypeExploded[0])) {
                continue;
            }
            $acquirerCode = $availableTypeExploded[0];
            $brand = $availableTypeExploded[1];

            $dcTypes[!empty($brand) ? $acquirerCode.'-'.$brand : $acquirerCode] = (empty($_acquirers[$acquirerCode]) ? $acquirerCode : $_acquirers[$acquirerCode]." - ").$brand;
        }

        return $dcTypes;
    }
}
