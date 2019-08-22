<?php

class AdminOrderOrderPage extends DefaultPage
{
    public static $URL = 'index.php/admin/sales_order/view/order_id/246/';

    public function voidCurrentOrder()
    {
        $this->user->waitForElementVisible('button[title="Void"]');
        $this->user->click('button[title="Void"]');
        $this->user->acceptPopup();
    }

    public function seeVoidSuccessMessage()
    {
    	$this->user->waitForText('The payment has been voided.', 30);
    }

    public function openTransactionsTab()
    {
        $this->user->click('#sales_order_view_tabs_order_transactions');
    }

    public function openTheLastTransaction()
    {
        $this->user->click('#order_transactions_table tbody tr:first-child');
    }

    public function seeTheVoidTransactionData()
    {
        $this->user->waitForText('Operation Successful', 30);
    }

    public function seeTheRefundTransactionData()
    {
        $this->user->waitForText('refund_0_braspagTransactionId', 30);
        $this->user->waitForText('Operation Successful', 30);   
    }

    public function refundCurrentOrder()
    {
        $this->user->waitForElementVisible('button[title="refund"]');
        $this->user->click('button[title="refund"]');
        $this->user->acceptPopup();
    }

    public function seeRefundSuccessMessage()
    {
        $this->user->waitForText('The payment has been refunded.', 30);
    }

    public function seeOrderStatusAsCanceled()
    {
        $status = $this->user->grabTextFrom('#order_status');
        if ($status != 'Canceled') {
            throw new Exception("Order not canceled status");            
        }
    }

    public function seeOrderStatusAsClosed()
    {
        $status = $this->user->grabTextFrom('#order_status');
        if ($status != 'Closed') {
            throw new Exception("Order not closed status");            
        }
    }

    public function invoiceCurrentOrder()
    {
        $this->user->waitForElementVisible('button[title="Invoice"]');
        $this->user->click('button[title="Invoice"]');
    }

    public function submitInvoice()
    {
        $this->user->waitForElementVisible('button[class="scalable save submit-button"]');
        $this->user->click('button[class="scalable save submit-button"]');
    }

    public function seeInvoiceSuccessMessage()
    {
        $this->user->waitForText('The invoice has been created.', 30);
    }

    public function seeTheInvoiceTransactionData()
    {
        $this->user->waitForText('transaction_0_braspagTransactionId');
        $this->user->waitForText('Operation Successful');
    }
}
