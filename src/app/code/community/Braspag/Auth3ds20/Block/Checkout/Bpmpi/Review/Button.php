<?php
class Braspag_Auth3ds20_Block_Checkout_Bpmpi_Review_Button extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * @var Braspag_Auth3ds20_Model_Config_Mpi
     */
    protected $bpmpiConfig;

    public function __construct()
    {
        $this->bpmpiConfig = Mage::getSingleton('braspag_auth3ds20/config_bpmpi');
    }

    /**
     * @return bool
     */
    public function isBpmpiActive()
    {
        return (bool) ($this->bpmpiConfig->isMpiCreditCardActive() || $this->bpmpiConfig->isMpiDebitCardActive());
    }
}
