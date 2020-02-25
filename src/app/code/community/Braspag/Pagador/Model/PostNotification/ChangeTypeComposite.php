<?php

/**
 * Model Cart
 *
 * @package     Webjump_AmbevCart
 * @author      Webjump Core Team <contato@webjump.com.br>
 * @copyright   2019 Webjump. (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */
class Braspag_Pagador_Model_PostNotification_ChangeTypeComposite
{
    protected $changeTypes;

    public function __construct()
    {
        $this->changeTypes = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $changeType
     */
    public function addChangeType(Mage_Core_Model_Abstract $changeType)
    {
        $this->changeTypes->attach($changeType);
    }

    /**
     * @param $paymentId
     * @param $recurrentPaymentId
     * @return bool
     */
    public function notifyAll($paymentId, $recurrentPaymentId)
    {
        foreach ($this->changeTypes as $changeType) {
            $changeType->notify($paymentId, $recurrentPaymentId);
        }

        return true;
    }
}
