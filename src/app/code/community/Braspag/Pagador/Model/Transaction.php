<?php
class Braspag_Pagador_Model_Transaction extends Mage_Core_Model_Abstract
{
    /**
     * @param $payment
     * @param $transaction
     * @return $this
     */
    public function debugTransaction($payment, $transaction)
    {
        $payment->getMethodInstance()->debugData(array(
            'request' => $transaction->debug(),
            'response' => $transaction->debugResponse(),
        ));

        return $this;
    }

    /**
     * @return Braspag_Lib_Core_Service_Manager
     */
    public function getServiceManager()
    {
        return new Braspag_Lib_Core_Service_Manager(Mage::getModel('braspag_paymentsplit/config')->getConfig());
    }
}