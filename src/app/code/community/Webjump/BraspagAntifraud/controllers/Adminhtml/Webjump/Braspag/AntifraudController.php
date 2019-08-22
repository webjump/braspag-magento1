<?php
class Webjump_BraspagAntifraud_Adminhtml_Webjump_Braspag_AntifraudController extends Mage_Adminhtml_Controller_Action
{
    public function reviewOrderAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $reviewOrder = Mage::getModel('webjump_braspag_antifraud/antifraud')->reviewOrder($orderId);

			$this->_setMessage($reviewOrder);
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));

        } catch (Exception $e) {
            $this->_getSession()->addError($this->__($e->getMessage(), $e->getCode()));
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
    }

    public function updateStatusAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $status = $this->getRequest()->getParam('status');
            $antifraud = Mage::getModel('webjump_braspag_antifraud/antifraud')->updateStatus($orderId, $status);
            
            if ($antifraud->getIsUpdateRequired()) {
	            $this->_getSession()->addSuccess($this->__('Review status update was received. Your request will be processed soon.'));
	        } else {
				$this->_setMessage($antifraud);
	            $this->_getSession()->addSuccess($this->__('Review status update was received.'));
	        }
	        
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));

        } catch (Exception $e) {
            $this->_getSession()->addError($this->__($e->getMessage(), $e->getCode()));
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
    }

    public function viewAction()
    {
        $detail = $this->_initAntifraud();
        if (!$detail) {
            return;
        }
        $this->_title($this->__('Sales'))->_title($this->__('Antifraud'))
             ->_title(sprintf("#%s", $detail->getAntifraudDetailId()));

        $this->loadLayout()
             ->_setActiveMenu('sales/antifraud')
             ->renderLayout();
    }

    protected function _initAntifraud()
    {
        $detail = Mage::getModel('webjump_braspag_antifraud/antifraud_detail')->load(
            $this->getRequest()->getParam('detail_id'),
            'antifraud_detail_id'
        );

        if (!$detail->getAntifraudDetailId()) {
            $this->_getSession()->addError($this->__('Wrong Detail ID specified.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        if ($orderId = $detail->getOrderId()) {
            $detail->setOrderUrl(
                $this->getUrl('*/sales_order/view', array('order_id' => $orderId))
            );
        }

        Mage::register('current_antifraud_detail', $detail);

        return $detail;
    }
    
    function _setMessage($antifraud) {
		switch ($antifraud->getStatusCode()) {
			case Webjump_BraspagAntifraud_Model_Api::STATUS_ACCEPT:
				$this->_getSession()->addSuccess($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_ACCEPT));
				break;

			case Webjump_BraspagAntifraud_Model_Api::STATUS_REJECT:
				$this->_getSession()->addError($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_REJECT));
				break;

			case Webjump_BraspagAntifraud_Model_Api::STATUS_REVIEW:
				$this->_getSession()->addNotice($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_REVIEW));
				break;

			case Webjump_BraspagAntifraud_Model_Api::STATUS_STARTED:
				$this->_getSession()->addNotice($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_STARTED));
				break;

			case Webjump_BraspagAntifraud_Model_Api::STATUS_PENDENT:
				$this->_getSession()->addNotice($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_PENDENT));
				break;

			case Webjump_BraspagAntifraud_Model_Api::STATUS_UNFINISHED:
				$this->_getSession()->addNotice($this->__(Webjump_BraspagAntifraud_Model_Config::MESSAGE_STATUS_UNFINISHED));
				break;

			default:
				$detail = $antifraud->getDetail();
				$this->_getSession()->addWarning($this->__('%s (code %s)', $detail->getAdditionalInformation()->TransactionStatusDescription, $detail->getStatusCode()));
				break;

		}
		return $this;
	}
}
