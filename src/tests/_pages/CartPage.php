<?php

class CartPage extends DefaultPage
{
    public static $URL = '/checkout/cart/';

    public static $proceedToCartButton = '.btn-proceed-checkout';

    public function proceedToCheckout()
    {
        $this->user->click(self::$proceedToCartButton);
    }
}
