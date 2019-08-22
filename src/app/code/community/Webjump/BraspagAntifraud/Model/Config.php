<?php
class Webjump_BraspagAntifraud_Model_Config
{
	const DEBUG_FILE = 'webjump_braspagantifraud.log';
	const MERCHANT_ID_DEMO_ACCOUNT = '3841446B-629C-47EA-924A-09F71660F3C7';

	const ENVIRONMENT_SANDBOX = 'sandbox';
	const ENVIRONMENT_PRODUCTION = 'production';

	const STATUS_APPROVED = 'wj_braspag_fraud_approved';
	const STATUS_REJECTED = 'wj_braspag_fraud_rejected';
	const STATUS_REVIEW = 'wj_braspag_fraud_review';
	const STATUS_ERROR = 'wj_braspag_fraud_error';

    const STATUS_CODE_500 = 'STARTED';
    const STATUS_CODE_501 = 'ACCEPT';
    const STATUS_CODE_502 = 'REVIEW';
    const STATUS_CODE_503 = 'REJECT';
    const STATUS_CODE_504 = 'PENDENT';
    const STATUS_CODE_505 = 'UNFINISHED';
    const STATUS_CODE_506 = 'ABORTED';

	const MESSAGE_STATUS_STARTED = 'Transaction analysis started (Braspag Antifraud).';
	const MESSAGE_STATUS_ACCEPT = 'Transaction approved (Braspag Antifraude).';
	const MESSAGE_STATUS_REVIEW = 'Transaction will be manually reviewed (Braspag Antifraud).';
	const MESSAGE_STATUS_REJECT = 'Transaction interpreted as fraud (Braspag Antifraud).';
	const MESSAGE_STATUS_PENDENT = 'Transaction analysis is pendent (Braspag Antifraud).';
	const MESSAGE_STATUS_UNFINISHED = 'Attempt to send the transaction for CyberSource return an error. Check details in order tab.';
	const MESSAGE_STATUS_ABORTED = 'Transaction analysis was aborted (Braspag Antifraud).';

	const SHORT_MESSAGE_STATUS_STARTED = 'Analysis Started';
	const SHORT_MESSAGE_STATUS_ACCEPT = 'Transaction Approved';
	const SHORT_MESSAGE_STATUS_REVIEW = 'Under Review';
	const SHORT_MESSAGE_STATUS_REJECT = 'Suspected Fraud';
	const SHORT_MESSAGE_STATUS_PENDENT = 'Analysis is Pendent';
	const SHORT_MESSAGE_STATUS_UNFINISHED = 'Error on Analysis';
	const SHORT_MESSAGE_STATUS_ABORTED = 'Analysis was Aborted';

	const SHIPPING_METHOD_SAME_DAY = 'SameDay';
	const SHIPPING_METHOD_ONE_DAY = 'OneDay';
	const SHIPPING_METHOD_TWO_DAY = 'TwoDay';
	const SHIPPING_METHOD_THREE_DAY = 'ThreeDay';
	const SHIPPING_METHOD_LOW_COST = 'LowCost';
	const SHIPPING_METHOD_PICKUP = 'Pickup';
	const SHIPPING_METHOD_OTHER = 'Other';
	const SHIPPING_METHOD_NONE = 'None';

	const ACTION_HOLD = 'hold';
	const ACTION_UNHOLD = 'unhold';
	const ACTION_PROCESS = 'process';
	const ACTION_CANCEL = 'cancel';

	public function getStatusApproved()
	{
		$salesOrderConfigModel = Mage::getModel('sales/order_config');
    	$hlp = Mage::helper('webjump_braspag_antifraud');
		$return = array(
			self::ACTION_HOLD => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_HOLDED),
			self::ACTION_UNHOLD => $hlp->__('Unhold'),
			self::ACTION_PROCESS => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_PROCESSING),
		);
		
		return $return;
	}

	public function getStatusRejected()
	{
		$salesOrderConfigModel = Mage::getModel('sales/order_config');
		
		$return = array(
			self::ACTION_HOLD => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_HOLDED),
			self::ACTION_CANCEL => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_CANCELED),
		);
		
		return $return;
	}

	public function getStatusReview()
	{
		$salesOrderConfigModel = Mage::getModel('sales/order_config');
		$return = array(
			self::ACTION_HOLD => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_HOLDED),
		);
		
		return $return;
	}

	public function getStatusError()
	{
		$salesOrderConfigModel = Mage::getModel('sales/order_config');
		$return = array(
			self::ACTION_HOLD => $salesOrderConfigModel->getStateLabel(Mage_Sales_Model_Order::STATE_HOLDED),
		);
		
		return $return;
	}

    public function getShippingMethods()
    {
    	$hlp = Mage::helper('webjump_braspag_antifraud');
        $return = array(
			self::SHIPPING_METHOD_SAME_DAY => $hlp->__('Service of delivery on the same day.'),
			self::SHIPPING_METHOD_ONE_DAY => $hlp->__('Service of delivery overnight or on the next day.'),
			self::SHIPPING_METHOD_TWO_DAY => $hlp->__('Service of delivery in two days.'),
			self::SHIPPING_METHOD_THREE_DAY => $hlp->__('Service of delivery in three days.'),
			self::SHIPPING_METHOD_LOW_COST => $hlp->__('Low-cost delivery service.'),
			self::SHIPPING_METHOD_PICKUP => $hlp->__('Product withdrawn from store.'),
			self::SHIPPING_METHOD_OTHER => $hlp->__('Other delivery method.'),
			self::SHIPPING_METHOD_NONE => $hlp->__('No delivery service, since it is a subscription or service.'), 
		);
		
		return $return;
    }
}