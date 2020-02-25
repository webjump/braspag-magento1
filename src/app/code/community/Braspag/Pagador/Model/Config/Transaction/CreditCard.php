<?php
class Braspag_Pagador_Model_Config_Transaction_CreditCard extends Mage_Core_Model_Abstract
{
    /**
     * @return array
     */
    public function getAcquirersCreditCardPaymentMethods()
    {
        $return = array(
            'Cielo' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover"),
            'Cielo30' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover", "Hipercard", "Hiper"),
            'Redecard' => array("Visa", "Master", "Hipercard", "Hiper", "Diners"),
            'Rede2' => array("Visa", "Master", "Hipercard", "Hiper", "Diners", "Elo", "Amex"),
            'Getnet' => array("Visa", "Master", "Elo", "Amex"),
            'GlobalPayments' => array("Visa", "Master"),
            'Stone' => array("Visa", "Master", "Hipercard", "Elo"),
            'FirstData' => array("Visa", "Master", "Cabal"),
            'Sub1' => array("Visa", "Master", "Diners", "Amex", "Discover", "Cabal", "Naranja e Nevada"),
            'Banorte' => array("Visa", "Master", "Carnet"),
            'Credibanco' => array("Visa", "Master", "Diners", "Amex", "Credential"),
            'Transbank' => array("Visa", "Master", "Diners", "Amex"),
            'RedeSitef' => array("Visa", "Master", "Hipercard", "Diners"),
            'CieloSitef' => array("Visa", "Master", "Amex", "Elo", "Aura", "Jcb", "Diners", "Discover"),
            'SantanderSitef' => array("Visa", "Master"),
            'DMCard' => array(),
            'Simulado' => array('Simulado')
        );

        return $return;
    }

    /**
     * @return array
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getAvailableCreditCardPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/braspag_creditcard/acquirers', $storeId));
    }
}