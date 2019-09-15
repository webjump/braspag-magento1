<?php

class Webjump_BraspagPagador_Model_Source_Status_NewPending extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{
    /**
     * @var array 
     */
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
    );
}
