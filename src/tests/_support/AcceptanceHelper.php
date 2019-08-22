<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptanceHelper extends \Codeception\Module
{
    public function addProductExampleToCart()
    {
        $I = $this->getModule('WebDriver');
        $I->amOnPage('accessories/eyewear/aviator-sunglasses.html');
        $I->click('button[onclick="productAddToCartForm.submit(this)"]');
        $I->seeInCurrentUrl('/checkout/cart/');
    }

    public function gotoCheckoutPage()
    {
        $I = $this->getModule('WebDriver');
        $I->amOnPage('/checkout/onepage/');
    }

    public function doLogin()
    {
        $I = $this->getModule('WebDriver');
        $I->amOnPage('/customer/account/login/');
        $I->fillField('login[username]', 'webjump@webjump.com');
        $I->fillField('login[password]', '123456789');
        $I->click('#send2');
    }

    public function setAddresses()
    {
        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/');
        $I->click('button[onclick="billing.save()"]');
        // $I->wait(5);
        $I->waitForElementVisible('button[onclick="shippingMethod.save()"]', 30);
    }

    public function setShippingMethod()
    {

        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/');
        $I->click('button[onclick="shippingMethod.save()"]');
        // $I->wait(5);
        $I->waitForElementVisible('button[onclick="payment.save()"]', 30);
    }

    public function savePaymentMethod()
    {
        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/');
        $I->click('button[onclick="payment.save()"]');
        // $I->wait(5);
        $I->waitForElementVisible('button[onclick="review.save();"]', 30);
    }

    public function closeOrder()
    {
        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/');
        $I->click('button[onclick="review.save();"]');
        $I->wait(10);
    }

    public function seeSuccessPage()
    {
        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/success/');
        $I->see('YOUR ORDER HAS BEEN RECEIVED.');
    }

    public function seeSuccessPageWithPrintBoletoButtom()
    {
        $I = $this->getModule('WebDriver');
        $I->seeInCurrentUrl('/checkout/onepage/success/');
        $I->see('YOUR ORDER HAS BEEN RECEIVED.');
        $I->see('Clique aqui para imprimir o boleto');
    }
}
