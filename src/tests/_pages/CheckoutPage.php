<?php

class CheckoutPage extends DefaultPage
{
    public static $URL = '/checkout/onepage';
    public static $waitfor = 30;

    public static $billingSaveButton = 'button[onclick="billing.save()"]';
    public static $shippingMethodSaveButton = 'button[onclick="shippingMethod.save()"]';
    public static $paymentMethodSaveButton = 'button[onclick="payment.save()"]';
    public static $reviewSaveButton = 'button[onclick="review.save();"]';
    public static $successMessage = 'YOUR ORDER HAS BEEN RECEIVED.';
    public static $printBoletoButton = '.print-boleto-button';

    public function setBillingData()
    {
        $this->user->waitForElementVisible(self::$billingSaveButton, self::$waitfor);
        $this->user->click(self::$billingSaveButton);
    }

    public function setShippingMethod()
    {
        $this->user->waitForElementVisible(self::$shippingMethodSaveButton, self::$waitfor);
        $this->user->click(self::$shippingMethodSaveButton);
    }

    public function setPaymentMethod(DefaultPaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethod->setOptions($this->user);
        $this->user->waitForElementVisible(self::$paymentMethodSaveButton, self::$waitfor);
        $this->user->click(self::$paymentMethodSaveButton);
    }

    public function closeOrder()
    {
        $this->user->waitForElementVisible(self::$reviewSaveButton, self::$waitfor);
        $this->user->click(self::$reviewSaveButton);
    }

    public function seeSuccessPage()
    {
        $this->user->waitForText(self::$successMessage, 30);
    }

    public function seeSuccessPageWithPrintBoletoButtom()
    {
        $this->user->waitForText(self::$successMessage, 30);
        $this->user->seeElement(self::$printBoletoButton);
    }

    public function seeNotAuthorizedPaymentMessage()
    {
        $this->user->wait(3);
        $this->user->seeInPopup('Not Authorized (code 2).');
        $this->user->acceptPopup();
    }
}
