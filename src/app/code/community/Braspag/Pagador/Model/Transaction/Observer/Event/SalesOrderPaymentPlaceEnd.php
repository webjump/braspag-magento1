<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Observer Autoloader
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Observer
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Observer_Event_SalesOrderPaymentPlaceEnd
extends Braspag_Pagador_Model_Transaction_Observer_AbstractEvent
{
    /**
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function execute(Varien_Event_Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();

        try {

            $events = $this->getBraspagCoreConfigHelper()
                ->getDefaultConfigClassComposite('braspag_pagador/transaction/observer/event/sales_order_payment_place_end');

            $eventComposite = Mage::getModel('braspag_pagador/transaction_observer_composite');
            foreach ($events as $event) {
                $eventComposite->addEvent($event);
            }

            $eventComposite->executeAll($observer);

            $paymentMethod = $payment->getMethodInstance()->getCode();

            $sendEmail = Mage::getStoreConfig('braspag_pagador/status_update/send_email');

            $paymentInvoiceManager = Mage::getModel('braspag_pagador/payment_invoiceManager');

            if ($paymentInvoiceManager->canInvoice($payment, $paymentMethod)) {

                $amount = $payment->getAmountOrdered();

                $paymentInvoiceManager->create($payment, $amount, $sendEmail, true);
            }

            return $observer;

        } catch (\Exception $e) {

            $order = $payment->getOrder();
            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
            $order->save();
        }

        return $observer;
    }
}