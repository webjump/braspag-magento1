<?php

class ProductExamplePage extends DefaultPage
{
    public static $URL = 'produto-teste.html';
    // public static $URL = 'accessories/eyewear/aviator-sunglasses.html';;

    public static $addToCardButton = 'button[onclick="productAddToCartForm.submit(this)"]';

    public function addProductToCart()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->click(self::$addToCardButton);
    }
}
