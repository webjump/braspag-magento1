<?php

class Webjump_BraspagPagador_Model_Source_Status_Review extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{
    /**
     * @var array
     */
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW,
        Mage_Sales_Model_Order::STATE_CANCELED
    );
}
