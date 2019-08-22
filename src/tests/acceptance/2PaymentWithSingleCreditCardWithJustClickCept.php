<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment with single creditcard With JustClick');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setSingleCreditCardDataWithJustClick();
$I->savePaymentMethod();
$I->closeOrder();
$I->seeSuccessPage();





