<?php

class AdminOrderListPage extends DefaultPage
{
    public static $URL = '/index.php/admin/sales_order/';

    public function openTheLastOrder()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->click('#sales_order_grid_table tbody tr:first-child');
    }
}
