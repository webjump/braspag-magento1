<?php
class Webjump_BraspagAntifraud_Model_Antifraud extends Mage_Core_Model_Abstract
{
    protected $_details = null;
    protected $_order = null;

    public function _construct()
    {
        $this->_init('webjump_braspag_antifraud/antifraud');
    }

    public function addAntifraud(Webjump_BraspagAntifraud_Model_Antifraud_Detail $detail)
    {
        $antifraud = $this->getCollection()->setOrderFilter($detail->getOrderId())->getLastItem();
        if (!$antifraud->getId()) {
            $this->setData($detail->getData());
        } else {
            $this->load($antifraud->getId())->addData($detail->getData());
        }
        return $this;
    }

    public function addDetail(Webjump_BraspagAntifraud_Model_Antifraud_Detail $detail)
    {
        $this->addAntifraud($detail);

        $detail->setAntifraud($this)
               ->setAntifraudId($this->getId());
        if (!$detail->getId()) {
            $this->getDetailsCollection()->addItem($detail);
        }
        return $this;
    }

    public function setDetail(Webjump_BraspagAntifraud_Model_Antifraud_Detail $detail)
    {
        $this->addDetail($detail);
        return $this;
    }

    /**
     * Set created_at parameter
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $date = Mage::getModel('core/date')->gmtDate();
        if ($this->isObjectNew() && !$this->getCreatedAt()) {
            $this->setCreatedAt($date);
        } else {
            $this->setUpdatedAt($date);
        }

        return parent::_beforeSave();
    }

    /**
     * Save order related objects
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _afterSave()
    {
        if (null !== $this->_details) {
            $this->_details->save();
        }
        return parent::_afterSave();
    }

    public function setOrder($order = null)
    {
        $hlp = Mage::helper('webjump_braspag_antifraud');

        if (!$order) {
            throw new Exception($hlp->__('Review order fail. Order was not sent.'));
        }

        if (is_numeric($order)) {
            $order = Mage::getModel('sales/order')->load($order);
        }

        if (!$order instanceof Mage_Sales_Model_Order || !$orderId = $order->getId()) {
            throw new Exception($hlp->__('Review order fail. Order was not found.'));
        }

        $this->_order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->_order;
    }

    public function getDetail()
    {
        return $this->getAllDetails()->getLastItem();
    }

    public function getAllDetails()
    {
        return $this->getDetailsCollection();
    }

    public function getDetailsCollection()
    {
        if (is_null($this->_details)) {
            $this->_details = Mage::getResourceModel('webjump_braspag_antifraud/antifraud_detail_collection')
                 ->setAntifraudFilter($this);

            if ($this->getId()) {
                foreach ($this->_details as $detail) {
                    $detail->setAntifraud($this);
                }
            }
        }
        return $this->_details;
    }

    public function reviewOrder($order = null)
    {

        try {
	        $hlp = Mage::helper('webjump_braspag_antifraud');

        	if (empty($order) && !$this->getOrder()->getId()) {
				throw new Exception($hlp->__('Fail while review order. The order was not set.'));
        	}
        	
        	if (!empty($order)) {
		        $this->setOrder($order);
		    }
		    
    	    $hlp->debug('ReviewOrder - ' . $this->getOrder()->getIncrementId());

            Mage::dispatchEvent('webjump_braspag_antifraud_review_order_before', array('antifraud' => $this));
            $api = $this->_reviewOrderApi();
            $this->updateOrderStatusAutomaticaly();
            Mage::dispatchEvent('webjump_braspag_antifraud_review_order_after', array('antifraud' => $this));

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $this;
    }

    protected function _reviewOrderApi()
    {
        $order = $this->getOrder();
        $antifraud = Mage::getModel('webjump_braspag_antifraud/antifraud')->getCollection()->setOrderFilter($order)->getLastItem();
        $api = Mage::getModel('webjump_braspag_antifraud/api');

        if (!$antifraud->getId() || $antifraud->getStatusCode() == Webjump_BraspagAntifraud_Model_Api::STATUS_UNFINISHED) {
            $api
                ->setOrder($order)
                ->setMerchantId(Mage::helper('webjump_braspag_antifraud')->getMerchantId())
                ->reviewOrder();
            $this->_reviewOrderSaveResponse($api);
        } else {
            $api
                ->setOrder($order)
                ->setMerchantId(Mage::helper('webjump_braspag_antifraud')->getMerchantId())
                ->setAntifraud($antifraud)
                ->updateReviewOrder();
            $this->_updateReviewOrderSaveResponse($api);
        }
        return $api;
    }

    protected function _reviewOrderSaveResponse($api)
    {
        //Save api response
        $response = $api->getResponse();

        $orderId = $this->getOrder()->getId();

        $antifraudDetailData = array(
            'order_id' => $orderId,
            'antifraud_transaction_id' => $response->FraudAnalysisResult->AntiFraudTransactionId,
            'status_code' => $response->FraudAnalysisResult->TransactionStatusCode,
            'score' => $response->FraudAnalysisResult->AntiFraudResponse->AfsReply->AfsResult,
            'additional_information' => $response->FraudAnalysisResult,
			'is_update_required' => (Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW == $response->FraudAnalysisResult->TransactionStatusCode && Mage::helper('webjump_braspag_antifraud')->isDecisionManagerActive() ? true : false),
        );


        $this
            ->setDetail(
                Mage::getModel('webjump_braspag_antifraud/antifraud_detail')->setData($antifraudDetailData)
            )
            ->save();
        return $this;
    }

    public function _updateReviewOrderSaveResponse($api)
    {
        //Save api response
        $response = $api->getResponse();
        $orderId = $this->getOrder()->getId();

        $antifraudDetailData = array(
            'order_id' => $orderId,
            'antifraud_transaction_id' => $response->FraudAnalysisTransactionDetailsResult->AntiFraudTransactionId,
            'status_code' => $response->FraudAnalysisTransactionDetailsResult->AntiFraudTransactionStatusCode,
            'score' => $response->FraudAnalysisTransactionDetailsResult->AntiFraudAnalysisScore,
            'additional_information' => $response->FraudAnalysisTransactionDetailsResult,
            'is_update_required' => (Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW == $response->FraudAnalysisTransactionDetailsResult->AntiFraudTransactionStatusCode ? true : false),
        );

        $this
            ->setDetail(
                Mage::getModel('webjump_braspag_antifraud/antifraud_detail')->setData($antifraudDetailData)
            )
            ->save();
        return $this;
    }

    public function updateOrderStatusAutomaticaly()
    {
        $hlp = Mage::helper('webjump_braspag_antifraud');
        $storeId = $this->getOrder()->getStoreId();
        if ($hlp->isStatusUpdateActive($storeId)) {
            $collection = $this->getCollection()->setOrderFilter($this->getOrder())->getLastItem();
            switch ($collection->getStatusCode()) {
                case Webjump_BraspagAntifraud_Model_Api::STATUS_ACCEPT:
                    $action = $hlp->getStatusUpdateApproved($storeId);
                    $status = Webjump_BraspagAntifraud_Model_Config::STATUS_APPROVED;
                    $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_ACCEPT);
                    break;

                case Webjump_BraspagAntifraud_Model_Api::STATUS_REJECT:
                    $action = $hlp->getStatusUpdateRejected($storeId);
                    $status = Webjump_BraspagAntifraud_Model_Config::STATUS_REJECTED;
                    $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_REJECT);
                    break;

                case Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW:
                case Webjump_BraspagAntifraud_Model_Api::STATUS_PENDENT:
                case Webjump_BraspagAntifraud_Model_Api::STATUS_STARTED:
                    $action = $hlp->getStatusUpdateReview($storeId);
                    $status = Webjump_BraspagAntifraud_Model_Config::STATUS_REVIEW;

                    switch ($collection->getStatusCode()) {
                    case Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW:
                            $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_REVIEW);
                            break;

                    case Webjump_BraspagAntifraud_Model_Api::STATUS_PENDENT:
                            $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_PENDENT);
                            break;

                    case Webjump_BraspagAntifraud_Model_Api::STATUS_STARTED:
                            $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_STARTED);
                            break;

                    }
                    break;

                case Webjump_BraspagAntifraud_Model_Api::STATUS_UNFINISHED:
                    $action = $hlp->getStatusUpdateError($storeId);
                    $status = Webjump_BraspagAntifraud_Model_Config::STATUS_ERROR;
                    $message = $hlp->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_UNFINISHED);
                    break;

                default:
                    throw new Exception($hlp->__('Fail while update order status automaticaly. The status code %s was not recognized.', $collecion->getStatusCode()));
            }

			try {
	            $this->updateOrderStatus($action, $status, $message);

                //Invoice order if antifraud approved
                if ($collection->getStatusCode() == Webjump_BraspagAntifraud_Model_Api::STATUS_ACCEPT) {
                    $this->invoiceOrderAutomatically();
                }

	        } catch (Exception $e) {
	            throw new Exception($hlp->__('Update order status fail. %s', $e->getMessage()), $e->getCode());
	        }
        }
        return $this;
    }

    public function updateOrderStatus($action, $status, $message = null)
    {
        $order = $this->getOrder();
        if (!$order instanceof Mage_Sales_Model_Order || !$orderId = $order->getId()) {
            throw new Exception($hlp->__('Update order fail. Order was not defined.'));
        }

        switch ($action) {
            case Webjump_BraspagAntifraud_Model_Config::ACTION_HOLD:
                if ($order->getState() != Mage_Sales_Model_Order::STATE_HOLDED) {
                    $order->hold();
                }
                $order->setStatus($status)->save();
                $order->addStatusHistoryComment($message, $status)->save();
                break;

            case Webjump_BraspagAntifraud_Model_Config::ACTION_UNHOLD:
                if ($order->canUnhold()) {
                    $order->unhold()->save();
                }

                if ($message) {
                    $order->addStatusHistoryComment($message)->save();
                }
                break;

            case Webjump_BraspagAntifraud_Model_Config::ACTION_PROCESS:
                if ($order->canUnhold()) {
                    $order->unhold()->save();
                }
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, $status, $message)->save();
                break;

            case Webjump_BraspagAntifraud_Model_Config::ACTION_CANCEL:
                if ($order->canUnhold()) {
                    $order->unhold()->save();
                }
                $order->cancel()->setStatus($status)->save();
                $order->addStatusHistoryComment($message, $status)->save();
                break;

            default:
                throw new Exception($hlp->__('Fail while update order status. The action %s was not recognized.', $action));

        }
        return $this;
    }

    public function updateStatus($order = null, $newStatus = null)
    {
        $hlp = Mage::helper('webjump_braspag_antifraud');
        $this->setOrder($order);
        $hlp->debug('UpdateStatus - ' . $this->getOrder()->getIncrementId() . ' - ' . $newStatus);

        try {
            Mage::dispatchEvent('webjump_braspag_antifraud_update_status_before', array('antifraud' => $this));
            $api = $this->_updateStatusApi($newStatus);
            $this->_updateStatusSaveResponse($api);
            $this->reviewOrder();//Check if status was updated at Braspag
            Mage::dispatchEvent('webjump_braspag_antifraud_update_status_before', array('antifraud' => $this));

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $this;
    }

    public function invoiceOrderAutomatically()
    {
        $hlp = Mage::helper('webjump_braspag_antifraud');
        $order = $this->getOrder();
        if (!$order || !$order->getId()) {
            throw new Exception($hlp->__('Auto invoice failed. No order was defined'));
        }

        if ($hlp->isAutoInvoiceOrder($order)) {
            Mage::dispatchEvent('webjump_braspag_antifraud_invoice_before', array('antifraud' => $this));
            $sendEmail = $hlp->isAutoInvoiceSendMail($order->getStoreId());
            $order->addStatusHistoryComment($hlp->__('Order automaticaly invoiced by Braspag Antifraud'));
            Mage::helper('webjump_braspag_pagador')->invoiceOrder($order, $sendEmail);
            Mage::dispatchEvent('webjump_braspag_antifraud_invoice_after', array('antifraud' => $this));
        }
        return $this;
    }

    protected function _updateStatusApi($newStatus)
    {
        $order = $this->getOrder();
        $antifraud = Mage::getModel('webjump_braspag_antifraud/antifraud')->getCollection()->setOrderFilter($order)->getFirstItem();
        $api = Mage::getModel('webjump_braspag_antifraud/api');

        if (!$antifraud->getId()) {
            throw new Exception(Mage::helper('webjump_braspag_antifraud')->__('Update status fail. No analysis was found for this order.'));
        } elseif ($antifraud->getStatusCode() != Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW) {
            throw new Exception(Mage::helper('webjump_braspag_antifraud')->__('Analysis status is %s. Only REVIEW status can be updated.', Mage::helper('webjump_braspag_antifraud')->getStatusName($antifraud->getStatusCode())));
        } else {
        	try {
				$api
					->setOrder($order)
					->setMerchantId(Mage::helper('webjump_braspag_antifraud')->getMerchantId())
					->setAntifraud($antifraud)
					->setNewStatus(Mage::helper('webjump_braspag_antifraud')->getStatusName($newStatus))
					->updateStatus();
			} catch (Exception $e) {
				if (in_array($api::ERROR_CAN_CHANGE_ONLY_REVIEW, explode(',', $e->getCode()))) {
					$this->load($antifraud->getId())->setIsUpdateRequired(true)->save();
				}
				throw new Exception($e->getMessage(), $e->getCode());
			}
        }
        return $api;
    }

    protected function _updateStatusSaveResponse($api)
    {
        //Save api response
        $response = $api->getResponse();
        $order = $this->getOrder();
        $hlp = Mage::helper('webjump_braspag_antifraud');

        switch ($api->getNewStatus()) {
            case Webjump_BraspagAntifraud_Model_Config::STATUS_CODE_501:
                $message = $hlp->__('Braspag Antifraud received your approve request with successful.');
                break;

            case Webjump_BraspagAntifraud_Model_Config::STATUS_CODE_503:
                $message = $hlp->__('Braspag Antifraud received your reject request with successful.');
                break;
        }
        $order->addStatusHistoryComment($message)->save();

        $antifraud = $this->getCollection()->setOrderFilter($order->getId())->getLastItem();

        if (!$antifraud->getId()) {
            throw new Exception($hlp->__('Update status fail. Antifraud data was not found.'));
        }
		$this->load($antifraud->getId())->addData(array(
			'is_update_required' => true,
			'is_manual_review'	 => true,
		))->save();

        return $this;
    }
}
