<?php

class AdminInvoicesListPage extends DefaultPage
{
    public static $URL = '/index.php/admin/sales_invoice/index/';

    public function openTheLastInvoice()
    {
        $this->user->click('Sales');
        $this->user->click('Invoices');
        $this->user->waitForElement('#sales_invoice_grid_table tbody tr:first-child');
        $this->user->click('#sales_invoice_grid_table tbody tr:first-child');
    }
}
