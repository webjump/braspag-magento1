<?php

class SalesOrderViewPage extends DefaultPage
{
    public function seeNotPaymentAuthorizedMessage()
    {
        $this->user->see('Not Authorized');
    }

    public function seeCanceledByUserMessage()
    {
        $this->user->see('Compra cancelada pelo Cliente');
    }

    public function seeInCurrentSalesOrderViewPage()
    {
        $this->user->seeInCurrentUrl('sales/order/view/order_id/');
    }
}
