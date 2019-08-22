<?php
class Webjump_BraspagAntifraud_Model_Observer_Mdd
{
	const SALES_CHANNEL_CALLCENTER = 'Call Center';
	const SALES_CHANNEL_WEB = 'Web';
	const SALES_CHANNEL_PORTAL = 'Portal';
	const SALES_CHANNEL_QUIOSQUE = 'Quiosque';
	const SALES_CHANNEL_MOVEL = 'Movel';
	
	
	function salesOrderPlaceBefore(Varien_Event_Observer $observer)
	{
		Mage::getSingleton('webjump_braspag_antifraud/session')->addPurchaseAttempt();
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

			//Order placed in backend
			if (Mage::app()->getStore()->isAdmin()){ 
				$hlp->debug('Order placed via backend - Not save mdd');
			} else {
				Mage::getModel('webjump_braspag_antifraud/mdd')->setOrder($order)->setOrderMddData()->save();
			}
			Mage::getSingleton('webjump_braspag_antifraud/session')->unsPurchaseAttempts();
		} catch (Exception $e) {
			Mage::throwException($e->getMessage());
		}
	}

    public function reviewPrepareDataAfter(Varien_Event_Observer $observer)
    {
        $reviewData = $observer->getEvent()->getReviewData();

        $order = $observer->getEvent()->getOrder();
		$customerId = $order->getCustomerId();
		$mdd = Mage::getModel('webjump_braspag_antifraud/mdd')->getOrderMddData($order);
		$mddAdditionalInformation = $mdd->getAdditionalInformation();

		$fieldsData = array();
		if (!$order->getRemoteIp()) {
			$fieldsData[1] = 'N/A';//Order placed in backend
		} elseif ($order->getCustomerIsGuest()) {
			$fieldsData[1] = 'NAO';
		} else {
			$fieldsData[1] = 'SIM';
		}
		
		if ($customerId) {
			$customer = Mage::getModel('customer/customer')->load($customerId);
			$datetime1 = new DateTime($customer->getCreatedAt());
			$datetime2 = new DateTime(date('Y-m-d H:i'));
			$interval = $datetime1->diff($datetime2);
			$fieldsData[2] = $interval->format('%a');
		}

		if (!$order->getRemoteIp()) {
			$fieldsData[4] = self::SALES_CHANNEL_CALLCENTER;
		} elseif ($mddAdditionalInformation['is_mobile'] && !$mddAdditionalInformation['is_tablet']) {
			$fieldsData[4] = self::SALES_CHANNEL_MOVEL;
		} else {
			$fieldsData[4] = self::SALES_CHANNEL_WEB;
		}

		if ($order->getCouponCode()) {
			$fieldsData[5] = $order->getCouponCode();
		}
		
		$lastOrderCollection = Mage::getModel('sales/order')->getCollection()
			->addFilter('customer_id', $customerId)
			->setOrder('created_at', Varien_Data_Collection_Db::SORT_ORDER_DESC)
			->setPageSize(1)
            ->setCurPage(2)
            ->getFirstItem();
		
		if ($lastOrderCollection->getId()) {
			$fieldsData[6] = $lastOrderCollection->getCreatedAtStoreDate()->toString('MM-dd-YYYY');
		}

		$fieldsData[8] = $mddAdditionalInformation['purchase_attempts'];

		$api = Mage::getModel('webjump_braspag_antifraud/api')->setOrder($order);
		$shippingMethod = $api->getShippingMethod();
		
		if ($shippingMethod == Webjump_BraspagAntifraud_Model_Config::SHIPPING_METHOD_PICKUP) {
			$fieldsData[9] = 'SIM';
		} else {
			$fieldsData[9] = 'NAO';
		}

		foreach($fieldsData AS $key => $value) {
			$reviewData->request['AntiFraudRequest']['AdditionalDataCollection']['AdditionalData'][] = array('Id' => $key, 'Value' => $value);
		}
    }
}
