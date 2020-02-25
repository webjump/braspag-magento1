<?php
class Braspag_Pagador_Model_Config_Transaction_DebitCard extends Mage_Core_Model_Abstract
{
    /**
     * @param null $storeId
     * @return mixed
     */
    public function getDebitCardReturnUrl($storeId = null)
    {
        return Mage::getStoreConfig('payment/braspag_debitcard/return_url', $storeId);
    }

    /**
     * @return array
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getAvailableDebitCardPaymentMethods()
    {
        $storeId = Mage::app()->getStore()->getId();
        return explode(",", Mage::getStoreConfig('payment/braspag_debitcard/debitcardtypes', $storeId));
    }

    /**
     * @return array
     */
    public function getAcquirersDebitCardPaymentMethods()
    {
        $return = array(
            'Cielo' => array("Visa", "Master"),
            'Cielo30' => array("Visa", "Master"),
            'Getnet' => array("Visa", "Master"),
            'FirstData' => array("Visa", "Master"),
            'GlobalPayments' => array("Visa", "Master"),
            'Simulado' => array('Simulado')
        );

        return $return;
    }
}