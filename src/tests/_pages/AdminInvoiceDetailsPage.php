<?php

class AdminInvoiceDetailsPage extends DefaultPage
{
    public static $URL = '/admin/sales_order_creditmemo/new/order_id/130/invoice_id/11/';

    public function clickRefundButton()
    {
        $this->user->waitForElementVisible('button[title="Refund"]');
        $this->user->click('button[title="Refund"]');
    }
}
