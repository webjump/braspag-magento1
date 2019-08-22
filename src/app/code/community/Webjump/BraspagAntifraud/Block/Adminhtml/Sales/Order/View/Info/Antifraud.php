<?php
class Webjump_BraspagAntifraud_Block_Adminhtml_Sales_Order_View_Info_Antifraud extends Mage_Adminhtml_Block_Sales_Order_Abstract
{
	public function getAntifraud()
	{
		$_order = $this->getOrder();
		return Mage::getModel('webjump_braspag_antifraud/antifraud')->getCollection()->setOrderFilter($_order)->getLastItem();
	}

}