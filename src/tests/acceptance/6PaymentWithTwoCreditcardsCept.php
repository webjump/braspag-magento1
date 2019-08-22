<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a multi payment with two credit cards');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setPaymentWithTwoCreditCards();
$I->savePaymentMethod();
$I->closeOrder();
$I->seeSuccessPage();






