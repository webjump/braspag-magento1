<?php
class Webjump_BraspagAntifraud_Model_Setup_Status
{
	public function getCollection()
	{
		$hlp = Mage::helper('webjump_braspag_antifraud');
		
		$items = array(
			array(
				'label' => $hlp->__('Approved (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_APPROVED,
				'state' => Mage_Sales_Model_Order::STATE_HOLDED,
			),
			array(
				'label' => $hlp->__('Approved (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_APPROVED,
				'state' => Mage_Sales_Model_Order::STATE_PROCESSING,
			),
			array(
				'label' => $hlp->__('Rejected (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_REJECTED,
				'state' => Mage_Sales_Model_Order::STATE_HOLDED,
			),
			array(
				'label' => $hlp->__('Rejected (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_REJECTED,
				'state' => Mage_Sales_Model_Order::STATE_CANCELED,
			),
			array(
				'label' => $hlp->__('Error (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_ERROR,
				'state' => Mage_Sales_Model_Order::STATE_HOLDED,
			),
			array(
				'label' => $hlp->__('Under Review (Braspag Antifraud)'),
				'status' => Webjump_BraspagAntifraud_Model_Config::STATUS_REVIEW,
				'state' => Mage_Sales_Model_Order::STATE_HOLDED,
			),
		);
		
		$collection = new Varien_Data_Collection();				
		foreach ($items as $item) {
			$varienObject = new Varien_Object();
			$varienObject->setData($item);
			$collection->addItem($varienObject);
		}
		return $collection;		
	}
}