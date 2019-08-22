<?php
class Webjump_BraspagPagador_StatusController extends Mage_Core_Controller_Front_Action
{
    
    public function updateAction()
    {
        $_hlp = Mage::helper('webjump_braspag_pagador');

        try {
            $orderIncrementId = $this->getRequest()->getPost('NumPedido');
            $model = Mage::getModel('webjump_braspag_pagador/status_update');
            $model->process($orderIncrementId);
        } catch (Exception $e) {
            $_hlp->debug($e->getMessage());
            $_hlp->debug($this->getRequest()->getPost());

            $order = $model->getOrder();
            if ($order && $order->getId()) {
                $order->addStatusHistoryComment($_hlp->__('Order status updated (2º Post): Exception occurred during update action. Exception message: %s.', $e->getMessage()), false);
                $order->save();
            }

            echo '<status>ERROR</status>';
            exit;
        }
        echo '<status>OK</status>';
        exit;
    }
}
