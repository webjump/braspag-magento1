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
class Braspag_Pagador_Model_Transaction_Processor_Composite
{
    protected $processes;

    public function __construct()
    {
        $this->processes = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $process
     */
    public function addProcess(Mage_Core_Model_Abstract $process)
    {
        $this->processes->attach($process);
    }

    /**
     * @param $payment
     * @param null $resourceData
     * @return $this
     */
    public function processAll($payment, $resourceData = null)
    {
        foreach ($this->processes as $process) {
            $process->process($payment, $resourceData);
        }

        return $this;
    }
}
