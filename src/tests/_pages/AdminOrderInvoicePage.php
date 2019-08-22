<?php

class AdminOrderInvoicePage extends DefaultPage
{
    public static $URL = 'index.php/admin/sales_order_invoice/new/order_id/116/';

    public function submitInvoice()
    {
        $this->user->waitForElementVisible('button[class="scalable save submit-button"]');
        $this->user->click('button[class="scalable save submit-button"]');
    }

    public function seeInvoiceMessageSucccess()
    {
        $this->user->waitForText('The invoice has been created.', 30);
    }
}
