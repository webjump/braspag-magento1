<?php
//class Webjump_BraspagPagador_PostController extends Mage_Core_Controller_Front_Action
//{
//    /**
//     * Action predispatch
//     *
//     * Check customer authentication for some actions
//     */
//    public function preDispatch()
//    {
//        parent::preDispatch();
//        $action = $this->getRequest()->getActionName();
//        $loginUrl = Mage::helper('customer')->getLoginUrl();
//
//        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
//            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
//        }
//    }
//
//    public function payAction()
//    {
//        $this->loadLayout();
//
//        try {
//            $orderId = (int) $this->getRequest()->getParam('order_id');
//            if (!$order = Mage::getSingleton('sales/order')->load($orderId)) {
//                $errorMsg = Mage::helper('webjump_braspag_pagador')->__('Order id not received.');
//                Mage::throwException($errorMsg);
//            }
//
//            if ($order->getStatus() == Mage_Sales_Model_Order::STATE_PROCESSING) {
//                Mage::getSingleton('core/session')->addNotice(Mage::helper('webjump_braspag_pagador')->__('Order is already paid.'));
//
//                return $this->_redirect("sales/order/view", array('order_id' => $orderId));
//            }
//
//            $block = $this->getLayout()->createBlock(
//                'webjump_braspag_pagador/post_form',
//                'braspag_payment_form',
//                array('order_id' => $orderId)
//            );
//            $this->getLayout()->getBlock('content')->append($block);
//
//        } catch (Exception $e) {
//            Mage::getSingleton('customer/session')->addError($e->getMessage());
//        }
//
//        $this->_initLayoutMessages('customer/session');
//        $this->renderLayout();
//    }
//
//    public function notificationAction()
//    {
//        $_hlp = Mage::helper('webjump_braspag_pagador');
//        $isRequestFromBraspag = $_hlp->isRequestFromBraspag();
//        if (!$isRequestFromBraspag) {
//             Mage::throwException($_hlp->__('Braspag Error: Unallowed request received'));
//        }
//        try {
//            $model = Mage::getModel('webjump_braspag_pagador/notification');
//            $model
//                ->setData($this->getRequest()->getPost())
//                ->process()
//            ;
//        } catch (Exception $e) {
//            Mage::getSingleton('core/session')->addNotice(Mage::helper('webjump_braspag_pagador')->__($e->getMessage()));
//
//            return $this->_redirect("sales/order/view", array('order_id' => $model->getOrderId()));
//        }
//
//        $this->_redirect('checkout/onepage/success');
//    }
//}
