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
class Braspag_Pagador_Model_Transaction_Observer_Composite
{
    protected $events;

    public function __construct()
    {
        $this->events = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $event
     */
    public function addEvent(Mage_Core_Model_Abstract $event)
    {
        $this->events->attach($event);
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function executeAll(Varien_Event_Observer $observer)
    {
        foreach ($this->events as $event) {
            $event->execute($observer);
        }

        return $this;
    }
}
