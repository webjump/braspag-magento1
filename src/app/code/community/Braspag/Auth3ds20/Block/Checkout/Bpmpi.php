<?php

/**
 * Class Braspag_Auth3ds20_Block_Checkout_Bpmpi
 */
class Braspag_Auth3ds20_Block_Checkout_Bpmpi extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * @var Braspag_Auth3ds20_Model_Config
     */
    protected $generalConfig;

    /**
     * @var Mage_Core_Model_Abstract
     */
    protected $mpiCreditcardConfig;

    /**
     * @var
     */
    protected $mpiDebitcardConfig;

    /**
     * Braspag_Auth3ds20_Block_Checkout_Bpmpi constructor.
     */
    public function __construct()
    {
        $this->generalConfig = Mage::getSingleton('braspag_core/config_general');
        $this->mpiCreditcardConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi_creditCard');
        $this->mpiDebitcardConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi_debitCard');
    }

    /**
     * @return bool
     */
    public function isTestEnvironmentEnabled()
    {
        return (bool) $this->generalConfig->isTestEnvironmentEnabled();

    }

    /**
     * @return bool
     */
    public function isBpmpiCreditCardEnabled()
    {
        return (bool) $this->mpiCreditcardConfig->isMpiCreditCardActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiDebitCardEnabled()
    {
        return (bool) $this->mpiDebitcardConfig->isMpiDebitCardActive();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyCreditCardEnabled()
    {
        return (bool) $this->mpiCreditcardConfig->isBpmpiMcNotifyOnlyCreditCardEnabled();
    }

    /**
     * @return bool
     */
    public function isBpmpiMcNotifyOnlyDebitCardEnabled()
    {
        return (bool) $this->mpiDebitcardConfig->isBpmpiMcNotifyOnlyDebitCardEnabled();
    }
}
