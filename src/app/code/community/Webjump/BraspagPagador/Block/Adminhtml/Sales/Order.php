<?php

class Webjump_BraspagPagador_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    protected function _initTotals() {
        parent::_initTotals();

        if (($total = $this->getSource()->getPayment()->getAdditionalInformation('authorized_total_paid')) &&
            ($this->getSource()->getPayment()->getMethodInstance()->getCode() == 'webjump_braspag_cccc')) {
            $this->_totals['braspag_authorized_paid'] = new Varien_Object(array(
                'code'      => 'braspag_authorized_paid',
                'strong'    => true,
                'value'     => $total,
                'base_value'=> $total,
                'label'     => $this->helper('webjump_braspag_pagador')->__('Total Authorized Paid'),
                'area'      => 'footer',
                'order'     => 1,
            ));
            $this->_totals['braspag_authorized_due'] = new Varien_Object(array(
                'code'      => 'braspag_authorized_due',
                'strong'    => true,
                'value'     => $this->getSource()->getGrandTotal() - $total,
                'base_value'=> $this->getSource()->getGrandTotal() - $total,
                'label'     => $this->helper('webjump_braspag_pagador')->__('Total Authorize pending'),
                'area'      => 'footer',
                'order'     => 1,
            ));
        }       

        return $this;
    } 
}