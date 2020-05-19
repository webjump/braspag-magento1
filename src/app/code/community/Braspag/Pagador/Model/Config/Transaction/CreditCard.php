<?php
class Braspag_Pagador_Model_Config_Transaction_CreditCard extends Mage_Core_Model_Abstract
{
    /**
     * @return array
     */
    public function getAcquirersCreditCardPaymentMethods()
    {
        $return = array(
            'Simulado' => array('Simulado'),
            'Cielo' => array('Visa', 'Master', 'Amex', 'Elo', 'Aura', 'Jcb', 'Diners', 'Discover'),
            'Cielo30' => array('Visa', 'Master', 'Amex', 'Elo', 'Aura', 'Jcb', 'Diners', 'Discover', 'Hipercard', 'Hiper'),
            'Getnet' => array('Visa', 'Master', 'Elo', 'Amex', 'Hipercard'),
            'Rede' => array('Visa', 'Master', 'Hipercard', 'Hiper', 'Diners', 'Elo', 'Amex'),
            'Rede2' => array('Visa', 'Master', 'Hipercard', 'Hiper', 'Diners', 'Elo', 'Amex'),
            'GlobalPayments' => array('Visa', 'Master'),
            'Stone' => array('Visa', 'Master', 'Hipercard', 'Elo'),
            'Safra' => array('Visa', 'Master', 'Hipercard', 'Elo', 'Amex'),
            'FirstData' => array('Visa', 'Master', 'Cabal', 'Elo', 'Hipercard', 'Amex')
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