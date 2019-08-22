<?php

class Webjump_BraspagAntifraud_Block_Adminhtml_Sales_Order_View_Tab_Antifraud
 extends Webjump_BraspagAntifraud_Block_Antifraud_Detail_Grid
 implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('webjump_braspag_antifraud')->__('Braspag Antifraud');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('webjump_braspag_antifraud')->__('Braspag Antifraud');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return Mage::helper('webjump_braspag_antifraud')->isActive();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
