<?php
class Webjump_BraspagAntifraud_Model_Observer
{
	function saveDeviceFingerPrintSessionId(Varien_Event_Observer $observer)
	{
		$hlp = Mage::helper('webjump_braspag_antifraud');
		
		if (!$hlp->isActive()) {
			return;
		}

		try {
			$order = $observer->getEvent()->getOrder();
			$hlp->debug('Event - '. $observer->getEvent()->getName() . '/' . __METHOD__ . ' - ' . $order->getIncrementId());

			//Order placed in backend
			if (Mage::app()->getStore()->isAdmin()){ 
				$hlp->debug('Order placed via backend - Not save devicefingerprint');
				return;
			}
			Mage::getModel('webjump_braspag_antifraud/devicefingerprint')->saveOrderSessionId($order);
		} catch (Exception $e) {
			Mage::throwException($e->getMessage());
		}
	}
	
	function salesOrderPlaceAfter(Varien_Event_Observer $observer)
	{
		$hlp = Mage::helper('webjump_braspag_antifraud');
		
		if (!$hlp->isActive()) {
			return;
		}

		try {
			$order = $observer->getEvent()->getOrder();
			$hlp->debug('Event - '. $observer->getEvent()->getName() . '/' . __METHOD__ . ' - ' . $order->getIncrementId());
			$isAutoReviewOrder = $hlp->isAutoReviewOrder($order);
	
			if ($isAutoReviewOrder) {
				Mage::getModel('webjump_braspag_antifraud/antifraud')->reviewOrder($order);
			}
		} catch (Exception $e) {
			//If fail put order in hold state
			$status = Webjump_BraspagAntifraud_Model_Config::STATUS_ERROR;
			$order->hold();
			$order->setStatus($status)->save();
			$order->addStatusHistoryComment($e->getMessage(), $status)->save();
		}
	}
	
	function adminhtmlWidgetContainerHtmlBefore(Varien_Event_Observer $observer)
	{
		$block = $observer->getBlock();
		
		if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
			$hlp = Mage::helper('webjump_braspag_antifraud');
			$order = $block->getOrder();

			if (!$hlp->isActive() || $order->isCanceled()) {
				return;
			}
			
			$antifraud = Mage::getModel('webjump_braspag_antifraud/antifraud')->getCollection()->setOrderFilter($order)->getLastItem();
			$orderId = $order->getId();

			if (!$antifraud->getId() || $antifraud->getStatusCode() == Webjump_BraspagAntifraud_Model_Api::STATUS_UNFINISHED) {
				$message = $hlp->__('Are you sure you want submit this order to Braspag Antifraud?');
				$block->addButton('webjump_braspag_antifraud', array(
					'label'     => $hlp->__('Submit to Braspag Antifraud'),
					'onclick'   => "confirmSetLocation('{$message}', '{$block->getUrl('*/webjump_braspag_antifraud/reviewOrder')}/order/{$orderId}')",
					'class'     => 'go'
				));
			}
		}
	}

	function coreBlockAbstractToHtmlAfter($observer)
	{
		$block = $observer->getBlock();
		if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View_Info && 
			$block->getNameInLayout() == 'order_info' &&
			in_array('adminhtml_sales_order_view', Mage::app()->getLayout()->getUpdate()->getHandles())
		) {
			$normalOutput = $observer->getTransport()->getHtml();
			$orderReviewBlock = Mage::helper('webjump_braspag_antifraud')->getOrderReviewBlock();
			$block->append($orderReviewBlock);
			$observer->getTransport()->setHtml($orderReviewBlock->toHtml() . $normalOutput);
		}
	}
}