<?php

class AdminInvoiceViewPage extends DefaultPage
{
    public static $URL = '/index.php/admin/sales_invoice/view/';

    public function clickCreditMemoButon()
    {
        $this->user->waitForElementVisible('button[title="Credit Memo"]', 30);
        $this->user->click('button[title="Credit Memo"]');
    }
}
