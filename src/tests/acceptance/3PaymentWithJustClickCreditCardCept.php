<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment with JustClick creditcard');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setJustClickCreditCardData();
$I->savePaymentMethod();
$I->closeOrder();
$I->seeSuccessPage();





